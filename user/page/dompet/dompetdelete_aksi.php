<?php
session_start();
include('koneksi.php'); // Pastikan koneksi database di-include

if (!isset($_SESSION['user_id'])) {
    header("Location: ../message_layout.php?message=Please login first&type=info&context=signup-success");
    exit;
}

$user_id = $_SESSION['user_id'];

// Validasi parameter ID
$wallet_id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Pastikan hanya menerima angka
if ($wallet_id <= 0) {
    echo "<script>
        Swal.fire({
            icon: 'info',
            title: 'Information',
            text: 'ID dompet tidak valid.'
        }).then(() => {
            window.location.href='index.php?halaman=dompet';
        });
      </script>";
    exit;
}

// Ambil informasi dompet yang akan dihapus
$query_old = "SELECT id, current_balance FROM wallets WHERE id = ? AND user_id = ?";
$stmt_old = $varkoneksi->prepare($query_old);
$stmt_old->bind_param("ii", $wallet_id, $user_id);
$stmt_old->execute();
$result_old = $stmt_old->get_result();

if ($result_old->num_rows === 0) {
    echo "<script>
        Swal.fire({
            icon: 'info',
            title: 'Information',
            text: 'Wallet not found!'
        }).then(() => {
            window.location.href='index.php?halaman=dompet';
        });
      </script>";
    exit;
}

$wallet = $result_old->fetch_assoc();
$current_balance = $wallet['current_balance'];
$stmt_old->close();

// Cek apakah dompet memiliki saldo yang tersisa
if ($current_balance > 0) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Cannot delete wallet with balance more than 0." . mysqli_error($varkoneksi) . "'
        }).then(() => {
            window.location.href='index.php?halaman=dompet';
        });
      </script>";
    exit;
}

// Hapus transaksi terkait dompet
$query_delete_transactions = "DELETE FROM transactions WHERE wallet_id = ? AND user_id = ?";
$stmt_delete_transactions = $varkoneksi->prepare($query_delete_transactions);
$stmt_delete_transactions->bind_param("ii", $wallet_id, $user_id);
$stmt_delete_transactions->execute();
$stmt_delete_transactions->close();

// Hapus dompet dari tabel wallets
$query_delete_wallet = "DELETE FROM wallets WHERE id = ? AND user_id = ?";
$stmt_delete_wallet = $varkoneksi->prepare($query_delete_wallet);
$stmt_delete_wallet->bind_param("ii", $wallet_id, $user_id);

if ($stmt_delete_wallet->execute()) {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Wallet has been deteled!'
        }).then(() => {
            window.location.href='index.php?halaman=dompet';
        });
      </script>";
} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Delete Failed!: " . $stmt_delete_wallet->error . "'
        }).then(() => {
            window.location.href='index.php?halaman=dompet';
        });
      </script>";
}

$stmt_delete_wallet->close();
$varkoneksi->close();
?>