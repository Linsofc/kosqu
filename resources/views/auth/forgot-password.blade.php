<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - KOSQU</title>
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
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            background-image: radial-gradient(at 0% 0%, hsla(192, 85%, 95%, 1) 0, transparent 50%),
                              radial-gradient(at 100% 100%, hsla(180, 85%, 95%, 1) 0, transparent 50%);
        }
        .login-card {
            background: #FFFFFF;
            border: 1px solid #EDF2F7;
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
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md p-4">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-cyan-700 tracking-tight">KOSQU</h1>
        </div>

        <div class="login-card p-8 md:p-10">
            <div class="mb-6">
                <a href="{{ route('login') }}" class="text-sm text-cyan-600 hover:text-cyan-800 font-semibold mb-4 inline-block">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Login
                </a>
                <h2 class="text-2xl font-bold text-slate-800 mt-2">Lupa Kata Sandi?</h2>
                <p class="text-slate-500 text-sm mt-2">Masukkan NIK terdaftar Anda. Kami akan mengirimkan kata sandi baru melalui WhatsApp.</p>
            </div>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 text-sm font-medium flex gap-3 items-center">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-slate-700">Nomor Induk Kependudukan (NIK)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fa-solid fa-id-card text-sm"></i>
                        </span>
                        <input type="text" name="identifier" required 
                               class="input-field w-full pl-10 pr-4 py-3 rounded-xl text-slate-800 placeholder-slate-400" 
                               placeholder="Masukkan NIK 16 digit Anda">
                    </div>
                    @error('identifier')
                        <p class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary w-full py-4 px-4 rounded-xl text-white font-bold tracking-wide uppercase text-sm shadow-lg shadow-cyan-900/10">
                    Kirim Sandi Baru
                </button>
            </form>
        </div>
    </div>
</body>
</html>
