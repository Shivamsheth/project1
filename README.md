<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Social Media API - Secure Sanctum Authentication

A comprehensive Laravel API with Sanctum authentication, role-based access control, and Redis queue integration for email notifications.

### 🎯 Features

- **Sanctum Authentication**: Secure token-based API authentication
- **Role-Based Access Control**: Admin and Member roles with specific permissions
- **User Registration**: Separate endpoints for admin and member signup
- **Single Admin Enforcement**: Only one admin can exist in the system
- **Email Validation**: Duplicate email prevention with unique constraints
- **Posts Management**: Full CRUD operations with role-based authorization
- **Redis Queue**: Asynchronous welcome email notifications
- **Eloquent ORM**: Model relationships and database interactions
- **API Security**: Proper validation, authorization, and error handling

### 📋 Quick Start

1. **Clone and Setup**
   ```bash
   cd c:\TechFlitter\SocialMedia\project1
   composer install
   ```

2. **Configure Environment**
   ```bash
   # Copy .env.example to .env
   cp .env.example .env
   
   # Generate application key
   php artisan key:generate
   ```

3. **Setup Database**
   ```bash
   # Configure database in .env
   php artisan migrate
   ```

4. **Start Services**
   ```bash
   # Terminal 1: Redis
   redis-server.exe
   
   # Terminal 2: Queue Worker
   php artisan queue:work redis
   
   # Terminal 3: Laravel Server
   php artisan serve
   ```

### 📚 Documentation

- **[API_DOCUMENTATION.md](./API_DOCUMENTATION.md)** - Complete API endpoint reference
- **[SETUP_GUIDE.md](./SETUP_GUIDE.md)** - Detailed setup and configuration guide
- **[QUICK_TEST_GUIDE.md](./QUICK_TEST_GUIDE.md)** - Quick testing instructions and examples

### 🔐 API Endpoints

**Authentication**
- `POST /api/auth/register` - Register admin user
- `POST /api/auth/register` - Register member user
- `POST /api/auth/login` - Login and get token
- `POST /api/auth/logout` - Logout and revoke token (Protected)
- `GET /api/auth/profile` - Get user profile (Protected)

**Posts**
- `GET /api/posts` - Get posts (role-based filtering) (Protected)
- `GET /api/posts/{id}` - Get single post (Protected)
- `POST /api/posts` - Create post (Protected)
- `PUT /api/posts/{id}` - Update post (Protected)
- `DELETE /api/posts/{id}` - Delete post (Protected)

### 🔄 Role-Based Access

**Admin**
- Can view all posts from all members
- Can create posts
- Can update and delete any post
- Only one admin can exist

**Member**
- Can view only their own posts and admin posts
- Cannot view other members' posts
- Can create, update, and delete their own posts only

### 📧 Email Notifications

Welcome emails are automatically sent via Redis queue when:
- A new admin registers
- A new member registers

Emails include:
- Personalized greeting
- Role-specific information
- Account details

### 💾 Database Schema

**Users Table**
- id, name, email (unique), password, role (admin/member), timestamps

**Posts Table**
- id, user_id (foreign key), title, content, timestamps

**Personal Access Tokens** (Sanctum)
- For API token management

### 🚀 Example Usage

```bash
# Register Admin
curl -X POST http://localhost:8000/api/auth/register-admin \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Admin User",
    "email": "admin@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "admin"
  }'

# Register Member
curl -X POST http://localhost:8000/api/auth/register-member \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Member User",
    "email": "member@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "member"
  }'

# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password123"
  }'

# Create Post (use token from login)
curl -X POST http://localhost:8000/api/posts \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -d '{
    "title": "My First Post",
    "content": "This is a longer content for my first post with meaningful information"
  }'
```

### 📁 Project Structure

```
project1/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php
│   │       └── PostController.php
│   ├── Models/
│   │   ├── User.php
│   │   └── Post.php
│   └── Mail/
│       └── WelcomeMail.php
├── database/
│   └── migrations/
│       ├── create_users_table.php
│       └── create_posts_table.php
├── routes/
│   └── api.php
├── resources/
│   └── views/
│       └── emails/
│           └── welcome.blade.php
├── config/
│   ├── database.php
│   ├── queue.php
│   └── mail.php
└── ...
```

### 🛠️ Technologies Used

- **Laravel 11** - PHP Framework
- **Sanctum** - API Authentication
- **Redis** - Queue Driver
- **Eloquent ORM** - Database ORM
- **MySQL/SQLite** - Database
- **Composer** - PHP Package Manager

### ✅ Validation Rules

**Registration**
- Username: Required, max 255 characters
- Email: Required, unique, valid email format
- Password: Required, minimum 8 characters
- Password Confirmation: Must match password
- Role: Required, either "admin" or "member"

**Post Creation**
- Title: Required, max 255 characters
- Content: Required, minimum 10 characters

### ⚠️ Important Notes

- Only one admin can be registered in the system
- Member email must be unique in the database
- Passwords must be at least 8 characters
- Admin can see and manage all posts
- Members cannot see other members' posts
- Welcome emails are sent via Redis queue
- All POST/PUT endpoints require authentication
- Token is required in `Authorization: Bearer {token}` header

### 📞 Support

For detailed information:
- See [API_DOCUMENTATION.md](./API_DOCUMENTATION.md) for all endpoints
- See [SETUP_GUIDE.md](./SETUP_GUIDE.md) for configuration
- See [QUICK_TEST_GUIDE.md](./QUICK_TEST_GUIDE.md) for testing examples

### 📄 License

This project is built with Laravel, which is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
