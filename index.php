<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Kasir Restoran</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #121212, #2c2c2c);
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 40px 30px;
            background-color: #1e1e1e;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
            text-align: center;
            color: #fff;
        }
        .login-container i {
            font-size: 50px;
            color: #ffbb00;
            margin-bottom: 20px;
        }
        h3 {
            font-weight: 600;
            margin-bottom: 30px;
            color: #ffbb00;
        }
        .form-control {
            background-color: #2c2c2c;
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-size: 15px;
            color: #fff;
        }
        .form-control:focus {
            background-color: #333;
            color: #fff;
            outline: none;
            box-shadow: none;
        }
        .form-label {
            text-align: left;
            display: block;
            font-weight: 500;
            color: #bbb;
            margin-bottom: 5px;
        }
        .btn-primary {
            background: linear-gradient(to right, #ffbb00, #ff8800);
            border: none;
            border-radius: 30px;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.3s ease;
            color: #1e1e1e;
        }
        .btn-primary:hover {
            background: linear-gradient(to right, #ff8800, #ffbb00);
        }
    </style>
</head>
<body>

<div class="login-container">
    <i class="fas fa-utensils"></i>
    <h3>Login</h3>
    <form action="proses_login.php" method="post">
        <div class="mb-3 text-start">
            <label for="namauser" class="form-label">Nama User</label>
            <input type="text" class="form-control" id="namauser" name="namauser" required>
        </div>
        <div class="mb-3 text-start">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div>

</body>
</html>
