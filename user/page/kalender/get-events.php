<?php
session_start();
header('Content-Type: application/json');

// Menggunakan jalur relatif untuk mengakses koneksi.php
include '../../koneksi.php'; // Menyesuaikan dengan struktur folder

// Pastikan koneksi berhasil
if (!$varkoneksi) {
    die(json_encode(['error' => 'Koneksi gagal: ' . mysqli_connect_error()]));
}

$user_id = $_SESSION['user_id']; // ID pengguna yang login

// Pastikan user_id ada
if (!isset($user_id)) {
    echo json_encode(['error' => 'User ID tidak ditemukan.']);
    exit;
}

// Ambil data dari calendar_summary
$query = "SELECT id, date, total_income, total_expense, net_balance FROM calendar_summary WHERE user_id = ?";
$stmt = $varkoneksi->prepare($query);

// Cek jika query gagal
if (!$stmt) {
    echo json_encode(['error' => 'Error preparing statement: ' . $varkoneksi->error]);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Mengumpulkan data untuk ditampilkan
$events = [];
while ($row = $result->fetch_assoc()) {
    // Logika untuk menghapus data dengan total_income dan total_expense = 0
    if ($row['total_income'] == 0 && $row['total_expense'] == 0) {
        $delete_query = "DELETE FROM calendar_summary WHERE id = ? AND user_id = ?";
        $delete_stmt = $varkoneksi->prepare($delete_query);
        if ($delete_stmt) {
            $delete_stmt->bind_param("ii", $row['id'], $user_id);
            $delete_stmt->execute();
            $delete_stmt->close();
        }
        continue; // Lewati iterasi jika data dihapus
    }

    // Menambahkan data ke events jika tidak kosong
    $events[] = [
        'id' => $row['id'],
        'title' => "Income: " . number_format($row['total_income'], 2) . " / Expense: " . number_format($row['total_expense'], 2),
        'start' => $row['date'],
        'description' => "Net Balance: " . number_format($row['net_balance'], 2)
    ];
}
    
// Cek jika tidak ada data ditemukan
if (empty($events)) {
    echo json_encode(['message' => 'No events found.']);
    exit;
}

// Menutup statement dan koneksi
$stmt->close();
$varkoneksi->close();

// Mengirim data ke frontend dalam format JSON
echo json_encode($events);
?>