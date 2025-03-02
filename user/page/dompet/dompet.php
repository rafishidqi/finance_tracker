<link rel="stylesheet" href="css/transaksi.css">

<script>
document.title = "Finance Tracker - My Wallet";
</script>

<?php
session_start();
// Koneksi ke database (sesuaikan dengan koneksi Anda)
include('koneksi.php');
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Ambil data wallets
    $query = "SELECT w.*, u.username AS user_name FROM wallets w
    INNER JOIN users u ON w.user_id = u.id WHERE user_id = '$user_id'
    ORDER BY w.id ASC";
    $result = mysqli_query($varkoneksi, $query);

    $counter = 1;

    if (mysqli_num_rows($result) > 0) {
    echo '<div class="card-container">'; // Wrapper untuk grid card
    while ($row = mysqli_fetch_assoc($result)) {
    // Menampilkan data dalam card
    echo '<div class="card">';
    echo '<div class="card-header" style="background-color: #3498db; color: white;">';
    echo 'Wallet ID: ' . $counter;
    echo '</div>';
    echo '<div class="card-body">';
    echo '<h5 class="card-title">Wallet Name: ' . htmlspecialchars($row['name']) . '</h5>';
    echo '<p class="card-text">Owner: ' . htmlspecialchars($row['user_name']) . '</p>';
    echo '<p class="card-text">Initial Balance: ' . number_format($row['initial_balance'], 2) . '</p>';
    echo '<p class="card-text">Current Balance: ' . number_format($row['current_balance'], 2) . '</p>';
    echo '<p class="card-text">Created At: ' . $row['created_at'] . '</p>';
    echo '<a href="index.php?halaman=dompet_edit&id=' . $row['id'] . '" class="btn btn-warning">Edit</a>';
    echo '<a href="index.php?halaman=dompetdelete_aksi&id=' . $row['id'] . '" class="btn btn-danger" onclick="confirmDeletion(event, ' . $row['id'] . ')">Delete</a>';
    echo '</div>';
    echo '</div>';

    $counter++;
    }
    echo '</div>'; // Tutup wrapper card-container
    } else {
    echo '<p>No wallets found.</p>';
    }
} else {
    // Jika user tidak login
    header("Location: ../message_layout.php?message=Please login first&type=info&context=signup-success");
    exit;
}

?>

<script>
function confirmDeletion(event, walletId) {
    event.preventDefault(); // Mencegah aksi default tag <a>

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you really want to delete this wallet? This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
    }).then((result) => {
        if (result.isConfirmed) {
            // Arahkan ke URL yang ditentukan pada tag <a>
            window.location.href = 'index.php?halaman=dompetdelete_aksi&id=' + walletId;
        } else {
            Swal.fire(
                'Cancelled',
                'Your wallet is safe :)',
                'info'
            );
        }
    });
}
</script>