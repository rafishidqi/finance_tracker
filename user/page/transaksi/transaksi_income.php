<?php
session_start(); // Pastikan session dimulai di awal file
include "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../message_layout.php?message=Please login first&type=info&context=signup-success");
    exit;
}

$user_id = $_SESSION['user_id'];

// Query untuk mendapatkan data kategori dan wallet
$query_kategori = "SELECT * FROM categories WHERE type = 'income'";
$query_wallet = "SELECT * FROM wallets WHERE user_id = '$user_id'";

$result_kategori = mysqli_query($varkoneksi, $query_kategori);
$result_wallet = mysqli_query($varkoneksi, $query_wallet);
?>

<link rel="stylesheet" href="css/inout.css?v=1.0">


<div class="bungkus-content">
    <main class="main-content">
        <header class="main-header">
            <h1>Add Income</h1>
        </header>
        <div class="main-container">
            <form action="index.php?halaman=income_aksi" method="POST" enctype="multipart/form-data">
                <section class="inout-table-section">
                    <table class="inout-table">
                        <tr>
                            <td>Date</td>
                            <td><input type="date" name="tanggal" id="tanggal" placeholder="Select Date" required></td>
                        </tr>

                        <tr>
                            <td>Amount (Rp)</td>
                            <td><input type="number" name="jumlah" id="jumlah" placeholder="Rp 0" required></td>
                        </tr>

                        <tr>
                            <td>Category</td>
                            <td>
                                <select name="kategori" id="kategori" required>
                                    <option value="">Select Category</option>
                                    <?php
                                    if ($result_kategori && mysqli_num_rows($result_kategori) > 0) {
                                        while ($row = mysqli_fetch_assoc($result_kategori)) {
                                    ?>
                                    <option value="<?php echo $row['id']?>">
                                        <?php echo $row['name']?>
                                    </option>";
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
                                    ?>
                                    <option value="<?php echo $row['id']?>">
                                        <?php echo $row['name']?>
                                    </option>";
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>Description</td>
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <!-- Input Memo -->
                                    <textarea name="deskripsi" id="deskripsi" placeholder="Short Description"
                                        style="flex: 1;"></textarea>

                                    <!-- Icon Upload -->
                                    <label for="foto" style="cursor: pointer; margin-left: 10px;">
                                        <i class="fa fa-upload" aria-hidden="true"
                                            style="font-size: 24px; color: #ffffff;"></i>
                                    </label>
                                    <input type="file" name="foto" id="foto" style="display: none;">
                                </div>
                            </td>
                        </tr>


                        <tr>
                            <td></td>
                            <td><input class="add-inout-btn" type="submit" value="Add Data" id="tomboltambah"
                                    name="tomboltambah"></td>
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

<script>
document.getElementById('foto').addEventListener('change', function() {
    // Mendapatkan elemen ikon
    var icon = document.querySelector('label[for="foto"] i');

    // Jika file dipilih, ubah warna ikon
    if (this.files && this.files[0]) {
        icon.style.color = '#007bff'; // Ubah warna menjadi hijau saat ada file yang dipilih
    } else {
        icon.style.color = '#ffffff'; // Kembalikan warna ikon jika tidak ada file yang dipilih
    }
});
</script>