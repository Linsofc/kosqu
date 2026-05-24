<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KOSQU Management System</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: #0088A8;
            --primary-light: #E0F7FA;
            --secondary: #0A9396;
            --background: #F8FBFC;
            --text-main: #2D3E50;
            --text-muted: #8E9BAE;
            --glass-border: #EDF2F7;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text-main);
            background-image: radial-gradient(at 0% 0%, hsla(192, 85%, 95%, 1) 0, transparent 50%),
                              radial-gradient(at 50% 0%, hsla(180, 85%, 95%, 1) 0, transparent 50%),
                              radial-gradient(at 100% 0%, hsla(170, 85%, 95%, 1) 0, transparent 50%);
        }

        .login-card {
            background: #FFFFFF;
            border: 1px solid var(--glass-border);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border-radius: 24px;
        }

        .btn-primary {
            background: var(--primary);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--secondary);
            box-shadow: 0 4px 15px rgba(0, 136, 168, 0.2);
            transform: translateY(-1px);
        }

        .input-field {
            background: #F1F5F9;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }

        .input-field:focus {
            background: #FFFFFF;
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 136, 168, 0.1);
        }

        .type-selector {
            background: #F1F5F9;
            padding: 4px;
            border-radius: 14px;
        }

        .type-btn {
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .type-btn.active {
            background: #FFFFFF;
            color: var(--primary);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .logo-text {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -1px;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-4">
        <div class="text-center mb-8">
            <h1 class="logo-text">KOSQU</h1>
            <p class="text-slate-400 font-medium text-sm tracking-wide">MANAGEMENT SYSTEM</p>
        </div>

        <div class="login-card p-8 md:p-10">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-slate-800">Selamat Datang</h2>
                <p class="text-slate-500 text-sm mt-1">Silakan masuk untuk mengelola properti Anda.</p>
            </div>

            <form action="/login" method="POST" class="space-y-6">
                @csrf
                
                <div class="type-selector flex mb-8">
                    <button type="button" onclick="setType('admin')" id="btn-admin" class="type-btn active flex-1">Admin</button>
                    <button type="button" onclick="setType('user')" id="btn-user" class="type-btn flex-1 text-slate-500">Penghuni</button>
                </div>
                
                <input type="hidden" name="login_type" id="login_type" value="admin">

                <div class="space-y-2">
                    <label id="label-identifier" class="block text-sm font-semibold text-slate-700">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fa-solid fa-user text-sm"></i>
                        </span>
                        <input type="text" name="username_or_nik" required 
                               class="input-field w-full pl-10 pr-4 py-3 rounded-xl text-slate-800 placeholder-slate-400" 
                               placeholder="Masukkan kredensial Anda">
                    </div>
                    @error('username_or_nik')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input type="password" name="password" required 
                               class="input-field w-full pl-10 pr-4 py-3 rounded-xl text-slate-800 placeholder-slate-400" 
                               placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <div class="text-right mt-1">
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-cyan-600 hover:text-cyan-800 transition-colors">
                            Lupa Password?
                        </a>
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" 
                           class="h-4 w-4 rounded border-slate-300 text-cyan-600 focus:ring-cyan-500">
                    <label for="remember" class="ml-2 block text-sm text-slate-500">Ingat sesi saya</label>
                </div>

                <button type="submit" class="btn-primary w-full py-4 px-4 rounded-xl text-white font-bold tracking-wide uppercase text-sm shadow-lg shadow-cyan-900/10">
                    Masuk Sekarang
                </button>
            </form>
        </div>

        <div class="mt-10 text-center">
            <p class="text-slate-400 text-xs font-semibold tracking-widest uppercase">Wisma AAM &copy; 2026</p>
        </div>
    </div>

    <script>
        function setType(type) {
            document.getElementById('login_type').value = type;
            const btnAdmin = document.getElementById('btn-admin');
            const btnUser = document.getElementById('btn-user');
            const labelIdent = document.getElementById('label-identifier');

            if (type === 'admin') {
                btnAdmin.classList.add('active');
                btnAdmin.classList.remove('text-slate-500');
                btnUser.classList.remove('active');
                btnUser.classList.add('text-slate-500');
                labelIdent.innerText = 'Username';
            } else {
                btnUser.classList.add('active');
                btnUser.classList.remove('text-slate-500');
                btnAdmin.classList.remove('active');
                btnAdmin.classList.add('text-slate-500');
                labelIdent.innerText = 'NIK (Nomor Induk Kependudukan)';
            }
        }
    </script>
</body>
</html>
