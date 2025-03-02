<?php
session_start();
include "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../message_layout.php?message=Please login first&type=info&context=signup-success");
    exit;
}

$user_id = $_SESSION['user_id'];

// Cek apakah ada ID transaksi yang dikirimkan
if (isset($_GET['id'])) {
    $transaction_id = $_GET['id'];

    // Query untuk mendapatkan data transaksi berdasarkan ID dan user_id
    $query = "SELECT * FROM transactions WHERE id = '$transaction_id' AND user_id = '$user_id'";
    $result = mysqli_query($varkoneksi, $query);
    $transaction = mysqli_fetch_assoc($result);

    // Jika transaksi tidak ditemukan
    if (!$transaction) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Transaction Not Found',
                text: 'Transaction not found!'
            }).then(() => {
                window.location='index.php?halaman=transaksi';
            });
        </script>";
        exit;
    }

    // Menghapus gambar terkait jika ada
    $image_path = $transaction['image_path'];
    if ($image_path && file_exists($image_path)) {
        if (unlink($image_path)) {
            // Mengupdate field image_path menjadi NULL di database setelah gambar dihapus
            $query_update = "UPDATE transactions SET image_path = NULL WHERE id = '$transaction_id' AND user_id = '$user_id'";
            if (mysqli_query($varkoneksi, $query_update)) {
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Image Deleted',
                        text: 'Image deleted successfully!'
                    }).then(() => {
                        window.location='index.php?halaman=deskripsi';
                    });
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Database Update Failed',
                        text: 'Failed to update database after deleting image!'
                    }).then(() => {
                        window.location='index.php?halaman=deskripsi';
                    });
                </script>";
            }
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Delete Failed',
                    text: 'Failed to delete image from server!'
                }).then(() => {
                    window.location='index.php?halaman=deskripsi';
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'info',
                title: 'No Image',
                text: 'No image found to delete!'
            }).then(() => {
                window.location='index.php?halaman=deskripsi';
            });
        </script>";
    }
} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Invalid request!'
        }).then(() => {
            window.location='index.php?halaman=deskripsi';
        });
    </script>";
}
?>