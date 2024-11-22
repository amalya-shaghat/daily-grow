# Financial Report System

A Laravel + Vue.js app for managing financial transactions.

## Features
1. **Authentication**: Login and registration.
2. **Dashboard**: View transactions, import 1C statements, and manage operations.
3. **Transactions**:
    - Grouped by date.
    - Includes counterparty, amount, type (income/expense), description, and actions (copy/delete).

## Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/amalya-shaghat/daily-grow.git
   cd daily-grow
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Configure `.env` and run migrations:
   ```bash
   php artisan migrate
   ```
4. Start the Laravel server:
   ```bash
   php artisan serve --port=9000
   ```

Access the app at: `http://127.0.0.1:9000`.

## Usage
1. Login or register.
2. Import financial statements from 1C.
3. Add, copy, or delete transactions.

---
