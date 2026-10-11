@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 lg:py-10" x-data="calibrationPanel()" x-init="init()">
    <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <div class="ui-eyebrow"><span></span>CALIBRATION CONTROL</div>
            <h1 class="text-2xl font-bold text-gray-900 ui-page-title mt-2">
                Calibration
            </h1>

            <p class="text-sm text-gray-500 mt-1 ui-page-subtitle">
                Configure each smart shelf in sequence and monitor its live sensor reading from one workspace.
            </p>
        </div>
        <div class="hidden sm:flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-500 shadow-sm"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>Live sensor monitoring</div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 mb-7 shadow-sm">
        <div class="grid grid-cols-1 lg:grid-cols-[1.15fr_.85fr] gap-6 items-end">
            <div>
                <div class="mb-3"><h2 class="text-sm font-extrabold text-slate-800">Shelf Selection</h2><p class="text-xs text-slate-500 mt-0.5">Choose the item and linked sensor you want to calibrate.</p></div>
                <label class="cal-label">Item / Sensor</label>
                <select x-model="itemId" @change="onItemChange()" class="cal-select">
                    <option value="">Select an item to calibrate…</option>
                    @foreach($items as $item)
                        <option value="{{ $item->id }}">{{ $item->item_name }} ({{ $item->sensor->sensor_code }})</option>
                    @endforeach
                </select>
                @if($items->isEmpty())
                    <p class="text-xs text-red-500 mt-1.5">No item has a sensor assigned yet.</p>
                @endif
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="cal-live-box"><span>Live Weight</span><strong x-text="live.weight !== null ? Number(live.weight).toFixed(1) + ' g' : '—'"></strong></div>
                <div class="cal-live-box"><span>Quantity</span><strong x-text="live.quantity ?? '—'"></strong></div>
                <div class="cal-live-box"><span>Last Seen</span><small x-text="live.recorded_at ?? 'No reading yet'"></small></div>
            </div>
        </div>
    </div>

    <div class="mb-8">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h2 class="text-sm font-semibold text-gray-900">Calibration Steps</h2>
                <p class="text-xs text-gray-500 mt-0.5">Complete the steps from left to right for a new or recalibrated item.</p>
            </div>
            <span class="cal-sequence-badge">1 → 2 → 3</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="cal-step-card" :class="{'cal-step-active': currentAction === 'tare' && busy}">
                <div class="cal-step-top"><span class="cal-step-number">1</span><span class="cal-step-tag">Start here</span></div>
                <h3>Tare Scale</h3>
                <p>Remove everything from the shelf and reset the scale to zero.</p>
                <button type="button" @click="confirmTare()" :disabled="!itemId || busy" class="cal-step-btn cal-step-btn-light">
                    <span x-show="!(busy && currentAction === 'tare')">Tare Empty Scale</span>
                    <span x-show="busy && currentAction === 'tare'">Taring…</span>
                </button>
            </div>

            <div class="cal-step-card" :class="{'cal-step-active': currentAction === 'scale' && busy}">
                <div class="cal-step-top"><span class="cal-step-number">2</span><span class="cal-step-tag">Known weight</span></div>
                <h3>Calibrate Scale</h3>
                <p>Use a reference weight with a known mass to calculate the scale factor.</p>
                <button type="button" @click="showScaleModal = true" :disabled="!itemId || busy" class="cal-step-btn">
                    <span x-show="!(busy && currentAction === 'scale')">Enter Reference Weight</span>
                    <span x-show="busy && currentAction === 'scale'">Calibrating…</span>
                </button>
            </div>

            <div class="cal-step-card" :class="{'cal-step-active': currentAction === 'unit_weight' && busy}">
                <div class="cal-step-top"><span class="cal-step-number">3</span><span class="cal-step-tag">One item only</span></div>
                <h3>Set Unit Weight</h3>
                <p>Place exactly <strong>1 piece</strong> of the selected item. Its measured weight becomes the unit weight.</p>
                <button type="button" @click="showUnitModal = true" :disabled="!itemId || busy" class="cal-step-btn">
                    <span x-show="!(busy && currentAction === 'unit_weight')">Set 1-Piece Unit Weight</span>
                    <span x-show="busy && currentAction === 'unit_weight'">Measuring…</span>
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
        <div class="cal-table-title">
            <div><h2 class="text-sm font-semibold text-gray-900">Current Calibration</h2><p class="text-xs text-gray-500 mt-0.5">Only the current saved calibration for each item is shown. Recalibration replaces this value.</p></div>
            <span class="cal-current-badge">{{ $items->whereNotNull('latestCalibration')->count() }} calibrated</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="cal-table-head"><th class="px-5 py-3 text-left w-16">No.</th>
                            <th>Item</th><th>Sensor</th><th>Unit Weight</th><th>Scale Factor</th><th>Last Calibrated</th><th>By</th></tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($items as $item)
                        @php($cal = $item->latestCalibration)
                        <tr class="hover:bg-gray-50/70">
                                        <td class="px-5 py-4 align-top"><x-table-number :loop="$loop" /></td>
                            <td class="px-5 py-4"><p class="font-medium text-gray-900">{{ $item->item_name }}</p><p class="text-xs text-gray-400">{{ $item->item_code }}</p></td>
                            <td class="px-5 py-4"><span class="cal-sensor">{{ $item->sensor->sensor_code }}</span></td>
                            <td class="px-5 py-4 font-semibold text-gray-900">{{ $cal ? number_format((float)$cal->item_weight_per_unit, 4) . ' g' : '—' }}</td>
                            <td class="px-5 py-4 text-gray-600">{{ $cal ? number_format((float)$cal->calibration_factor, 4) : '—' }}</td>
                            <td class="px-5 py-4 text-gray-500">{{ $cal?->calibrated_at?->format('Y-m-d H:i') ?? 'Not calibrated' }}</td>
                            <td class="px-5 py-4">
    @if($cal?->user)
        <div>
            <p class="font-medium text-gray-700">
                {{ $cal->user->name }}
            </p>

            <p class="text-xs text-gray-400 mt-0.5">
                {{ ucwords(
                    str_replace(
                        '_',
                        ' ',
                        $cal->user->role?->position ?? ''
                    )
                ) }}
            </p>
        </div>
    @else
        <span class="text-gray-400">—</span>
    @endif
</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-12 text-center text-gray-400">No sensor-linked items available.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TARE CONFIRMATION --}}
    <div x-show="showTareModal" x-cloak class="cal-overlay" @keydown.escape.window="showTareModal=false">
        <div class="cal-modal" @click.outside="showTareModal=false">
            <div class="cal-modal-icon">1</div><h3>Start with an Empty Shelf</h3>
            <p>Remove all items and objects from the selected shelf. When it is completely empty and stable, start the tare process.</p>
            <div class="cal-modal-note"><strong>Before continuing:</strong> the shelf must contain no reference weight and no inventory item.</div>
            <div class="cal-modal-actions"><button @click="showTareModal=false" class="cal-cancel">Cancel</button><button @click="runTare()" class="cal-confirm">Start Tare</button></div>
        </div>
    </div>

    {{-- SCALE MODAL --}}
    <div x-show="showScaleModal" x-cloak class="cal-overlay" @keydown.escape.window="showScaleModal=false">
        <div class="cal-modal" @click.outside="showScaleModal=false">
            <div class="cal-modal-icon">2</div><h3>Calibrate with Known Weight</h3>
            <p>Enter the exact mass of your reference weight. After starting, place that reference weight on the shelf and leave it stable.</p>
            <label class="cal-label mt-5">Reference Weight (g)</label>
            <input type="number" step="0.01" min="0.01" x-model="scaleReferenceWeight" class="cal-input" placeholder="e.g. 20.00">
            <div class="cal-modal-actions"><button @click="showScaleModal=false" class="cal-cancel">Cancel</button><button @click="runCalibrateScale()" :disabled="!scaleReferenceWeight || Number(scaleReferenceWeight)<=0" class="cal-confirm disabled:opacity-40">Start Calibration</button></div>
        </div>
    </div>

    {{-- UNIT WEIGHT MODAL: NO QUANTITY INPUT --}}
    <div x-show="showUnitModal" x-cloak class="cal-overlay" @keydown.escape.window="showUnitModal=false">
        <div class="cal-modal" @click.outside="showUnitModal=false">
            <div class="cal-modal-icon">3</div><h3>Set Unit Weight</h3>
            <p>The system no longer asks for a quantity. Unit weight is measured using <strong>one physical piece only</strong>.</p>
            <div class="cal-one-piece"><span class="cal-one-number">1</span><div><strong>Place exactly 1 piece</strong><small>Use one piece of the selected item and keep it stable on the shelf.</small></div></div>
            <div class="cal-modal-actions"><button @click="showUnitModal=false" class="cal-cancel">Cancel</button><button @click="runSetUnitWeight()" class="cal-confirm">I Placed 1 Piece — Start</button></div>
        </div>
    </div>

    {{-- FLOATING PROCESS CARD replaces blue inline message --}}
    <div x-show="busy && statusMessage" x-cloak x-transition class="cal-process-card">
        <div x-show="showPlacementTimer" class="cal-countdown" :style="`--progress:${countdownProgress}`">
            <div class="cal-countdown-inner">
                <strong x-text="countdownSeconds"></strong>
                <span>sec</span>
            </div>
        </div>
        <div x-show="!showPlacementTimer" class="cal-spinner"></div>
        <div>
            <strong x-text="processTitle"></strong>
            <p x-text="statusMessage"></p>
            <small x-text="showPlacementTimer ? 'Place the required weight/item before the timer reaches 0.' : 'Checking the sensor result…'"></small>
        </div>
    </div>

    {{-- RESULT MODAL --}}
    <div x-show="modalOpen" x-cloak class="cal-overlay" @keydown.escape.window="closeModal()">
        <div class="cal-modal text-center" @click.outside="closeModal()">
            <div class="cal-result-icon" :class="modalType === 'success' ? 'cal-success' : 'cal-error'">
                <span x-text="modalType === 'success' ? '✓' : '!' "></span>
            </div>
            <h3 x-text="modalTitle"></h3><p x-text="modalMessage"></p>
            <button @click="closeModal()" class="cal-confirm w-full mt-6">OK</button>
        </div>
    </div>
</div>

<style>
:root{--steel:#243B53;--navy:#334E68;--amber:#F59E0B;--slate:#64748B;--line:#E2E8F0;--soft:#F8FAFC}
.ui-eyebrow{display:flex;align-items:center;gap:.5rem;color:#B96B00;font-size:.68rem;font-weight:800;letter-spacing:.12em}
.ui-eyebrow span{width:.42rem;height:.42rem;border-radius:9999px;background:#F59E0B;box-shadow:0 0 0 4px rgba(245,158,11,.10)}
.cal-label{display:block;margin-bottom:.5rem;color:#64748B;font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em}.cal-select,.cal-input{width:100%;height:2.9rem;border:1px solid #CBD5E1;border-radius:.7rem;padding:0 .85rem;background:#fff;color:#0F172A;font-size:.875rem;transition:.18s}.cal-select:hover,.cal-input:hover{border-color:#94A3B8}.cal-select:focus,.cal-input:focus{border-color:var(--amber);box-shadow:0 0 0 3px rgba(245,158,11,.13);outline:none}
.cal-live-box{position:relative;min-height:5.15rem;padding:.9rem 1rem;background:linear-gradient(145deg,#F8FAFC,#fff);border:1px solid #E2E8F0;border-radius:.8rem;overflow:hidden}.cal-live-box:before{content:"";position:absolute;left:0;top:0;bottom:0;width:3px;background:var(--amber)}.cal-live-box span{display:block;color:#64748B;font-size:.62rem;font-weight:800;text-transform:uppercase;letter-spacing:.06em}.cal-live-box strong{display:block;margin-top:.32rem;color:#0F172A;font-size:1.3rem;font-weight:800}.cal-live-box small{display:block;margin-top:.38rem;color:#64748B;font-size:.68rem;line-height:1.3}
.cal-sequence-badge,.cal-current-badge,.cal-sensor{display:inline-flex;align-items:center;padding:.32rem .65rem;border-radius:999px;color:var(--steel);background:#EFF6FA;border:1px solid #D8E5EC;font-size:.68rem;font-weight:800}.cal-sequence-badge{color:#92400E;background:#FFFBEB;border-color:#FDE68A}
.cal-step-card{position:relative;display:flex;flex-direction:column;min-height:16rem;padding:1.35rem;background:#fff;border:1px solid #E2E8F0;border-radius:1rem;box-shadow:0 1px 2px rgba(15,23,42,.03);transition:.2s}.cal-step-card:before{content:"";position:absolute;top:0;left:1.35rem;right:1.35rem;height:3px;background:#E2E8F0;border-radius:0 0 4px 4px}.cal-step-card:hover{transform:translateY(-2px);border-color:#CBD5E1;box-shadow:0 10px 25px rgba(15,23,42,.07)}.cal-step-active{border-color:#F59E0B;box-shadow:0 0 0 3px rgba(245,158,11,.10),0 10px 25px rgba(15,23,42,.06)}.cal-step-active:before{background:var(--amber)}.cal-step-top{display:flex;align-items:center;justify-content:space-between}.cal-step-number{width:2.35rem;height:2.35rem;display:flex;align-items:center;justify-content:center;border-radius:.72rem;color:#fff;background:var(--steel);box-shadow:0 5px 12px rgba(36,59,83,.18);font-weight:800}.cal-step-tag{color:#64748B;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:999px;padding:.27rem .55rem;font-size:.64rem;font-weight:700}.cal-step-card h3{margin-top:1.15rem;color:#0F172A;font-size:1rem;font-weight:800}.cal-step-card p{margin-top:.45rem;color:#64748B;font-size:.79rem;line-height:1.6}.cal-step-btn{margin-top:auto;min-height:2.7rem;padding:0 .9rem;color:#fff;background:var(--steel);border:1px solid var(--steel);border-radius:.65rem;font-size:.78rem;font-weight:800;transition:.18s}.cal-step-btn:hover:not(:disabled){background:var(--navy);transform:translateY(-1px)}.cal-step-btn-light{color:var(--steel);background:#EFF6FA;border-color:#D8E5EC}.cal-step-btn-light:hover:not(:disabled){background:#E4EFF5}.cal-step-btn:disabled{opacity:.4;cursor:not-allowed;transform:none}
.cal-table-title{display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:1.15rem 1.3rem;background:linear-gradient(180deg,#fff,#FBFDFF);border-bottom:1px solid #E2E8F0}.cal-table-head{color:#64748B;background:#F8FAFC;font-size:.66rem;font-weight:800;text-transform:uppercase;letter-spacing:.055em}.cal-table-head th{padding:.8rem 1.25rem;text-align:left;border-bottom:1px solid #E2E8F0}
.cal-overlay{position:fixed;inset:0;z-index:50;display:flex;align-items:center;justify-content:center;padding:1rem;background:rgba(15,23,42,.58);backdrop-filter:blur(4px)}.cal-modal{width:100%;max-width:28rem;padding:1.65rem;background:#fff;border:1px solid rgba(255,255,255,.7);border-radius:1rem;box-shadow:0 28px 70px rgba(15,23,42,.3)}.cal-modal-icon{width:2.75rem;height:2.75rem;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;color:#fff;background:var(--steel);border-radius:.75rem;box-shadow:0 7px 16px rgba(36,59,83,.2);font-weight:800}.cal-modal h3{color:#0F172A;font-size:1.08rem;font-weight:800}.cal-modal>p{margin-top:.55rem;color:#64748B;font-size:.82rem;line-height:1.65}.cal-modal-note{margin-top:1rem;padding:.85rem;color:#475569;background:#FFFBEB;border:1px solid #FDE68A;border-radius:.65rem;font-size:.73rem;line-height:1.55}.cal-modal-actions{display:flex;justify-content:flex-end;gap:.65rem;margin-top:1.45rem;padding-top:1.05rem;border-top:1px solid #E2E8F0}.cal-cancel,.cal-confirm{min-height:2.6rem;padding:0 1rem;border-radius:.6rem;font-size:.78rem;font-weight:800;transition:.18s}.cal-cancel{color:#475569;background:#fff;border:1px solid #CBD5E1}.cal-cancel:hover{background:#F8FAFC}.cal-confirm{color:#fff;background:var(--steel);border:1px solid var(--steel)}.cal-confirm:hover:not(:disabled){background:var(--navy)}
.cal-one-piece{display:flex;align-items:center;gap:1rem;margin-top:1.15rem;padding:1rem;background:#F8FAFC;border:1px solid #E2E8F0;border-radius:.75rem}.cal-one-number{width:2.8rem;height:2.8rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#fff;background:var(--amber);border-radius:.7rem;font-size:1.35rem;font-weight:900}.cal-one-piece strong,.cal-one-piece small{display:block}.cal-one-piece strong{color:#1E293B;font-size:.82rem}.cal-one-piece small{margin-top:.2rem;color:#64748B;font-size:.7rem}
.cal-process-card{position:fixed;right:1.5rem;bottom:1.5rem;z-index:60;width:min(26rem,calc(100vw - 2rem));display:flex;gap:.9rem;padding:1.05rem;background:#fff;border:1px solid #FDE68A;border-left:4px solid var(--amber);border-radius:.85rem;box-shadow:0 18px 45px rgba(15,23,42,.2)}.cal-process-card>div:last-child>strong{display:block;color:#0F172A;font-size:.83rem}.cal-process-card p{margin-top:.18rem;color:#475569;font-size:.74rem;line-height:1.45}.cal-process-card small{display:block;margin-top:.38rem;color:#94A3B8;font-size:.65rem}.cal-spinner{width:1.15rem;height:1.15rem;flex-shrink:0;margin-top:.1rem;border:2px solid #FDE68A;border-top-color:var(--amber);border-radius:50%;animation:calspin .8s linear infinite}.cal-countdown{--progress:100;width:3.6rem;height:3.6rem;flex:0 0 3.6rem;display:grid;place-items:center;border-radius:50%;background:conic-gradient(var(--amber) calc(var(--progress)*1%),#FDE68A 0);position:relative}.cal-countdown:after{content:"";position:absolute;inset:4px;background:#fff;border-radius:50%}.cal-countdown-inner{position:relative;z-index:1;text-align:center;line-height:1}.cal-countdown-inner strong{display:block!important;color:#0F172A!important;font-size:1rem!important;font-weight:900}.cal-countdown-inner span{display:block;margin-top:.15rem;color:#94A3B8;font-size:.55rem;font-weight:800;text-transform:uppercase}@keyframes calspin{to{transform:rotate(360deg)}}.cal-result-icon{width:3.35rem;height:3.35rem;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;border-radius:.85rem;font-size:1.4rem;font-weight:900}.cal-success{color:#047857;background:#D1FAE5}.cal-error{color:#B91C1C;background:#FEE2E2}
@media(max-width:640px){.cal-process-card{right:1rem;bottom:1rem}.cal-modal-actions{flex-direction:column-reverse}.cal-cancel,.cal-confirm{width:100%}}
</style>

<script>
function calibrationPanel() {
    return {

        // =========================================================
        // STATE
        // =========================================================

        itemId: '',
        busy: false,

        currentAction: '',
        statusMessage: '',
        processTitle: 'Processing Calibration',

        showTareModal: false,
        showScaleModal: false,
        showUnitModal: false,

        scaleReferenceWeight: '',

        modalOpen: false,
        modalType: 'success',
        modalTitle: '',
        modalMessage: '',

        live: {
            weight: null,
            quantity: null,
            recorded_at: null,
        },

        liveTimer: null,
        countdownSeconds: 15,
        countdownProgress: 100,
        countdownTimer: null,
        countdownDelayTimer: null,
        showPlacementTimer: false,

        // Default timeout for short calibration commands.
        // Scale calibration and unit-weight calibration each allow the user
        // up to 15 seconds to place the required weight/item. Browser polling
        // stays slightly longer so ESP32 can return the final result first.
        maxCommandAttempts: 13,
        scaleCalibrationCommandAttempts: 17, // 17 × 1.5s ≈ 25.5 seconds
        unitWeightCommandAttempts: 17,       // 17 × 1.5s ≈ 25.5 seconds
        commandPollInterval: 1500,


        // =========================================================
        // INITIALIZATION
        // =========================================================

        init() {
            this.liveTimer = setInterval(
                () => this.pollLive(),
                1500
            );
        },


        // =========================================================
        // HELPERS
        // =========================================================

        csrfToken() {
            return document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content');
        },


        resetProcessState() {
            this.stopPlacementCountdown();
            this.busy = false;
            this.statusMessage = '';
        },

        startPlacementCountdown() {
            this.stopPlacementCountdown();
            this.countdownSeconds = 15;
            this.countdownProgress = 100;

            // ESP32 first gives the operator about 3 seconds to confirm the shelf is empty.
            // Start the visible 15-second placement countdown after that preparation step.
            this.countdownDelayTimer = setTimeout(() => {
                if (!this.busy || !['scale', 'unit_weight'].includes(this.currentAction)) return;
                this.showPlacementTimer = true;
                const startedAt = Date.now();
                this.countdownTimer = setInterval(() => {
                    const elapsed = Date.now() - startedAt;
                    const remainingMs = Math.max(0, 15000 - elapsed);
                    this.countdownSeconds = Math.ceil(remainingMs / 1000);
                    this.countdownProgress = (remainingMs / 15000) * 100;
                    if (remainingMs <= 0) {
                        clearInterval(this.countdownTimer);
                        this.countdownTimer = null;
                        this.showPlacementTimer = false;
                        this.statusMessage = 'Checking the final sensor result…';
                    }
                }, 100);
            }, 3000);
        },

        stopPlacementCountdown() {
            if (this.countdownTimer) clearInterval(this.countdownTimer);
            if (this.countdownDelayTimer) clearTimeout(this.countdownDelayTimer);
            this.countdownTimer = null;
            this.countdownDelayTimer = null;
            this.showPlacementTimer = false;
            this.countdownSeconds = 15;
            this.countdownProgress = 100;
        },


        resetLiveReading() {
            this.live = {
                weight: null,
                quantity: null,
                recorded_at: null,
            };
        },


        // =========================================================
        // RESULT MODAL
        // =========================================================

        showModal(type, title, message) {
            this.modalType = type;
            this.modalTitle = title;
            this.modalMessage = message;
            this.modalOpen = true;
        },


        closeModal() {
            this.modalOpen = false;

            if (
                this.modalType === 'success' &&
                this.currentAction === 'unit_weight'
            ) {
                window.location.reload();
            }
        },


        // =========================================================
        // ITEM SELECTION
        // =========================================================

        onItemChange() {
            this.resetLiveReading();

            this.currentAction = '';
            this.statusMessage = '';

            this.pollLive();
        },


        // =========================================================
        // LIVE READING
        // =========================================================

        async pollLive() {
            if (!this.itemId) {
                return;
            }

            try {
                const response = await fetch(
                    `{{ url('/calibration/live-reading') }}?item_id=${this.itemId}`,
                    {
                        headers: {
                            Accept: 'application/json',
                        },

                        cache: 'no-store',
                    }
                );

                if (response.ok) {
                    this.live = await response.json();
                }

            } catch (error) {
                console.warn(
                    'Live reading polling error:',
                    error
                );
            }
        },


        // =========================================================
        // POST JSON HELPER
        // =========================================================

        async postJson(url, body) {
            const response = await fetch(
                url,
                {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken(),
                    },

                    body: JSON.stringify(body),
                }
            );

            const data = await response
                .json()
                .catch(() => ({}));

            if (!response.ok) {
                throw new Error(
                    data.message ||
                    'The request could not be completed.'
                );
            }

            return data;
        },


        // =========================================================
        // STEP 1 — TARE
        // =========================================================

        confirmTare() {
            if (
                !this.itemId ||
                this.busy
            ) {
                return;
            }

            this.showTareModal = true;
        },


        async runTare() {
            if (
                !this.itemId ||
                this.busy
            ) {
                return;
            }

            this.showTareModal = false;

            this.currentAction = 'tare';

            this.processTitle =
                'Step 1 of 3 — Taring Scale';

            await this.startCommand(
                () => this.postJson(
                    "{{ route('calibration.tare') }}",
                    {
                        item_id: this.itemId,
                    }
                ),

                'Resetting the empty shelf to zero…'
            );
        },


        // =========================================================
        // STEP 2 — CALIBRATE SCALE
        // =========================================================

        async runCalibrateScale() {
            if (
                !this.itemId ||
                this.busy ||
                Number(this.scaleReferenceWeight) <= 0
            ) {
                return;
            }

            this.showScaleModal = false;

            this.currentAction = 'scale';

            this.processTitle =
                'Step 2 of 3 — Calibrating Scale';

            await this.startCommand(
                () => this.postJson(
                    "{{ route('calibration.scale') }}",
                    {
                        item_id: this.itemId,

                        reference_weight:
                            Number(
                                this.scaleReferenceWeight
                            ),
                    }
                ),

                'Reading the known reference weight and calculating the scale factor…'
            );
        },


        // =========================================================
        // STEP 3 — SET UNIT WEIGHT
        // =========================================================

        async runSetUnitWeight() {
            if (
                !this.itemId ||
                this.busy
            ) {
                return;
            }

            this.showUnitModal = false;

            this.currentAction = 'unit_weight';

            this.processTitle =
                'Step 3 of 3 — Measuring Unit Weight';

            await this.startCommand(
                () => this.postJson(
                    "{{ route('calibration.unit-weight') }}",
                    {
                        item_id: this.itemId,
                    }
                ),

                'Measuring the single item. Keep exactly 1 piece stable on the shelf…'
            );
        },


        // =========================================================
        // START CALIBRATION COMMAND
        // =========================================================

        async startCommand(
            request,
            waitingMessage
        ) {
            this.busy = true;

            this.statusMessage =
                waitingMessage;

            if (['scale', 'unit_weight'].includes(this.currentAction)) {
                this.startPlacementCountdown();
            }

            try {
                const data =
                    await request();

                if (!data.command_id) {
                    throw new Error(
                        'No calibration command ID was returned.'
                    );
                }

                await this.pollCommand(
                    data.command_id
                );

            } catch (error) {

                this.resetProcessState();

                this.showModal(
                    'error',
                    'Unable to Start Calibration',
                    error.message ||
                    'Please try again.'
                );
            }
        },


        // =========================================================
        // POLL COMMAND STATUS
        // =========================================================

        async pollCommand(commandId) {
            const url =
                `{{ url('/calibration/commands') }}/${commandId}`;

            let attempts = 0;

            // Scale calibration and unit-weight calibration both include a 3-second
            // empty-shelf step followed by up to 15 seconds for placement.
            // Give the ESP32 a few extra seconds to POST its final result.
            const allowedAttempts =
                this.currentAction === 'unit_weight'
                    ? this.unitWeightCommandAttempts
                    : this.currentAction === 'scale'
                        ? this.scaleCalibrationCommandAttempts
                        : this.maxCommandAttempts;


            const poll = async () => {

                attempts++;


                try {

                    const response =
                        await fetch(
                            url,
                            {
                                headers: {
                                    Accept:
                                        'application/json',
                                },

                                cache:
                                    'no-store',
                            }
                        );


                    // ---------------------------------------------
                    // HTTP ERROR
                    // ---------------------------------------------

                    if (!response.ok) {

                        if (
                            attempts >=
                            allowedAttempts
                        ) {
                            await this.handleCommandTimeout(
                                commandId
                            );

                            return;
                        }

                        setTimeout(
                            poll,
                            this.commandPollInterval
                        );

                        return;
                    }


                    const data =
                        await response.json();


                    // ---------------------------------------------
                    // COMPLETED
                    // ---------------------------------------------

                    if (
                        data.status ===
                        'completed'
                    ) {
                        this.handleCommandCompleted();

                        return;
                    }


                    // ---------------------------------------------
                    // FAILED
                    // ---------------------------------------------

                    if (
                        data.status ===
                        'failed'
                    ) {
                        this.handleCommandFailed(
                            data
                        );

                        return;
                    }


                    // ---------------------------------------------
                    // TIMEOUT
                    // ---------------------------------------------

                    if (
                        attempts >=
                        allowedAttempts
                    ) {
                        await this.handleCommandTimeout(
                            commandId
                        );

                        return;
                    }


                    // ---------------------------------------------
                    // CONTINUE POLLING
                    // ---------------------------------------------

                    setTimeout(
                        poll,
                        this.commandPollInterval
                    );


                } catch (error) {

                    this.resetProcessState();

                    this.showModal(
                        'error',
                        'Unable to Confirm Calibration',
                        error.message ||
                        'Please try again.'
                    );
                }
            };


            poll();
        },


        // =========================================================
        // COMMAND COMPLETED
        // =========================================================

        handleCommandCompleted() {
            this.resetProcessState();


            const messages = {

                tare: [
                    'Scale Zeroed Successfully',

                    'Step 1 is complete. The empty shelf is now set to zero. Continue to Step 2.',
                ],


                scale: [
                    'Scale Calibrated Successfully',

                    'Step 2 is complete. The scale factor has been saved. Continue to Step 3.',
                ],


                unit_weight: [
                    'Unit Weight Set Successfully',

                    'Step 3 is complete. The item used for calibration is kept as Quantity 1. You can now add the remaining items directly without taring again.',
                ],
            };


            const message =
                messages[this.currentAction] ||
                [
                    'Calibration Completed',

                    'Calibration completed successfully.',
                ];


            this.showModal(
                'success',
                message[0],
                message[1]
            );
        },


        // =========================================================
        // COMMAND FAILED
        // =========================================================

        handleCommandFailed(data) {
            this.resetProcessState();


            let title =
                'Calibration Failed';

            let message =
                data.result?.message ||
                'The calibration could not be completed.';


            if (
                this.currentAction ===
                'tare'
            ) {
                title =
                    'Unable to Zero Scale';

                message =
                    'Make sure the shelf is empty and stable, then try Step 1 again.';
            }


            else if (
                this.currentAction ===
                'scale'
            ) {
                title =
                    'Reference Weight Not Detected';

                message =
                    'Check the reference weight, keep it stable on the shelf, then try Step 2 again.';
            }


            else if (
                this.currentAction ===
                'unit_weight'
            ) {
                title =
                    'Single Item Not Detected';

                message =
                    'Place exactly one item on the shelf, keep it stable, then try Step 3 again.';
            }


            this.showModal(
                'error',
                title,
                message
            );
        },


        // =========================================================
        // COMMAND TIMEOUT
        // =========================================================

        async handleCommandTimeout(
            commandId
        ) {
            this.resetProcessState();


            // Tell Laravel that this pending command has expired.
            // This prevents ESP32 from executing an old command
            // when it reconnects later.
            try {

                await this.postJson(
                    `{{ url('/calibration/commands') }}/${commandId}/expire`,
                    {}
                );

            } catch (error) {

                console.warn(
                    'Unable to expire calibration command:',
                    error
                );
            }


            if (this.currentAction === 'unit_weight') {
                this.showModal(
                    'error',
                    'Unit Weight Calibration Timed Out',
                    'The sensor did not return a final result within about 25 seconds. Retry Step 3. If the ESP32 is not responding at all, check its power and Wi-Fi connection.'
                );
                return;
            }

            if (this.currentAction === 'scale') {
                this.showModal(
                    'error',
                    'Reference Weight Calibration Timed Out',
                    'The sensor did not return a final result within about 25 seconds. Retry Step 2 and place the reference weight within 15 seconds.'
                );
                return;
            }

            this.showModal(
                'error',
                'Calibration Device Not Responding',
                'No response was received from the selected sensor. Make sure the ESP32 is powered on and connected to the same network, then try again.'
            );
        },

    };
}
</script>
@endsection
