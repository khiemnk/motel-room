<?php
ob_start();
session_start();

if (isset($_SESSION["authenticated"]))
{
    if ($_SESSION["authenticated"] == "1")
    {
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
    <title>Update Room </title>
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
                            <h3 class="mb-0 my-2">Update Room</h3>
                        </div>
                        <div class="card-body">
                            <form action="index.php" class="form" role="form" autocomplete="off" id="update-motel-room" method="post" enctype="multipart/form-data">
                                <input type="number" id="motelRoomId" hidden
                                       name="motelRoomId" value="<?php echo $updateRoom["id"];?>" >
                                <div class="form-group">
                                    <label for="nameMotelRoom">Name</label>
                                    <input type="text" class="form-control" id="nameMotelRoom"
                                           name="nameMotelRoom" placeholder="Enter your name" value="<?php echo $updateRoom["name"];?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="typeRoom">Type</label>
                                    <select id="typeMotelRoom" name="typeMotelRoom" class="form-control">
                                        <option value="Nhà trọ">Nhà Trọ</option>
                                        <option value="Chung cư">Chung cư</option>
                                        <option value="Ở ghép">Ở ghép</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="addressMotelRoom">Address</label>
                                    <input type="text" class="form-control" id="addressMotelRoom"
                                           name="addressMotelRoom" placeholder="Enter address of room" value="<?php echo $updateRoom["address"];?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="summaryMotelRoom">Summary</label>
                                    <input type="text" class="form-control" placeholder="Summary" id="summaryMotelRoom"
                                           name="summaryMotelRoom" value="<?php echo $updateRoom["summary"];?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="descriptionMotelRoom">Description</label>
                                    <textarea type="text" class="form-control" placeholder="Description" id="descriptionMotelRoom"
                                              name="descriptionMotelRoom" required> <?php echo $updateRoom["description"];?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="priceMotelRoom">Price</label>
                                    <input type="number"  step="0.1" class="form-control" placeholder="Enter price of room" id="priceMotelRoom"
                                           name="priceMotelRoom" value="<?php echo $updateRoom["price"];?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="contactMotelRoom">Contact</label>
                                    <input type="number" class="form-control" id="contactMotelRoom"
                                           name="contactMotelRoom" placeholder="Enter contact for room" value="<?php echo $updateRoom["contact"];?>" required>
                                </div>
                                <label for="contactMotelRoom">Select image to upload:</label>
                                <div>
                                    <input type="file" name="fileToUpload" id="fileToUpload">
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary btn-md float-right"
                                           name="createMotelRoomBtn" value="Update">
                                </div>
                            </form>
                            <div class="form-group">
                                <a  href="index.php">
                                    <input type="submit" class="btn btn-danger" name="cancelMotelRoomBtn" value="Cancel">
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
