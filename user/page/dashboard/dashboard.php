<link rel="stylesheet" href="css/dashboard.css?v=1.0">
<link rel="stylesheet" href="css/circles.css?v=1.0">
<script>
document.title = "Finance Tracker - Dashboard";
</script>
<main class="content">
    <header>
        <h1>Dashboard</h1>
    </header>

    <section class="transactions">
        <div class="card-container">
            <!-- Card My Balance -->
            <?php
                session_start();
                include "koneksi.php";
                if (isset($_SESSION['user_id'])) {
                    $user_id = $_SESSION['user_id'];

                    // Query untuk mengambil data wallet berdasarkan user_id
                    $query = "SELECT SUM(current_balance) AS total_balance FROM wallets WHERE user_id = '$user_id'";
                    $query_income = "SELECT SUM(amount) AS total_income FROM transactions WHERE type = 'income' AND user_id = '$user_id';";
                    $query_expense = "SELECT SUM(amount) AS total_expense FROM transactions WHERE type = 'expense' AND user_id = '$user_id';";
                    $result = mysqli_query($varkoneksi, $query);
                    $result_income = mysqli_query($varkoneksi, $query_income);
                    $result_expense = mysqli_query($varkoneksi, $query_expense);

                    // Cek jika data ditemukan
                    if ($result && mysqli_num_rows($result) > 0) {
                        $tampildata = mysqli_fetch_array($result);
                    } else {
                        $tampildata = null;
                    }
                    if ($result_income && mysqli_num_rows($result_income) > 0) {
                        $tampildata_income = mysqli_fetch_array($result_income);
                    } else {
                        $tampildata_income = null;
                    }
                    if ($result_expense && mysqli_num_rows($result_expense) > 0) {
                        $tampildata_expense = mysqli_fetch_array($result_expense);
                    } else {
                        $tampildata_expense = null;
                    }

                    // Ambil data kategori pengeluaran
                    $expense_query = "SELECT c.name, SUM(t.amount) AS total_amount 
                    FROM transactions t
                    JOIN categories c ON t.category_id = c.id
                    WHERE t.type = 'expense' AND t.user_id = '$user_id'
                    GROUP BY c.name";

                    $expense_result = mysqli_query($varkoneksi, $expense_query);
                    $expense_data = mysqli_fetch_all($expense_result, MYSQLI_ASSOC);

                    // Ambil data kategori pemasukan
                    $income_query = "SELECT c.name, SUM(t.amount) AS total_amount
                    FROM transactions t
                    JOIN categories c ON t.category_id = c.id
                    WHERE t.type = 'income' AND t.user_id = '$user_id'
                    GROUP BY c.name";
                    $income_result = mysqli_query($varkoneksi, $income_query);
                    $income_data = mysqli_fetch_all($income_result, MYSQLI_ASSOC);

                    // Ambil data wallet berdasarkan user_id
                    $query_wallets = "SELECT w.name, w.current_balance FROM wallets w WHERE w.user_id = '$user_id'";
                    $result_wallets = mysqli_query($varkoneksi, $query_wallets);

                    // Periksa apakah data wallet ditemukan
                    if ($result_wallets && mysqli_num_rows($result_wallets) > 0) {
                        $wallet_data = mysqli_fetch_all($result_wallets, MYSQLI_ASSOC);
                    } else {
                        $wallet_data = [];
                    }

                    // Siapkan data untuk chart "Balance by Wallet"
                    $wallet_labels = [];
                    $wallet_balances = [];

                    foreach ($wallet_data as $wallet) {
                        $wallet_labels[] = $wallet['name'];  // Nama wallet
                        $wallet_balances[] = $wallet['current_balance'];  // Saldo wallet
                    }

                    // Default ke bulan dan tahun saat ini
                    $current_month = isset($_GET['month']) ? $_GET['month'] : date('m');
                    $current_year = isset($_GET['year']) ? $_GET['year'] : date('Y');

                    // Query total income dan expense berdasarkan bulan dan tahun
                    $query_monthly_income = "SELECT SUM(amount) AS monthly_income 
                                            FROM transactions 
                                            WHERE type = 'income' AND user_id = '$user_id' 
                                            AND MONTH(date) = '$current_month' 
                                            AND YEAR(date) = '$current_year'";

                    $query_monthly_expense = "SELECT SUM(amount) AS monthly_expense 
                                            FROM transactions 
                                            WHERE type = 'expense' AND user_id = '$user_id' 
                                            AND MONTH(date) = '$current_month' 
                                            AND YEAR(date) = '$current_year'";

                    $result_monthly_income = mysqli_query($varkoneksi, $query_monthly_income);
                    $result_monthly_expense = mysqli_query($varkoneksi, $query_monthly_expense);

                    $monthly_income = $result_monthly_income ? mysqli_fetch_array($result_monthly_income)['monthly_income'] : 0;
                    $monthly_expense = $result_monthly_expense ? mysqli_fetch_array($result_monthly_expense)['monthly_expense'] : 0;

            } else {
                header("Location: ../message_layout.php?message=Please login first&type=info&context=signup-success");
                exit;
            }
            ?>
            <div class="card card-total my-balance">
                <a href="index.php?halaman=dompet_tambah">
                    <h2><i class="fas fa-wallet"></i> My Balance</h2>
                    <p>Total: <span id="my-balance">Rp <?php echo $tampildata["total_balance"]?> </span></p>
                </a>
            </div>

            <!-- Card Income -->
            <div class="card card-total income">
                <a href="index.php?halaman=income">
                    <h2><i class="fa-solid fa-arrow-down"></i> Income</h2>
                    <p>Total: <span id="income-total">Rp <?php echo $tampildata_income["total_income"]?></span></p>
                </a>
            </div>

            <!-- Card Expense -->
            <div class="card card-total outcome">
                <a href="index.php?halaman=expense">
                    <h2><i class="fa-solid fa-arrow-up"></i> Expense</h2>
                    <p>Total: <span id="expense-total">Rp <?php echo $tampildata_expense["total_expense"]?></span></p>
                </a>
            </div>
        </div>
    </section>

    <!-- Chart Section -->
    <section class="charts">

        <div class="card-container">
            <!-- Balance Chart -->
            <div class="card chart-card my-balance">
                <h3>Balance by Wallet</h3>
                <a href="index.php?halaman=dompet_tambah">
                    <canvas id="balanceChart"></canvas>
                </a>
            </div>

            <!-- Income Chart -->
            <div class="card chart-card income">
                <h3>Income by Category</h3>
                <a href="index.php?halaman=income">
                    <canvas id="incomeChart"></canvas>
                </a>
            </div>

            <!-- Expense Chart -->
            <div class="card chart-card expense">
                <h3>Expense by Category</h3>
                <a href="index.php?halaman=expense">
                    <canvas id="expenseChart"></canvas>
                </a>
            </div>
        </div>
    </section>

    <section class="calendar">
        <div class="card card-calendar">
            <header class="calendar-header">
                <button id="prevMonth">◀</button>
                <h2 id="calendarMonthYear"></h2>
                <button id="nextMonth">▶</button>
            </header>
            <a href="index.php?halaman=kalender">
                <div class="calendar-content">
                    <p>Total Income: <span id="calendarIncome">Rp 0</span></p>
                    <p>Total Expense: <span id="calendarExpense">Rp 0</span></p>
                </div>
            </a>

        </div>
    </section>


</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Data untuk chart pengeluaran
var expenseData = <?php echo json_encode($expense_data); ?>;
var expenseLabels = expenseData.map(function(item) {
    return item.name;
});
var expenseAmounts = expenseData.map(function(item) {
    return item.total_amount;
});

// Data untuk chart pemasukan
var incomeData = <?php echo json_encode($income_data); ?>;
var incomeLabels = incomeData.map(function(item) {
    return item.name;
});
var incomeAmounts = incomeData.map(function(item) {
    return item.total_amount;
});

// Chart untuk pengeluaran
var ctxExpense = document.getElementById('expenseChart').getContext('2d');
var expenseChart = new Chart(ctxExpense, {
    type: 'pie',
    data: {
        labels: expenseLabels,
        datasets: [{
            label: 'Total Pengeluaran',
            data: expenseAmounts,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)', // Red
                'rgba(54, 162, 235, 0.2)', // Blue
                'rgba(255, 206, 86, 0.2)', // Yellow
                'rgba(75, 192, 192, 0.2)', // Teal
                'rgba(153, 102, 255, 0.2)', // Purple
                'rgba(255, 159, 64, 0.2)', // Orange
                'rgba(100, 255, 218, 0.2)', // Aqua
                'rgba(201, 203, 207, 0.2)', // Gray
                'rgba(255, 127, 80, 0.2)', // Coral
                'rgba(144, 238, 144, 0.2)', // Light Green
                'rgba(123, 104, 238, 0.2)', // Medium Purple
                'rgba(255, 140, 0, 0.2)', // Dark Orange
                'rgba(72, 209, 204, 0.2)', // Medium Turquoise
                'rgba(240, 230, 140, 0.2)' // Khaki
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)', // Red
                'rgba(54, 162, 235, 1)', // Blue
                'rgba(255, 206, 86, 1)', // Yellow
                'rgba(75, 192, 192, 1)', // Teal
                'rgba(153, 102, 255, 1)', // Purple
                'rgba(255, 159, 64, 1)', // Orange
                'rgba(100, 255, 218, 1)', // Aqua
                'rgba(201, 203, 207, 1)', // Gray
                'rgba(255, 127, 80, 1)', // Coral
                'rgba(144, 238, 144, 1)', // Light Green
                'rgba(123, 104, 238, 1)', // Medium Purple
                'rgba(255, 140, 0, 1)', // Dark Orange
                'rgba(72, 209, 204, 1)', // Medium Turquoise
                'rgba(240, 230, 140, 1)' // Khaki
            ],
            borderWidth: 1

        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return 'Rp ' + tooltipItem.raw.toLocaleString();
                    }
                }
            }
        }
    }
});

// Chart untuk pemasukan
var ctxIncome = document.getElementById('incomeChart').getContext('2d');
var incomeChart = new Chart(ctxIncome, {
    type: 'pie',
    data: {
        labels: incomeLabels,
        datasets: [{
            label: 'Total Pemasukan',
            data: incomeAmounts,
            backgroundColor: [
                'rgba(255, 159, 64, 0.2)', // Orange
                'rgba(75, 192, 192, 0.2)', // Teal
                'rgba(153, 102, 255, 0.2)', // Purple
                'rgba(54, 162, 235, 0.2)', // Blue
                'rgba(255, 99, 132, 0.2)', // Red
                'rgba(255, 206, 86, 0.2)', // Yellow
                'rgba(100, 255, 218, 0.2)', // Aqua
                'rgba(201, 203, 207, 0.2)', // Gray
                'rgba(180, 144, 255, 0.2)' // Lavender
            ],
            borderColor: [
                'rgba(255, 159, 64, 1)', // Orange
                'rgba(75, 192, 192, 1)', // Teal
                'rgba(153, 102, 255, 1)', // Purple
                'rgba(54, 162, 235, 1)', // Blue
                'rgba(255, 99, 132, 1)', // Red
                'rgba(255, 206, 86, 1)', // Yellow
                'rgba(100, 255, 218, 1)', // Aqua
                'rgba(201, 203, 207, 1)', // Gray
                'rgba(180, 144, 255, 1)' // Lavender
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return 'Rp ' + tooltipItem.raw.toLocaleString();
                    }
                }
            }
        }
    }
});

var walletLabels = <?php echo json_encode($wallet_labels); ?>;
var walletBalances = <?php echo json_encode($wallet_balances); ?>;

// Chart untuk Balance by Wallet
var ctxBalance = document.getElementById('balanceChart').getContext('2d');
var balanceChart = new Chart(ctxBalance, {
    type: 'pie', // Bisa menggunakan 'pie' atau 'doughnut'
    data: {
        labels: walletLabels, // Label kategori wallet
        datasets: [{
            label: 'Balance by Wallet',
            data: walletBalances, // Data saldo wallet
            backgroundColor: [
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(75, 192, 192, 0.2)'

            ],
            borderColor: [
                'rgba(153, 102, 255, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(75, 192, 192, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top'
            },
            tooltip: {
                callbacks: {
                    label: function(tooltipItem) {
                        return 'Rp ' + tooltipItem.raw.toLocaleString(); // Format dengan 'Rp'
                    }
                }
            }
        }
    }
});
</script>

<script>
let currentMonth = new Date().getMonth() + 1; // JavaScript bulan dimulai dari 0
let currentYear = new Date().getFullYear();

function updateCalendar() {
    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    // Perbarui header bulan dan tahun
    document.getElementById('calendarMonthYear').textContent = `${monthNames[currentMonth - 1]} ${currentYear}`;

    // Fetch data pemasukan dan pengeluaran
    fetch(`fetch_monthly_data.php?month=${currentMonth}&year=${currentYear}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('calendarIncome').textContent = `Rp ${parseInt(data.income).toLocaleString()}`;
            document.getElementById('calendarExpense').textContent =
                `Rp ${parseInt(data.expense).toLocaleString()}`;
        });
}

document.getElementById('prevMonth').addEventListener('click', () => {
    currentMonth -= 1;
    if (currentMonth < 1) {
        currentMonth = 12;
        currentYear -= 1;
    }
    updateCalendar();
});

document.getElementById('nextMonth').addEventListener('click', () => {
    currentMonth += 1;
    if (currentMonth > 12) {
        currentMonth = 1;
        currentYear += 1;
    }
    updateCalendar();
});

// Inisialisasi kalender
updateCalendar();
</script>