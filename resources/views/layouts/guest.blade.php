<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'PPDB' }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            background-image: url('/images/halaman.jpeg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 16px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 380px; /* Diperkecil dari 420px */
            padding: 24px;
            color: white;
        }

        @media (max-width: 480px) {
            .glass-card {
                max-width: 340px;
                padding: 20px;
            }
        }

        @media (max-width: 360px) {
            .glass-card {
                max-width: 300px;
                padding: 16px;
            }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="glass-card">
        {{ $slot }}
    </div>
</body>
</html>