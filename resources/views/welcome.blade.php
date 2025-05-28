<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            margin: 0;
            background-color: #f7fafc;
            color: #1a202c;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            text-align: center;
        }
        a {
            display: inline-block;
            margin: 10px;
            font-size: 18px;
            color: #3182ce;
            text-decoration: none;
            border: 2px solid #3182ce;
            padding: 10px 20px;
            border-radius: 5px;
            transition: 0.3s;
        }
        a:hover {
            background-color: #3182ce;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>مرحباً بك في النظام</h1>
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}">لوحة التحكم</a>
            @else
                <a href="{{ route('login') }}">تسجيل الدخول</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">إنشاء حساب</a>
                @endif
            @endauth
        @endif
    </div>
</body>
</html>