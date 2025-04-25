<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang đăng nhập quản trị</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 1000px;
            margin: 50px auto;
            display: flex;
        }
        .login-image {
            flex: 1;
            padding-right: 20px;
        }
        .login-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 15px 0 0 15px;
        }
        .login-form {
            flex: 1;
            background-color: white;
            padding: 40px;
            border-radius: 0 15px 15px 0;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .login-title {
            font-size: 28px;
            font-weight: 700;
            color: #344767;
            margin-bottom: 30px;
        }
        .form-label {
            font-weight: 600;
            color: #344767;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #d2d6da;
        }
        .btn-login {
            background-color: #00b074;
            border-color: #00b074;
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            margin-top: 10px;
        }
        .btn-login:hover {
            background-color: #009e68;
            border-color: #009e68;
        }
        .social-login {
            margin-top: 30px;
        }
        .btn-social {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #344767;
            background-color: #f8f9fa;
            border: 1px solid #d2d6da;
        }
        .btn-social i {
            margin-right: 10px;
        }
        .forgot-password {
            margin-top: 20px;
            text-align: left;
        }
        .forgot-password a, .create-account a {
            color: #00b074;
            text-decoration: none;
            font-weight: 600;
        }
        .create-account {
            margin-top: 10px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="login-image">
                <img src="https://images.unsplash.com/photo-1497215728101-856f4ea42174?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Login">
            </div>
            <div class="login-form">
                <h2 class="login-title">Đăng nhập</h2>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.login') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
                    </div>

                    <button type="submit" class="btn btn-primary btn-login">Đăng nhập</button>
                    
                    {{-- <div class="social-login">
                        <a href="#" class="btn-social">
                            <i class="fab fa-facebook-f"></i> Login With Facebook
                        </a>
                        
                        <a href="#" class="btn-social">
                            <i class="fab fa-google"></i> Login With Google
                        </a>
                    </div> --}}
                    
                    {{-- <div class="forgot-password">
                        <a href="#">Forgot your password?</a>
                    </div>
                    
                    <div class="create-account">
                        <a href="#">Create account</a>
                    </div> --}}
                </form>
            </div>
        </div>
    </div>
</body>
</html> 