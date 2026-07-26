# RankIt REST API Backend

This is the Laravel REST API backend service for the **RankIt** application stack. It manages categories, topics, candidate items, user submissions, and calculates Borda Count standings to power the leaderboards.

---

## 🛠️ Features & Tech Stack
- **Framework:** Laravel 10 (PHP)
- **Database:** MySQL (Default database: `rankit`)
- **Authentication Middleware:** Custom `VerifyFirebaseToken` middleware. It decodes Firebase ID Tokens (JWTs) on-the-fly, extracts client details, and dynamically registers or updates the user inside the local MySQL database.
- **Development Fallback:** Supports a raw string UID payload in the Authorization header (length >= 20 characters) to simplify local REST testing.

---

## ⚙️ Project Setup

### 1. Install Dependencies
Run composer to install PHP dependencies:
```bash
composer install
```

### 2. Configure Database Environment Settings
Rename `.env.example` to `.env` (if not already done) and configure your database settings to connect to your local MySQL server:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rankit
DB_USERNAME=root
DB_PASSWORD=YOUR_PASSWORD
```

### 3. Generate Application Key
```bash
php artisan key:generate
```

### 4. Build Schema & Seed Database
Run the migration command to construct the tables (using Firebase UIDs as string keys) and populate them with mock seed data:
```bash
php artisan migrate:fresh --seed
```

### 5. Launch Local Server
Start the Laravel development runner:
```bash
php artisan serve
```
By default, the server runs on `http://127.0.0.1:8000`.

---

## 🔌 API Endpoints Reference

| Method | Endpoint | Description | Auth Required |
|---|---|---|---|
| **POST** | `/api/register` | Legacy Sanctum registration | No |
| **POST** | `/api/login` | Legacy Sanctum login | No |
| **POST** | `/api/logout` | Invalidate session | Yes (Firebase) |
| **GET** | `/api/user` | Get current user profile (triggers sync) | Yes (Firebase) |
| **GET** | `/api/categories` | Fetch all available ranking categories | No |
| **GET** | `/api/topics` | Fetch all active voting topics | No |
| **GET** | `/api/topics/{id}` | Fetch details of a single topic | No |
| **POST** | `/api/submissions` | Post/update a user Borda Count ballot | No |
| **POST** | `/api/topics/{id}/suggestions` | Suggest candidate item for a topic | No |
| **GET** | `/api/leaderboard/{topicId}` | Fetch calculated Borda Count standings | No |

---

## 🔒 Custom Firebase JWT Middleware
Requests to protected endpoints (e.g. `/api/user`) must include the Firebase ID Token in the header:
```http
Authorization: Bearer <firebase_id_token>
```
The VerifyFirebaseToken middleware parses the token payload, fetches user information, and synchronizes the record dynamically into the MySQL `users` database table before authentication.
