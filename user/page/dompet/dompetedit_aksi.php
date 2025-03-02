<?php
session_start();
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form edit
    $id = mysqli_real_escape_string($varkoneksi, $_POST['id']); // ID dompet
    $user_id = mysqli_real_escape_string($varkoneksi, $_SESSION['user_id']); // ID pengguna dari sesi
    $name = mysqli_real_escape_string($varkoneksi, $_POST['name']); // Nama dompet baru
    $initial_balance = mysqli_real_escape_string($varkoneksi, $_POST['initial_balance']); // Saldo awal baru
    $current_balance = mysqli_real_escape_string($varkoneksi, $_POST['current_balance']); // Saldo saat ini baru

    // Mulai transaksi database
    mysqli_begin_transaction($varkoneksi);

    try {
        // Update data dompet di tabel wallets
        $query_update_wallet = "UPDATE wallets SET 
            name = '$name', 
            initial_balance = '$initial_balance', 
            current_balance = '$current_balance' 
            WHERE id = '$id' AND user_id = '$user_id'";
        mysqli_query($varkoneksi, $query_update_wallet);

        // Commit transaksi
        mysqli_commit($varkoneksi);

        // Redirect ke halaman daftar dompet setelah berhasil
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Edit Wallet Successful!'
        }).then(() => {
            window.location.href='index.php?halaman=dompet';
        });
        </script>";
        exit();
    } catch (Exception $e) {
        // Rollback jika terjadi kesalahan
        mysqli_rollback($varkoneksi);
        echo "Update wallet failed: " . $e->getMessage();
    }
} else {
    echo "Invalid request method.";
}
?>