<?php
require_once("boundless_connect.php");

include("main-css.php");

// Step 1: Define the number of records per page
$perPage = 5;

// Get the current page, default to 1 if not set
$page = isset($_GET['page']) ? $_GET['page'] : 1;

if (isset($_GET["price-min"]) && isset($_GET["price-max"])) {
    $min = $_GET["price-min"];
    $max = $_GET["price-max"];

    $sql = "SELECT lesson.*, lesson_category.name AS category_name, teacher_info.name AS teacher_name, classroom.name AS classroom_name FROM lesson 
    JOIN lesson_category ON lesson.category_id = lesson_category.id
    JOIN teacher_info ON lesson.teacher_id = teacher_info.id
    JOIN classroom ON lesson.classroom_id = classroom.id
    WHERE lesson.price > $min AND lesson.price < $max AND lesson.valid =1 ORDER BY lesson.id ASC";
} else if (isset($_GET["category"])) {
    $category = $_GET["category"];

    $sql = "SELECT lesson.*, lesson_category.name AS category_name, teacher_info.name AS teacher_name, classroom.name AS classroom_name FROM lesson 
    JOIN lesson_category ON lesson.category_id = lesson_category.id
    JOIN teacher_info ON lesson.teacher_id = teacher_info.id
    JOIN classroom ON lesson.classroom_id = classroom.id
    WHERE lesson.category_id = $category AND lesson.valid =1 ORDER BY lesson.id ASC";
} else {
    $sql = "SELECT lesson.*, lesson_category.name AS category_name, teacher_info.name AS teacher_name, classroom.name AS classroom_name FROM lesson 
    JOIN lesson_category ON lesson.category_id = lesson_category.id 
    JOIN teacher_info ON lesson.teacher_id = teacher_info.id
    JOIN classroom ON lesson.classroom_id = classroom.id
    WHERE lesson.valid=1
    ORDER BY lesson.id ASC";
}

// Step 2: Add LIMIT and OFFSET to the SQL query
$startItem = ($page - 1) * $perPage;
$sql .= " LIMIT $startItem, $perPage";

$result = $conn->query($sql);

if (!$result) {
    die("Error in SQL query: " . $conn->error);
}

$lessonCount = $result->num_rows;
$rows = $result->fetch_all(MYSQLI_ASSOC);
//var_dump($rows);

$conn->close();

// Step 3: Display pagination links
$totalPages = ceil($lessonCount / $perPage);

$id = "id";
$name = "name";
$price = "price";
$category_id = "category_id";
$classroom_id = "classroom_id";
$teacher_id = "teacher_id";
$img = "img";
$info = "info";
?>

<!-- nav bar的模板 -->
<link href="css/sb-admin-2.min.css" rel="stylesheet">

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Lesson-list</title>
    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <link rel="icon" href="favicon.svg">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fa-solid fa-music"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Boundless</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item -->
            <li class="nav-item">
                <a class="nav-link" href="user-list.php">
                    <i class="fa-solid fa-user"></i>
                    <span>會員</span></a>
            </li>

            <!-- Nav Item -->
            <li class="nav-item">
                <a class="nav-link" href="instrument-list.php">
                    <i class="fa-solid fa-guitar"></i>
                    <span>樂器</span>
                </a>
            </li>

            <!-- Nav Item - 折疊式選單 -->
            <li class="nav-item active">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <span>課程&教師</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <!-- <h6 class="collapse-header">Custom Components:</h6> -->
                        <a class="collapse-item" href="lesson-list.php">課程管理</a>
                        <a class="collapse-item" href="teacher-list.php">教師資訊</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item -->
            <li class="nav-item">
                <a class="nav-link" href="classroom-list.php">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <span>場地空間</span></a>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
                    <i class="fa-solid fa-tags"></i>
                    <span>分類標籤</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">樂器</h6>
                        <a class="collapse-item" href="brand_list.php">品牌</a>
                        <a class="collapse-item" href="instrument_category_list.php">樂器類別</a>

                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">課程</h6>
                        <a class="collapse-item" href="lesson_category_list.php">課程</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#orderCollapsePages" aria-expanded="true" aria-controls="collapsePages">
                    <i class="fa-solid fa-check-to-slot"></i>
                    <span>訂單</span>
                </a>
                <div id="orderCollapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="order_list.php">
                            <span>樂器訂單</span>
                        </a>
                        <a class="collapse-item" href="lesson_order_list.php">
                            <span>課程訂單</span>
                        </a>
                        <a class="collapse-item" href="classroom_order_list.php">
                            <span>場地租借訂單</span>
                        </a>
                    </div>
                </div>
            </li>

            <!-- Nav Item -->
            <li class="nav-item">
                <a class="nav-link" href="coupons-list.php">
                    <i class="fa-solid fa-ticket"></i>
                    <span>優惠券</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="article-list.php">
                    <i class="fa-solid fa-feather-pointed"></i>
                    <span>文章</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <form class="form-inline">
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>
                    </form>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-dark" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-dark" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>
                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Nue</span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="container-top_Nue py-2 d-flex justify-content-between align-items-center">
                        <div>
                            <!-- Page Heading -->
                            <h1 class="h3 mb-2 text-gray-800">課程管理</h1>
                            <p class="mb-4">本頁面能夠新增、刪除及查看課程內容。</p>
                        </div>
                        <div class="justify-content-end">
                            <a class="btn btn-dark" href="lesson-list.php?page=<?= $page ?>&order=1"><i class="bi bi-sort-up"></i></a>
                            <a class="btn btn-dark" href="lesson-list.php?page=<?= $page ?>&order=2"><i class="bi bi-sort-down"></i></a>
                            <a class="btn btn-dark " title="新增課程" href="add-lesson.php">
                                <i class="bi bi-plus-lg"></i>
                                新增
                            </a>
                        </div>
                    </div>

                    <!-- <form class=" col py-2" action="">
                        <div class="input-group"><input type="text" class="form-control" placeholder="搜尋..." name="search">
                            <button class="btn btn-dark" type="submit" id=""><i class="bi bi-search"></i></button>
                    </form>
                    <a class="btn btn-dark" href="user-list.php?page=<?= $page ?>&order=1"><i class="bi bi-sort-down-alt"></i></a>
                    <a class="btn btn-dark" href="user-list.php?page=<?= $page ?>&order=2"><i class="bi bi-sort-up"></i></a>-->

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-dark">課程資料表</h6>
                        </div>
                        <!-- 價格分類 -->
                        <div class="py-2 ms-3">
                            <form action="">
                                <div class="row g-3 align-items-center">
                                    <?php if (isset($_GET["price-min"])) : ?>
                                        <!-- 有price-min的變數才會顯示出來 -->
                                        <div class="col-auto">
                                            <a class="btn btn-dark text-white" href="lesson-list.php"><i class="fa-solid fa-arrow-left"></i></a>
                                        </div>
                                    <?php endif; ?>
                                    <!-- <div class="col-auto">
                                    <label for="">價錢</label>
                                </div> -->
                                    <div class="col-auto">
                                        <input type="number" class="form-control text-end price-input" name="price-min" value="<?php $priceMin = isset($_GET["price-min"]) ? $min : 0;
                                                                                                                                echo $priceMin; ?>">
                                    </div>
                                    <div class="col-auto">~</div>
                                    <div class="col-auto">
                                        <input type="number" class="form-control text-end price-input" name="price-max" value="<?php $priceMax = isset($_GET["price-max"]) ? $max : 99999;
                                                                                                                                echo $priceMax; ?>">
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-dark text-white" type="submit">金額範圍</button>
                                    </div>
                            </form>
                            <div class="py-2">
                                共 <?= $lessonCount ?> 筆
                            </div>
                        </div>
                        <!-- 種類分類 -->
                        <div>
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="lesson-list.php">全部</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="lesson-list.php?category=1">鋼琴</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="lesson-list.php?category=2">吉他</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="lesson-list.php?category=3">理樂</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="lesson-list.php?category=4">鼓</a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <!-- dataTable 修改過 -->
                                <table class="table table-bordered" id="dataTable123" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>編號</th>
                                            <th>課程名稱</th>
                                            <th>價格</th>
                                            <th>課程種類</th>
                                            <th>地點</th>
                                            <th>教師名稱</th>
                                            <!-- <th>img</th> -->
                                            <th>詳細資訊</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($lessonCount > 0) : ?>
                                            <?php foreach ($rows as $row) : ?>
                                                <tr>
                                                    <td><?= $row["id"] ?></td>
                                                    <td><?= $row["name"] ?></td>
                                                    <td><?= $row["price"] ?></td>
                                                    <td><?= $row["category_name"] ?></td>
                                                    <td>
                                                        <?= $row["classroom_name"] ?></a>
                                                    </td>
                                                    <td><a href="teacher-info.php?id=<?= $row["teacher_id"] ?>"><?= $row["teacher_name"] ?></a></td>
                                                    <!-- <td><?= $row["img"] ?></td> -->
                                                    <td><a class="btn btn-info bg-dark text-white" href="lesson-info.php?id=<?= $row["id"] ?>"><i class="justify-content-end fa-solid fa-circle-info"></i></a></td>
                                                </tr>
                                            <?php endforeach ?>
                                        <?php else : ?>
                                            目前無使用者
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                                <nav aria-label="Page navigation example">
                                    <ul class="pagination">
                                        <?php for ($i = 1; $i <= $lessonCount; $i++) : ?>
                                            <li class="page-item <?php if ($page == $i) echo "active"; ?>">
                                                <a class="page-link bg-secondary text-light" href="lesson-list.php?page=<?= $i ?>&order=<?= $order ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor ?>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>


                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Borderless 2023</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-dark" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/datatables-demo.js"></script>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

</body>

</html>