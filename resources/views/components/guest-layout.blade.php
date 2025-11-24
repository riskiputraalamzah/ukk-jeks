<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'PPDB' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
          rel="stylesheet">

    <style>
        body {
            position: relative;
            color: white;
            background-image: url('/images/halaman.jpeg');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            padding: 35px;
            width: 420px;
            color: black;
        }

        .auth-input {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(0,0,0,0.2);
        }

        .auth-btn {
            background: #0d6efd;
            border-radius: 10px;
            padding: 10px;
            font-weight: 600;
            color: white;
        }

        .auth-link a {
            color: #0e41b7ff;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="auth-card">
        {{ $slot }}
    </div>

</body>

</html>
