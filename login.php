<?php 

    session_start(); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in MCA</title>

    <link rel="stylesheet" href="login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- เพิ่ม Font Awesome สำหรับไอคอน -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <main class="form-signin w-100 m-auto">
        <form action="login_db.php" method="post">
            <!-- เพิ่มไอคอน -->
            <div class="text-center mb-4">
                <i class="fas fa-user-circle fa-3x text-primary mb-3"></i>
            </div>
            <h1 class="h3 mb-3 fw-normal">ลงชื่อเข้าสู่ระบบ</h1>

            <?php
            if (isset($_SESSION['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']); 
                    ?>
                </div>
            <?php } ?>


            <div class="form-floating">
                <input type="text" class="form-control" name="employee_id" id="floatingInput" placeholder="User ID">
                <label for="floatingInput"><i class="fas fa-id-card me-2"></i>User ID</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" name="Password" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword"><i class="fas fa-lock me-2"></i>Password</label>
            </div>

            <button class="btn btn-lg btn-primary w-100 py-2" name="login" type="submit">
                Sign in
            </button>
            <p class="mt-5 mb-3 text-muted text-center">© 2025</p>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="login.js"></script>
</body>

</html>