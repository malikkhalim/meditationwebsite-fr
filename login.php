<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $username, $hashed_password);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "Email not registered.";
    }

    $stmt->close();
    $conn->close();
}

if (password_verify($password, $hashed_password)) {
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $username;
    echo "<script>alert('Login successful!'); window.location.href = 'index.php';</script>";
    exit();
} else {
    echo "<script>alert('Incorrect password.'); window.location.href = 'login.php';</script>";
}

?>
