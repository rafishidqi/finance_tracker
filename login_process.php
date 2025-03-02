<?php
    session_start();
    include "user/koneksi.php";

    $username = $_POST['username'];
    $password = md5($_POST['password']);

    // Query untuk cek username dan password
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $ambildata = mysqli_query($varkoneksi, $query);

    // Cek jumlah data
    $cek = mysqli_num_rows($ambildata);

    if ($cek > 0) {
        $user = mysqli_fetch_assoc($ambildata);

        // Simpan data ke session
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_id'] = $user['id']; // Tambahkan user_id
        $_SESSION['status'] = "login";

        header("location:user");
    } else {
        header("location:message_layout.php?type=error&message=Login failed, please try again&context=login-failed");
    }
?>