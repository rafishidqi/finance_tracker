<?php
    session_start();

    // Pastikan user sudah login
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../message_layout.php?message=Please login first&type=info&context=signup-success");
        exit;
    }

    if (isset($_POST['tomboltambah'])) {
        // Ambil data dari form
        include "koneksi.php";
        $user_id = $_SESSION['user_id']; // Ambil user_id dari session login
        $name = mysqli_real_escape_string($varkoneksi, $_POST['name']);
        $initial_balance = mysqli_real_escape_string($varkoneksi, $_POST['initial_balance']);
        $current_balance = $initial_balance; // Sama dengan initial_balance saat pertama kali dibuat
        $created_at = date('Y-m-d H:i:s'); // Timestamp saat ini

        if (!is_numeric($initial_balance) || $initial_balance < 0) {
            echo "<script>
            Swal.fire({
                icon: 'info',
                title: 'Information',
                text: 'Initial balance must be a positive number!'
            }).then(() => {
            window.location.href='index.php?halaman=dompet';
            });
            </script>";
            exit;
        }

        

        // Query untuk insert data
        $query = "INSERT INTO wallets (user_id, name, initial_balance, current_balance, created_at) 
                VALUES ('$user_id', '$name', '$initial_balance', '$current_balance', '$created_at')";

        // Eksekusi query
        if (mysqli_query($varkoneksi, $query)) {
            // Jika berhasil
            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Wallet successfully added!'
            }).then(() => {
                window.location.href='index.php?halaman=dompet';
            });
            </script>";
        } else {
            // Jika gagal
            echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'There is an error: " . mysqli_error($varkoneksi) . "'
            }).then(() => {
                window.location.href='index.php?halaman=dompet';
            });
            </script>";
        }
    }
?>