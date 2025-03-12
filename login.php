<?php 
session_start();
include('includes/header.php'); 
include('includes/navbar.php');
?>

<div class="login-container">
    <div class="form-container">
        <!-- FEUR CAPE Title -->
        <div class="text-center mb-3">
            <h2 class="feur-title">FEUR CAPE</h2>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Login Form</h5>
            </div>
            <div class="card-body">
                <?php if(isset($_SESSION['status'])): ?>
                    <div class="alert alert-warning">
                        <?= $_SESSION['status']; ?>
                    </div>
                    <?php unset($_SESSION['status']); ?>
                <?php endif; ?>

                <form action="code.php" method="POST">
                    <div class="form-group mb-3">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="login_btn" class="btn btn-primary">Login Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
