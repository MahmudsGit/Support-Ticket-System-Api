# Mini Support Ticketing System (API)

## Overview
This is a simple PHP backend-only mini support ticketing system with user authentication, department management, tickets, notes, and file attachments using row PHP.

## Requirements
- PHP 7.4+
- MySQL
- Web server or PHP built-in server

## Installation

1. **Clone or copy the project to your web root**
```bash
git clone https://github.com/MahmudsGit/Support-Ticket-System-Api.git
```
2. **Create the database**

Using MySQL:
```sql
CREATE DATABASE support_ticket CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```
3. **Import the schema from ``schema.sql``
Or from terminal:** 

```bash
mysql -u root -p support_ticket < [your path]/to/schema.sql
```
4. **Configure database connection on Edit - 
``
config/db.php 
``
with your MySQL credentials.**

5. **seeding data**

```cmd
php seed.php
```

6. **Start the PHP server**

```cmd
php -S localhost:8000
```

7. **Use the API**

Register users: ``{URL}/api/register``

Login users: ``{URL}/api/login``

**API Authentication:**

Use token-based auth via ``Authorization: Bearer {token}`` header.

Manage departments, tickets, notes, and uploads via RESTful endpoints.

8. **API Documantation Or import postman collection from git to postman**

```cmd
https://documenter.getpostman.com/view/21749152/2sB2qcCLwb
```

Authenticate and test endpoints easily.
