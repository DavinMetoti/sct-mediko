<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .card {
            max-width: 500px;
            width: 100%;
            background: #fff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .card-header {
            background: #007bff;
            color: #fff;
            text-align: center;
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
        }
        .card-body {
            padding: 20px;
        }
        .alert {
            background: #e9ecef;
            padding: 15px;
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            border-radius: 5px;
        }
        .card-footer {
            background: #f8f9fa;
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            Your OTP Code
        </div>
        <div class="card-body">
            <p>Hello,</p>
            <p>Your One-Time Password (OTP) for resetting your password is:</p>
            <div class="alert">{{ $otp }}</div>
            <p>This OTP is valid for <strong>5 minutes</strong>. Please do not share this code with anyone.</p>
            <p>If you didn't request this code, please ignore this email.</p>
            <p>Thank you for using our service!</p>
        </div>
        <div class="card-footer">
            &copy; 2024 MEDIKO.id. All rights reserved.
        </div>
    </div>
</body>
</html>