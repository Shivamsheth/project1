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

-> POST - http://localhost:8000/api/auth/verify-email

-> JSON body for OTP verification:

{
    "email": "youremail@example.com",
    "otp": "123456"
}

**Resend Verification Email** 

-> POST - http://localhost:8000/api/auth/resend-verification-email

-> JSON body:

{
    "email": "youremail@example.com"
}

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

**POSTS MANAGEMENT**

-> Get All Posts (Admin sees all, Members see all posts)
- GET - http://localhost:8000/api/posts (Requires Token)

-> Get Single Post 
- GET - http://localhost:8000/api/posts/{id} (Requires Token)

-> Create Post
- POST - http://localhost:8000/api/posts (Requires Token)
  
  JSON body:
  {
      "title": "Post Title",
      "content": "Post Content"
  }

-> Update Post (Admin can update any post, Members can update only their own)
- PUT - http://localhost:8000/api/posts/{id} (Requires Token)

-> Delete Post (Admin can delete any post, Members can delete only their own)
- DELETE - http://localhost:8000/api/posts/{id} (Requires Token)

**SCHEDULED TASKS**

-> Clear Expired OTPs (runs every 10 minutes)
- php artisan otp:clear-expired (Manual execution)
- php artisan schedule:run (Process scheduler in background)

**ROLE-BASED PERMISSIONS**

**Admin**
- Can see all posts from all users
- Can create posts
- Can update and delete ANY post
- Only one admin can exist in system

**Member**
- Can see all posts from all users
- Can create posts
- Can only update and delete their OWN posts
- Cannot update/delete admin posts or other members' posts






