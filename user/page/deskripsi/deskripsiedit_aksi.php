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
if (isset($_POST['id'])) {
    $transaction_id = $_POST['id'];
    $description = $_POST['deskripsi']; // Deskripsi yang baru
    
    // Menangani upload gambar jika ada file yang diunggah
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Menentukan lokasi dan nama file gambar
        $image_temp_name = $_FILES['image']['tmp_name'];
        $image_name = time() . '_' . $_FILES['image']['name'];
        $image_target = 'uploads/' . $image_name;

        // Pindahkan file gambar ke folder uploads
        if (move_uploaded_file($image_temp_name, $image_target)) {
            $image_path = $image_target; // Simpan path gambar
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Image Failed',
                    text: 'Failed to upload image!'
                }).then(() => {
                    window.location='index.php?halaman=deskripsi';
                });
            </script>";
            exit;
        }
    } else {
        // Jika tidak ada gambar yang diunggah, gunakan gambar lama
        $query_get_image = "SELECT image_path FROM transactions WHERE id = '$transaction_id' AND user_id = '$user_id'";
        $result = mysqli_query($varkoneksi, $query_get_image);
        $transaction = mysqli_fetch_assoc($result);
        $image_path = $transaction['image_path']; // Ambil path gambar lama
    }

    // Query untuk memperbarui data transaksi (deskripsi dan gambar)
    $query_update = "UPDATE transactions 
                     SET description = '$description', image_path = '$image_path' 
                     WHERE id = '$transaction_id' AND user_id = '$user_id'";

    if (mysqli_query($varkoneksi, $query_update)) {
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Description & Image updated successfully!'
        }).then(() => {
            window.location.href='index.php?halaman=deskripsi';
        });
      </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Edit Failed',
                    text: 'Failed to update description or image!'
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