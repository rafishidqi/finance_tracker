<?php
session_start();
include "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('User not logged in!'); window.location='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Cek apakah ada parameter ID di URL untuk mengedit transaksi
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
            icon: 'error',
            title: 'Error!',
            text: 'Transaction not found!'
        }).then(() => {
            window.location.href='index.php?halaman=dompet';
        });
      </script>";
        exit;
    }
} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Invalid Requests!'
        }).then(() => {
            window.location.href='index.php?halaman=transaksi';
        });
      </script>";
    exit;
}

// Query untuk mendapatkan kategori dan dompet
$query_kategori = "SELECT * FROM categories WHERE type = '" . $transaction['type'] . "'"; // Tipe sesuai dengan tipe transaksi
$query_wallet = "SELECT * FROM wallets WHERE user_id = '$user_id'";

$result_kategori = mysqli_query($varkoneksi, $query_kategori);
$result_wallet = mysqli_query($varkoneksi, $query_wallet);
?>

<link rel="stylesheet" href="css/inout.css">

<div class="bungkus-content">
    <main class="main-content">
        <header class="main-header">
            <h1>Edit Transaction</h1>
        </header>
        <div class="main-container">
            <form action="index.php?halaman=edit_aksi" method="POST">
                <section class="inout-table-section">
                    <table class="inout-table">
                        <input type="hidden" name="id" value="<?php echo $transaction['id']; ?>">
                        <input type="hidden" name="type" value="<?php echo $transaction['type']; ?>">

                        <tr>
                            <td>Date</td>
                            <td><input type="date" name="tanggal" id="tanggal"
                                    value="<?php echo $transaction['date']; ?>" required></td>
                        </tr>

                        <tr>
                            <td>Amount (Rp)</td>
                            <td><input type="number" name="jumlah" id="jumlah"
                                    value="<?php echo $transaction['amount']; ?>" required></td>
                        </tr>

                        <tr>
                            <td>Category</td>
                            <td>
                                <select name="kategori" id="kategori" required>
                                    <option value="">Select Category</option>
                                    <?php
                                    if ($result_kategori && mysqli_num_rows($result_kategori) > 0) {
                                        while ($row = mysqli_fetch_assoc($result_kategori)) {
                                            $selected = $row['id'] == $transaction['category_id'] ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>>
                                        <?php echo $row['name']; ?>
                                    </option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Wallet</td>
                            <td>
                                <select name="dompet" id="dompet" required>
                                    <option value="">Select Wallet</option>
                                    <?php
                                    if ($result_wallet && mysqli_num_rows($result_wallet) > 0) {
                                        while ($row = mysqli_fetch_assoc($result_wallet)) {
                                            $selected = $row['id'] == $transaction['wallet_id'] ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>>
                                        <?php echo $row['name']; ?>
                                    </option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Description</td>
                            <td><textarea name="deskripsi" id="deskripsi"
                                    required><?php echo $transaction['description']; ?></textarea></td>
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
<script type="text/javascript">
$(document).ready(function() {
    $('#dompet').select2({
        placeholder: 'Select Wallet',
        allowClear: true
    });

    $('#kategori').select2({
        placeholder: 'Select Category',
        allowClear: true
    });
});
</script>