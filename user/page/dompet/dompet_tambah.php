<link rel="stylesheet" href="css/inout.css?v=1.0">

<div class="bungkus-content">
    <main class="main-content">
        <header class="main-header">
            <h1>Add Wallet</h1>
        </header>
        <div class="main-container">
            <form action="index.php?halaman=dompet_aksi" method="POST">
                <section class="inout-table-section">
                    <table class="inout-table">
                        <tr>
                            <td>Wallet Name</td>
                            <td><input type="text" name="name" id="name" placeholder="Enter Wallet Name" required></td>
                        </tr>

                        <tr>
                            <td>Initial Balance</td>
                            <td><input type="number" name="initial_balance" id="initial_balance"
                                    placeholder="Enter Initial Balance" required></td>
                        </tr>

                        <tr>
                            <td></td>
                            <td><input class="add-inout-btn" type="submit" value="Add Wallet" id="tomboltambah"
                                    name="tomboltambah"></td>
                        </tr>
                    </table>
                </section>
            </form>
        </div>
    </main>
</div>