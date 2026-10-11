<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Support\TextFormatter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $status = $request->input('status', 'all');

        if (! in_array($status, ['all', 'occupied', 'available'], true)) {
            $status = 'all';
        }

        $totalLocations = Location::count();
        $occupiedLocations = Location::whereHas('item')->count();
        $availableLocations = Location::whereDoesntHave('item')->count();

        $locations = Location::with('item')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($locationQuery) use ($search) {
                    $locationQuery
                        ->where('aisle', 'like', '%' . $search . '%')
                        ->orWhere('bay', 'like', '%' . $search . '%')
                        ->orWhere('shelf', 'like', '%' . $search . '%')
                        ->orWhere('bin', 'like', '%' . $search . '%')
                        ->orWhereRaw(
                            "CONCAT(aisle, '-', bay, '-', shelf, '-', bin) LIKE ?",
                            ['%' . $search . '%']
                        );
                });
            })
            ->when(
                $status === 'occupied',
                fn ($query) => $query->whereHas('item')
            )
            ->when(
                $status === 'available',
                fn ($query) => $query->whereDoesntHave('item')
            )
            ->orderBy('aisle')
            ->orderBy('bay')
            ->orderBy('shelf')
            ->orderBy('bin')
            ->paginate(5)
            ->withQueryString();

        return view('locations.index', compact(
            'locations',
            'search',
            'status',
            'totalLocations',
            'occupiedLocations',
            'availableLocations'
        ));
    }

    public function create(): View
    {
        return view('locations.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedLocation($request);
        $data = $this->normalizeLocation($data);

        if ($this->locationExists($data)) {
            return back()
                ->withErrors(['location' => 'This location already exists.'])
                ->withInput();
        }

        Location::create($data);

        return redirect()
            ->route('locations.index')
            ->with('success', 'Location created successfully.');
    }

    public function edit(Location $location): View
    {
        return view('locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location): RedirectResponse
    {
        $data = $this->validatedLocation($request);
        $data = $this->normalizeLocation($data);

        if ($this->locationExists($data, $location->id)) {
            return back()
                ->withErrors(['location' => 'This location already exists.'])
                ->withInput();
        }

        $location->update($data);

        return redirect()
            ->route('locations.index')
            ->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location): RedirectResponse
    {
        if ($location->item()->exists()) {
            return back()->with(
                'error',
                'This location cannot be deleted because it is currently occupied by an item.'
            );
        }

        $location->delete();

        return redirect()
            ->route('locations.index')
            ->with('success', 'Location deleted successfully.');
    }

    /**
     * Prototype location format:
     * Aisle = exactly one letter A-Z
     * Bay   = exactly one letter A-Z
     * Shelf = exactly one digit 1-9
     * Bin   = exactly one digit 1-9
     */
    private function validatedLocation(Request $request): array
    {
        return $request->validate([
            'aisle' => ['required', 'string', 'regex:/^[A-Za-z]$/'],
            'bay' => ['required', 'string', 'regex:/^[A-Za-z]$/'],
            'shelf' => ['required', 'regex:/^[1-9]$/'],
            'bin' => ['required', 'regex:/^[1-9]$/'],
        ], [
            'aisle.regex' => 'Aisle must contain exactly one letter (A-Z).',
            'bay.regex' => 'Bay must contain exactly one letter (A-Z).',
            'shelf.regex' => 'Shelf must contain exactly one number from 1 to 9.',
            'bin.regex' => 'Bin must contain exactly one number from 1 to 9.',
        ]);
    }

    private function normalizeLocation(array $data): array
    {
        $data['aisle'] = TextFormatter::uppercase($data['aisle']);
        $data['bay'] = TextFormatter::uppercase($data['bay']);

        return $data;
    }

    private function locationExists(array $data, ?int $ignoreId = null): bool
    {
        return Location::query()
            ->where('aisle', $data['aisle'])
            ->where('bay', $data['bay'])
            ->where('shelf', $data['shelf'])
            ->where('bin', $data['bin'])
            ->when(
                $ignoreId !== null,
                fn ($query) => $query->where('id', '!=', $ignoreId)
            )
            ->exists();
    }
}
