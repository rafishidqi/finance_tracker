<?php
include "user/koneksi.php";

$username = $_POST['username'];
$password = md5($_POST['password']);
$email = $_POST['email'];

// Periksa apakah username sudah digunakan
$query_check = "SELECT * FROM users WHERE username='$username' OR email='$email'";
$check_result = mysqli_query($varkoneksi, $query_check);

if (mysqli_num_rows($check_result) > 0) {
    $user = mysqli_fetch_assoc($check_result);
    if ($user['username'] === $username) {
        header("location:message_layout.php?status=error&message=Username already exists&context=signup-failed");
    } elseif ($user['email'] === $email) {
        header("location:message_layout.php?status=error&message=Email already exists&context=signup-failed");
    }
} else {
    // Masukkan data pengguna baru
    $query_insert = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
    $result = mysqli_query($varkoneksi, $query_insert);

    if ($result) {
        // Redirect dengan pesan sukses
        header("location:message_layout.php?type=success&message=Signup successful, please login&context=signup-success");
    } else {
        // Redirect dengan pesan gagal
        header("location:message_layout.php?type=error&message=Signup failed, please try again&context=signup-failed");
    }
}
?>