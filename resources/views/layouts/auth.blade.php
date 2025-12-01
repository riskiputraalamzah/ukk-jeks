<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - PPDB</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            position: relative;
            color: white;
            /* padding: 120px 0; */
            text-align: center;
            background-image: url('/images/halaman.jpeg');
            background-size: cover;
            background-position: center;
            min-height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.25);

            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }


        .auth-input {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(6, 6, 6, 0.4);
            backdrop-filter: blur(6px);
        }

        .auth-btn {
            background: #0d6efd;
            border-radius: 10px;
            padding: 10px;
            font-weight: 600;
        }

        .auth-link a {
            color: #0e41b7ff;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    @yield('content')

</body>

</html>