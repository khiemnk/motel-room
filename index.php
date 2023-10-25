<?php
$imagePath = "image/tro1.jpg";
if (isset($_FILES["fileToUpload"])){
    $target_dir = "image/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imagePath = $target_file;
    $_POST['image_path'] = $imagePath;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

// Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

//// Check file size
//if ($_FILES["fileToUpload"]["size"] > 500000) {
//    echo "Sorry, your file is too large.";
//    $uploadOk = 0;
//}

// Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

// Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

?>
<?php
ob_start();
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/inner/favicon.png"/>
    <link rel="stylesheet" type="text/css" href="calenplugin/css/daterangepicker.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css"/>
    <!--     <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css" />-->
    <link rel="stylesheet" type="text/css" href="assets/css/animate.css"/>
    <link href="assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    <link rel="apple-touch-icon" sizes="180x180" href="image/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="image/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="image/favicon/favicon-16x16.png">
    <link rel="manifest" href="image/favicon/site.webmanifest">
    <link rel="mask-icon" href="image/favicon/safari-pinned-tab.svg" color="#5bbad5">

    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.2.5/css/select.dataTables.min.css">

    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>

    <?php

    require 'lib/phpPasswordHashing/passwordLib.php';
    require 'app/DB.php';
    require 'app/Util.php';
    require 'app/dao/CustomerDAO.php';
    require 'app/dao/BookingDetailDAO.php';
    require 'app/dao/MotelRoomDAO.php';
    require 'app/models/RequirementEnum.php';
    require 'app/models/Customer.php';
    require 'app/models/Booking.php';
    require 'app/models/MotelRoom.php';
    require 'app/models/Reservation.php';
    require 'app/handlers/CustomerHandler.php';
    require 'app/handlers/BookingDetailHandler.php';
    require 'app/handlers/MotelRoomHandler.php';

    $username = $cHandler = $bdHandler = $cBookings = $motelRoomHandler = $motelRoomList = $customerHandler = $customerList = null;
    $isSessionExists = false;
    $isAdmin = [];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_SESSION['postdata'] = $_POST;
        unset($_POST);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_SESSION["username"])) {

        $username = $_SESSION["username"];

        $cHandler = new CustomerHandler();

        $cHandler = $cHandler->getCustomerObj($_SESSION["accountEmail"]);
        $cAdmin = new Customer();
        $cAdmin->setEmail($cHandler->getEmail());

        $bdHandler = new BookingDetailHandler();
        $cBookings = $bdHandler->getCustomerBookings($cHandler);
        $motelRoomHandler = new MotelRoomHandler();

        // Create motel room
        if (isset($_SESSION['postdata']["nameMotelRoom"]) || isset($_SESSION['postdata']["addressMotelRoom"]) || isset($_SESSION['postdata']["summaryMotelRoom"]) ||
            isset($_SESSION['postdata']["descriptionMotelRoom"]) || isset($_SESSION['postdata']["priceMotelRoom"]) || isset($_SESSION['postdata']["contactMotelRoom"])) {
            $id = $_SESSION['postdata']["motelRoomId"];
            $name = $_SESSION['postdata']["nameMotelRoom"];
            $address = $_SESSION['postdata']["addressMotelRoom"];
            $summary = $_SESSION['postdata']["summaryMotelRoom"];
            $description = $_SESSION['postdata']["descriptionMotelRoom"];
            $price = $_SESSION['postdata']["priceMotelRoom"];
            $contact = $_SESSION['postdata']["contactMotelRoom"];
            $type = $_SESSION['postdata']["typeMotelRoom"];
            $motelRoom = new MotelRoom();
            $motelRoom->setId($id);
            $motelRoom->setOwnerId($cHandler->getId());
            $motelRoom->setName($name);
            $motelRoom->setAddress($address);
            $motelRoom->setSummary($summary);
            $motelRoom->setDesciption($description);
            $motelRoom->setContact($contact);
            $motelRoom->setPrice($price);
            $motelRoom->setImage($_SESSION['postdata']['image_path']);
            $motelRoom->setRating("10");
            $motelRoom->setType($type);

            if ($id != null) {
                $motelRoomHandler->updateMotelRoom($motelRoom);
            } else {
                $motelRoomHandler->addMotelRoom($motelRoom);
            }
        }


        $motelRoomList = $motelRoomHandler->getAllMotelRoom();

        $customerHandler = new CustomerHandler();
        $customerList = $customerHandler->getAllCustomer($cHandler->getId());
        if (array_key_exists('postdata', $_SESSION)) {

            if (isset($_SESSION['postdata']['motelRoomId']) && isset($_SESSION['postdata']['startDateRent'])
                && isset($_SESSION['postdata']['numberMonthRent'])) {
                $motelRoomId = $_SESSION['postdata']['motelRoomId'];
                $startDateRent = $_SESSION['postdata']['startDateRent'];
                $numberMonthRent = $_SESSION['postdata']['numberMonthRent'];
                $motelRoomRentList = $motelRoomHandler->getMotelRoomById($motelRoomId);
                $motelRoomHandler->addRentingMotelRoom($cHandler->getId(), $motelRoomRentList[0]["owner_id"], $motelRoomId, $startDateRent, $numberMonthRent);

                $motelRoomRentList = $motelRoomHandler->getAllMotelRoom();
            }

            $priceLess1 = $price1To2 = $price2To3 = $price3To4 = $priceMore4 = $nhaTro = $chungCu = $oGhep = null;
            if (isset($_SESSION['postdata']["priceLess1"])) {
                $priceLess1 = isset($_SESSION['postdata']["priceLess1"]);
            }
            if (isset($_SESSION['postdata']["price1To2"])) {
                $price1To2 = isset($_SESSION['postdata']["price1To2"]);
            }
            if (isset($_SESSION['postdata']["price2To3"])) {
                $price2To3 = isset($_SESSION['postdata']["price2To3"]);
            }
            if (isset($_SESSION['postdata']["price3To4"])) {
                $price3To4 = isset($_SESSION['postdata']["price3To4"]);
            }
            if (isset($_SESSION['postdata']["priceMore4"])) {
                $priceMore4 = isset($_SESSION['postdata']["priceMore4"]);
            }

            if (isset($_SESSION['postdata']["nhaTro"])) {
                $nhaTro = isset($_SESSION['postdata']["nhaTro"]);
            }
            if (isset($_SESSION['postdata']["chungCu"])) {
                $chungCu = isset($_SESSION['postdata']["chungCu"]);
            }
            if (isset($_SESSION['postdata']["oGhep"])) {
                $oGhep = isset($_SESSION['postdata']["oGhep"]);
            }

            // Handle your submitted form here using the $_SESSION['postdata'] instead of $_POST

            if (isset($_SESSION['postdata']["nameValueFilter"]) || isset($_SESSION['postdata']["locationValueFilter"])) {
                $name = $_SESSION['postdata']["nameValueFilter"];
                $location = $_SESSION['postdata']["locationValueFilter"];
                $motelRoomList = $motelRoomHandler->getMotelRoomByName($name, $location);
            }

            if ($priceLess1 == "on" || $price1To2 == "on" || $price2To3 == "on" || $price3To4 == "on"
                || $priceMore4 == "on" || $nhaTro == "on" || $chungCu == "on" || $oGhep == "on")
                foreach ($motelRoomList as $k => $item) {
                    if ($priceLess1 == "on" && $item["price"] < 1) {
                        continue;
                    }
                    if ($price1To2 == "on" && $item["price"] >= 1 && $item["price"] <= 2) {
                        continue;
                    }
                    if ($price2To3 == "on" && $item["price"] >= 2 && $item["price"] <= 3) {
                        continue;
                    }
                    if ($price3To4 == "on" && $item["price"] >= 3 && $item["price"] <= 4) {
                        continue;
                    }
                    if ($priceMore4 == "on" && $item["price"] > 4) {
                        continue;
                    }
                    if ($nhaTro == "on" && $item["type"] == "Nhà trọ") {
                        continue;
                    }
                    if ($chungCu == "on" && $item["type"] == "Chung cư") {
                        continue;
                    }
                    if ($oGhep == "on" && $item["type"] == "Ở ghép") {
                        continue;
                    }
                    unset($motelRoomList[$k]);
                }

            // After using the postdata, don't forget to unset/clear it
            unset($_SESSION['postdata']);
        }
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            if ($id == $cHandler->getId()) {
                foreach ($motelRoomList as $k => $item) {
                    if ($item["owner_id"] != $cHandler->getId()) {
                        unset($motelRoomList[$k]);
                    }
                }
            }
        }

        $rentedRoomList = $customerHandler->getRentingRoom($cHandler->getId());
        $isSessionExists = true;
        $isAdmin = $_SESSION["authenticated"];

    }
    if (isset($_SESSION["isAdmin"]) && isset($_SESSION["username"])) {
        $isSessionExists = true;
        $username = $_SESSION["username"];
        $isAdmin = $_SESSION["isAdmin"];
    }

    // if (isset($_COOKIE['is_admin'])) {
    //     echo $_COOKIE['is_admin'];
    //     var_dump($isAdmin);
    // }

    ?>
    <title>Home</title>
    <?php //echo '<title>Home isAdmin=' . $isAdmin . ' $isSessionExists=' . $isSessionExists . '</title>'?>
</head>
<script>
  document.getElementById('filterMotelRoom').reset();
  document.getElementById('createMotelRoom').reset();
</script>
<body>

<header>
    <div class="bg-dark collapse" id="navbarHeader" style="">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-md-7 py-4">
                    <h4 class="text-white">About</h4>
                    <p class="text-muted">Room information sharing service</p>
                </div>
                <div class="col-sm-4 offset-md-1 py-4 text-right">
                    <?php if ($isSessionExists) { ?>
                        <h4 class="text-white"><?php echo $username; ?></h4>
                        <ul class="list-unstyled">
                            <li><a href="MyProfile.php?id=<?php echo $cHandler->getId() ?>"
                                   class="text-white">My profile<i class="fas fa-sign-out-alt ml-2"></i></a></li>
                            <?php if ($isAdmin[1] == "true" && isset($_COOKIE['is_admin']) && $_COOKIE['is_admin'] == "true") { ?>
                                <li><a href="admin.php" class="text-white">Manage customer reservation(s)<i
                                                class="far fa-address-book ml-2"></i></a></li>
                            <?php } else { ?>
                                <li>
                                    <a href="#" class="text-white" data-toggle="modal" data-target="#myProfileModal">Update profile<i class="fas fa-user ml-2"></i></a>
                                </li>
                            <?php } ?>
                            <li><a href="#" id="sign-out-link" class="text-white">Sign out<i
                                            class="fas fa-sign-out-alt ml-2"></i></a></li>
                            <li><a href="#" data-toggle="modal" data-target=".customer-list"
                                   class="text-white">Customer Renting List<i class="fas fa-sign-out-alt ml-2"></i></a>
                            </li>
                            <li><a href="index.php?id=<?php echo $cHandler->getId() ?>"
                                   class="text-white">My Room List<i class="fas fa-sign-out-alt ml-2"></i></a></li>
                            <li><a href="#" data-toggle="modal" data-target=".rented-room-list" id="sign-out-link"
                                   class="text-white">Rented Room List<i class="fas fa-sign-out-alt ml-2"></i></a></li>
                        </ul>
                    <?php } else { ?>
                        <h4>
                            <a class="text-white" href="sign-in.php">Sign in</a> <span class="text-white">or</span>
                            <a href="register.php" class="text-white">Register </a>
                        </h4>
                        <p class="text-muted">Log in so you can take advantage with our hotel room prices.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="navbar navbar-dark bg-dark box-shadow">
        <div class="container d-flex justify-content-between">
            <a href="index.php" class="navbar-brand d-flex align-items-center">
                <i class="fas fa-h-square mr-2"></i>
                <strong>DigiTall Renting Platform</strong>
            </a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarHeader"
                    aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </div>
</header>
<main role="main">

    <section class="jumbotron text-center">
        <div class="container pt-lg-5 pl-5 px-5">
            <h1 class="display-3">Welcome to our website</h1>
            <p class="lead text-muted">Find your motel room with us now.</p>
        </div>
    </section>
    <form action="index.php" id="filterMotelRoom" method="post">
        <!--section-->
        <section>
            <div class="container">
                <div class="row">
                    <!--left portion start-->
                    <div class="col-md-12 col-lg-3 col-xl-3 col-xs-12 mx-auto mt-3 ">
                        <div class="filter-head text-left">
                            <h6>4 of 4 Room</h6>
                        </div>
                        <div class="filter-area">

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="input-group-text" type="submit"><i class="fa fa-search"
                                                                                      aria-hidden="true"></i></button>
                                </div>
                                <input name="nameValueFilter" type="text" class="form-control"
                                       placeholder="Filter by Name" value="">
                            </div>
                        </div>
                        <div class="filter-area">
                            <h6>Price</h6>
                            <ul>
                                <li>
                                    <input type="checkbox" name="price1To2">1 triệu - 2 triệu
                                </li>
                                <li>
                                    <input type="checkbox" name="price2To3">2 triệu - 3 triệu
                                </li>
                                <li>
                                    <input type="checkbox" name="price3To4">3 triệu - 4 triệu
                                </li>
                                <li>
                                    <input type="checkbox" name="priceMore4">> 4 triệu
                                </li>

                            </ul>
                        </div>
                        <div class="filter-area">
                            <h6>Type</h6>
                            <ul>
                                <li>
                                    <input type="checkbox" name="nhaTro">Nhà trọ
                                </li>
                                <li>
                                    <input type="checkbox" name="chungCu">Chung cư
                                </li>
                                <li>
                                    <input type="checkbox" name="oGhep">Ở ghép
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!--left portion end-->
                    <!--right portion start-->
                    <div class="col-md-12 col-lg-9 col-xl-9 col-sm-12 mx-auto mt-3 hotel-listing">
                        <!-- START: HOTEL LIST VIEW -->
                        <div class="row">
                            <div class="col wow zoomIn">
                                <div class="hotel-list-view">
                                    <div class="row hotel-search-div">
                                        <div class="col-lg-9 col-md-9 col-12">
                                            <form action="index.php" method="post">
                                                <div class="input-group">
                                                    <i class="fa fa-map-marker" aria-hidden="true"></i><span>Search Address:</span>
                                                    <div class="input-group-prepend">
                                                        <button class="input-group-text"><i class="fa fa-search"
                                                                                            aria-hidden="true"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text" name="locationValueFilter" class="form-control"
                                                           placeholder="Search for Location">
                                                </div>
                                            </form>
                                        </div>
                                        <?php if ($isSessionExists) { ?>
                                            <div class="col-lg-3 col-md-3 col-12">
                                                <button type="button" class="btn btn-primary"><a href="createRoom.php"
                                                                                                 style="color: white !important;">Post
                                                        Motel Room</a>
                                                </button>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <?php if (!empty($motelRoomList) && $motelRoomHandler->getExecutionFeedback() == 1) {
                                        $dem = 0; ?>
                                        <?php foreach ($motelRoomList as $k => $item) {
                                            if ($dem == 20) break;
                                            if (!isset($item)) continue;
                                            $dem++;
                                            $item ?>
                                            <div class="row hotel-name">
                                                <div class="col-md-12 col-lg-4 col-xl-4 col-sm-12">
                                                    <div class="banner1">
                                                        <div id="hambet-banner" class="carousel slide"
                                                             data-ride="carousel" data-interval="9000">
                                                            <!-- Indicators -->
                                                            <ul class="carousel-indicators">
                                                                <li data-target="#hambet-banner" data-slide-to="0"
                                                                    class="active"></li>
                                                                <li data-target="#hambet-banner" data-slide-to="1"></li>
                                                                <li data-target="#hambet-banner" data-slide-to="2"></li>
                                                            </ul>
                                                            <!-- The slideshow -->
                                                            <div class="carousel-inner">
                                                                <div class="carousel-item active">
                                                                    <img src=<?php echo $item["image"] ?> alt="Single
                                                                         Room"
                                                                    />
                                                                </div>
                                                                <div class="carousel-item">
                                                                    <img src=<?php echo $item["image"] ?> alt="Single
                                                                         Room"
                                                                    />
                                                                </div>
                                                                <div class="carousel-item">
                                                                    <img src=<?php echo $item["image"] ?> alt="Single
                                                                         Room"
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-lg-4 col-xl-4 col-sm-12  tourDiv">
                                                    <h4><?php echo $item["name"] ?></h4>
                                                    <h6><?php echo $item["price"] ?> triệu/tháng</h6>
                                                    <span><?php echo $item["address"] ?></span><br>
                                                    <?php if (isset($_GET["id"]) && $_GET["id"] == $cHandler->getId()) { ?>
                                                        <span>Room Id: <?php echo $item["id"] ?></span>
                                                    <?php } ?>
                                                </div>
                                                <div class="col-md-12 col-lg-4 col-xl-4 col-sm-12 ratingDiv">
                                                    <h5 style="font-family: Montserrat !important;">
                                                        <?php echo $item["type"] ?>
                                                    </h5>
                                                    <?php if ($item["is_available"] == 0) { ?>
                                                        <h6 class="perday" style="color:#ff5d3a;font-size: 14px"><i
                                                                    class="fa fa-check" aria-hidden="true"></i>Hết phòng
                                                        </h6>
                                                    <?php } else { ?>
                                                        <h6 class="perday" style="font-size: 14px"><i
                                                                    class="fa fa-check" aria-hidden="true"></i>Còn phòng
                                                        </h6>
                                                    <?php } ?>
                                                    <div class="bttn">
                                                        <a href="DetailMotelRoom.php?id=<?php echo $item["id"] ?>&cusId=<?php echo $cHandler->getId() ?>">View
                                                            Details</a>
                                                    </div>
                                                    <?php if ($cHandler->getId() == $item["owner_id"]) { ?>
                                                        <div class="bttn">
                                                            <a href="updateRoom.php?id=<?php echo $item["id"] ?>">Modify</a>
                                                        </div>
                                                    <?php } else if ($item["is_available"] == 1) { ?>
                                                        <div class="bttn" style="background-color: #4ca2be">
                                                            <a href="rentRoom.php?id=<?php echo $item["id"] ?>">Rent</a>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </div>

                        </div>
                        <div class="clearfix"></div>
                        <!-- END: CRUISE LIST VIEW -->
                    </div>
                </div>
            </div>
        </section>
        <!--end Section1-->
    </form>
    <?php if (isset($_COOKIE['is_admin']) && $_COOKIE['is_admin'] == "false") : ?>
        <div class="modal fade book-now-modal-lg" tabindex="-1" role="dialog" aria-labelledby="bookNowModalLarge"
             aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reservation form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body" id="reservationModalBody">
                        <?php if ($isSessionExists == 1 && $isAdmin[1] == "false") { ?>
                            <form role="form" autocomplete="off" method="post" id="multiStepRsvnForm">
                                <div class="rsvnTab">
                                    <?php if ($isSessionExists) { ?>
                                        <input type="number" name="cid" value="<?php echo $cHandler->getId() ?>" hidden>
                                    <?php } ?>
                                    <div class="form-group row">
                                        <label for="startDate" class="col-sm-3 col-form-label">Check-in
                                            <span class="red-asterisk"> *</span>
                                        </label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                                </div>
                                                <input type="date" class="form-control"
                                                       name="startDate" min="<?php echo Util::dateToday('0'); ?>"
                                                       required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-3 col-form-label" for="roomType">Room type
                                            <span class="red-asterisk"> *</span>
                                        </label>
                                        <div class="col-sm-9">
                                            <select required class="custom-select mr-sm-2" name="roomType">
                                                <option value="<?php echo \models\RequirementEnum::DELUXE; ?>">Deluxe
                                                    room
                                                </option>
                                                <option value="<?php echo \models\RequirementEnum::DOUBLE; ?>">Double
                                                    room
                                                </option>
                                                <option value="<?php echo \models\RequirementEnum::SINGLE; ?>">Single
                                                    room
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-3 col-form-label" for="roomRequirement">Room
                                            requirements</label>
                                        <div class="col-sm-9">
                                            <select class="custom-select mr-sm-2" name="roomRequirement">
                                                <option value="no preference" selected>No preference</option>
                                                <option value="non smoking">Non smoking</option>
                                                <option value="smoking">Smoking</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-3 col-form-label" for="adults">Adults
                                            <span class="red-asterisk"> *</span>
                                        </label>
                                        <div class="col-sm-9">
                                            <select required class="custom-select mr-sm-2" name="adults">
                                                <option selected value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-3 col-form-label" for="children">Children</label>
                                        <div class="col-sm-9">
                                            <select class="custom-select mr-sm-2" name="children">
                                                <option selected value="0">-</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-3 col-form-label" for="specialRequests">Special
                                            requirements</label>
                                        <div class="col-sm-9">
                                            <textarea rows="3" maxlength="500" name="specialRequests"
                                                      class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <button type="button" class="btn btn-info" style="margin-left: 0.8em;"
                                                data-container="body" data-toggle="popover"
                                                data-placement="right"
                                                data-content="Check-in time starts at 3 PM. If a late check-in is planned, please contact our support department.">
                                            Check-in policies
                                        </button>
                                    </div>
                                </div>

                                <div class="rsvnTab">
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-3 col-form-label font-weight-bold" for="bookedDate">Booked
                                            Date</label>
                                        <div class="col-sm-9 bookedDateTxt">
                                            July 13, 2019
                                        </div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-3 col-form-label font-weight-bold" for="roomPrice">Room
                                            Price</label>
                                        <div class="col-sm-9 roomPriceTxt">235.75</div>
                                    </div>
                                    <div class="form-group row align-items-center">
                                        <label class="col-sm-3 col-form-label font-weight-bold" for="numNights"><span
                                                    class="numNightsTxt">3</span> nights </label>
                                        <div class="col-sm-9">
                                            $<span class="roomPricePerNightTxt">69.63</span> avg. / night
                                        </div>
                                        <label class="col-sm-3 col-form-label font-weight-bold" for="numNights">From -
                                            to</label>
                                        <div class="col-sm-9 fromToTxt">
                                            Mon. July 4 to Wed. July 6
                                        </div>
                                        <label class="col-sm-3 col-form-label font-weight-bold">Taxes </label>
                                        <div class="col-sm-9">
                                            $<span class="taxesTxt">0</span>
                                        </div>
                                        <label class="col-sm-3 col-form-label font-weight-bold">Total </label>
                                        <div class="col-sm-9">
                                            $<span class="totalTxt">0.00</span>
                                        </div>
                                    </div>
                                </div>

                                <div style="text-align:center;margin-top:40px;">
                                    <span class="step"></span>
                                    <span class="step"></span>
                                </div>

                            </form>
                            <div style="overflow:auto;">
                                <div style="float:right;">
                                    <button type="button" class="btn btn-success" id="rsvnPrevBtn"
                                            onclick="rsvnNextPrev(-1)">Previous
                                    </button>
                                    <button type="button" class="btn btn-success" id="rsvnNextBtn"
                                            onclick="rsvnNextPrev(1)" readySubmit="false">Next
                                    </button>
                                </div>
                            </div>
                        <?php } else { ?>
                            <p>Booking is reserved for customers.</p>
                        <?php } ?>
                    </div>

                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="modal sign-in-to-book-modal" tabindex="-1" role="dialog" aria-labelledby="signInToBookModal"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Sign in required</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4>You have to <a href="sign-in.php">sign in</a> in order to reserve a room.</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade customer-list" tabindex="-1" role="dialog" aria-labelledby="bookNowModalLarge"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Customer Renting List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <table>
                    <tr>
                        <th>STT</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Room Id</th>
                        <th>Rental Start Date</th>
                        <th>Estimate Month Rental</th>
                        <th>Created At</th>
                    </tr>
                    <?php
                    $i = 0;
                    if ($customerList != null)
                        foreach ($customerList as $item) {
                            $i++;
                            echo '
                        <tr>
                          <td>' . $i . '</td>
                          <td>' . $item["fullname"] . '</td>
                          <td>' . $item["phone"] . '</td>
                          <td>' . $item["email"] . '</td>
                          <td>' . $item["motel_room_id"] . '</td>
                          <td>' . $item["rental_start_date"] . '</td>
                          <td>' . $item["total_month_rental"] . '</td>
                          <td>' . $item["created_at"] . '</td>
                        </tr>
                        ';
                        } ?>
                </table>

            </div>
        </div>
    </div>

    <div class="modal fade rented-room-list" tabindex="-1" role="dialog" aria-labelledby="bookNowModalLarge"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Customer Renting List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <table>
                    <tr>
                        <th>STT</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Room Id</th>
                        <th>Rental Start Date</th>
                        <th>Estimate Month Rental</th>
                        <th>Created At</th>
                    </tr>
                    <?php
                    $i = 0;
                    if ($rentedRoomList != null)
                        foreach ($rentedRoomList as $item) {
                            $i++;
                            echo '
                        <tr>
                          <td>' . $i . '</td>
                          <td>' . $item["fullname"] . '</td>
                          <td>' . $item["phone"] . '</td>
                          <td>' . $item["email"] . '</td>
                          <td>' . $item["motel_room_id"] . '</td>
                          <td>' . $item["rental_start_date"] . '</td>
                          <td>' . $item["total_month_rental"] . '</td>
                          <td>' . $item["created_at"] . '</td>
                        </tr>
                        ';
                        } ?>
                </table>

            </div>
        </div>
    </div>

    <?php if (($isSessionExists == 1 && $isAdmin[1] == "false") && isset($_COOKIE['is_admin']) && $_COOKIE['is_admin'] == "false") : ?>
        <div class="modal" id="myProfileModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Profile</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card border-0">
                            <div class="card-body p-0">
                                <?php if ($isSessionExists) { ?>
                                    <form class="form" role="form" autocomplete="off" id="update-profile-form"
                                          method="post">
                                        <input type="number" id="customerId" hidden
                                               name="customerId" value="<?php echo $cHandler->getId(); ?>">
                                        <div class="form-group">
                                            <label for="updateFullName">Full Name</label>
                                            <input type="text" class="form-control" id="updateFullName"
                                                   name="updateFullName"
                                                   value="<?php echo $cHandler->getFullName(); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="updatePhoneNumber">Phone Number</label>
                                            <input type="text" class="form-control" id="updatePhoneNumber"
                                                   name="updatePhoneNumber"
                                                   value="<?php echo $cHandler->getPhone(); ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="updateEmail">Email</label>
                                            <input type="email" class="form-control" id="updateEmail"
                                                   name="updateEmail" value="<?php echo $cHandler->getEmail(); ?>"
                                                   readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="updatePassword">New Password</label>
                                            <input type="password" class="form-control" id="updatePassword"
                                                   name="updatePassword"
                                                   title="At least 4 characters with letters and numbers">
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary btn-md float-right"
                                                   name="updateProfileSubmitBtn" value="Update">
                                        </div>
                                    </form>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!--    Create motel room -->
    <div class="modal" id="createMotelRoom" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Motel Room</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card border-0">
                        <div class="card-body p-0">
                            <form action="index.php" class="form" role="form" autocomplete="off" id="create-motel-room"
                                  method="post" onsubmit="" enctype="multipart/form-data">
                                <input type="number" id="createMotelRoom" hidden
                                       name="motelRoomId">
                                <div class="form-group">
                                    <label for="nameMotelRoom">Name</label>
                                    <input type="text" class="form-control" id="createMotelRoom"
                                           name="nameMotelRoom" placeholder="Enter your name" required>
                                </div>
                                <div class="form-group">
                                    <label for="typeRoom">Type</label>
                                    <select id="createMotelRoom" name="typeMotelRoom" class="form-control">
                                        <option value="Nhà trọ">Nhà Trọ</option>
                                        <option value="Chung cư">Chung cư</option>
                                        <option value="Ở ghép">Ở ghép</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="addressMotelRoom">Address</label>
                                    <input type="text" class="form-control" id="createMotelRoom"
                                           name="addressMotelRoom" placeholder="Enter address of room" required>
                                </div>
                                <div class="form-group">
                                    <label for="summaryMotelRoom">Summary</label>
                                    <input type="text" class="form-control" placeholder="Summary" id="createMotelRoom"
                                           name="summaryMotelRoom" required>
                                </div>
                                <div class="form-group">
                                    <label for="descriptionMotelRoom">Description</label>
                                    <textarea type="text" class="form-control" placeholder="Description"
                                              id="createMotelRoom"
                                              name="descriptionMotelRoom" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="priceMotelRoom">Price</label>
                                    <input type="number" class="form-control" placeholder="Enter price of room"
                                           id="createMotelRoom"
                                           name="priceMotelRoom" required>
                                </div>
                                <div class="form-group">
                                    <label for="contactMotelRoom">Contact</label>
                                    <input type="number" class="form-control" id="createMotelRoom"
                                           name="contactMotelRoom" placeholder="Enter contact for room" required>
                                </div>
                                <label for="contactMotelRoom">Select image to upload:</label>
                                <div>
                                    <input type="file" name="fileToUpload" id="fileToUpload">
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary btn-md float-right"
                                           name="createMotelRoomBtn" value="Create">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<footer class="container">
    <p>&copy; Good luck</p>
</footer>
<script src="js/utilityFunctions.js"></script>
<script src="node_modules/jquery/dist/jquery.min.js"></script>
<script src="node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script defer src="https://use.fontawesome.com/releases/v5.0.10/js/all.js"
        integrity="sha384-slN8GvtUJGnv6ca26v8EzVaR9DC58QEwsIk9q1QXdCU8Yu8ck/tL/5szYlBbqmS+"
        crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.5/js/dataTables.select.min.js"></script>
<script src="js/animatejscx.js"></script>
<script src="js/form-submission.js"></script>
<script>
  $(document).ready(function () {
    let reservationDiv = $('#my-reservations-div');
    reservationDiv.hide();
    $('.my-reservations').click(function () {
      reservationDiv.slideToggle('slow');
    });
    $('#myReservationsTbl').DataTable();

    // dynamically entered room type value on show modal
    $('.book-now-modal-lg').on('show.bs.modal', function (event) {
      let button = $(event.relatedTarget);
      let roomType = button.data('rtype');
      let modal = $(this);
      modal.find('.modal-body select[name=roomType]').val(roomType);
    });

    // check-in policies popover
    $('[data-toggle="popover"]').popover();
  });

  function updateMotelRoom (id) {
      <?php $id?>= id;
  }
</script>
<script src="js/multiStepsRsvn.js"></script>
</body>
</html>
