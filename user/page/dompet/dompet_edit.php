<?php
session_start();
include "koneksi.php";

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../message_layout.php?message=Please login first&type=info&context=signup-success");
    exit;
}

$user_id = $_SESSION['user_id'];

// Cek apakah ada parameter ID di URL
if (isset($_GET['id'])) {
    $wallet_id = $_GET['id'];

    // Query dengan JOIN untuk mendapatkan nama pengguna
    $query = "
        SELECT wallets.*, users.username 
        FROM wallets 
        JOIN users ON wallets.user_id = users.id 
        WHERE wallets.id = '$wallet_id' AND wallets.user_id = '$user_id'";
        
    $result = mysqli_query($varkoneksi, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $wallet = mysqli_fetch_assoc($result);
    } else {
        echo "<script>
        Swal.fire({
            icon: 'info',
            title: 'Not found',
            text: 'Wallet not found!'
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
            window.location.href='index.php?halaman=dompet';
        });
      </script>";
    exit;
}
?>


<link rel="stylesheet" href="css/inout.css?v=1.0">

<div class="bungkus-content">
    <main class="main-content">
        <header class="main-header">
            <h1>Edit Wallet</h1>
        </header>
        <div class="main-container">
            <form action="index.php?halaman=dompetedit_aksi" method="POST">
                <section class="inout-table-section">
                    <table class="inout-table">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($wallet['id']); ?>">

                        <tr>
                            <td>Owner</td>
                            <td><input type="text" style="color: #7a7a7a;"
                                    value="<?php echo htmlspecialchars($wallet['username']); ?>" readonly>
                            </td>
                        </tr>

                        <tr>
                            <td>Wallet Name</td>
                            <td>
                                <input type="text" name="name" id="name"
                                    value="<?php echo htmlspecialchars($wallet['name']); ?>" required>
                            </td>
                        </tr>

                        <tr>
                            <td>Initial Balance</td>
                            <td>
                                <input type="number" name="initial_balance" id="initial_balance"
                                    value="<?php echo htmlspecialchars($wallet['initial_balance']); ?>" required>
                            </td>
                        </tr>

                        <tr>
                            <td>Current Balance</td>
                            <td>
                                <input type="number" name="current_balance" id="current_balance"
                                    value="<?php echo htmlspecialchars($wallet['current_balance']); ?>" required>
                            </td>
                        </tr>

                        <tr>
                            <td></td>
                            <td><input class="add-inout-btn" type="submit" value="Update Wallet" name="tomboltambah">
                            </td>
                        </tr>
                    </table>
                </section>
            </form>
        </div>
    </main>
</div>