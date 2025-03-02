<?php
        if (isset($_GET['halaman'])) {
            $halaman = $_GET['halaman'];
            switch ($halaman) { 
                case 'dashboard':
                    include "page/dashboard/dashboard.php";
                    break;
                    
                case 'transaksi':
                    include "page/transaksi/transaksi.php";
                    break;

                case 'kalender':
                    include "page/kalender/kalender.php";
                    break;

                case 'statistik':
                    include "page/statistik/statistik.php";
                    break;

                case 'income':
                    include "page/transaksi/transaksi_income.php";
                    break;

                case 'expense':
                    include "page/transaksi/transaksi_expense.php";
                    break;

                case 'edit':
                    include "page/transaksi/transaksi_edit.php";
                    break;

                case 'dompet':
                    include "page/dompet/dompet.php";
                    break;

                case 'deskripsi':
                    include "page/deskripsi/deskripsi.php";
                    break;

                case 'about':
                    include "page/about/about.php";
                    break;

                case 'help':
                    include "page/help/help.php";
                    break;

                case 'logout':
                    include "page/logout/logout.php";
                    break;

                case 'dompet_edit':
                    include "page/dompet/dompet_edit.php";
                    break;

                case 'dompetedit_aksi':
                    include "page/dompet/dompetedit_aksi.php";
                    break;

                case 'dompetdelete_aksi':
                    include "page/dompet/dompetdelete_aksi.php";
                    break;

                case 'deskripsi_edit':
                    include "page/deskripsi/deskripsi_edit.php";
                    break;

                case 'deskripsiedit_aksi':
                    include "page/deskripsi/deskripsiedit_aksi.php";
                    break;

                case 'deskripsidelete_aksi':
                    include "page/deskripsi/deskripsidelete_aksi.php";
                    break;
                    
                case 'income_aksi':
                    include "page/transaksi/income_aksi.php";
                    break;
                
                case 'expense_aksi':
                    include "page/transaksi/expense_aksi.php";
                    break;

                case 'edit_aksi':
                    include "page/transaksi/edit_aksi.php";
                    break;

                case 'delete_aksi':
                    include "page/transaksi/delete_aksi.php";
                    break;

                case 'dompet_tambah':
                    include "page/dompet/dompet_tambah.php";
                    break;

                case 'dompet_aksi':
                    include "page/dompet/dompet_aksi.php";
                    break;

                case 'logout_aksi':
                    include "page/logout/logout_aksi.php";
                    break;

                default:
                    include "page/dashboard/dashboard.php";
                    break;
            }
        } else {
            include "page/dashboard/dashboard.php";
        }
    ?>