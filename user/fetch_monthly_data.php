<?php
session_start();
include "koneksi.php";


if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $month = $_GET['month'];
    $year = $_GET['year'];

    $query_income = "SELECT SUM(amount) AS income FROM transactions WHERE type = 'income' AND user_id = '$user_id' AND MONTH(date) = '$month' AND YEAR(date) = '$year'";
    $query_expense = "SELECT SUM(amount) AS expense FROM transactions WHERE type = 'expense' AND user_id = '$user_id' AND MONTH(date) = '$month' AND YEAR(date) = '$year'";

    $income_result = mysqli_query($varkoneksi, $query_income);
    $expense_result = mysqli_query($varkoneksi, $query_expense);

    $income = $income_result ? mysqli_fetch_assoc($income_result)['income'] : 0;
    $expense = $expense_result ? mysqli_fetch_assoc($expense_result)['expense'] : 0;

    echo json_encode(['income' => $income, 'expense' => $expense]);
}
?>