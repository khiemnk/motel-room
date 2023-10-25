<?php
ob_start();
session_start();

if (isset($_SESSION["authenticated"])) {
    if ($_SESSION["authenticated"] == "1") {
        header("Location: index.php");
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/main.css">

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
    // get update motel room

    $motelRoomHandler = new MotelRoomHandler();
    $customerHandler = $customerList = null;
    $customerHandler = new CustomerHandler();
    $cHandler = new CustomerHandler();

    $cHandler = $cHandler->getCustomerObj($_SESSION["accountEmail"]);
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_SESSION['postdata'] = $_POST;
        unset($_POST);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
    if (array_key_exists('postdata', $_SESSION)) {
        $idRenting = $_SESSION['postdata']['idRenting'];
        if($_SESSION['postdata']['submit'] == "Approve"){
            $motelRoomHandler->approveRenting($idRenting);
        }
        else {
            $motelRoomHandler->cancelRenting($idRenting);
        }

        unset($_SESSION['postdata']);
    }
    $customerList = $customerHandler->getAllCustomer($cHandler->getId());
    ?>
    <title>Customer Renting List </title>
</head>
<body class="bg-secondary">

<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center mb-5"><a href="index.php" style="color: white">DigiTall Renting Platform</a></h2>
            <div class="row">
                <div class="">
                    <div class="card border-secondary">
                        <div class="card-header">
                            <h3 class="mb-0 my-2">Customer Renting List</h3>
                        </div>
                        <table class="table table-striped">
                            <tr>
                                <th>STT</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Room Id</th>
                                <th>Rental Start Date</th>
                                <th>Estimate Month Rental</th>
                                <th>Created At</th>
                                <th>Status</th>
                                <th></th>
                                <th></th>
                            </tr>
                            <?php
                            $i = 0;
                            if ($customerList != null)
                                foreach ($customerList as $item) {
                                    $i++;
                                    echo '
                        <tr>
                        <form action="ApproveRoom.php" id="approveRenting" method="post">
                          <td>' . $i . '</td>
                          <td>' . $item["fullname"] . '</td>
                          <td>' . $item["phone"] . '</td>
                          <td>' . $item["email"] . '</td>
                          <td><input hidden name="idRenting" value="' . $item["id"] . '">' . $item["motel_room_id"] . '</td>
                          <td>' . $item["rental_start_date"] . '</td>
                          <td>' . $item["total_month_rental"] . '</td>
                          <td>' . $item["created_at"] . '</td>
                          <td>' . $item["status"] . '</td>';
                                    if ($item["status"] == "Pending") {
                                        echo '
                          <td><input name="submit" type="submit" class="btn-success" value="Approve"></td>
                          <td><input name="submit" type="submit" class="btn-danger" value="Cancel"></td>';
                                    } else if ($item["status"] == "Success") {
                                        echo '
                          <td></td>
                          <td><input name="submit" type="submit" class="btn-danger" value="Cancel"></td>';
                                    }
                                    else echo '<td></td> <td></td>';
                                    echo '
                          </form>
                        </tr>
                        ';
                                } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/utilityFunctions.js"></script>
<script src="node_modules/jquery/dist/jquery.min.js"></script>
<script src="node_modules/popper.js/dist/popper.min.js"></script>
<script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="js/form-submission.js"></script>
</body>
</html>
