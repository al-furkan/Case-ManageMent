<?php
session_start();
include('./db.php');
if (!isset($_SESSION['id'])) {
  header("Location: login.php");
  exit();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Case-Management</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="assets/vendors/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/vendors/owl-carousel-2/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/vendors/owl-carousel-2/owl.theme.default.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- End layout styles -->
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
                <a class="sidebar-brand brand-logo" href="index.html">
                    <h3>Case-Management</h3>
                </a>

            </div>
            <ul class="nav">
                <li class="nav-item profile">
                    <div class="profile-desc">
                        <?php
            $user_id = $_SESSION['id']; // Assuming user_id is stored in session
            $result = $conn->query("SELECT Name, Image FROM users WHERE id = $user_id");
            $user = $result->fetch_assoc();
            ?>

                        <div class="profile-pic">
                            <div class="count-indicator">
                                <img class="img-xs rounded-circle " src="./register/<?php echo $user['Image']; ?>"
                                    alt="<?php echo $user['Image']; ?>">
                                <span class="count bg-success"></span>
                            </div>
                            <div class="profile-name">
                                <h5 class="mb-0 font-weight-normal"><?php echo $user['Name']; ?></h5>
                                <span>User</span>
                            </div>
                        </div>
                        <a href="#" id="profile-dropdown" data-toggle="dropdown"><i
                                class="mdi mdi-dots-vertical"></i></a>
                        <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list"
                            aria-labelledby="profile-dropdown">
                            <a href="#" class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-settings text-primary"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject ellipsis mb-1 text-small">Account settings</p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-onepassword  text-info"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject ellipsis mb-1 text-small">Change Password</p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item preview-item">
                                <div class="preview-thumbnail">
                                    <div class="preview-icon bg-dark rounded-circle">
                                        <i class="mdi mdi-calendar-today text-success"></i>
                                    </div>
                                </div>
                                <div class="preview-item-content">
                                    <p class="preview-subject ellipsis mb-1 text-small">To-do list</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </li>
                <li class="nav-item nav-category">
                    <span class="nav-link">Navigation</span>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link" href="index.php">
                        <span class="menu-icon">
                            <i class="mdi mdi-speedometer"></i>
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link <?php echo (isset($_GET['dailynotes']) ? 'active' : ''); ?>"
                        href="./index.php?dailynotes=1">
                        <span class="menu-icon">
                            <i class="mdi mdi-table-large"></i>
                        </span>
                        <span class="menu-title">Daily Notes</span>
                    </a>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false"
                        aria-controls="ui-basic">
                        <span class="menu-icon">
                            <i class="mdi mdi-laptop"></i>
                        </span>
                        <span class="menu-title">Cases</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="ui-basic">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <a class="nav-link <?php echo (isset($_GET['caseadd']) ? 'active' : ''); ?>"
                                    href="./index.php?caseadd=1">Add New Case</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo (isset($_GET['allcase']) ? 'active' : ''); ?>"
                                    href="./index.php?allcase=1">All Cases</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo (isset($_GET['runningcase']) ? 'active' : ''); ?>"
                                    href="./Case/RunningCase.php">Running Cases</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo (isset($_GET['todaycase']) ? 'active' : ''); ?>"
                                    href="./Case/TodayCases.php">Today's Cases</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo (isset($_GET['tomorocase']) ? 'active' : ''); ?>"
                                    href="./Case/TomorrowCases.php">Tomorrow's Cases</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo (isset($_GET['updatecase']) ? 'active' : ''); ?>"
                                    href="./Case/NotUpdateCases.php">Not Updated Cases</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo (isset($_GET['decidedcase']) ? 'active' : ''); ?>"
                                    href="./Case/DecidedCases.php">Decided Cases</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo (isset($_GET['abandonedcase']) ? 'active' : ''); ?>"
                                    href="./Case/AbandonedCases.php">Abandoned Cases</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                        <span class="menu-icon">
                            <i class="mdi mdi-security"></i>
                        </span>
                        <span class="menu-title">Master Setup</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="auth">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item">
                                <a class="nav-link <?php echo (isset($_GET['courtsetup']) ? 'active' : ''); ?>"
                                    href="./index.php?courtsetup=1">Court Setup</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo (isset($_GET['casetype']) ? 'active' : ''); ?>"
                                    href="./index.php?casetype=1">Case Type Setup</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo (isset($_GET['police']) ? 'active' : ''); ?>"
                                    href="./index.php?police=1">Police Station Setup</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo (isset($_GET['register']) ? 'active' : ''); ?>"
                                    href="./index.php?register=1">Register</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo (isset($_GET['register']) ? 'active' : ''); ?>"
                                    href="./register/view.php">View User</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link" href="pages/forms/basic_elements.html">
                        <span class="menu-icon">
                            <i class="mdi mdi-playlist-play"></i>
                        </span>
                        <span class="menu-title">Upgrade Plane</span>
                    </a>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link" href="pages/charts/chartjs.html">
                        <span class="menu-icon">
                            <i class="mdi mdi-chart-bar"></i>
                        </span>
                        <span class="menu-title">SMS Sender</span>
                    </a>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link" href="./register/register.php">
                        <span class="menu-icon">
                            <i class="mdi mdi-contacts"></i>
                        </span>
                        <span class="menu-title">Register User</span>
                    </a>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link" href="">
                        <span class="menu-icon">
                            <i class="mdi mdi-file-document-box"></i>
                        </span>
                        <span class="menu-title">Settings</span>
                    </a>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link" href="">
                        <span class="menu-icon">
                            <i class="mdi mdi-file-document-box"></i>
                        </span>
                        <span class="menu-title">Contact</span>
                    </a>
                </li>
                <li class="nav-item menu-items">
                    <a class="nav-link" href="">
                        <span class="menu-icon">
                            <i class="mdi mdi-file-document-box"></i>
                        </span>
                        <span class="menu-title">About</span>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_navbar.html -->
            <nav class="navbar p-0 fixed-top d-flex flex-row">
                <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
                    <a class="navbar-brand brand-logo-mini" href="index.html"><img src="assets/images/logo-mini.svg"
                            alt="logo" /></a>
                </div>
                <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
                    <button class="navbar-toggler navbar-toggler align-self-center" type="button"
                        data-toggle="minimize">
                        <span class="mdi mdi-menu"></span>
                    </button>
                    <ul class="navbar-nav w-100">
                        <li class="nav-item w-100">
                            <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search">
                                <input type="text" class="form-control" placeholder="Search products">
                            </form>
                        </li>
                    </ul>
                    <ul class="navbar-nav navbar-nav-right">
                        <li class="nav-item dropdown d-none d-lg-block">
                            <a class="nav-link btn btn-success create-new-button" id="createbuttonDropdown"
                                data-toggle="dropdown" aria-expanded="false" href="./Case/addNewcase.php">+ Create New
                                case</a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                                aria-labelledby="createbuttonDropdown">
                                <a class="dropdown-item preview-item" href="./index.php?caseadd=1">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-dark rounded-circle">
                                            <i class="mdi mdi-file-outline text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <p class="preview-subject ellipsis mb-1">Create New Cases</p>

                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item" href="./Case/allCases.php">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-dark rounded-circle">
                                            <i class="mdi mdi-web text-info"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <p class="preview-subject ellipsis mb-1">See ALL Cases</p>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                            </div>
                        </li>
                        <li class="nav-item nav-settings d-none d-lg-block">
                            <a class="nav-link" href="#">
                                <i class="mdi mdi-view-grid"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
                                <div class="navbar-profile">
                                    <img class="img-xs rounded-circle" src="./register/<?php echo $user['Image']; ?>"
                                        alt="<?php echo $user['Image']; ?>">
                                    <p class="mb-0 d-none d-sm-block navbar-profile-name"><?php echo $user['Name']; ?>
                                    </p>
                                    <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list"
                                aria-labelledby="profileDropdown">
                                <h6 class="p-3 mb-0">Profile</h6>
                                <div class="dropdown-divider"></div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item preview-item" href="./logout.php">
                                    <div class="preview-thumbnail">
                                        <div class="preview-icon bg-dark rounded-circle">
                                            <i class="mdi mdi-logout text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="preview-item-content">
                                        <p class="preview-subject mb-1">Log out</p>
                                    </div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <p class="p-3 mb-0 text-center">Advanced settings</p>
                            </div>
                        </li>
                    </ul>
                    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button"
                        data-toggle="offcanvas">
                        <span class="mdi mdi-format-line-spacing"></span>
                    </button>
                </div>
            </nav>
            <!-- partial -->


            <div class="main-panel">
                <div class="content-wrapper">
                    <?php
          // Dynamic Content Loader
          if (filter_input(INPUT_GET, 'caseadd', FILTER_VALIDATE_INT)) {
            $filePath = realpath('./Case/addNewcase.php');
            if ($filePath) {
              include_once($filePath);
            } else {
              echo "Error: File not found for Add New Case.";
            }
          } elseif (filter_input(INPUT_GET, 'allcase', FILTER_VALIDATE_INT)) {
            $filePath = realpath('./Case/allCases.php');
            if ($filePath) {
              include_once($filePath);
            } else {
              echo "Error: File not found for All Cases.";
            }
          } elseif (filter_input(INPUT_GET, 'dailynotes', FILTER_VALIDATE_INT)) {
            $filePath = realpath('./note/note.php');
            if ($filePath) {
              include_once($filePath);
            } else {
              echo "Error: File not found for Update.";
            }
          } 
          elseif (filter_input(INPUT_GET, 'courtsetup', FILTER_VALIDATE_INT)) {
            $filePath = realpath('./MasterSetup/codesetup.php');
            if ($filePath) {
              include_once($filePath);
            } else {
              echo "Error: File not found for Update.";
            }
          } 
          elseif (filter_input(INPUT_GET, 'casetype', FILTER_VALIDATE_INT)) {
            $filePath = realpath('./MasterSetup/case_type.php');
            if ($filePath) {
              include_once($filePath);
            } else {
              echo "Error: File not found for Update.";
            }
          } 
          elseif (filter_input(INPUT_GET, 'police', FILTER_VALIDATE_INT)) {
            $filePath = realpath('./MasterSetup/police.php');
            if ($filePath) {
              include_once($filePath);
            } else {
              echo "Error: File not found for Update.";
            }
          } 
          elseif (filter_input(INPUT_GET, 'register', FILTER_VALIDATE_INT)) {
            $filePath = realpath('./register/register.php');
            if ($filePath) {
              include_once($filePath);
            } else {
              echo "Error: File not found for Update.";
            }
          }
          elseif (filter_input(INPUT_GET, 'user', FILTER_VALIDATE_INT)) {
            $filePath = realpath('./register/view.php');
            if ($filePath) {
              include_once($filePath);
            } else {
              echo "Error: File not found for Update.";
            }
          } else {
          ?>
                    <div class="row">
                        <?php
// Queries to get counts for different cases
$allCasesQuery = "SELECT COUNT(*) AS total FROM cases";
$runningCasesQuery = "SELECT COUNT(*) AS total FROM cases WHERE status = 'running'";
$todaysCasesQuery = "SELECT COUNT(*) AS total FROM cases WHERE DATE(hearing_date) = CURDATE()";
$tomorrowsCasesQuery = "SELECT COUNT(*) AS total FROM cases WHERE DATE(hearing_date) = CURDATE() + INTERVAL 1 DAY";
$notUpdatedCasesQuery = "SELECT COUNT(*) AS total FROM cases WHERE last_updated IS NULL OR last_updated = ''";
$todaysNotesQuery = "SELECT COUNT(*) AS total FROM notes WHERE DATE(note_date) = CURDATE()";
$decidedCasesQuery = "SELECT COUNT(*) AS total FROM cases WHERE status = 'decided'";
$abandonedCasesQuery = "SELECT COUNT(*) AS total FROM cases WHERE status = 'abandoned'";

// Execute queries
$allCases = $conn->query($allCasesQuery)->fetch_assoc()['total'];
$runningCases = $conn->query($runningCasesQuery)->fetch_assoc()['total'];
$todaysCases = $conn->query($todaysCasesQuery)->fetch_assoc()['total'];
$tomorrowsCases = $conn->query($tomorrowsCasesQuery)->fetch_assoc()['total'];
$notUpdatedCases = $conn->query($notUpdatedCasesQuery)->fetch_assoc()['total'];
$todaysNotes = $conn->query($todaysNotesQuery)->fetch_assoc()['total'];
$decidedCases = $conn->query($decidedCasesQuery)->fetch_assoc()['total'];
$abandonedCases = $conn->query($abandonedCasesQuery)->fetch_assoc()['total'];
?>

                        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="d-flex align-items-center align-self-start">
                                                <h3 class="mb-0 text-white"><?php echo $allCases; ?></h3>
                                                <p class="text-success ml-2 mb-0 font-weight-medium">Cases</p>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <a href="./Case/allCases.php">
                                                <div class="icon icon-box-success ">
                                                    <span class="mdi mdi-arrow-top-right icon-item"></span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <h6 class="text-muted font-weight-normal">All Cases</h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="d-flex align-items-center align-self-start">
                                                <h3 class="mb-0 text-white"><?php echo $runningCases; ?></h3>
                                                <p class="text-success ml-2 mb-0 font-weight-medium">Cases</p>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <a href="./Case/RunningCase.php">
                                                <div class="icon icon-box-success">
                                                    <span class="mdi mdi-arrow-top-right icon-item"></span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <h6 class="text-muted font-weight-normal">Running Cases</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="d-flex align-items-center align-self-start">
                                                <h3 class="mb-0 text-white"><?php echo $todaysCases; ?></h3>
                                                <p class="text-success ml-2 mb-0 font-weight-medium">Cases</p>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <a href="./Case/TodayCases.php">
                                                <div class="icon icon-box-success">
                                                    <span class="mdi mdi-arrow-top-right icon-item"></span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <h6 class="text-muted font-weight-normal">Today's Cases</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="d-flex align-items-center align-self-start">
                                                <h3 class="mb-0 text-white"><?php echo $tomorrowsCases; ?></h3>
                                                <p class="text-success ml-2 mb-0 font-weight-medium">Cases</p>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <a href="./Case/TomorrowCases.php">
                                                <div class="icon icon-box-success">
                                                    <span class="mdi mdi-arrow-top-right icon-item"></span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <h6 class="text-muted font-weight-normal">Tomorrow's Cases</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="d-flex align-items-center align-self-start">
                                                <h3 class="mb-0 text-white"><?php echo $notUpdatedCases; ?></h3>
                                                <p class="text-success ml-2 mb-0 font-weight-medium">Cases</p>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <a href="./Case/NotUpdateCases.php">
                                                <div class="icon icon-box-success">
                                                    <span class="mdi mdi-arrow-top-right icon-item"></span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <h6 class="text-muted font-weight-normal">Not Updated Cases</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="d-flex align-items-center align-self-start">
                                                <h3 class="mb-0 text-white"><?php echo $todaysNotes; ?></h3>
                                                <p class="text-success ml-2 mb-0 font-weight-medium">Notes</p>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <a href="./index.php?dailynotes=1">
                                                <div class="icon icon-box-success">
                                                    <span class="mdi mdi-arrow-top-right icon-item"></span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <h6 class="text-muted font-weight-normal">Today's Notes</h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="d-flex align-items-center align-self-start">
                                                <h3 class="mb-0 text-white"><?php echo $decidedCases; ?></h3>
                                                <p class="text-danger ml-2 mb-0 font-weight-medium">Cases</p>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <a href="./Case/DecidedCases.php">
                                                <div class="icon icon-box-danger">
                                                    <span class="mdi mdi-arrow-bottom-left icon-item"></span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <h6 class="text-muted font-weight-normal">Decided Cases</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="d-flex align-items-center align-self-start">
                                                <h3 class="mb-0 text-white"><?php echo $abandonedCases; ?></h3>
                                                <p class="text-success ml-2 mb-0 font-weight-medium">Cases</p>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <a href="./Case/AbandonedCases.php">
                                                <div class="icon icon-box-success ">
                                                    <span class="mdi mdi-arrow-top-right icon-item"></span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <h6 class="text-muted font-weight-normal">Abandoned Cases</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    // Fetch case statistics
$sql = "SELECT 
            SUM(status = 'Running') AS running_cases,
            SUM(status = 'Decided') AS decided_cases,
            SUM(status = 'Abandoned') AS abandoned_cases,
            COUNT(*) AS total_cases
        FROM cases";

$result = $conn->query($sql);
$stats = $result->fetch_assoc();

// Assign values
$running = $stats['running_cases'] ?? 0;
$decided = $stats['decided_cases'] ?? 0;
$abandoned = $stats['abandoned_cases'] ?? 0;
$total = $stats['total_cases'] ?? 0;
date_default_timezone_set('Asia/Dhaka');

?>
                    <div class="row">
                        <div class="col-md-4 grid-margin stretch-card mx-auto">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title text-center">Your Diary Statistics</h4>
                                    <div
                                        class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                                        <div class="text-md-center text-xl-left">
                                            <h6 class="mb-1 text-white">Running Cases</h6>
                                        </div>
                                        <div
                                            class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                                            <h6 class="font-weight-bold mb-0 text-white"><?php echo $running; ?></h6>
                                        </div>
                                    </div>
                                    <div
                                        class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                                        <div class="text-md-center text-xl-left">
                                            <h6 class="mb-1 text-white">Decided Cases</h6>
                                        </div>
                                        <div
                                            class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                                            <h6 class="font-weight-bold mb-0 text-white"><?php echo $decided; ?></h6>
                                        </div>
                                    </div>
                                    <div
                                        class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                                        <div class="text-md-center text-xl-left">
                                            <h6 class="mb-1 text-white">Abandoned Cases</h6>
                                        </div>
                                        <div
                                            class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                                            <h6 class="font-weight-bold mb-0 text-white"><?php echo $abandoned; ?></h6>
                                        </div>
                                    </div>
                                    <div
                                        class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
                                        <div class="text-md-center text-xl-left">
                                            <h6 class="mb-1 text-white">Total Cases</h6>
                                            <p class="text-muted mb-0"><?php echo date('d-m-Y H:i:s'); ?></p>
                                        </div>
                                        <div
                                            class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                                            <h6 class="font-weight-bold mb-0 text-white"><?php echo $total; ?></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <?php
          }
      ?>

                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright
                            ©2025</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"><a
                                href="https://fonclick.com/furkan" target="_blank">Fonclick</a> Fonclick</span>
                    </div>
                </footer>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="assets/vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="assets/vendors/chart.js/Chart.min.js"></script>
    <script src="assets/vendors/progressbar.js/progressbar.min.js"></script>
    <script src="assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
    <script src="assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="assets/vendors/owl-carousel-2/owl.carousel.min.js"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="assets/js/off-canvas.js"></script>
    <script src="assets/js/hoverable-collapse.js"></script>
    <script src="assets/js/misc.js"></script>
    <script src="assets/js/settings.js"></script>
    <script src="assets/js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="assets/js/dashboard.js"></script>
    <!-- End custom js for this page -->
</body>

</html>