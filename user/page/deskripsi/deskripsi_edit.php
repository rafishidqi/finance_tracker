<?php
session_start();
include "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../message_layout.php?message=Please login first&type=info&context=signup-success");
    exit;
}

$user_id = $_SESSION['user_id'];

// Cek apakah ada parameter ID di URL untuk mengedit gambar
if (isset($_GET['id'])) {
    $transaction_id = $_GET['id'];

    // Query untuk mendapatkan data transaksi berdasarkan ID
    $query = "SELECT * FROM transactions WHERE id = '$transaction_id' AND user_id = '$user_id'";
    $result = mysqli_query($varkoneksi, $query);
    $transaction = mysqli_fetch_assoc($result);

    // Jika transaksi tidak ditemukan
    if (!$transaction) {
        echo "<script>
        Swal.fire({
            icon: 'info',
            title: 'Information',
            text: 'Transaction not found!'
        }).then(() => {
            window.location.href='index.php?halaman=deskripsi';
            });
        </script>";
    }
} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Invalid Requests!'
        }).then(() => {
            window.location.href='index.php?halaman=deskripsi';
        });
      </script>";
    exit;
}

?>

<link rel="stylesheet" href="css/inout.css?v=0.1">

<div class=" bungkus-content">
    <main class="main-content">
        <header class="main-header">
            <h1>Edit Description & Image</h1>
        </header>
        <div class="main-container">
            <form action="index.php?halaman=deskripsiedit_aksi" method="POST" enctype="multipart/form-data">
                <section class="inout-table-section">
                    <table class="inout-table">
                        <input type="hidden" name="id" value="<?php echo $transaction['id']; ?>">

                        <tr>
                            <td>Description</td>
                            <td><textarea name="deskripsi" id="deskripsi"
                                    required><?php echo $transaction['description']; ?></textarea></td>
                        </tr>

                        <tr>
                            <td>Image</td>
                            <td>
                                <?php if (!empty($transaction['image_path'])): ?>
                                <img src="<?php echo $transaction['image_path']; ?>" alt="Uploaded Image"
                                    class="uploaded-image">
                                <br>
                                <label for="image">Change Image:</label>
                                <?php endif; ?>
                                <input type="file" name="image" id="image" accept="image/*">
                            </td>
                        </tr>

                        <tr>
                            <td></td>
                            <td><input class="add-inout-btn" type="submit" value="Update Data" name="tomboltambah"></td>
                        </tr>
                    </table>
                </section>
            </form>
        </div>
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>