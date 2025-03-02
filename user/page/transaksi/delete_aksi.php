<?php
session_start();
include('koneksi.php'); // Pastikan koneksi database di-include

if (!isset($_SESSION['user_id'])) {
    header("Location: ../message_layout.php?message=Please login first&type=info&context=signup-success");
    exit;
}

$user_id = $_SESSION['user_id'];

// Validasi parameter ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Pastikan hanya menerima angka
if ($id <= 0) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Invalid Transaction ID',
            text: 'The transaction ID is invalid.'
        }).then(() => {
            window.location='index.php?halaman=transaksi';
        });
    </script>";
    exit;
}

// Ambil informasi transaksi yang akan dihapus
$query_old = "SELECT amount, wallet_id, type, date FROM transactions WHERE id = ? AND user_id = ?";
$stmt_old = $varkoneksi->prepare($query_old);
$stmt_old->bind_param("ii", $id, $user_id);
$stmt_old->execute();
$result_old = $stmt_old->get_result();

if ($result_old->num_rows === 0) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Transaction Not Found',
            text: 'The requested transaction does not exist.'
        }).then(() => {
            window.location='index.php?halaman=transaksi';
        });
    </script>";
    exit;
}

$transaction = $result_old->fetch_assoc();
$amount = $transaction['amount'];
$wallet_id = $transaction['wallet_id'];
$type = $transaction['type'];
$date = $transaction['date'];
$stmt_old->close();

// Update current_balance di tabel wallet
$query_update_wallet = $type === 'income'
    ? "UPDATE wallets SET current_balance = current_balance - ? WHERE id = ? AND user_id = ?"
    : "UPDATE wallets SET current_balance = current_balance + ? WHERE id = ? AND user_id = ?";

$stmt_update_wallet = $varkoneksi->prepare($query_update_wallet);
$stmt_update_wallet->bind_param("dii", $amount, $wallet_id, $user_id);

if (!$stmt_update_wallet->execute()) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Update Failed',
            text: '" . htmlspecialchars($stmt_update_wallet->error) . "'
        }).then(() => {
            window.location='index.php?halaman=transaksi';
        });
    </script>";
    exit;
}
$stmt_update_wallet->close();

// Hapus transaksi dari tabel transactions
$query_delete = "DELETE FROM transactions WHERE id = ? AND user_id = ?";
$stmt_delete = $varkoneksi->prepare($query_delete);
$stmt_delete->bind_param("ii", $id, $user_id);

if ($stmt_delete->execute()) {
    // Update calendar_summary setelah transaksi dihapus
    updateCalendarSummary($varkoneksi, $user_id, $date, $amount, $type);
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Transaction Deleted',
            text: 'The transaction has been successfully deleted.'
        }).then(() => {
            window.location='index.php?halaman=transaksi';
        });
    </script>";
} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Delete Failed',
            text: '" . htmlspecialchars($stmt_delete->error) . "'
        }).then(() => {
            window.location='index.php?halaman=transaksi';
        });
    </script>";
}
$stmt_delete->close();

// Fungsi untuk memperbarui calendar_summary
function updateCalendarSummary($conn, $user_id, $date, $amount, $type) {
    // Ambil data summary sebelumnya
    $query_summary = "SELECT total_income, total_expense, net_balance FROM calendar_summary WHERE user_id = ? AND date = ?";
    $stmt_summary = $conn->prepare($query_summary);
    $stmt_summary->bind_param("is", $user_id, $date);
    $stmt_summary->execute();
    $result_summary = $stmt_summary->get_result();

    if ($row = $result_summary->fetch_assoc()) {
        $total_income = $row['total_income'] ?? 0;
        $total_expense = $row['total_expense'] ?? 0;
        $net_balance = $row['net_balance'] ?? 0;

        // Sesuaikan total income dan expense setelah penghapusan
        if ($type === 'income') {
            $total_income -= $amount; // Kurangi total income dengan amount transaksi yang dihapus
        } elseif ($type === 'expense') {
            $total_expense -= $amount; // Kurangi total expense dengan amount transaksi yang dihapus
        }

        // Hitung net_balance
        $net_balance = $total_income - $total_expense;

        // Update summary
        $query_update_summary = "UPDATE calendar_summary 
                                 SET total_income = ?, total_expense = ?, net_balance = ? 
                                 WHERE user_id = ? AND date = ?";
        $stmt_update_summary = $conn->prepare($query_update_summary);
        $stmt_update_summary->bind_param("dddis", $total_income, $total_expense, $net_balance, $user_id, $date);
        $stmt_update_summary->execute();
        $stmt_update_summary->close();
    }

    $stmt_summary->close();
}
$varkoneksi->close();
?>