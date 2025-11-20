<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login ToMaT</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

    <div class="login-wrapper">
        
        <div class="main-logo">ToMaT</div>

        <div class="login-card">
            
            <div class="card-header">
                <h1>Login</h1>
                <a href="{{ route('register') }}" class="register-link">Register</a>
            </div>

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                @error('login_identifier')
                    <div class="alert-error">
                        {{ $message }}
                    </div>
                @enderror

                <div class="form-group">
                    <label>Phone Number or Email</label>
                    <input type="text" name="login_identifier" value="{{ old('login_identifier') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-login">Login</button>

            </form>
        </div>

        <div class="global-footer">
            &copy; 2025, PT ToMaT | <a href="#">Help</a>
        </div>

    </div>

</body>
</html>