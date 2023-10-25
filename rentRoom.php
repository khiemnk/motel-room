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
    $updateRoom = $motelRoomHandler = $updateRoomList = null;
    $motelRoomHandler = new MotelRoomHandler();
    $id = $_GET['id'];
    $updateRoomList = $motelRoomHandler->getMotelRoomById($id);
    $updateRoom = $updateRoomList[0];
    ?>
    <title>Rent Room </title>
</head>
<body class="bg-secondary">

<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center mb-5">DigiTall Renting Platform</h2>
            <div class="row">
                <div class="col-md-6 mx-auto card-holder">
                    <div class="card border-secondary">
                        <div class="card-header">
                            <h3 class="mb-0 my-2">Rent Room</h3>
                        </div>
                        <div class="card-body">
                            <form action="index.php" class="form" role="form" autocomplete="off" id="rent-motel-room"
                                  method="post">
                                <input type="number" id="motelRoomId" hidden
                                       name="motelRoomId" value="<?php echo $updateRoom["id"]; ?>">
                                <div class="form-group">
                                    <label for="nameMotelRoom">Room Name</label>
                                    <input type="text" class="form-control" id="nameMotelRoom"
                                           placeholder="Enter your name" value="<?php echo $updateRoom["name"]; ?>"
                                           readonly>
                                </div>
                                <div class="form-group">
                                    <label for="typeRoom">Type</label>
                                    <input type="text" class="form-control" id="nameMotelRoom"
                                           value="<?php echo $updateRoom["type"]; ?>"
                                           readonly>
                                </div>
                                <div class="form-group">
                                    <label for="addressMotelRoom">Address</label>
                                    <input type="text" class="form-control" id="addressMotelRoom"
                                           placeholder="Enter address of room"
                                           value="<?php echo $updateRoom["address"]; ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="startDate">Rental start date
                                        <span class="red-asterisk"> *</span>
                                    </label>
                                    <input type="date" class="form-control"
                                           name="startDateRent" min="<?php echo Util::dateToday('0'); ?>"
                                           required>
                                </div>
                                <div class="form-group">
                                    <label for="numberMonthRent">Number of months of rental</label>
                                    <input type="number" class="form-control"
                                           placeholder="Enter estimated number of months of rental" id="numberMonthRent"
                                           name="numberMonthRent" required>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary btn-md float-right"
                                           name="rentMotelRoomBtn" value="Rent">
                                </div>
                            </form>
                            <div class="form-group">
                                <a href="index.php">
                                    <input type="submit" class="btn btn-danger" name="cancelMotelRoomBtn"
                                           value="Cancel">
                                </a>
                            </div>
                        </div>
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
