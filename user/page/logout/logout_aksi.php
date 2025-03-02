<?php
    //mulai session
    session_start();

    session_unset();

    //hapus session
    session_destroy();

    // Mengatur header untuk mencegah caching
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");

    //alihkan ke halaman login
    header("location:../login.php");
    exit;
?>