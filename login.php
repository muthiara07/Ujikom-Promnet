<?php
session_start();
require 'config.php';
$error='';

if($_SERVER['REQUEST_METHOD']==='POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare('SELECT id,username,password FROM users WHERE username=? LIMIT 1');
    $stmt->bind_param('s',$username);
    $stmt->execute();
    $res = $stmt->get_result();
    if(!$row = $res->fetch_assoc()) {
        $error = 'Username salah';
    } else {
        if(!password_verify($password, $row['password'])) {
            $error = 'Password salah';
        } else {
            $_SESSION['user'] = $row['username'];
            header('Location: index.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - SIMBS</title>
<style>
/* Reset ringan */
* { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
body { height: 100vh; display: flex; justify-content: center; align-items: center; background: #f0f2f5; }

/* Kotak login */
.login-box { background: #fff; padding: 30px 25px; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
.login-box h2 { margin-bottom: 20px; color: #333; font-size: 26px; }

/* Label input */
.login-box label { display: block; text-align: left; margin-bottom: 5px; color: #555; font-size: 14px; }

/* Input form */
.login-box input { width: 100%; padding: 10px 12px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 6px; outline: none; font-size: 14px; }
.login-box input:focus { border-color: #2575fc; box-shadow: 0 0 5px rgba(37,117,252,0.3); }

/* Tombol submit */
.login-box .btn { width: 100%; padding: 12px; background-color: #2575fc; border: none; border-radius: 6px; color: white; font-size: 16px; cursor: pointer; transition: background 0.3s ease; }
.login-box .btn:hover { background-color: #6a11cb; }

/* Pesan error */
.login-box .error { background-color: #ffe5e5; border-left: 4px solid #ff5c5c; padding: 10px; margin-bottom: 15px; text-align: left; color: #a00; border-radius: 5px; font-size: 14px; }

/* Link bawah form */
.login-box p { margin-top: 12px; font-size: 14px; color: #555; }
.login-box p a { color: #2575fc; text-decoration: none; }
.login-box p a:hover { text-decoration: underline; }

/* Responsif kecil */
@media (max-width: 480px) {
    .login-box { padding: 25px 20px; }
    .login-box h2 { font-size: 22px; }
}
</style>
</head>
<body>
<div class="login-box">
    <h2>Login</h2>
    <?php if($error): ?>
        <div class="error"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>
    <form method="post">
        <label>Username</label>
        <input name="username" required>

        <label>Password</label>
        <input name="password" type="password" required>

        <button class="btn" type="submit">Login</button>
    </form>
    <p>Belum punya akun? <a href="register.php">Register</a></p>
</div>
</body>
</html>
