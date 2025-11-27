<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Login - ToMaT</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <style>
        /* Sedikit modifikasi agar terlihat beda */
        .main-logo { color: #333; } /* Logo Hitam untuk Owner */
        .btn-login { background-color: #333; } /* Tombol Hitam */
        .btn-login:hover { background-color: #555; }
    </style>
</head>
<body>

    <div class="login-wrapper">
        
        <div class="main-logo">ToMaT <span style="font-size: 1rem; font-family: Arial;">(Owner)</span></div>

        <div class="login-card">
            
            <div class="card-header">
                <h1>Login Pemilik</h1>
                </div>

            <form action="{{ route('owner.login.submit') }}" method="POST">
                @csrf

                @error('email')
                    <div class="alert-error">
                        {{ $message }}
                    </div>
                @enderror

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@tomat.com">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required placeholder="Enter password">
                </div>

                <button type="submit" class="btn-login">Masuk ke Dashboard</button>

            </form>
        </div>

        <div class="global-footer">
            &copy; 2025, PT ToMaT | Administrator Access Only
        </div>

    </div>

</body>
</html>