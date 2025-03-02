<link rel="stylesheet" href="css/logout.css?v=1.0">
<script>
document.title = "Finance Tracker - Logout";
</script>

<!-- Modal Logout Confirmation -->
<div class="card-container">
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Confirm Logout</h5>
                </div>
                <div class="modal-body">
                    Are you sure you want to log out?
                </div>
                <div class="modal-footer">
                    <!-- No button (close modal) --><a href="index.php?halaman=dashboard">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    </a>
                    <!-- Yes button (proceed to logout) -->
                    <a href="index.php?halaman=logout_aksi" class="btn btn-danger">Yes</a>
                </div>
            </div>
        </div>
    </div>
</div>