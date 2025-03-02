<link rel="stylesheet" href="css/transaksi.css?v=1.0">

<script>
document.title = "Finance Tracker - Transactions";
</script>

<?php
session_start();
include('koneksi.php');

// Pastikan user sudah login
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Tangani filter jika ada
    $wallet_filter = isset($_GET['wallet']) ? $_GET['wallet'] : '';
    $type_filter = isset($_GET['type']) ? $_GET['type'] : '';
    $category_filter = isset($_GET['category']) ? $_GET['category'] : '';

    // Mulai membuat form filter
    echo '<div style="margin-bottom: 20px;">';
    echo '<i class="fa fa-filter" id="filter-icon" style="font-size: 24px; cursor: pointer;"></i>';
    echo '<form method="GET" action="index.php" id="filter-form">';

    // Menambahkan parameter 'halaman' ke URL
    echo '<input type="hidden" name="halaman" value="transaksi">';

    // Filter by Wallet
    echo '<select name="wallet" class="form-select select2" style="margin-right: 10px;">';
    echo '<option value="">Show by Wallet</option>';

    // Ambil daftar wallet dari database
    $wallet_query = "SELECT id, name FROM wallets WHERE user_id = '$user_id'";
    $wallet_result = mysqli_query($varkoneksi, $wallet_query);
    while ($wallet = mysqli_fetch_assoc($wallet_result)) {
        // Pilih wallet sesuai filter
        $selected = ($wallet['id'] == $wallet_filter) ? 'selected' : '';
        echo '<option value="' . $wallet['id'] . '" ' . $selected . '>' . $wallet['name'] . '</option>';
    }
    echo '</select>';

    // Filter by Type (Income/Expense)
    echo '<select name="type" class="form-select select2" style="margin-right: 10px;">';
    echo '<option value="">Show by Type</option>';
    echo '<option value="income" ' . ($type_filter == 'income' ? 'selected' : '') . '>Income</option>';
    echo '<option value="expense" ' . ($type_filter == 'expense' ? 'selected' : '') . '>Expense</option>';
    echo '</select>';

    // Filter by Category
    echo '<select name="category" class="form-select select2">';
    echo '<option value="">Show by Category</option>';

    // Ambil daftar kategori dari database
    $category_query = "SELECT id, name FROM categories";
    $category_result = mysqli_query($varkoneksi, $category_query);
    while ($category = mysqli_fetch_assoc($category_result)) {
        // Pilih kategori sesuai filter
        $selected = ($category['id'] == $category_filter) ? 'selected' : '';
        echo '<option value="' . $category['id'] . '" ' . $selected . '>' . $category['name'] . '</option>';
    }
    echo '</select>';

    // Tombol filter
    echo '<button type="submit" class="btn btn-primary">Filter</button>';
    // Tombol reset filter
    echo '<a href="index.php?halaman=transaksi" class="btn btn-secondary">Reset</a>';
    echo '</form>';
    echo '</div>';

    // Mulai menyusun query
    $query = "SELECT t.*, c.name AS category_name, w.name AS wallet_name FROM transactions t
              INNER JOIN categories c ON t.category_id = c.id
              INNER JOIN wallets w ON t.wallet_id = w.id
              WHERE t.user_id = '$user_id'";

    // Filter berdasarkan wallet
    if ($wallet_filter) {
        $query .= " AND t.wallet_id = '$wallet_filter'";
    }

    // Filter berdasarkan type (income/expense)
    if ($type_filter) {
        $query .= " AND t.type = '$type_filter'";
    }

    // Filter berdasarkan category
    if ($category_filter) {
        $query .= " AND t.category_id = '$category_filter'";
    }

    // Urutkan berdasarkan ID transaksi
    $query .= " ORDER BY t.id ASC";

    // Eksekusi query
    $result = mysqli_query($varkoneksi, $query);

    $counter = 1;
    if (mysqli_num_rows($result) > 0) {
        echo '<div class="card-container">'; // Wrapper untuk grid card
        while ($row = mysqli_fetch_assoc($result)) {
            $header_color = ($row['type'] == 'income') ? '#2ecc71;' : '#e74c3c';
            // Menampilkan data dalam card
            echo '<div class="card">';
            echo '<div class="card-header" style="background-color: ' . $header_color . '; color: white;">';
            echo '<i class="fa-solid fa-arrow-right-arrow-left"></i> Transaction ID: ' . $counter;
            echo '</div>';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">Amount: ' . number_format($row['amount'], 2) . '</h5>';
            echo '<p class="card-text">Date: ' . $row['date'] . '</p>';
            echo '<p class="card-text">Description: ' . $row['description'] . '</p>';
            echo '<p class="card-text">Category Name: ' . $row['category_name'] . '</p>';
            echo '<p class="card-text">Wallet Name: ' . $row['wallet_name'] . '</p>';
            echo '<p class="card-text">Type: ' . $row['type'] . '</p>';
            echo '<p class="card-text">Created At: ' . $row['created_at'] . '</p>';
            echo '<a href="index.php?halaman=edit&id=' . $row['id'] . '" class="btn btn-warning">Edit</a>';
            echo '<a href="index.php?halaman=delete_aksi&id=' . $row['id'] . '" class="btn btn-danger" onclick="confirmDeletion(event, ' . $row['id'] . ')">Delete</a>';
            echo '</div>';
            echo '</div>';

            $counter++;
        }
        echo '</div>'; // Tutup wrapper card-container
    } else {
        echo '<p>No transactions found.</p>';
    }
} else {
    // Jika user tidak login
    header("Location: ../message_layout.php?message=Please login first&type=info&context=signup-success");
    exit;
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
// Menangani klik pada ikon filter
document.getElementById("filter-icon").addEventListener("click", function() {
    var filterForm = document.getElementById("filter-form");
    filterForm.classList.toggle("show"); // Toggle class untuk menampilkan atau menyembunyikan form filter
});

// Initialize Select2
$(document).ready(function() {
    $('.select2').select2();
});
</script>
<script>
function confirmDeletion(event, transactionId) {
    event.preventDefault(); // Mencegah aksi default tag <a>

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you really want to delete this transaction? This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
    }).then((result) => {
        if (result.isConfirmed) {
            // Arahkan ke URL yang ditentukan pada tag <a>
            window.location.href = 'index.php?halaman=delete_aksi&id=' + transactionId;
        } else {
            Swal.fire(
                'Cancelled',
                'Your transaction is safe :)',
                'info'
            );
        }
    });
}
</script>