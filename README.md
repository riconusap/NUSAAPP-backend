# NUSAAPP Backend API

**Garden Management System - REST API**

A comprehensive Laravel-based REST API for managing garden maintenance operations, including employee management, client contracts, task assignments, GPS-validated attendance tracking, and inventory management.

## 🚀 Tech Stack

- **Framework**: Laravel 11
- **Language**: PHP 8.3
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Sanctum (Bearer Token)
- **Authorization**: Spatie Laravel Permission
- **API Style**: RESTful

## ✨ Features

### Module 1: Authentication & Authorization
- User registration and login
- Token-based authentication (Laravel Sanctum)
- Role-Based Access Control (RBAC)
- 5 predefined roles with 55 granular permissions

### Module 2: User Management
- User CRUD operations
- Role assignment
- Permission management
- Search and filtering

### Module 3: Employee & HR Management
- Employee records management
- Employment contracts tracking
- Leave request system with approval workflow
- Employee status management (Active/Inactive/Terminated)

### Module 4: Client & Site Management
- Client information management
- Client contracts with value tracking
- Multiple sites per client
- Area management within sites
- GPS coordinates for geofencing

### Module 5: Operations Module
#### Task Management
- Task CRUD with priority levels (Low/Medium/High/Urgent)
- Task types: Daily, Weekly, Monthly, Yearly, Accidental
- Task assignment to employees
- Activity logging with before/after photos
- Overdue task detection

#### Attendance System (GPS-Validated)
- Clock in/out with GPS coordinates
- Automatic location validation using Haversine formula
- Status auto-determination:
  - **Present**: Clock in before 8:00 AM
  - **Late**: Clock in 8:00 AM - 12:00 PM
  - **Half-day**: Clock in after 12:00 PM
- Working hours calculation
- Duplicate prevention

#### Inventory Management
- Master inventory items (Tools, Materials, Fertilizers, Chemicals)
- Site-specific stock tracking
- Low stock alerts (< 10 units)
- Consumable vs non-consumable items
- Multiple unit types (pcs, kg, liter, zak)

## 📋 Requirements

- PHP >= 8.3
- Composer
- MySQL >= 8.0
- Node.js & NPM (for asset compilation)

## 🔧 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd NUSAAPP-backend
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure database**
   
   Edit `.env` file:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nusaapp
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate --seed
   ```

6. **Start development server**
   ```bash
   php artisan serve
   ```

   API will be available at: `http://localhost:8000/api`

## 🔑 Default Credentials

After seeding, use these credentials to login:

**Super Admin:**
- Email: `superadmin@nusaapp.com`
- Password: `password`

**Admin:**
- Email: `admin@nusaapp.com`
- Password: `password`

## 📚 Documentation

### API Documentation
Complete API documentation with all endpoints, request/response examples: [API_DOCUMENTATION.md](API_DOCUMENTATION.md)

### Postman Collection
Import the Postman collection for easy API testing: [NUSAAPP.postman_collection.json](NUSAAPP.postman_collection.json)

**How to use:**
1. Open Postman
2. Click **Import** → **Upload Files**
3. Select `NUSAAPP.postman_collection.json`
4. The collection includes auto-token capture on login
5. Use the **Login** request first to authenticate

## 🛠️ Key Technologies & Concepts

### GPS Validation
- Uses **Haversine formula** for accurate distance calculation
- Validates employee location against site's allowed radius
- Earth's curvature accounted for in calculations

### Role-Based Access Control
**5 Roles:**
1. **Super Admin** - Full system access
2. **Admin** - Administrative operations
3. **Manager** - Day-to-day operations management
4. **Supervisor** - Team supervision, leave approvals
5. **Staff** - Basic operations (tasks, attendance)

**55 Permissions** across all modules

### Database Design
- **UUID primary keys** for all models
- **Soft deletes** enabled on most tables
- **Timestamps** for audit trail
- **Foreign key constraints** for data integrity

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/Api/    # API Controllers
│   ├── Requests/           # Form Request Validation
│   └── Resources/          # API Resources (Transformers)
├── Models/                 # Eloquent Models
└── Providers/
database/
├── migrations/             # Database migrations
└── seeders/               # Data seeders
routes/
└── api.php                # API routes
```

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

## 🔐 Security Features

- Token-based authentication
- Permission-based authorization
- SQL injection protection (Eloquent ORM)
- XSS protection
- CSRF protection for web routes
- Rate limiting on API endpoints
- Encrypted passwords (bcrypt)

## 📊 Database Statistics

- **37 Tables** total
- **23 Custom tables** from ERD
- **14 Laravel/Package tables**
- **UUID-based relationships**

## 🚦 API Response Format

All API responses follow this format:

**Success:**
```json
{
  "success": true,
  "message": "Success message",
  "data": { ... }
}
```

**Error:**
```json
{
  "success": false,
  "message": "Error message",
  "errors": { ... }
}
```

## 🔄 API Versioning

Current version: **v1.0**

Base URL: `http://localhost:8000/api`

## 📝 License

This project is proprietary software.

---

**Built with ❤️ using Laravel 11**
