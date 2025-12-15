<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Penjual - ToMaT</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden max-w-4xl w-full flex flex-col md:flex-row">
        
        <!-- Left Panel (Image) -->
        <div class="hidden md:block md:w-1/2 bg-green-50 relative">
            <img src="{{ asset('images/store.png') }}" alt="Shop Illustration" class="absolute inset-0 w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent flex items-end p-8">
                <div class="text-white">
                    <h2 class="text-3xl font-bold mb-2">Selamat Datang Kembali</h2>
                    <p class="text-lg opacity-90">Kelola toko Anda dengan mudah di ToMaT.</p>
                </div>
            </div>
        </div>

        <!-- Right Panel (Form) -->
        <div class="w-full md:w-1/2 p-8 md:p-12">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-green-600 mb-2">ToMaT</h1>
                <h2 class="text-2xl font-semibold text-gray-800">Login Penjual</h2>
                <p class="text-gray-600 mt-2">Belum punya akun? <a href="{{ route('register') }}" class="text-green-600 hover:underline font-medium">Daftar</a></p>
            </div>
            
            @if (session('status'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded mb-6">
                    <p class="text-sm text-green-700">{{ session('status') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <ul class="list-disc list-inside text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            
            <form method="POST" action="{{ route('seller.login.submit') }}" class="space-y-5">                
                @csrf 

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                           placeholder="email@contoh.com" required autofocus>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors pr-10"
                               placeholder="Masukkan password" required>
                        <button type="button" class="toggle-password absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                            👁️
                        </button>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        Masuk
                    </button>
                </div>

            </form>
        </div>
    </div>
    
    <script>
        // Toggle Password Visibility
        document.querySelector('.toggle-password').addEventListener('click', function() {
            const input = document.getElementById('password');
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>
</body>
</html>
