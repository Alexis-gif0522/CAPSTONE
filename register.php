<?php 
session_start();
$page_title = "FEUR Registration Form";
include('includes/header.php'); 
include('includes/navbar.php');
?>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-5">
    <div class="card-body">
                <?php if(isset($_SESSION['status'])): ?>
                    <div class="alert alert-warning">
                        <?= $_SESSION['status']; ?>
                    </div>
                    <?php unset($_SESSION['status']); ?>
                <?php endif; ?>

        <div class="card shadow form-container">
            <div class="card-header bg-success text-white text-center">
                <h4>Registration Form</h4>
            </div>
            <div class="card-body">
                <form action="code.php" method="POST" onsubmit="return validateForm()">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-bold">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-bold">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required onkeyup="checkPasswordStrength()">
                        <small id="passwordHelp" class="text-danger"></small>
                    </div>

                    <div class="form-group mb-3">
                        <label class="fw-bold">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required onkeyup="checkConfirmPassword()">
                        <small id="confirmPasswordHelp" class="text-danger"></small>
                    </div>

                    <div class="form-group mb-3">
                        <input type="checkbox" id="showPassword" onclick="togglePassword()"> Show Password
                    </div>

                    <div class="d-grid">
                        <button type="submit" name="register_btn" class="btn btn-primary">Register Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Password Validation & Show Password -->
<script>
function togglePassword() {
    let passwordField = document.getElementById("password");
    let confirmPasswordField = document.getElementById("confirm_password");
    let type = passwordField.type === "password" ? "text" : "password";
    passwordField.type = type;
    confirmPasswordField.type = type;
}

function checkPasswordStrength() {
    let password = document.getElementById("password").value;
    let passwordHelp = document.getElementById("passwordHelp");
    let regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    if (!regex.test(password)) {
        passwordHelp.innerText = "Password must be at least 8 characters long, include uppercase, lowercase, a number, and a special symbol.";
        return false;
    } else {
        passwordHelp.innerText = "";
        return true;
    }
}

function checkConfirmPassword() {
    let password = document.getElementById("password").value;
    let confirmPassword = document.getElementById("confirm_password").value;
    let confirmPasswordHelp = document.getElementById("confirmPasswordHelp");

    if (password !== confirmPassword) {
        confirmPasswordHelp.innerText = "Passwords do not match!";
        return false;
    } else {
        confirmPasswordHelp.innerText = "";
        return true;
    }
}

function validateForm() {
    if (!checkPasswordStrength() || !checkConfirmPassword()) {
        alert("Please fix password errors before submitting.");
        return false;
    }
    return true;
}
</script>

<?php include('includes/footer.php'); ?>
