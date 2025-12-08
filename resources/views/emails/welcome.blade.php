<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to Our Social Media API</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 5px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            border-radius: 5px 5px 0 0;
            text-align: center;
        }
        .content {
            background-color: white;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }
        .footer {
            text-align: center;
            padding: 10px;
            color: #777;
            font-size: 12px;
            margin-top: 20px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Social Media API</h1>
        </div>
        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>

            <p>We're excited to have you join our platform as a <strong>{{ $role }}</strong>!</p>

            @if($user->isAdmin())
                <p>As an <strong>Admin</strong>, you have the following privileges:</p>
                <ul>
                    <li>View all posts from all members</li>
                    <li>Create and manage your own posts</li>
                    <li>Full access to the platform</li>
                </ul>
            @else
                <p>As a <strong>Member</strong>, you can:</p>
                <ul>
                    <li>Create and manage your own posts</li>
                    <li>View posts from the admin</li>
                    <li>View your own posts</li>
                </ul>
            @endif

            <p><strong>Your Account Details:</strong></p>
            <p>
                <strong>Email:</strong> {{ $user->email }}<br>
                <strong>Username:</strong> {{ $user->name }}<br>
                <strong>Role:</strong> {{ $role }}
            </p>

            <p>You can now log in to your account and start using the Social Media API.</p>

            <a href="{{ env('APP_URL') }}" class="button">Get Started</a>

            <p style="margin-top: 20px; color: #666;">If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
        </div>
        <div class="footer">
            <p>&copy; 2025 Social Media API. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
