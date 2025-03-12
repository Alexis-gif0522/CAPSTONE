<?php 
session_start();

$page_title = "FEUR Registration Form";
include('includes/header.php'); 
include('includes/navbar.php');
?>

<div class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                    <?php
                        if (isset($_SESSION['status'])) 
                        {
                            echo"<h4>".$_SESSION['status']."</h4>";
                            unset($_SESSION["status"]);
                        }
                    ?>
                  <div class="card shadow">
                    <div class="card-header">
                        <h4>Resend Email Verification</h4>
                    </div>
                    <div class="card-body">
                        
                        <form action="resend-code.php" method="POST">
                            <div class="form-group mb-3">
                                <label for="">Email Address</label>
                                <input type="text" name="email" class="form-control" placeholder="Enter Email Address">
                            </div>
                            <div class="form-group mb-3">
                                <button type="submit" name="resend_email_verify_btn" class="btn btn-primary">Submit</button>
                            </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>