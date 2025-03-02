<?php
session_start();
include "koneksi.php";

if (isset($_POST['tomboltambah'])) {
    // Ambil data dari form
    $user_id = $_SESSION['user_id']; // Ambil user_id dari session login
    $date = mysqli_real_escape_string($varkoneksi, $_POST['tanggal']);
    $amount = mysqli_real_escape_string($varkoneksi, $_POST['jumlah']);
    $category_id = mysqli_real_escape_string($varkoneksi, $_POST['kategori']);
    $description = mysqli_real_escape_string($varkoneksi, $_POST['deskripsi']);
    $wallet_id = mysqli_real_escape_string($varkoneksi, $_POST['dompet']); // Ambil wallet_id dari session atau input lain
    $type = 'income'; // Karena ini adalah form income
    $foto_path = null;
    $created_at = date('Y-m-d H:i:s'); // Timestamp saat ini

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/"; // Direktori untuk menyimpan gambar
        $file_name = uniqid() . "_" . basename($_FILES['foto']['name']); // Tambahkan prefix unik untuk nama file
        $target_file = $target_dir . $file_name;

        // Pindahkan file ke direktori target
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
            $foto_path = $target_file; // Simpan path gambar jika berhasil
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Image Failed',
                    text: 'Failed to upload image!'
                }).then(() => {
                    window.history.back();
                });
            </script>";
            exit;
        }
    }

    // Query untuk insert data transaksi
    $query = "INSERT INTO transactions (user_id, date, amount, description, category_id, wallet_id, type, image_path, created_at) 
            VALUES ('$user_id', '$date', '$amount', '$description', '$category_id', '$wallet_id', '$type', '$foto_path','$created_at')";
    
    // Eksekusi query transaksi
    if (mysqli_query($varkoneksi, $query)) {
        // Jika transaksi berhasil, update saldo wallet
        $update_balance_query = "UPDATE wallets SET current_balance = current_balance + $amount WHERE id = $wallet_id";
        mysqli_query($varkoneksi, $update_balance_query);

        // Query untuk insert/update ke tabel summary
        $summary_query = "
            INSERT INTO calendar_summary (user_id, date, total_income, total_expense, net_balance, created_at) 
            VALUES ('$user_id', '$date', $amount, 0, $amount, '$created_at')
            ON DUPLICATE KEY UPDATE 
                total_income = total_income + $amount,
                net_balance = total_income - total_expense";
        mysqli_query($varkoneksi, $summary_query);

        // Redirect ke halaman transaksi setelah berhasil
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Add Data Income Successful!'
            }).then(() => {
                window.location.href='index.php?halaman=transaksi';
            });
            </script>";
    } else {
        // Jika gagal insert transaksi
        echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something Wrong: " . mysqli_error($varkoneksi) . "'
                    }).then(() => {
                        window.history.back();
                    });
                </script>";
    }
}
?>