<?php
session_start();
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form edit
    $id = mysqli_real_escape_string($varkoneksi, $_POST['id']); // ID transaksi
    $user_id = mysqli_real_escape_string($varkoneksi, $_SESSION['user_id']); // ID pengguna
    $tanggal = mysqli_real_escape_string($varkoneksi, $_POST['tanggal']); // Tanggal transaksi
    $jumlah = mysqli_real_escape_string($varkoneksi, $_POST['jumlah']); // Jumlah transaksi baru
    $kategori = mysqli_real_escape_string($varkoneksi, $_POST['kategori']); // ID kategori
    $dompet = mysqli_real_escape_string($varkoneksi, $_POST['dompet']); // ID dompet (wallet)
    $deskripsi = mysqli_real_escape_string($varkoneksi, $_POST['deskripsi']); // Deskripsi transaksi
    $type = mysqli_real_escape_string($varkoneksi, $_POST['type']); // income atau expense

    // Ambil jumlah transaksi lama
    $query_old_transaction = "SELECT amount, wallet_id FROM transactions WHERE id = '$id' AND user_id = '$user_id'";
    $result_old_transaction = mysqli_query($varkoneksi, $query_old_transaction);

    if ($result_old_transaction && mysqli_num_rows($result_old_transaction) > 0) {
        $old_transaction = mysqli_fetch_assoc($result_old_transaction);
        $old_amount = $old_transaction['amount'];
        $old_wallet_id = $old_transaction['wallet_id'];

        // Hitung selisih jumlah transaksi baru dan lama
        $difference = $jumlah - $old_amount;

        // Sesuaikan selisih jika tipe transaksi adalah expense
        if ($type == 'expense') {
            $difference = -$difference; // Untuk expense, kurangi saldo
        }

        // Mulai transaksi database
        mysqli_begin_transaction($varkoneksi);

        try {
            // Update data transaksi di tabel transactions
            $query_update = "UPDATE transactions SET 
                date = '$tanggal', 
                amount = '$jumlah', 
                category_id = '$kategori', 
                wallet_id = '$dompet', 
                description = '$deskripsi' 
                WHERE id = '$id' AND user_id = '$user_id' AND type = '$type';";
            mysqli_query($varkoneksi, $query_update);

            // Jika wallet_id berubah, perbarui saldo wallet lama dan wallet baru
            if ($old_wallet_id != $dompet) {
                // Kembalikan saldo wallet lama
                $query_restore_old_wallet = "UPDATE wallets 
                                             SET current_balance = current_balance - ('$old_amount') 
                                             WHERE id = '$old_wallet_id';";
                mysqli_query($varkoneksi, $query_restore_old_wallet);

                // Tambahkan saldo ke wallet baru
                $query_update_new_wallet = "UPDATE wallets 
                                            SET current_balance = current_balance + ('$jumlah') 
                                            WHERE id = '$dompet';";
                mysqli_query($varkoneksi, $query_update_new_wallet);
            } else {
                // Update current_balance pada wallet yang sama
                $query_update_wallet = "UPDATE wallets 
                                        SET current_balance = current_balance + ('$difference') 
                                        WHERE id = '$dompet';";
                mysqli_query($varkoneksi, $query_update_wallet);
            }

            // Update atau insert ke tabel calendar_summary
            $summary_query = "INSERT INTO calendar_summary (user_id, date, total_income, total_expense, net_balance, created_at)
                  VALUES ('$user_id', '$tanggal', 
                          (SELECT IFNULL(SUM(amount), 0) FROM transactions WHERE user_id = '$user_id' AND date = '$tanggal' AND type = 'income'),
                          (SELECT IFNULL(SUM(amount), 0) FROM transactions WHERE user_id = '$user_id' AND date = '$tanggal' AND type = 'expense'),
                          (SELECT IFNULL(SUM(amount), 0) - IFNULL(SUM(amount), 0) FROM transactions WHERE user_id = '$user_id' AND date = '$tanggal'),
                          NOW())
                  ON DUPLICATE KEY UPDATE 
                      total_income = (SELECT IFNULL(SUM(amount), 0) FROM transactions WHERE user_id = '$user_id' AND date = '$tanggal' AND type = 'income'),
                      total_expense = (SELECT IFNULL(SUM(amount), 0) FROM transactions WHERE user_id = '$user_id' AND date = '$tanggal' AND type = 'expense'),
                      net_balance = total_income - total_expense;";

            mysqli_query($varkoneksi, $summary_query);

            // Commit transaksi
            mysqli_commit($varkoneksi);

            // Redirect ke halaman transaksi setelah berhasil
            echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Edit Data Successful!'
            }).then(() => {
                window.location.href='index.php?halaman=transaksi';
            });
            </script>";
            exit();
        } catch (Exception $e) {
            // Rollback jika terjadi kesalahan
            mysqli_rollback($varkoneksi);
            echo "Update data Failed: " . $e->getMessage();
        }
    } else {
        echo "Transaction Not Found.";
    }
} else {
    echo "Invalid request method.";
}
?>