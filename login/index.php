<?php
session_start();
include '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['namauser'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM user WHERE namauser='$username'");
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['iduser'] = $user['iduser'];
        $_SESSION['role'] = $user['role'];

        // Redirect ke dashboard sesuai role
        if ($user['role'] == 'admin') {
            header("Location: ../dashboard/admin.php");
        } elseif ($user['role'] == 'waiter') {
            header("Location: ../dashboard/waiter.php");
        } elseif ($user['role'] == 'kasir') {
            header("Location: ../dashboard/kasir.php");
        } elseif ($user['role'] == 'owner') {
            header("Location: ../dashboard/owner.php");
        }
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<h2>Login</h2>
<?php if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
} ?>
<form method="POST">
    Username: <br>
    <input type="text" name="namauser" required><br><br>

    Password: <br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>