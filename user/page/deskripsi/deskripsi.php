<link rel="stylesheet" href="css/transaksi.css?v=1.0">

<script>
document.title = "Finance Tracker - Description & Image";
</script>

<?php
session_start();
// Koneksi ke database (sesuaikan dengan koneksi Anda)
include('koneksi.php');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    // Ambil data deskripsi dan gambar
    $query = "SELECT id,description, image_path FROM transactions WHERE user_id = '$user_id' ORDER BY id ASC";
    $result = mysqli_query($varkoneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        echo '<div class="card-container">'; // Wrapper untuk grid card

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="card">';
            echo '<div class="card-body">';

            // Tampilkan deskripsi
            echo '<p class="description-header">Description: ' . htmlspecialchars($row['description']) . '</p>';

            // Tampilkan gambar jika ada
            if (!empty($row['image_path'])) {
                echo '<img src="' . htmlspecialchars($row['image_path']) . '" alt="Uploaded Image" class="uploaded-image">';
                echo '<p class="card-text"> </p>';
            } else {
                echo '<p class="card-text">No image uploaded.</p>';
            }
            echo '<a href="index.php?halaman=deskripsi_edit&id=' . $row['id'] . '" class="btn btn-warning">Edit</a>';
            echo '<a href="index.php?halaman=dompetdelete_aksi&id=' . $row['id'] . '" class="btn btn-danger" onclick="confirmDeletion(event, ' . $row['id'] . ')">Delete Image</a>';

            echo '</div>';
            echo '</div>';
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

<script>
function confirmDeletion(event, imageId) {
    event.preventDefault(); // Mencegah aksi default tag <a>

    Swal.fire({
        title: 'Are you sure?',
        text: "Do you really want to delete this image? This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
    }).then((result) => {
        if (result.isConfirmed) {
            // Arahkan ke URL yang ditentukan pada tag <a>
            window.location.href = 'index.php?halaman=deskripsidelete_aksi&id=' + imageId;
        } else {
            Swal.fire(
                'Cancelled',
                'Your image is safe :)',
                'info'
            );
        }
    });
}
</script>