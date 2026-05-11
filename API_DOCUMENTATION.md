# Role Management API Documentation

## Base URL
```
http://your-domain.com/api/role
```

## Database Schema (PostgreSQL)
```sql
CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 1. Create Role

**Endpoint:** `POST /api/role/create`

**Description:** Creates a new role in the system.

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Payload:**
```json
{
    "name": "Content Manager",
    "slug": "content-manager",
    "description": "Can manage content and media files",
    "is_active": true
}
```

**Response (201 Created):**
```json
{
    "success": true,
    "message": "Role created successfully!",
    "data": {
        "role": {
            "id": 6,
            "name": "Content Manager",
            "slug": "content-manager",
            "description": "Can manage content and media files",
            "is_active": true,
            "created_at": "2026-05-11T10:00:00.000000Z",
            "updated_at": "2026-05-11T10:00:00.000000Z"
        }
    }
}
```

**Validation Errors (422):**
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "name": ["The role name is required."],
        "slug": ["A role with this slug already exists."]
    }
}
```

---

## 2. Read All Roles

**Endpoint:** `GET /api/role/read`

**Description:** Retrieves all roles with optional filtering.

**Query Parameters:**
- `is_active` (boolean): Filter by active status
- `search` (string): Search in name and description

**Examples:**
```
GET /api/role/read
GET /api/role/read?is_active=true
GET /api/role/read?search=admin
GET /api/role/read?is_active=false&search=manager
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Roles retrieved successfully!",
    "data": {
        "roles": [
            {
                "id": 1,
                "name": "Super Admin",
                "slug": "super-admin",
                "description": "Full system access with all permissions",
                "is_active": true,
                "users_count": 2,
                "created_at": "2026-05-11T09:00:00.000000Z",
                "updated_at": "2026-05-11T09:00:00.000000Z"
            },
            {
                "id": 2,
                "name": "Admin",
                "slug": "admin",
                "description": "Administrative access to manage users and resources",
                "is_active": true,
                "users_count": 5,
                "created_at": "2026-05-11T09:00:00.000000Z",
                "updated_at": "2026-05-11T09:00:00.000000Z"
            }
        ]
    }
}
```

---

## 3. Read Single Role

**Endpoint:** `GET /api/role/read/{id}`

**Description:** Retrieves a specific role by ID.

**Example:**
```
GET /api/role/read/1
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Role retrieved successfully!",
    "data": {
        "role": {
            "id": 1,
            "name": "Super Admin",
            "slug": "super-admin",
            "description": "Full system access with all permissions",
            "is_active": true,
            "users_count": 2,
            "created_at": "2026-05-11T09:00:00.000000Z",
            "updated_at": "2026-05-11T09:00:00.000000Z"
        }
    }
}
```

**Not Found (404):**
```json
{
    "success": false,
    "message": "Resource not found"
}
```

---

## 4. Update Role

**Endpoint:** `PUT /api/role/update/{id}` or `PATCH /api/role/update/{id}`

**Description:** Updates an existing role.

**Headers:**
```
Content-Type: application/json
Accept: application/json
```

**Request Payload:**
```json
{
    "name": "Senior Manager",
    "slug": "senior-manager",
    "description": "Advanced management permissions",
    "is_active": true
}
```

**Partial Update Payload (PATCH):**
```json
{
    "description": "Updated description for senior manager role"
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Role updated successfully!",
    "data": {
        "role": {
            "id": 3,
            "name": "Senior Manager",
            "slug": "senior-manager",
            "description": "Updated description for senior manager role",
            "is_active": true,
            "users_count": 3,
            "created_at": "2026-05-11T09:00:00.000000Z",
            "updated_at": "2026-05-11T10:30:00.000000Z"
        }
    }
}
```

**Conflict (409):**
```json
{
    "success": false,
    "message": "Role already exists with this name."
}
```

---

## 5. Delete Role

**Endpoint:** `DELETE /api/role/delete/{id}`

**Description:** Deletes a role from the system.

**Example:**
```
DELETE /api/role/delete/5
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Role deleted successfully!",
    "data": null
}
```

**Cannot Delete (422):**
```json
{
    "success": false,
    "message": "Cannot delete role. It is assigned to users."
}
```

**Not Found (404):**
```json
{
    "success": false,
    "message": "Resource not found"
}
```

---

## PostgreSQL Integration Notes

### Database Connection
Ensure your Laravel `.env` file is configured for PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Migration Compatibility
The migration file is PostgreSQL compatible:
- `id()` creates `SERIAL PRIMARY KEY`
- `string()` creates `VARCHAR(255)`
- `text()` creates `TEXT`
- `boolean()` creates `BOOLEAN`
- `timestamps()` creates `TIMESTAMP` columns

### Query Examples
```sql
-- Insert role (equivalent to POST /api/role/create)
INSERT INTO roles (name, slug, description, is_active, created_at, updated_at)
VALUES ('Content Manager', 'content-manager', 'Can manage content', true, NOW(), NOW());

-- Select all roles (equivalent to GET /api/role/read)
SELECT *, 
       (SELECT COUNT(*) FROM users WHERE users.role = roles.name) as users_count
FROM roles 
WHERE is_active = true;

-- Update role (equivalent to PUT /api/role/update/{id})
UPDATE roles 
SET name = 'Senior Manager', 
    description = 'Updated description',
    updated_at = NOW()
WHERE id = 3;

-- Delete role (equivalent to DELETE /api/role/delete/{id})
DELETE FROM roles WHERE id = 5 AND 
    (SELECT COUNT(*) FROM users WHERE users.role = roles.name) = 0;
```

---

## Error Response Format

All API errors follow this consistent format:

```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field_name": ["Error message for this field"]
    }
}
```

---

## Testing with cURL

### Create Role
```bash
curl -X POST http://your-domain.com/api/role/create \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Content Manager",
    "slug": "content-manager",
    "description": "Can manage content and media files",
    "is_active": true
  }'
```

### Read All Roles
```bash
curl -X GET "http://your-domain.com/api/role/read?is_active=true"
```

### Update Role
```bash
curl -X PUT http://your-domain.com/api/role/update/1 \
  -H "Content-Type: application/json" \
  -d '{
    "description": "Updated description"
  }'
```

### Delete Role
```bash
curl -X DELETE http://your-domain.com/api/role/delete/5
```
