<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Sistem Iklan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="custom-bg">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row justify-content-center w-100">
            <div class="col-md-4">
                <div class="card p-4">
                    <img src="img/logo.png" alt="logo" class="d-block mx-auto mb-4">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('login.post') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="mb-4">
                            <label>Username</label>
                            <input type="text" class="form-control" name="username" autocomplete="off" required>
                        </div>

                        <div class="mb-4">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" autocomplete="off" required>
                        </div>

                        <button class="btn btn-primary w-100 mt-2" type="submit">Login</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</body>
</html>