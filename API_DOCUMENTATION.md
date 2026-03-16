# NUSAAPP Backend API Documentation

**Base URL:** `http://localhost:8000/api`  
**Version:** 1.0  
**Last Updated:** March 13, 2026

## Table of Contents
1. [Authentication](#authentication)
2. [Users](#users)
3. [Roles](#roles)
4. [Permissions](#permissions)
5. [Employees](#employees)
6. [Employee Contracts](#employee-contracts)
7. [Leave Requests](#leave-requests)
8. [Clients](#clients)
9. [Client Contracts](#client-contracts)
10. [Sites](#sites)
11. [Areas](#areas)
12. [Tasks](#tasks)
13. [Task Logs](#task-logs)
14. [Attendance](#attendance)
15. [Inventory Items](#inventory-items)
16. [Site Inventories](#site-inventories)
17. [Payrolls](#payrolls)
18. [Invoice Plans](#invoice-plans)
19. [Invoices](#invoices)
20. [Transactions](#transactions)

---

## Authentication

All endpoints except login and register require authentication using Bearer token.

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
Accept: application/json
```

### Login
**POST** `/auth/login`

**Request Body:**
```json
{
  "email": "superadmin@nusaapp.com",
  "password": "password"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": "uuid",
      "name": "Super Admin",
      "email": "superadmin@nusaapp.com",
      "roles": ["Super Admin"],
      "permissions": ["create_users", "edit_users", ...]
    },
    "token": "1|abcdef123456..."
  }
}
```

### Register
**POST** `/auth/register`

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": { ... },
    "token": "2|xyz789..."
  }
}
```

### Logout
**POST** `/auth/logout`

**Response (200):**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

### Get Profile
**GET** `/auth/profile`

**Response (200):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "id": "uuid",
    "name": "Super Admin",
    "email": "superadmin@nusaapp.com",
    "roles": ["Super Admin"],
    "permissions": ["create_users", ...]
  }
}
```

---

## Users

### List Users
**GET** `/users`

**Query Parameters:**
- `search` (optional): Search by name or email
- `role` (optional): Filter by role name
- `per_page` (optional): Items per page (default: 15)

**Permission Required:** `view_users`

**Response (200):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "users": [
      {
        "id": "uuid",
        "name": "Admin User",
        "email": "admin@example.com",
        "roles": ["Admin"],
        "permissions": ["create_users", ...],
        "created_at": "2026-03-03T10:00:00.000000Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 5,
      "per_page": 15,
      "total": 67
    }
  }
}
```

### Get User
**GET** `/users/{id}`

**Permission Required:** `view_users`

### Create User
**POST** `/users`

**Permission Required:** `create_users`

**Request Body:**
```json
{
  "name": "New User",
  "email": "newuser@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "role_ids": ["role-uuid-1", "role-uuid-2"]
}
```

### Update User
**PUT/PATCH** `/users/{id}`

**Permission Required:** `edit_users`

**Request Body:**
```json
{
  "name": "Updated Name",
  "email": "updated@example.com",
  "role_ids": ["role-uuid-1"]
}
```

### Delete User
**DELETE** `/users/{id}`

**Permission Required:** `delete_users`

---

## Roles

### List Roles
**GET** `/roles`

**Query Parameters:**
- `search` (optional): Search by role name
- `with_permissions` (optional): Include permissions for each role (true/false)
- `per_page` (optional): Items per page (default: 15). Omit for all roles without pagination

**Permission Required:** Authenticated user

**Response (200):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "roles": [
      {
        "id": 1,
        "name": "Super Admin",
        "guard_name": "web",
        "permissions": ["create_users", "edit_users", "delete_users", ...],
        "created_at": "2026-03-03T10:00:00.000000Z",
        "updated_at": "2026-03-03T10:00:00.000000Z"
      },
      {
        "id": 2,
        "name": "Admin",
        "guard_name": "web",
        "created_at": "2026-03-03T10:00:00.000000Z",
        "updated_at": "2026-03-03T10:00:00.000000Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 1,
      "per_page": 15,
      "total": 5
    }
  }
}
```

### Get Role Access Detail
**GET** `/roles/{id}`

Get full access detail (permissions) for a specific role.

**Permission Required:** Authenticated user

**Response (200):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "role": {
      "id": 1,
      "name": "Admin",
      "guard_name": "web",
      "total_access": 12,
      "access_detail": [
        {
          "id": 1,
          "name": "view_users",
          "guard_name": "web"
        },
        {
          "id": 2,
          "name": "create_users",
          "guard_name": "web"
        }
      ],
      "created_at": "2026-03-03T10:00:00.000000Z",
      "updated_at": "2026-03-03T10:00:00.000000Z"
    }
  }
}
```

### Create Role
**POST** `/roles`

**Permission Required:** `create_roles`

**Request Body:**
```json
{
  "name": "finance-admin",
  "guard_name": "web",
  "permissions": ["view_transactions", "create_transactions"]
}
```

### Update Role
**PUT/PATCH** `/roles/{id}`

**Permission Required:** `edit_roles`

**Request Body:**
```json
{
  "name": "finance-admin",
  "permissions": ["view_transactions", "create_transactions", "edit_transactions"]
}
```

### Delete Role
**DELETE** `/roles/{id}`

**Permission Required:** `delete_roles`

**Response (422):** Role cannot be deleted if it is assigned to users.

**Response without pagination:**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "roles": [
      {
        "id": 1,
        "name": "Super Admin",
        "guard_name": "web",
        "created_at": "2026-03-03T10:00:00.000000Z",
        "updated_at": "2026-03-03T10:00:00.000000Z"
      }
    ]
  }
}
```

---

## Permissions

### List Permissions
**GET** `/permissions`

**Query Parameters:**
- `search` (optional): Search by permission name
- `per_page` (optional): Items per page (default: 15). Omit for all permissions without pagination

**Permission Required:** `view_roles`

**Response (200):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "permissions": [
      {
        "id": 1,
        "name": "view_users",
        "guard_name": "web",
        "created_at": "2026-03-03T10:00:00.000000Z",
        "updated_at": "2026-03-03T10:00:00.000000Z"
      }
    ],
    "pagination": {
      "current_page": 1,
      "last_page": 1,
      "per_page": 15,
      "total": 62
    }
  }
}
```

---

## Employees

### List Employees
**GET** `/employees`

**Query Parameters:**
- `search` (optional): Search by name, email, or phone
- `gender` (optional): Filter by gender (Male/Female)
- `status` (optional): Filter by status (Active/Inactive/Terminated)
- `per_page` (optional): Items per page (default: 15)

**Permission Required:** `view_employees`

**Response (200):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "employees": [
      {
        "id": "uuid",
        "name": "Agus Setiawan",
        "email": "agus@nusaapp.com",
        "phone": "081234567890",
        "id_card_number": "3201234567890001",
        "gender": "Male",
        "date_of_birth": "1990-05-15",
        "address": "Jl. Mawar No. 10",
        "city": "Jakarta",
        "province": "DKI Jakarta",
        "postal_code": "12345",
        "status": "Active",
        "hire_date": "2024-01-15",
        "photo": null,
        "created_at": "2026-03-03T10:00:00.000000Z"
      }
    ],
    "pagination": { ... }
  }
}
```

### Get Employee
**GET** `/employees/{id}`

**Permission Required:** `view_employees`

### Create Employee
**POST** `/employees`

**Permission Required:** `create_employees`

**Notes:**
- Field `nip` is auto-generated by system with format `NIP{SITE_ID}YY####` (example for `site_id: 7` => `NIP007260001`)
- Field `site_id` is required for NIP generation and is not saved in employee table
- Do not send `nip` in request body

**Request Body:**
```json
{
  "site_id": 7,
  "name": "New Employee",
  "email": "employee@example.com",
  "phone": "081234567890",
  "id_card_number": "3201234567890001",
  "gender": "Male",
  "date_of_birth": "1995-06-20",
  "address": "Jl. Address",
  "city": "Jakarta",
  "province": "DKI Jakarta",
  "postal_code": "12345",
  "status": "Active",
  "hire_date": "2026-03-01"
}
```

### Update Employee
**PUT/PATCH** `/employees/{id}`

**Permission Required:** `edit_employees`

### Delete Employee
**DELETE** `/employees/{id}`

**Permission Required:** `delete_employees`

---

## Employee Contracts

### List Employee Contracts
**GET** `/employee-contracts`

**Query Parameters:**
- `employee_id` (optional): Filter by employee
- `contract_type` (optional): Filter by type (Permanent/Contract/Internship)
- `per_page` (optional): Items per page (default: 15)

**Permission Required:** `view_employee_contracts`

**Response (200):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "contracts": [
      {
        "id": "uuid",
        "employee_id": "uuid",
        "employee": {
          "id": "uuid",
          "name": "Agus Setiawan",
          "email": "agus@nusaapp.com"
        },
        "contract_type": "Permanent",
        "position": "Gardener",
        "start_date": "2024-01-15",
        "end_date": null,
        "salary": "5000000.00",
        "contract_file": "/storage/contracts/file.pdf",
        "created_at": "2026-03-03T10:00:00.000000Z"
      }
    ],
    "pagination": { ... }
  }
}
```

### Create Employee Contract
**POST** `/employee-contracts`

**Permission Required:** `create_employee_contracts`

**Request Body:**
```json
{
  "employee_id": "uuid",
  "contract_type": "Permanent",
  "position": "Senior Gardener",
  "start_date": "2026-03-01",
  "end_date": null,
  "salary": 6000000,
  "contract_file": "/path/to/file.pdf"
}
```

### Update Employee Contract
**PUT/PATCH** `/employee-contracts/{id}`

**Permission Required:** `edit_employee_contracts`

### Delete Employee Contract
**DELETE** `/employee-contracts/{id}`

**Permission Required:** `delete_employee_contracts`

---

## Leave Requests

### List Leave Requests
**GET** `/leave-requests`

**Query Parameters:**
- `employee_id` (optional): Filter by employee
- `leave_type` (optional): Filter by type (Sick Leave/Annual Leave/Unpaid Leave/Other)
- `status` (optional): Filter by status (Pending/Approved/Rejected)
- `per_page` (optional): Items per page (default: 15)

**Permission Required:** `view_leave_requests`

**Response (200):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "leave_requests": [
      {
        "id": "uuid",
        "employee_id": "uuid",
        "employee": {
          "id": "uuid",
          "name": "Agus Setiawan"
        },
        "leave_type": "Annual Leave",
        "start_date": "2026-03-10",
        "end_date": "2026-03-12",
        "days_requested": 3,
        "reason": "Family vacation",
        "status": "Pending",
        "approved_by": null,
        "approved_at": null,
        "rejection_reason": null,
        "created_at": "2026-03-03T10:00:00.000000Z"
      }
    ],
    "pagination": { ... }
  }
}
```

### Create Leave Request
**POST** `/leave-requests`

**Permission Required:** `create_leave_requests`

**Request Body:**
```json
{
  "employee_id": "uuid",
  "leave_type": "Annual Leave",
  "start_date": "2026-03-15",
  "end_date": "2026-03-17",
  "reason": "Personal matters"
}
```

### Approve Leave Request
**POST** `/leave-requests/{id}/approve`

**Permission Required:** `approve_leave_requests`

**Response (200):**
```json
{
  "success": true,
  "message": "Leave request approved",
  "data": { ... }
}
```

### Reject Leave Request
**POST** `/leave-requests/{id}/reject`

**Permission Required:** `approve_leave_requests`

**Request Body:**
```json
{
  "rejection_reason": "Not enough coverage during this period"
}
```

---

## Clients

### List Clients
**GET** `/clients`

**Query Parameters:**
- `search` (optional): Search by name or PIC name
- `per_page` (optional): Items per page (default: 15)

**Permission Required:** `view_clients`

**Response (200):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "clients": [
      {
        "id": "uuid",
        "name": "PT Green Garden Indonesia",
        "logo": "/storage/logos/client.png",
        "headquarter_address": "Jl. Sudirman No. 123, Jakarta",
        "pic_name": "Budi Santoso",
        "pic_phone": "081298765432",
        "sites": [
          {
            "id": "uuid",
            "site_name": "Taman Kota Jakarta"
          }
        ],
        "contracts": [
          {
            "id": "uuid",
            "contract_number": "CTR-2024-001"
          }
        ],
        "created_at": "2026-03-03T10:00:00.000000Z"
      }
    ],
    "pagination": { ... }
  }
}
```

### Create Client
**POST** `/clients`

**Permission Required:** `create_clients`

**Request Body:**
```json
{
  "name": "PT New Client",
  "headquarter_address": "Jl. Address",
  "pic_name": "John Doe",
  "pic_phone": "081234567890"
}
```

### Update Client
**PUT/PATCH** `/clients/{id}`

**Permission Required:** `edit_clients`

### Delete Client
**DELETE** `/clients/{id}`

**Permission Required:** `delete_clients`

---

## Client Contracts

### List Client Contracts
**GET** `/client-contracts`

**Query Parameters:**
- `client_id` (optional): Filter by client
- `contract_type` (optional): Filter by type (Service/Maintenance/Project)
- `active_only` (optional): Show only active contracts (true/false)
- `per_page` (optional): Items per page (default: 15)

**Permission Required:** `view_contracts`

**Response (200):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "contracts": [
      {
        "id": "uuid",
        "client_id": "uuid",
        "client": {
          "id": "uuid",
          "name": "PT Green Garden Indonesia"
        },
        "contract_number": "CTR-2024-001",
        "contract_type": "Maintenance",
        "start_date": "2024-01-01",
        "end_date": "2024-12-31",
        "value": "120000000.00",
        "contract_file": "/storage/contracts/file.pdf",
        "notes": "Monthly garden maintenance",
        "is_active": true,
        "created_at": "2026-03-03T10:00:00.000000Z"
      }
    ],
    "pagination": { ... }
  }
}
```

### Create Client Contract
**POST** `/client-contracts`

**Permission Required:** `create_contracts`

**Request Body:**
```json
{
  "client_id": "uuid",
  "contract_number": "CTR-2026-005",
  "contract_type": "Service",
  "start_date": "2026-04-01",
  "end_date": "2027-03-31",
  "value": 150000000,
  "notes": "Annual service contract"
}
```

---

## Sites

### List Sites
**GET** `/sites`

**Query Parameters:**
- `client_id` (optional): Filter by client
- `per_page` (optional): Items per page (default: 15)

**Permission Required:** `view_sites`

**Response (200):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "sites": [
      {
        "id": "uuid",
        "client_id": "uuid",
        "client": {
          "id": "uuid",
          "name": "PT Green Garden Indonesia"
        },
        "site_name": "Taman Kota Jakarta Pusat",
        "address": "Jl. Thamrin, Jakarta Pusat",
        "latitude": "-6.175100",
        "longitude": "106.865000",
        "radius_meters": 150,
        "areas": [
          {
            "id": "uuid",
            "area_name": "Taman Depan"
          }
        ],
        "created_at": "2026-03-03T10:00:00.000000Z"
      }
    ],
    "pagination": { ... }
  }
}
```

### Create Site
**POST** `/sites`

**Permission Required:** `create_sites`

**Request Body:**
```json
{
  "client_id": "uuid",
  "site_name": "New Garden Site",
  "address": "Jl. New Address",
  "latitude": -6.175,
  "longitude": 106.865,
  "radius_meters": 100
}
```

**Note:** `latitude`, `longitude`, and `radius_meters` are used for GPS-based attendance validation.

---

## Areas

### List Areas
**GET** `/areas`

**Query Parameters:**
- `site_id` (optional): Filter by site
- `per_page` (optional): Items per page (default: 15)

**Permission Required:** `view_areas`

**Response (200):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "areas": [
      {
        "id": "uuid",
        "site_id": "uuid",
        "site": {
          "id": "uuid",
          "site_name": "Taman Kota Jakarta"
        },
        "area_name": "Taman Depan",
        "surface_area_m2": "500.50",
        "current_condition_image": "/storage/areas/condition.jpg",
        "tasks": [
          {
            "id": "uuid",
            "title": "Weekly Grass Cutting"
          }
        ],
        "created_at": "2026-03-03T10:00:00.000000Z"
      }
    ],
    "pagination": { ... }
  }
}
```

### Create Area
**POST** `/areas`

**Permission Required:** `create_areas`

**Request Body:**
```json
{
  "site_id": "uuid",
  "area_name": "Zone B - Garden",
  "surface_area_m2": 750.25
}
```

---

## Tasks

### List Tasks
**GET** `/tasks`

**Query Parameters:**
- `area_id` (optional): Filter by area
- `assigned_to_id` (optional): Filter by assigned employee
- `status` (optional): Filter by status (To Do/In Progress/Review/Completed)
- `priority` (optional): Filter by priority (Low/Medium/High/Urgent)
- `task_type` (optional): Filter by type (Daily/Weekly/Monthly/Yearly/Accidental)
- `overdue` (optional): Show only overdue tasks (true/false)
- `search` (optional): Search by title or description
- `per_page` (optional): Items per page (default: 15)

**Permission Required:** `view_tasks`

**Response (200):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "tasks": [
      {
        "id": "uuid",
        "area_id": "uuid",
        "area": {
          "id": "uuid",
          "site_id": "uuid",
          "site": {
            "id": "uuid",
            "client_id": "uuid",
            "client": {
              "id": "uuid",
              "name": "PT Green Garden Indonesia"
            },
            "site_name": "Taman Kota Jakarta"
          },
          "area_name": "Taman Depan"
        },
        "assigned_to_id": "uuid",
        "assigned_to": {
          "id": "uuid",
          "name": "Agus Setiawan"
        },
        "title": "Weekly Grass Cutting",
        "description": "Cut grass in all zones of Area A",
        "task_type": "Weekly",
        "priority": "High",
        "status": "In Progress",
        "due_date": "2026-03-15",
        "is_overdue": false,
        "logs": [
          {
            "id": "uuid",
            "activity_note": "Started work"
          }
        ],
        "created_at": "2026-03-03T10:00:00.000000Z"
      }
    ],
    "pagination": { ... }
  }
}
```

### Create Task
**POST** `/tasks`

**Permission Required:** `create_tasks`

**Request Body:**
```json
{
  "area_id": "uuid",
  "assigned_to_id": "uuid",
  "title": "Monthly Tree Pruning",
  "description": "Prune all trees in the area",
  "task_type": "Monthly",
  "priority": "High",
  "status": "To Do",
  "due_date": "2026-03-20"
}
```

**Task Types:** Daily, Weekly, Monthly, Yearly, Accidental  
**Priority Levels:** Low, Medium, High, Urgent  
**Status:** To Do, In Progress, Review, Completed

### Get Task
**GET** `/tasks/{id}`

**Permission Required:** `view_tasks`

### Update Task
**PUT/PATCH** `/tasks/{id}`

**Permission Required:** `edit_tasks`

### Delete Task
**DELETE** `/tasks/{id}`

**Permission Required:** `delete_tasks`

### Assign Task
**POST** `/tasks/{id}/assign`

**Permission Required:** `assign_tasks`

**Request Body:**
```json
{
  "assigned_to_id": "employee-uuid"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Task assigned successfully",
  "data": { ... }
}
```

---

## Task Logs

### List Task Logs
**GET** `/task-logs`

**Query Parameters:**
- `task_id` (optional): Filter by task
- `employee_id` (optional): Filter by employee
- `per_page` (optional): Items per page (default: 15)

**Permission Required:** `view_tasks`

**Response (200):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "task_logs": [
      {
        "id": "uuid",
        "task_id": "uuid",
        "task": {
          "id": "uuid",
          "title": "Weekly Grass Cutting",
          "area": {
            "area_name": "Taman Depan"
          }
        },
        "employee_id": "uuid",
        "employee": {
          "id": "uuid",
          "name": "Agus Setiawan"
        },
        "activity_note": "Completed grass cutting. Tools cleaned.",
        "photo_before": "/storage/task-logs/before.jpg",
        "photo_after": "/storage/task-logs/after.jpg",
        "created_at": "2026-03-03T10:00:00.000000Z"
      }
    ],
    "pagination": { ... }
  }
}
```

### Create Task Log
**POST** `/task-logs`

**Permission Required:** `edit_tasks`

**Request Body:**
```json
{
  "task_id": "uuid",
  "employee_id": "uuid",
  "activity_note": "Completed the task. All areas cleaned.",
  "photo_before": "/path/to/before.jpg",
  "photo_after": "/path/to/after.jpg"
}
```

### Get Task Log
**GET** `/task-logs/{id}`

**Permission Required:** `view_tasks`

### Delete Task Log
**DELETE** `/task-logs/{id}`

**Permission Required:** `edit_tasks`

---

## Attendance

### List Attendances
**GET** `/attendances`

**Query Parameters:**
- `employee_id` (optional): Filter by employee
- `site_id` (optional): Filter by site
- `date` (optional): Filter by specific date (YYYY-MM-DD)
- `start_date` (optional): Filter from date
- `end_date` (optional): Filter to date
- `status` (optional): Filter by status (Present/Late/Half-day/Absent)
- `per_page` (optional): Items per page (default: 15)

**Permission Required:** `view_attendances`

**Response (200):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "attendances": [
      {
        "id": "uuid",
        "employee_id": "uuid",
        "employee": {
          "id": "uuid",
          "name": "Agus Setiawan"
        },
        "site_id": "uuid",
        "site": {
          "id": "uuid",
          "site_name": "Taman Kota Jakarta"
        },
        "date": "2026-03-03",
        "clock_in": "2026-03-03 07:45:00",
        "clock_out": "2026-03-03 16:30:00",
        "latitude_in": "-6.175100",
        "longitude_in": "106.865000",
        "selfie_path_in": "/storage/attendance/in.jpg",
        "selfie_path_out": "/storage/attendance/out.jpg",
        "status": "Present",
        "working_hours": 8.75,
        "is_within_radius": true,
        "created_at": "2026-03-03T07:45:00.000000Z"
      }
    ],
    "pagination": { ... }
  }
}
```

### Clock In
**POST** `/attendances/clock-in`

**Permission Required:** `create_attendances`

**Request Body:**
```json
{
  "employee_id": "uuid",
  "site_id": "uuid",
  "date": "2026-03-03",
  "latitude": -6.175100,
  "longitude": 106.865000,
  "selfie_path": "/storage/selfies/in.jpg"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "Clocked in successfully",
  "data": {
    "id": "uuid",
    "status": "Present",
    "is_within_radius": true,
    "working_hours": null,
    ...
  }
}
```

**Status Determination:**
- **Present**: Clock in before 8:00 AM
- **Late**: Clock in between 8:00 AM - 12:00 PM
- **Half-day**: Clock in after 12:00 PM

**GPS Validation:**
- System validates if employee's location is within site's radius
- Uses Haversine formula for accurate distance calculation
- Returns `is_within_radius: false` if outside allowed area

**Errors:**
- `400`: Already clocked in for this site today
- `400`: Clock-in location is outside the allowed site radius

### Clock Out
**POST** `/attendances/clock-out`

**Permission Required:** `create_attendances`

**Request Body:**
```json
{
  "employee_id": "uuid",
  "site_id": "uuid",
  "date": "2026-03-03",
  "selfie_path": "/storage/selfies/out.jpg"
}
```

**Response (200):**
```json
{
  "success": true,
  "message": "Clocked out successfully",
  "data": {
    "id": "uuid",
    "status": "Present",
    "working_hours": 8.75,
    ...
  }
}
```

**Working Hours Calculation:**
- Automatically calculated based on clock_in and clock_out times
- Returned in decimal hours (e.g., 8.75 = 8 hours 45 minutes)

**Errors:**
- `404`: No clock-in record found for today
- `400`: Already clocked out

### Get Attendance
**GET** `/attendances/{id}`

**Permission Required:** `view_attendances`

---

## Inventory Items

### List Inventory Items
**GET** `/inventory-items`

**Query Parameters:**
- `category` (optional): Filter by category (Tool/Material/Fertilizer/Chemical)
- `consumable_only` (optional): Show only consumable items (true/false)
- `search` (optional): Search by item name
- `per_page` (optional): Items per page (default: 15)

**Permission Required:** `view_inventory`

**Response (200):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "inventory_items": [
      {
        "id": "uuid",
        "item_name": "Fertilizer NPK 16-16-16",
        "category": "Fertilizer",
        "unit": "kg",
        "is_consumable": true,
        "total_stock": 125.5,
        "site_inventories": [
          {
            "id": "uuid",
            "site_id": "uuid",
            "stock_quantity": 50
          }
        ],
        "created_at": "2026-03-03T10:00:00.000000Z"
      }
    ],
    "pagination": { ... }
  }
}
```

**Categories:** Tool, Material, Fertilizer, Chemical  
**Units:** pcs, kg, liter, zak

### Create Inventory Item
**POST** `/inventory-items`

**Permission Required:** `create_inventory`

**Request Body:**
```json
{
  "item_name": "Lawn Mower Electric",
  "category": "Tool",
  "unit": "pcs",
  "is_consumable": false
}
```

### Update Inventory Item
**PUT/PATCH** `/inventory-items/{id}`

**Permission Required:** `edit_inventory`

### Delete Inventory Item
**DELETE** `/inventory-items/{id}`

**Permission Required:** `delete_inventory`

---

## Site Inventories

### List Site Inventories
**GET** `/site-inventories`

**Query Parameters:**
- `site_id` (optional): Filter by site
- `inventory_item_id` (optional): Filter by inventory item
- `low_stock` (optional): Show only low stock items (true/false)
- `per_page` (optional): Items per page (default: 15)

**Permission Required:** `view_inventory`

**Response (200):**
```json
{
  "success": true,
  "message": "Success",
  "data": {
    "site_inventories": [
      {
        "id": "uuid",
        "site_id": "uuid",
        "site": {
          "id": "uuid",
          "site_name": "Taman Kota Jakarta"
        },
        "inventory_item_id": "uuid",
        "inventory_item": {
          "id": "uuid",
          "item_name": "Fertilizer NPK 16-16-16",
          "category": "Fertilizer",
          "unit": "kg"
        },
        "stock_quantity": 5,
        "is_low_stock": true,
        "created_at": "2026-03-03T10:00:00.000000Z"
      }
    ],
    "pagination": { ... }
  }
}
```

**Low Stock:**
- Items with stock quantity < 10 are flagged as `is_low_stock: true`
- Use `?low_stock=true` query parameter to filter low stock items

### Create Site Inventory
**POST** `/site-inventories`

**Permission Required:** `create_inventory`

**Request Body:**
```json
{
  "site_id": "uuid",
  "inventory_item_id": "uuid",
  "stock_quantity": 50
}
```

### Get Site Inventory
**GET** `/site-inventories/{id}`

**Permission Required:** `view_inventory`

### Update Site Inventory
**PUT/PATCH** `/site-inventories/{id}`

**Permission Required:** `edit_inventory`

**Request Body:**
```json
{
  "stock_quantity": 75
}
```

### Delete Site Inventory
**DELETE** `/site-inventories/{id}`

**Permission Required:** `delete_inventory`

---

## Payrolls

### List Payrolls
**GET** `/payrolls`

**Query Parameters:**
- `employee_id` (UUID) - Filter by employee
- `period_month` (integer) - Filter by month (1-12)
- `period_year` (integer) - Filter by year
- `status` (string) - Filter by status (Draft, Approved, Paid)
- `per_page` (integer) - Per page (default: 15)

**Required Permission:** `view_payrolls`

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": "uuid",
      "employee_id": "uuid",
      "employee": {
        "id": "uuid",
        "nik": "EMP001",
        "full_name": "John Doe"
      },
      "period_month": 3,
      "period_year": 2026,
      "period": "March 2026",
      "basic_salary": 5000000,
      "total_days_worked": 22,
      "total_hours_worked": 176,
      "overtime_hours": 8,
      "overtime_pay": 231213.87,
      "allowances": 500000,
      "deductions": 100000,
      "gross_salary": 5731213.87,
      "net_salary": 5631213.87,
      "status": "Draft",
      "paid_at": null,
      "notes": "Payroll for March 2026",
      "created_at": "2026-03-03 12:00:00",
      "updated_at": "2026-03-03 12:00:00"
    }
  ],
  "pagination": { ...  }
}
```

### Generate Payroll from Attendance
**POST** `/payrolls/generate`

**Request Body:**
```json
{
  "employee_id": "uuid",
  "period_month": 3,
  "period_year": 2026,
  "allowances": 500000,
  "deductions": 100000,
  "notes": "Optional notes"
}
```

**Required Permission:** `create_payrolls`

**Notes:**
- Automatically calculates based on attendance records for the given period
- Fetches employee's active contract to determine base salary
- Calculates overtime pay for hours exceeding 8 hours/day
- Overtime rate: base_salary / 173 (average monthly working hours)
- Prevents duplicate payroll for same employee and period

**Response (201):**
```json
{
  "success": true,
  "message": "Payroll generated successfully",
  "data": { ...  }
}
```

### Create Payroll Manually
**POST** `/payrolls`

**Request Body:**
```json
{
  "employee_id": "uuid",
  "period_month": 3,
  "period_year": 2026,
  "basic_salary": 5000000,
  "total_days_worked": 22,
  "total_hours_worked": 176,
  "overtime_hours": 8,
  "overtime_pay": 231213.87,
  "allowances": 500000,
  "deductions": 100000,
  "status": "Draft",
  "notes": "Manual payroll entry"
}
```

**Required Permission:** `create_payrolls`

**Response (201):**
```json
{
  "success": true,
  "message": "Payroll created successfully",
  "data": { ... }
}
```

### Get Payroll Details
**GET** `/payrolls/{id}`

**Required Permission:** `view_payrolls`

**Response (200):**
```json
{
  "success": true,
  "data": { ... }
}
```

### Update Payroll
**PUT/PATCH** `/payrolls/{id}`

**Request Body:**
```json
{
  "basic_salary": 5500000,
  "allowances": 600000,
  "deductions": 150000,
  "status": "Approved",
  "notes": "Updated notes"
}
```

**Required Permission:** `edit_payrolls`

**Response (200):**
```json
{
  "success": true,
  "message": "Payroll updated successfully",
  "data": { ... }
}
```

### Mark Payroll as Paid
**POST** `/payrolls/{id}/mark-as-paid`

**Required Permission:** `edit_payrolls`

**Response (200):**
```json
{
  "success": true,
  "message": "Payroll marked as paid successfully",
  "data": {
    "status": "Paid",
    "paid_at": "2026-03-03 14:30:00",
    ...
  }
}
```

### Delete Payroll
**DELETE** `/payrolls/{id}`

**Required Permission:** `delete_payrolls`

**Response (200):**
```json
{
  "success": true,
  "message": "Payroll deleted successfully"
}
```

---

## Invoice Plans

### List Invoice Plans
**GET** `/invoice-plans`

**Query Parameters:**
- `client_contract_id` (UUID) - Filter by contract
- `invoice_schedule` (string) - Filter by schedule (Monthly, Quarterly, Yearly, One-time)
- `per_page` (integer) - Per page (default: 15)

**Required Permission:** `view_invoices`

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": "uuid",
      "client_contract_id": "uuid",
      "client_contract": {
        "id": "uuid",
        "contract_number": "CNT-20260303-001",
        "client": {
          "id": "uuid",
          "client_name": "PT Kebun Raya Indonesia"
        }
      },
      "invoice_schedule": "Monthly",
      "amount_per_invoice": 15000000,
      "tax_percentage": 11,
      "tax_amount": 1650000,
      "total_amount": 16650000,
      "invoices_count": 3,
      "notes": "Monthly garden maintenance fee",
      "created_at": "2026-03-03 12:00:00",
      "updated_at": "2026-03-03 12:00:00"
    }
  ],
  "pagination": { ... }
}
```

### Create Invoice Plan
**POST** `/invoice-plans`

**Request Body:**
```json
{
  "client_contract_id": "uuid",
  "invoice_schedule": "Monthly",
  "amount_per_invoice": 15000000,
  "tax_percentage": 11,
  "notes": "Optional notes"
}
```

**Required Permission:** `create_invoices`

**Response (201):**
```json
{
  "success": true,
  "message": "Invoice plan created successfully",
  "data": { ... }
}
```

### Get Invoice Plan Details
**GET** `/invoice-plans/{id}`

**Required Permission:** `view_invoices`

**Response (200):**
```json
{
  "success": true,
  "data": { ... }
}
```

### Update Invoice Plan
**PUT/PATCH** `/invoice-plans/{id}`

**Request Body:**
```json
{
  "invoice_schedule": "Quarterly",
  "amount_per_invoice": 45000000,
  "tax_percentage": 11,
  "notes": "Updated to quarterly"
}
```

**Required Permission:** `edit_invoices`

**Response (200):**
```json
{
  "success": true,
  "message": "Invoice plan updated successfully",
  "data": { ... }
}
```

### Delete Invoice Plan
**DELETE** `/invoice-plans/{id}`

**Required Permission:** `delete_invoices`

**Response (200):**
```json
{
  "success": true,
  "message": "Invoice plan deleted successfully"
}
```

---

## Invoices

### List Invoices
**GET** `/invoices`

**Query Parameters:**
- `client_contract_id` (UUID) - Filter by contract
- `status` (string) - Filter by status (Draft, Sent, Paid, Overdue, Cancelled)
- `overdue_only` (boolean) - Show only overdue invoices
- `search` (string) - Search by invoice number
- `start_date` (date) - Filter by invoice date from
- `end_date` (date) - Filter by invoice date to
- `per_page` (integer) - Per page (default: 15)

**Required Permission:** `view_invoices`

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": "uuid",
      "invoice_plan_id": "uuid",
      "invoice_plan": {
        "id": "uuid",
        "invoice_schedule": "Monthly"
      },
      "client_contract_id": "uuid",
      "client_contract": {
        "id": "uuid",
        "contract_number": "CNT-20260303-001",
        "client": {
          "id": "uuid",
          "client_name": "PT Kebun Raya Indonesia"
        }
      },
      "invoice_number": "INV-202603-0001",
      "invoice_date": "2026-03-03",
      "due_date": "2026-04-03",
      "amount": 15000000,
      "tax_amount": 1650000,
      "total_amount": 16650000,
      "status": "Paid",
      "is_overdue": false,
      "paid_at": "2026-03-03 14:30:00",
      "payment_method": "Bank Transfer",
      "notes": "March invoice",
      "created_at": "2026-03-03 12:00:00",
      "updated_at": "2026-03-03 14:30:00"
    }
  ],
  "pagination": { ... }
}
```

### Generate Invoice from Plan
**POST** `/invoices/generate-from-plan`

**Request Body:**
```json
{
  "invoice_plan_id": "uuid",
  "invoice_date": "2026-03-03",
  "due_date": "2026-04-03"
}
```

**Required Permission:** `create_invoices`

**Notes:**
- Automatically generates invoice number in format: INV-YYYYMM-XXXX
- Amount and tax are copied from invoice plan
- Default due date is 30 days from invoice date if not provided

**Response (201):**
```json
{
  "success": true,
  "message": "Invoice generated successfully",
  "data": { ... }
}
```

### Create Invoice Manually
**POST** `/invoices`

**Request Body:**
```json
{
  "invoice_plan_id": "uuid",
  "client_contract_id": "uuid",
  "invoice_number": "INV-202603-0001",
  "invoice_date": "2026-03-03",
  "due_date": "2026-04-03",
  "amount": 15000000,
  "tax_amount": 1650000,
  "total_amount": 16650000,
  "status": "Draft",
  "notes": "Custom invoice"
}
```

**Required Permission:** `create_invoices`

**Notes:**
- If invoice_number is not provided, it will be auto-generated

**Response (201):**
```json
{
  "success": true,
  "message": "Invoice created successfully",
  "data": { ... }
}
```

### Get Invoice Details
**GET** `/invoices/{id}`

**Required Permission:** `view_invoices`

**Response (200):**
```json
{
  "success": true,
  "data": { ... }
}
```

### Update Invoice
**PUT/PATCH** `/invoices/{id}`

**Request Body:**
```json
{
  "invoice_date": "2026-03-05",
  "due_date": "2026-04-05",
  "amount": 16000000,
  "tax_amount": 1760000,
  "total_amount": 17760000,
  "status": "Sent",
  "notes": "Updated notes"
}
```

**Required Permission:** `edit_invoices`

**Response (200):**
```json
{
  "success": true,
  "message": "Invoice updated successfully",
  "data": { ... }
}
```

### Mark Invoice as Paid
**POST** `/invoices/{id}/mark-as-paid`

**Request Body:**
```json
{
  "payment_method": "Bank Transfer"
}
```

**Required Permission:** `edit_invoices`

**Notes:**
- Automatically creates an Income transaction for the invoice amount
- Sets status to "Paid" and records paid_at timestamp

**Response (200):**
```json
{
  "success": true,
  "message": "Invoice marked as paid successfully",
  "data": {
    "status": "Paid",
    "paid_at": "2026-03-03 14:30:00",
    "payment_method": "Bank Transfer",
    ...
  }
}
```

### Delete Invoice
**DELETE** `/invoices/{id}`

**Required Permission:** `delete_invoices`

**Response (200):**
```json
{
  "success": true,
  "message": "Invoice deleted successfully"
}
```

---

## Transactions

### List Transactions
**GET** `/transactions`

**Query Parameters:**
- `transaction_type` (string) - Filter by type (Income, Expense)
- `category` (string) - Filter by category
- `start_date` (date) - Filter by transaction date from
- `end_date` (date) - Filter by transaction date to
- `payment_method` (string) - Filter by payment method
- `search` (string) - Search by description
- `per_page` (integer) - Per page (default: 15)

**Required Permission:** `view_transactions`

**Response (200):**
```json
{
  "success": true,
  "data": [
    {
      "id": "uuid",
      "transaction_date": "2026-03-03",
      "transaction_type": "Expense",
      "category": "Tools Purchase",
      "amount": 2500000,
      "reference_type": null,
      "reference_id": null,
      "payment_method": "Bank Transfer",
      "description": "Purchase of gardening tools and equipment",
      "receipt_path": null,
      "created_at": "2026-03-03 12:00:00",
      "updated_at": "2026-03-03 12:00:00"
    }
  ],
  "pagination": { ... }
}
```

### Get Financial Summary
**GET** `/transactions/summary`

**Query Parameters:**
- `start_date` (date) - Required
- `end_date` (date) - Required

**Required Permission:** `view_transactions`

**Response (200):**
```json
{
  "success": true,
  "data": {
    "period": {
      "start_date": "2026-03-01",
      "end_date": "2026-03-31"
    },
    "summary": {
      "total_income": 16650000,
      "total_expense": 2500000,
      "net_income": 14150000
    },
    "income_by_category": [
      {
        "category": "Invoice Payment",
        "total": 16650000
      }
    ],
    "expense_by_category": [
      {
        "category": "Tools Purchase",
        "total": 2500000
      }
    ]
  }
}
```

### Create Transaction
**POST** `/transactions`

**Request Body:**
```json
{
  "transaction_date": "2026-03-03",
  "transaction_type": "Expense",
  "category": "Tools Purchase",
  "amount": 2500000,
  "reference_type": "App\\Models\\Invoice",
  "reference_id": "uuid",
  "payment_method": "Bank Transfer",
  "description": "Purchase of gardening tools and equipment",
  "receipt_path": "/storage/receipts/receipt001.pdf"
}
```

**Required Permission:** `create_transactions`

**Notes:**
- `reference_type` and `reference_id` are optional polymorphic references
- Common categories: Invoice Payment, Payroll, Tools Purchase, Fertilizer, Transportation, etc.
- Payment methods: Cash, Bank Transfer, Credit Card, Debit Card, Cheque, Other

**Response (201):**
```json
{
  "success": true,
  "message": "Transaction created successfully",
  "data": { ... }
}
```

### Get Transaction Details
**GET** `/transactions/{id}`

**Required Permission:** `view_transactions`

**Response (200):**
```json
{
  "success": true,
  "data": { ... }
}
```

### Update Transaction
**PUT/PATCH** `/transactions/{id}`

**Request Body:**
```json
{
  "transaction_date": "2026-03-05",
  "amount": 2600000,
  "description": "Updated description"
}
```

**Required Permission:** `edit_transactions`

**Response (200):**
```json
{
  "success": true,
  "message": "Transaction updated successfully",
  "data": { ... }
}
```

### Delete Transaction
**DELETE** `/transactions/{id}`

**Required Permission:** `delete_transactions`

**Response (200):**
```json
{
  "success": true,
  "message": "Transaction deleted successfully"
}
```

---

## Error Responses

All endpoints return consistent error responses:

**400 Bad Request:**
```json
{
  "success": false,
  "message": "Validation error message"
}
```

**401 Unauthorized:**
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

**403 Forbidden:**
```json
{
  "success": false,
  "message": "This action is unauthorized"
}
```

**404 Not Found:**
```json
{
  "success": false,
  "message": "Resource not found"
}
```

**422 Unprocessable Entity (Validation):**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "field_name": [
      "The field name is required."
    ]
  }
}
```

**500 Internal Server Error:**
```json
{
  "success": false,
  "message": "Internal server error"
}
```

---

## Permissions Reference

### User Management
- `view_users`
- `create_users`
- `edit_users`
- `delete_users`

### Employee Management
- `view_employees`
- `create_employees`
- `edit_employees`
- `delete_employees`

### Employee Contracts
- `view_employee_contracts`
- `create_employee_contracts`
- `edit_employee_contracts`
- `delete_employee_contracts`

### Leave Requests
- `view_leave_requests`
- `create_leave_requests`
- `edit_leave_requests`
- `delete_leave_requests`
- `approve_leave_requests`

### Clients
- `view_clients`
- `create_clients`
- `edit_clients`
- `delete_clients`

### Client Contracts
- `view_contracts`
- `create_contracts`
- `edit_contracts`
- `delete_contracts`

### Sites
- `view_sites`
- `create_sites`
- `edit_sites`
- `delete_sites`

### Areas
- `view_areas`
- `create_areas`
- `edit_areas`
- `delete_areas`

### Tasks
- `view_tasks`
- `create_tasks`
- `edit_tasks`
- `delete_tasks`
- `assign_tasks`

### Attendance
- `view_attendances`
- `create_attendances`
- `edit_attendances`
- `delete_attendances`

### Inventory
- `view_inventory`
- `create_inventory`
- `edit_inventory`
- `delete_inventory`

---

## Roles

**Super Admin** - Has all permissions

**Admin** - Has most permissions except sensitive user management

**Manager** - Can view and manage day-to-day operations (tasks, attendance, inventory)

**Supervisor** - Can view and create basic records, approve leave requests

**Staff** - Can view assigned tasks and clock in/out

---

## Notes

1. All timestamps are in UTC and formatted as ISO 8601
2. All IDs are UUIDs (version 4)
3. Pagination is available on all list endpoints
4. Soft deletes are enabled on most models
5. File uploads should be sent as multipart/form-data
6. GPS coordinates use decimal degrees format
7. Distances are calculated using Haversine formula for accuracy

---

**Version History:**
- v1.0 (March 3, 2026): Initial API documentation
