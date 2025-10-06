<div class="container">
    <header class="p-3 mb-3 border-bottom">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
                    <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap">
                        <use xlink:href="#bootstrap"></use>
                    </svg>
                </a>

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><img src="logo/MAC.png" alt="โลโก้" class="img-fluid me-3" style="height: 50px;"></li>
                    <li><a href="location.php" class="nav-link px-2 link-dark">สถานที่</a></li>
                    <li><a href="" class="nav-link px-2 link-dark">ประวัติการทำงาน</a></li>
                    <li><a href="#" class="nav-link px-2 link-dark">ข้อมูลส่วนบุคคล</a></li>
                </ul>

                <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                    <input type="search" class="form-control" placeholder="ค้นหา..." aria-label="Search">
                </form>
                <div class="text-end">
                    <?php if (!isset($_SESSION['employee_id'])) { ?>
                        <a href="login.php" class="btn btn-outline-primary me-2">Login</a>
                    <?php } else { ?>
                            <a href="login.php" class="btn btn-danger">logout</a>
                    <?php } ?>
                
                   

                </div>
            </div>
        </div>
    </header>   
</div>