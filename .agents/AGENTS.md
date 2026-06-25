# Workspace Guidelines - `leecommerce`

This file contains the project architecture, conventions, and configuration guidelines for the `leecommerce` workspace. It serves as a reference for developers and AI agents working on this codebase.

---

## 1. Project Architecture Overview

`leecommerce` is a Laravel 11 e-commerce application integrated with a Vue 2.7.16 frontend compiled via Vite.

### Tech Stack
- **Backend:** Laravel 11.54.0 (PHP >= 8.2 compatible).
- **Frontend:** Vue 2.7.16, Bootstrap 5.3.3, Tailwind CSS v2.2.19, jQuery 3.7.1, Axios 1.6.8, Lodash 4.17.21, and Popper.js (`@popperjs/core`).
- **Build Tool:** Vite 5.2.8 with `laravel-vite-plugin` and `@vitejs/plugin-vue2`.
- **Integrations:** Razorpay API, Paytm Wallet API, Slack Webhook.
- **Document Generation:** Dompdf (PDF invoices/receipts) and Maatwebsite Excel.
- **Barcodes:** Milon Barcode and Picqer Barcode Generator.

---

## 2. Core Development Conventions

### 2.1. Frontend Modules & Imports
- **ES Modules Syntax:** All client-side JavaScript must use **ES Modules** (`import`/`export`) instead of CommonJS (`require()`).
- **Bootstrap Integration:** Do not wrap imports in `try...catch` blocks. Statically import libraries at the top of the file:
  ```javascript
  import _ from 'lodash';
  window._ = _;
  import * as Popper from '@popperjs/core';
  window.Popper = Popper;
  import jQuery from 'jquery';
  window.$ = window.jQuery = jQuery;
  import 'bootstrap';
  import axios from 'axios';
  window.axios = axios;
  ```

### 2.2. SASS Imports
- **No Webpack Prefix:** Vite resolves imports from `node_modules` automatically. **Do not** prefix module imports with `~`.
  - **Correct:** `@import 'bootstrap/scss/bootstrap';`
  - **Incorrect:** `@import '~bootstrap/scss/bootstrap';`

### 2.3. Vue Component Registration
- **Runtime-Compiler Build:** The application boots Vue in Blade templates using runtime rendering. Always ensure Vue is aliased to the compiler build in `vite.config.js`:
  ```javascript
  resolve: {
      alias: {
          'vue': 'vue/dist/vue.esm.js',
      },
  }
  ```

### 2.4. SASS Deprecation Warnings
- **Warning Suppression:** To keep builds clean, SASS compiler warnings are silenced in `vite.config.js` via:
  ```javascript
  css: {
      preprocessorOptions: {
          scss: {
              quietDeps: true,
              silenceDeprecations: ['import', 'legacy-js-api'],
          },
      },
  }
  ```

---

## 3. Domain Model Reference

The application database schema consists of **20 tables** mapped to **18 Eloquent models**:
- **User & Security:** `User`, `Role` (Administrator, Store Manager, Web Admin, Customer).
- **Product Catalog:** `Category`, `SubCategory`, `ProductGroup`, `Product`.
- **Cart & Transactions:** `Cart`, `WishList`, `Address`, `Order`, `OrderData`.
- **Logistics & CMS:** `Purchase` (stock supply), `Offer` (coupons), `Setting`, `Slider`, `Feature`, `Page`, `Content`.

---

## 4. Key Layout Files
Assets are loaded using the `@vite()` directive in the head of these layouts:
- [website.blade.php](file:///Users/sandeep/Projects/leecommerce/resources/views/layouts/website.blade.php)
- [webadmin.blade.php](file:///Users/sandeep/Projects/leecommerce/resources/views/layouts/webadmin.blade.php)
- [administrator.blade.php](file:///Users/sandeep/Projects/leecommerce/resources/views/layouts/administrator.blade.php)
- [storemanager.blade.php](file:///Users/sandeep/Projects/leecommerce/resources/views/layouts/storemanager.blade.php)
