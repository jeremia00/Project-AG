# Mini Inventory & Purchase Order System (Laravel + Node.js)

A web-based **Inventory and Purchase Order Management System** built for restaurants, retail, or distribution businesses to track products, suppliers, purchase orders, partial goods receiving, and complete stock movement audit logs.

Designed with a modern SaaS user interface inspired by **Mekari Jurnal** using a custom palette (`#E3FDFD`, `#CBF1F5`, `#A6E3E9`, `#71C9CE`), integrated with **Node.js services and CLI tools**.

---

## 🛠 Technology Stack
* **Backend Framework:** Laravel 11 (PHP 8.2+)
* **Node.js Stack:** Node.js (v18+), ES Modules, `mysql2`, HTTP Microservices, Node CLI
* **Database:** MySQL / MariaDB (Database: `senja_inventory`)
* **Frontend:** Blade Templates, Bootstrap 5, Bootstrap Icons, Google Fonts (Plus Jakarta Sans), Vite Pipeline
* **Architecture:** MVC with Eloquent ORM + Node.js Analytics Microservices

---

## 🟢 Node.js Features & Tools Included

### 1. Node.js Inventory API Microservice
Start the Node.js REST API service listening on port 3000:
```bash
npm run node-api
```
Available REST endpoints:
* `GET http://localhost:3000/api/node/status`: Real-time system health and total metrics summary.
* `GET http://localhost:3000/api/node/low-stock`: Returns JSON list of all items below minimum stock.
* `GET http://localhost:3000/api/node/inventory-summary`: Returns total inventory valuation and catalog items.

### 2. Node.js Terminal CLI Tool
Execute terminal inventory inspections via Node.js:
```bash
npm run node-cli
```
Outputs formatted ASCII tables of product stock, low-stock warnings, and warehouse alerts directly in your terminal.

---

## 🚀 Installation & Setup Guide

### 1. Requirements
* PHP >= 8.2 with PDO extension
* Node.js >= 18.0 & NPM
* Composer
* MySQL Database

### 2. Steps to Run Locally
1. Clone the repository:
   ```bash
   git clone <repository_url>
   cd "Project AG"
   ```
2. Install PHP & Node.js dependencies:
   ```bash
   composer install
   npm install
   ```
3. Setup Environment File:
   ```bash
   cp .env.example .env
   ```
4. Configure Database connection in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=senja_inventory
   DB_USERNAME=root
   DB_PASSWORD=
   ```
5. Create database, run migrations & seed test data:
   ```bash
   mysql -u root -e "CREATE DATABASE IF NOT EXISTS senja_inventory;"
   php artisan migrate:fresh --seed
   ```
6. Start local servers:
   - **Laravel Web App:** `php artisan serve` (http://127.0.0.1:8000)
   - **Node.js Service:** `npm run node-api` (http://localhost:3000)
   - **Node.js CLI Inspector:** `npm run node-cli`

---

## 🏗 Architecture & Main Workflow

The application strictly enforces the core business flow:

$$\text{Product} \longrightarrow \text{Supplier} \longrightarrow \text{Purchase Order (Ordered)} \longrightarrow \text{Goods Receiving} \longrightarrow \text{Inventory Updated}$$

---

## 📊 Database Schema & Data Structure

* **`suppliers`**: `id`, `name`, `contact_person`, `phone`, `email`, `address`, `timestamps`
* **`products`**: `id`, `name`, `sku` (unique), `category`, `unit`, `current_stock`, `minimum_stock`, `purchase_price`, `supplier_id` (foreign key), `timestamps`
* **`purchase_orders`**: `id`, `po_number` (unique), `supplier_id` (foreign key), `order_date`, `status` (`Draft`, `Ordered`, `Partially Received`, `Received`, `Cancelled`), `total_amount`, `timestamps`
* **`purchase_order_items`**: `id`, `purchase_order_id` (foreign key), `product_id` (foreign key), `quantity_ordered`, `quantity_received`, `purchase_price`, `line_total`, `timestamps`
* **`inventory_histories`**: `id`, `product_id` (foreign key), `quantity_change`, `reason`, `timestamps`

---

## 🤖 AI Tools Usage

* **AI Tools Used:** Google Antigravity / Gemini / ChatGPT.
* **How AI Was Used:**
  - Generating model, controller, Node.js API, and migration structures.
  - Designing UI Blade views based on Mekari Jurnal styling and custom color palettes (`#E3FDFD`, `#CBF1F5`, `#A6E3E9`, `#71C9CE`).
  - Building Node.js CLI inspection utilities and Node.js microservices.

---

## 🔍 Problem & Solution Log

### Problem 1: Premature Stock Increase on Purchase Order Creation
* **What Happened:** Creating a Purchase Order originally incremented product stock levels in the database.
* **Why It Happened:** Stock update logic was initially placed inside `PurchaseOrderController@store`.
* **How It Was Solved:** Removed stock increment code from PO creation. Restructured the architecture so stock increases occur strictly inside `PurchaseOrderController@receive` within a database transaction.

### Problem 2: Over-receiving Goods Beyond Ordered Quantity
* **What Happened:** Users could accidentally enter a receiving quantity greater than the remaining ordered amount.
* **Why It Happened:** Absence of backend validation checking incoming receive quantities against `(quantity_ordered - quantity_received)`.
* **How It Was Solved:** Implemented custom validation in `PurchaseOrderController@receive` that throws a `ValidationException` if `receive_qty > remaining_qty` for any line item.

### Problem 3: Stock Adjustments Without Audit Trail & Negative Stock Vulnerability
* **What Happened:** Manual stock adjustments directly updated `current_stock` without recording reasons.
* **Why It Happened:** Stock edits were executed as raw model updates without historical logging or validation bounds.
* **How It Was Solved:** Enclosed manual adjustments in a database transaction (`DB::transaction`) that validates `current_stock >= decrease_amount` and automatically inserts a record into `inventory_histories`.
