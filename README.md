# url-shortener-assignment
Implemented a service which will allow users to generate short urls.

# URL Shortener Assignment

This is a role-based URL Shortener system built using **Laravel 11**.

---

## 🚀 Features

### 👤 Authentication
- Users can log in and log out
- New users are created only through invitation links

### 🏢 Multi-Company Structure
- Each company can have multiple users
- Users belong to one company

### 🎭 Roles
- SuperAdmin
- Admin
- Manager
- Sales
- Member

---

## ✉️ Invitation System

- SuperAdmin can invite users to existing or new companies
- SuperAdmin **cannot invite Admin** when creating a new company
- Admin cannot invite another Admin or Member in their own company
- Invitation link allows the user to create their account
- Invitation becomes invalid after use

---

## 🔗 URL Shortener Rules

### ❌ Cannot Create Short URLs
- SuperAdmin
- Admin
- Member

### ✅ Can Create Short URLs
- Sales
- Manager

Each short URL stores:
- Original URL
- Short code
- Company ID
- Creator User ID
- Click count

---

## 👀 URL Visibility Rules

| Role | Visibility |
|------|------------|
SuperAdmin | Cannot see all URLs from all companies |
Admin | Can only see URLs NOT created in their own company |
Member | Can only see URLs NOT created by themselves |

---

## 🔒 Security Rule

Short URLs are **NOT publicly accessible**.  
Users must be logged in to use a short URL.

---

## 🛠️ Setup Instructions

### 1. Clone the project

https://github.com/vishnu7660/url-shortener-assignment.git
cd url-shortener-assignment


**. Install dependencies**
composer install
npm install && npm run build


.Configure environment
cp .env.example .env
php artisan key:generate


uperAdmin Credentials
Email: superadmin@example.com
Password: password
. Run migrations
php artisan migrate --seed

 Start server
php artisan serve
