**First all Commands**


- php artisan make:controller AuthController
- php artisan make:controller PostController
- php artisan make:job SendWelcomeEmail
- php artisan make:mail WelcomeMail
- php artisan make:model Post
- php artisan make:model User
- php artisan make:notification EmailVerificatioNotification
- php artisan make:view emails.welcome

**All migration commands**

- php artisan make:migration create_posts_table
- php artisan make:migration create_users_table (This table will be already exist whike building project)


**For Verification-Mail Template**

Create HTML file in resources/views/... .html



**All Routes to Check API Step by Step**

-> Registration (ADMIN / MEMBER)
- 1. POST - http://localhost:8000/api/auth/register-admin 
- 2. POST - http://localhost:8000/api/auth/register-member

-> JSON body for registration.

{
    "name": "Your Name",
    "email": "youremail@example.com",
    "password": "password",
    "password_confirmation": "password",
    "role": "member" 
        OR 
    "role":"admin"
}

**verify email link** 

-> GET - http://localhost:8000/api/auth/verify-email/{id}/(email -hash code after clicking on mail verify from URL).

**Login (Email Verified Successfully)**
-> Json Body
{
    "email":"yourmail@example.com",
    "password":"password"
}


->  JSON response

{
  "success": true,
  "message": "Login successful.",
  "data": {
    "id": 9,
    "name": "Your Name",
    "email": "yourname@example.com",
    "role": "member",
    "email_verified": true,
    "token": "12|IHcN2gtyhaRwFJI9IqWtTjo8UJYGfenHAJDtO5xxxxxxxxxx"
  }
}








