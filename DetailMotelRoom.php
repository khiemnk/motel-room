<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>welcome to Travel Agents</title>
    <link rel="shortcut icon" type="image/x-icon" href="assets/images/inner/favicon.png"/>
    <link rel="stylesheet" type="text/css" href="calenplugin/css/daterangepicker.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/animate.css"/>
    <link href="assets/font-awesome/css/font-awesome.min.css" rel="stylesheet">

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

    $motelRoom = $motelRoomHandler = $motelRoomList = $commentList = null;
    $id = $_GET['id'];
    $cusId = $_GET['cusId'];

    $motelRoomHandler = new MotelRoomHandler();
    $motelRoomList = $motelRoomHandler->getMotelRoomById($id);
    $motelRoom = $motelRoomList[0];

    if (isset($_POST["contentOfComment"])) {
        $contentOfComment = $_POST["contentOfComment"];
        $motelRoomHandler->addComment($id, $cusId, $contentOfComment);
    }
    $commentList = $motelRoomHandler->getAllComment($id);
    ?>
    <?php

    if (!isset($_SESSION)) {
        session_start();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_SESSION['postdata'] = $_POST;
        unset($_POST);
        header("Location: ".$_SERVER['PHP_SELF'].'?id='.$id.'&cusId='.$cusId);
        exit;
    }

    // This code can be used anywhere you redirect your user to using the header("Location: ...")
    if (array_key_exists('postdata', $_SESSION)) {
        // Handle your submitted form here using the $_SESSION['postdata'] instead of $_POST

        // After using the postdata, don't forget to unset/clear it
        unset($_SESSION['postdata']);
    }
    ?>
</head>
<body>
<!--start Header-->
<header id="header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a href="index.php" class="navbar-brand font-weight-bold wow fadeInDown">
                        <h2>
                            Motel Room Platform
                        </h2>
                    </a>
                </nav>
            </div>
        </div>
    </div>
    </div>
    <div class="clearfix"></div>
</header>
<!--section-->
<div class="container">
    <section id="box-shadow-hotel">
        <div class="container">
            <div class="row hotelDiv">
                <div class="col-lg-6 col-md-6 col-6 col-12">
                    <h2><?php echo $motelRoom["name"] ?></h2>
                    <span><?php echo $motelRoom["address"] ?></span>
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-xl-3 col-12 fivestar">
                            <div>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                                <i class="fa fa-star" aria-hidden="true"></i>
                            </div>
                            <span>5 Star</span>
                        </div>
                        <div class="col-lg-9 col-md-9 col-xl-9 col-12 tripDiv">
                            <div><img src="assets/images/inner/trip.png" alt="hotelselect"></div>
                            <span>1918 reviews</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-6 col-12 shortlist text-right">
                    <span class="short-bold"><?php echo $motelRoom["price"] ?>đ</span>
                    <span class="short-btn"><i class="fa fa-heart-o" aria-hidden="true"></i> Shortlist</span>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-xl-8 col-12 hotel-menu">
                    <a href="#section3">Amenties & Review</a>
                    <a href="" data-toggle="modal" data-target="#myModal">Photos </a>
                    <a href="" data-toggle="modal" data-target="#myModal1">Map</a>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row hotel-banner">
                <div class="col-12 ">
                    <img src="assets/images/select/hotel1.jpg" alt="hotelselect">
                    <img src="assets/images/select/hotel3.jpg" alt="hotelselect">
                    <a href="" data-toggle="modal" data-target="#myModal2"><img src="assets/images/select/hotel4.jpg"
                                                                                alt="hotelselect"></a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row highlightDiv">
                <div class="col-lg-4 col-md-4 col-4 col-12">
                    <h3>Summary</h3>
                    <ul>
                        <li><?php echo $motelRoom["summary"] ?></li>
                        <!-- <li>Entire floor dedicate to women travellers with unique amenities</li> -->
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4 col-4 col-12 direction">
                </div>
                <div class="col-lg-4 col-md-4 col-4 col-12 direction">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3724.6156909980314!2d105.82098527504809!3d21.008036888490437!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ac818ed419a1%3A0xaa44f530659d9dc4!2zMjUyIFAuIFTDonkgU8ahbiwgVHJ1bmcgTGnhu4d0LCDEkOG7kW5nIMSQYSwgSMOgIE7hu5lpLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1696437176234!5m2!1svi!2s"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
        <div class="container" id="section3">
            <div class="row">
                <div class="col-12 amenitiesDiv">
                    <span>Amenities & Info</span>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-xl-4 col-12 about-hotel">
                    <img src="assets/images/select/dining.jpg" alt="hotelselect">
                    <img src="assets/images/select/bar.jpg" alt="hotelselect">
                    <img src="assets/images/select/banquet.jpeg" alt="hotelselect">
                    <img src="assets/images/select/spa.jpg" alt="hotelselect">
                </div>
                <div class="col-lg-8 col-md-8 col-xl-8 col-12 hotel-info">
                    <h3>About the Room</h3>
                    <p><?php echo $motelRoom["description"] ?></p>
                    <div class="row">
                        <div class="col-12 room-title">
                            <h3>Quick facts</h3>
                        </div>
                    </div>
                    <div class="row quick-fact">
                        <div class="col-lg-2 col-md-2 col-xl-2 col-6">
                            <h2>2 PM</h2>
                            <span>Check-in</span>
                        </div>
                        <div class="col-lg-2 col-md-2 col-xl-2 col-6">
                            <h2>12 PM</h2>
                            <span>Check-out</span>
                        </div>
                        <div class="col-lg-2 col-md-2 col-xl-2 col-6">
                            <h2>225</h2>
                            <span>Rooms</span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xl-6 col-6">
                            <h2>8</h2>
                            <span>Floors</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 room-title">
                            <h3>General</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-xl-4 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>24 Hour Front Desk</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Doctor on Call</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Housekeeping</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Telephone Service</div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xl-4 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>24 Hour Security</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Doorman</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Internet</div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xl-4 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>Air Conditioning</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Elevator</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Laundry</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 room-title">
                            <h3>Food & Beverage</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-xl-4 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>24 Hour Room Service</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Coffee Shop</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Room Service</div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xl-4 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>Banquet Hall</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Lounge</div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xl-4 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>Bar</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Restaurant</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 room-title">
                            <h3>Business Services</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-xl-4 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>Audio Visual Equipment</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Conference Hall</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Meeting Room</div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xl-4 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>Board Room</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Fax</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Photocopy</div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xl-4 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>Business Centre</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>LCD/Projector</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 room-title">
                            <h3>Front Desk Services</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-xl-4 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>Concierge</div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-xl-8 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>Currency Exchange</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 room-title">
                            <h3>Travel</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-xl-4 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>Parking</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Travel Desk</div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xl-4 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>Pick & Drop</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Valet Parking</div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xl-4 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>Porter</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 room-title">
                            <h3>Recreation</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-xl-4 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>Fitness Centre</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Massage Centre</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Swimming Pool</div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xl-4 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>Gift Shop</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Pool/Snooker Table</div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-xl-4 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>Jacuzzi</div>
                            <div><i class="fa fa-check" aria-hidden="true"></i>Spa</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 room-title">
                            <h3>Kids</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>Babysitting</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 room-title">
                            <h3>Smoking Policy</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-xl-4 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>Smoking Floors</div>
                        </div>
                        <div class="col-lg-8 col-md-8 col-xl-8 col-12 facilityDiv1">
                            <div><i class="fa fa-check" aria-hidden="true"></i>Smoking Rooms</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="box-shadow-hotel" style="background-color: #f2f2f2">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-md-12 col-lg-10 col-xl-8">
                    <div class="card">

                        <div class="card-body p-4">
                            <h4 class="text-center mb-4 pb-2">Comments</h4>
                            <?php if (!empty($motelRoomList) && $motelRoomHandler->getExecutionFeedback() == 1) { ?>
                                <?php for ($i = 0; $i < sizeof($commentList); $i++) { $item = $commentList[$i] ?>
                                    <div class="row ">
                                        <div class="col">
                                            <div class="d-flex flex-start">
                                                <img class="rounded-circle shadow-1-strong me-3"
                                                     style="width: 6%; height: 6%"
                                                     src="image/image-cmt.jpg" alt="avatar" width="65"
                                                     height="65"/>
                                                <div>
                                                    <h6 class="fw-bold text-primary mb-1"
                                                        style="margin-left: 10pt"><?php echo $item["fullname"] ?></h6>
                                                    <p class="text-muted small mb-0" style="margin-left: 10pt">
                                                        <?php echo $item["created_at"] ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <p class="mt-3 mb-4 pb-2">
                                                <?php echo $item["content"] ?>
                                            </p>
                                            <div class="small d-flex justify-content-start">
                                                <a href="#!" class="d-flex align-items-center me-3"
                                                   style="margin-left: 10pt">
                                                    <!--                                                <i class="far fa-thumbs-up me-2"></i>-->
                                                    <p class="mb-0">Like</p>
                                                </a>
                                                <a href="#!" class="d-flex align-items-center me-3"
                                                   style="margin-left: 10pt">
                                                    <!--                                                <i class="far fa-comment-dots me-2"></i>-->
                                                    <p class="mb-0">Comment</p>
                                                </a>
                                                <a href="#!" class="d-flex align-items-center me-3"
                                                   style="margin-left: 10pt">
                                                    <!--                                                <i class="fas fa-share me-2"></i>-->
                                                    <p class="mb-0">Share</p>
                                                </a>
                                            </div>
                                        </div>

                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>

                        <div class="card-footer py-3 border-0" style="background-color: #f8f9fa;">
                            <form action="" method="post" href="">
                            <div class="d-flex flex-start w-100">
                                <img class="rounded-circle shadow-1-strong me-3" style="width: 40px; height: 40px"
                                     src="image/image-cmt.jpg" alt="avatar" width="40"
                                     height="40"/>
                                    <div class="form-outline w-100">
                                <textarea class="form-control" id="textAreaExample" rows="4" name="contentOfComment"
                                          style="background: #fff; margin-left: 5pt" placeholder="Message" required></textarea>
                                    </div>
                            </div>
                            <div class="float-end mt-2 pt-1">
                                <button type="submit" class="btn btn-primary btn-sm">Post comment</button>
                                <button type="reset" class="btn btn-outline-primary btn-sm">Cancel</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!--start footer-->
<footer class="page-footer font-small mdb-color pt-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-8 col-12">
                <!--Copyright-->
                <p class="text-md-left">© 2020 Copyright :
                    <a href="http://travjury.com">
                        <strong> Travel Agents</strong>
                    </a>Powered By Travjury
                </p>
            </div>
            <div class="col-lg-3 col-md-4 col-12 foot-social">
                connect
                <a class="social-footer" href="https://www.facebook.com/travjurys" target="_blank">
                    <i class="fa fa-facebook"></i>
                </a>
                <a class="social-footer" href="https://twitter.com/travjuryS" target="_blank">
                    <i class="fa fa-twitter"></i>
                </a>
                <a class="social-footer" href="https://www.linkedin.com/company/travjury-software-pvt-ltd"
                   target="_blank">
                    <i class="fa fa-linkedin"></i>
                </a>
                <a class="social-footer" href="https://in.pinterest.com/travjurysoftware/?autologin=true"
                   target="_blank">
                    <i class="fa fa-pinterest-p" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>
</footer>
<!-- The Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">The Chancery Pavilion - Bengalore(10 Nights)</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="hotel-photo" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ul class="carousel-indicators">
                        <li data-target="#hotel-photo" data-slide-to="0" class="active"></li>
                        <li data-target="#hotel-photo" data-slide-to="1"></li>
                        <li data-target="#hotel-photo" data-slide-to="2"></li>
                    </ul>
                    <!-- The slideshow -->
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="assets/images/select/hotel1.jpg" alt="Los Angeles">
                        </div>
                        <div class="carousel-item">
                            <img src="assets/images/select/hotel1.jpg" alt="Chicago">
                        </div>
                        <div class="carousel-item">
                            <img src="assets/images/select/hotel1.jpg" alt="New York">
                        </div>
                    </div>
                    <!-- Left and right controls -->
                    <a class="carousel-control-prev" href="#demo" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#demo" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="myModal1">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">The Chancery Pavilion - Location</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body modal-loc">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3888.091909949511!2d77.59648391430436!3d12.96597031851123!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3bae15d7b63cf2eb%3A0x8f7b9a58eea12c1b!2sThe%20Chancery%20Pavilion!5e0!3m2!1sen!2sin!4v1580816651760!5m2!1sen!2sin"
                        allowfullscreen=""></iframe>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="myModal2">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">The Chancery Pavilion - Bengalore(10 Nights)</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <div id="hotel-one-photo" class="carousel slide" data-ride="carousel">
                    <!-- The slideshow -->
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="assets/images/select/hotel1.jpg" alt="Los Angeles">
                        </div>
                        <div class="carousel-item">
                            <img src="assets/images/select/hotel1.jpg" alt="Chicago">
                        </div>
                        <div class="carousel-item">
                            <img src="assets/images/select/hotel1.jpg" alt="New York">
                        </div>
                    </div>
                    <!-- Left and right controls -->
                    <a class="carousel-control-prev" href="#demo" data-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </a>
                    <a class="carousel-control-next" href="#demo" data-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </a>
                </div>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- The Modal -->
<div class="modal" id="myModal4">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Get a Cleartrip Account</h4>
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <button type="button" class="registerfb" onclick="window.location.href = 'https://www.facebook.com/';">
                    <i class="fa fa-facebook" aria-hidden="true"></i>Sign up with Facebook
                </button>

                <form>
                    <div class="container">
                        <p>or, Sign up with your current email address</p>
                        <hr>
                        <label><b>Email</b></label>
                        <input type="email" class="textBox" placeholder="Enter Email" name="email" required>

                        <label><b>Password</b></label>
                        <input type="password" class="textBox" placeholder="Enter Password" name="psw" required>

                        <label><b>Repeat Password</b></label>
                        <input type="password" class="textBox" placeholder="Repeat Password" name="psw-repeat" required>
                        <hr>
                        <p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>
                        <button type="submit" class="registerbtn">Create Account</button>
                    </div>

                    <div class="container signin">
                        <p>Already have an account? <a href="#">Sign in</a>.</p>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- The Modal -->
<div class="modal" id="myModal3">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Sign in to Cleartrip</h4>
                <button type="button" class="close" data-dismiss="modal"><i class="fa fa-times" aria-hidden="true"></i>
                </button>
            </div>
            <!-- Modal body -->
            <div class="modal-body">
                <button type="button" class="registerfb"><i class="fa fa-facebook" aria-hidden="true">
                    </i>Sign in with Facebook
                </button>
                <form>
                    <div class="container">
                        <p>or Log in with your Cleartrip account</p>
                        <hr>
                        <label><b>Email</b></label>
                        <input type="email" class="textBox" placeholder="Enter Email" name="email" required>
                        <label><b>Password</b></label>
                        <input type="password" class="textBox" placeholder="Enter Password" name="psw" required>
                        <hr>
                        <p>By creating an account you agree to our <a href="#">Terms & Privacy</a>.</p>
                        <button type="submit" class="registerbtn">Sign in</button>
                    </div>
                    <div class="container signin">
                        <p>Don’t have a Cleartrip Account? <a href="">Sign Up</a>.</p>
                    </div>
                </form>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--End footer-->
<script src="assets/js/jquery-3.4.1.js"></script>
<script src="assets/js/jquery.validate.min.js"></script>
<script src="assets/js/countries.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/validate.js"></script>
<script src="assets/js/custom-dynamic.js"></script>
<script src="assets/js/selectone.js"></script>
<script src="calenplugin/js/moment.min.js"></script>
<script src="calenplugin/js/daterangepicker.min.js"></script>
<script src="calenplugin/js/select.js"></script>
</body>
</html>
