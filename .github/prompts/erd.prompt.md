erDiagram
    %% --- RBAC & AUTHENTICATION ---
    USER ||--o{ ROLE : "has"
    ROLE ||--o{ PERMISSION : "assigned"
    EMPLOYEE ||--o{ USER : "has_account"

    %% --- CLIENT & SITE HIERARCHY ---
    CLIENT ||--o{ CLIENT_CONTRACT : "signs"
    CLIENT ||--o{ INVOICE : "billed_to"
    CLIENT ||--o{ SITE : "owns"
    SITE ||--o{ AREA : "divided_into"
    SITE ||--o{ SITE_INVENTORY : "stores"
    SITE ||--o{ SITE_EXPENSE : "incurs_costs"
    SITE ||--o{ EMPLOYEE_CONTRACT : "assignment_location"
    SITE ||--o{ ATTENDANCE : "marked_at"

    %% --- OPERATIONAL (PLANTS & TASKS) ---
    AREA ||--o{ PLANT_INVENTORY : "contains"
    AREA ||--o{ TASK : "has_tasks"
    PLANT_MASTER ||--o{ PLANT_INVENTORY : "references"
    TASK ||--o{ TASK_LOG : "has_progress"
    
    %% --- WORKFORCE & ATTENDANCE ---
    EMPLOYEE ||--o{ EMPLOYEE_CONTRACT : "has_history"
    EMPLOYEE ||--o{ EMPLOYEE_DOCUMENT : "owns"
    EMPLOYEE ||--o{ TASK_LOG : "records_activity"
    EMPLOYEE ||--o{ ATTENDANCE : "clocks_in_out"
    EMPLOYEE ||--o{ PAYROLL : "receives"
    EMPLOYEE ||--o{ LEAVE_REQUEST : "requests"

    %% --- FINANCIAL & BILLING ---
    CLIENT_CONTRACT ||--o{ INVOICE_PLAN : "defines_terms"
    INVOICE_PLAN ||--o{ INVOICE : "triggers"
    INVOICE ||--o{ INVOICE_ATTACHMENT : "has_documents"
    INVOICE ||--o{ TRANSACTION : "records_income"
    PAYROLL ||--o{ TRANSACTION : "records_expense"
    SITE_EXPENSE ||--o{ TRANSACTION : "records_expense"

    %% --- INVENTORY & USAGE ---
    INVENTORY_ITEM ||--o{ SITE_INVENTORY : "stocked_as"
    INVENTORY_ITEM ||--o{ MATERIAL_USAGE : "used_in"
    TASK_LOG ||--o{ MATERIAL_USAGE : "consumes"

    %% --- CORE TABLES ---

    USER {
        uuid id PK
        uuid employee_id FK
        string email
        string password
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    ROLE {
        uuid id PK
        string name "super-admin, admin-hr, site-leader, gardener, client"
        string guard_name "web"
    }

    PERMISSION {
        uuid id PK
        string name
        string guard_name
    }

    CLIENT {
        uuid id PK
        string name
        string logo
        string headquarter_address
        string pic_name
        string pic_phone
        timestamp created_at
        timestamp updated_at
        timestamp deleted_at
    }

    CLIENT_CONTRACT {
        uuid id PK
        uuid client_id FK
        string contract_number
        enum contract_type "Monthly_Retainer, Project_Based"
        date start_date
        date end_date
        decimal total_contract_value
        timestamp created_at
        timestamp deleted_at
    }

    INVOICE_PLAN {
        uuid id PK
        uuid client_contract_id FK
        string term_name
        decimal amount_to_bill
        date scheduled_date
        boolean is_invoiced
        timestamp created_at
    }

    INVOICE {
        uuid id PK
        uuid client_id FK
        uuid invoice_plan_id FK
        string invoice_number
        date due_date
        decimal subtotal
        decimal tax_amount
        decimal total_amount
        enum status "Unpaid, Paid, Overdue"
        timestamp created_at
        timestamp deleted_at
    }

    INVOICE_ATTACHMENT {
        uuid id PK
        uuid invoice_id FK
        enum document_type "BAST, E-Faktur, Receipt"
        string file_path
        string file_name
        timestamp uploaded_at
    }

    SITE {
        uuid id PK
        uuid client_id FK
        string site_name
        string address
        decimal latitude
        decimal longitude
        float radius_meters
        timestamp created_at
        timestamp deleted_at
    }

    AREA {
        uuid id PK
        uuid site_id FK
        string area_name
        float surface_area_m2
        string current_condition_image
        timestamp created_at
    }

    PLANT_MASTER {
        uuid id PK
        string local_name
        string latin_name
        string category
        text care_instructions
        timestamp created_at
    }

    PLANT_INVENTORY {
        uuid id PK
        uuid area_id FK
        uuid plant_master_id FK
        integer quantity
        string health_status
        date last_inspected
        timestamp updated_at
    }

    TASK {
        uuid id PK
        uuid area_id FK
        uuid assigned_to_id FK
        string title
        text description
        enum task_type "Daily, Weekly, Monthly, Yearly, Accidental"
        enum priority "Low, Medium, High, Urgent"
        enum status "To Do, In Progress, Review, Completed"
        date due_date
        timestamp created_at
        timestamp deleted_at
    }

    TASK_LOG {
        uuid id PK
        uuid task_id FK
        uuid employee_id FK
        text activity_note
        string photo_before
        string photo_after
        timestamp created_at
    }

    ATTENDANCE {
        uuid id PK
        uuid employee_id FK
        uuid site_id FK
        date date
        timestamp clock_in
        timestamp clock_out
        string latitude_in
        string longitude_in
        string selfie_path_in
        enum status "Present, Late, Half-day"
        timestamp created_at
    }

    PAYROLL {
        uuid id PK
        uuid employee_id FK
        date period_month
        integer total_attendance_days
        decimal basic_salary_total
        decimal total_allowance
        decimal total_deduction
        decimal net_salary
        enum payment_status "Draft, Paid"
        timestamp paid_at
        timestamp created_at
    }

    SITE_EXPENSE {
        uuid id PK
        uuid site_id FK
        uuid created_by FK
        string category
        decimal amount
        text description
        string receipt_path
        timestamp created_at
    }

    TRANSACTION {
        uuid id PK
        enum transaction_type "Income, Expense"
        enum source_type "Invoice, Payroll, Site_Expense"
        uuid source_id
        decimal amount
        date transaction_date
        timestamp created_at
    }

    INVENTORY_ITEM {
        uuid id PK
        string item_name
        enum category "Tool, Material, Fertilizer, Chemical"
        string unit "pcs, kg, liter, zak"
        boolean is_consumable
        timestamp created_at
        timestamp deleted_at
    }

    SITE_INVENTORY {
        uuid id PK
        uuid site_id FK
        uuid inventory_item_id FK
        integer stock_quantity
        timestamp updated_at
    }

    MATERIAL_USAGE {
        uuid id PK
        uuid task_log_id FK
        uuid inventory_item_id FK
        integer quantity_used
        timestamp created_at
    }

    EMPLOYEE {
        uuid id PK
        string nik PK
        string full_name
        string phone_number
        string email
        enum employment_status "Active, Resigned"
        timestamp created_at
        timestamp deleted_at
    }

    EMPLOYEE_CONTRACT {
        uuid id PK
        uuid employee_id FK
        uuid site_id FK
        string internal_contract_number
        enum salary_type "Monthly, Daily"
        decimal base_salary
        decimal daily_rate
        string position
        date start_date
        date end_date
        timestamp created_at
        timestamp deleted_at
    }

    EMPLOYEE_DOCUMENT {
        uuid id PK
        uuid employee_id FK
        string document_type
        string file_path
        timestamp created_at
    }

    LEAVE_REQUEST {
        uuid id PK
        uuid employee_id FK
        enum leave_type "Sick, Annual, Permission"
        date start_date
        date end_date
        text reason
        enum status "Pending, Approved, Rejected"
        uuid approved_by FK
        timestamp created_at
    }
