# Smart Shelf

Smart Shelf is a web app for tracking inventory on shelves fitted with weight sensors. Each product sits on a load cell (HX711 + ESP32), and the shelf reports its weight so the system can work out how many units are left without anyone counting by hand.

## What it does

- **Product registry** — each product has a name, product code, unit weight (grams per item), a minimum stock threshold, and the ID of the sensor it's linked to.
- **Stock tracking by weight** — because each product's unit weight is known, the total weight read from a shelf's sensor can be converted into a live item count.
- **Low-stock awareness** — the `min_stock` threshold on each product is the basis for flagging when a shelf needs restocking.
- **User accounts** — registration, login, email verification, password reset, and profile management (built on Laravel's standard auth scaffolding).

## Tech stack

- **Backend:** Laravel 12 (PHP 8.2+)
- **Frontend:** Blade templates, Tailwind CSS, Alpine.js
- **Build tool:** Vite
- **Database:** MySQL (configurable via `.env`)
- **Hardware (external to this repo):** ESP32 microcontrollers with HX711 load cell amplifiers report shelf weight

## Getting started

### Requirements
- PHP 8.2+
- Composer
- Node.js + npm
- MySQL

### Setup

```bash
composer install
npm install
cp .env.example .env      # create this if it doesn't exist yet
php artisan key:generate
```

Update `.env` with your database credentials, then:

```bash
php artisan migrate
npm run build      # or `npm run dev` while developing
php artisan serve
```

Visit `http://localhost:8000`.

## Project structure

- `app/Models/Product.php` — the product/shelf-item model
- `app/Http/Controllers/ProductController.php` — CRUD for products
- `database/migrations/*_create_products_table.php` — products table schema (name, product_code, unit_weight, min_stock, sensor_id)
- `resources/views/products/` — product listing and creation pages

## Roadmap ideas

- Live sensor readings feeding directly into stock counts
- Restock alerts/notifications when a product drops below `min_stock`
- Dashboard view showing all shelves at a glance

## License

This project is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).