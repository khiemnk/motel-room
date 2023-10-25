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
    $profile = $customerHandler = $updateRoomList = null;
    $customerHandler = new CustomerHandler();
    $id=null;
    if (isset($_GET['id'])){
        $id = $_GET['id'];
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $_SESSION['postdata'] = $_POST;
        unset($_POST);
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=".$id);
        exit;
    }

    $profile = $customerHandler->getCustomerObjByCid($id);
    if (array_key_exists('postdata', $_SESSION)){
        $profile->setId($_SESSION['postdata']["customerId"]);
        $profile->setFullName($_SESSION['postdata']["customerName"]);
        $profile->setEmail($_SESSION['postdata']["emailCustomer"]);
        $profile->setAddress($_SESSION['postdata']["addressCustomer"]);
        $profile->setPhone($_SESSION['postdata']["phoneCustomer"]);
        $profile->setAvatar($_SESSION['postdata']["image_path"]);
        $customerHandler->updateProfile($profile);
        unset($_SESSION['postdata']);
    }
    ?>
    <title> My profile </title>
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
                            <h3 class="mb-0 my-2">My profile</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <img class="rounded-circle shadow-1-strong me-3" style="width: 100px; height: 100px"
                                     src="<?php echo $profile->getAvatar()?>" alt="avatar" width="100"
                                     height="100"/>
                            </div>
                            <form href="MyProfile.php?id=<?php echo $profile->getId()?>" class="form" role="form" autocomplete="off" id="my-profile" method="post" enctype="multipart/form-data">
                                <input type="number" id="customerId" hidden
                                       name="customerId" value="<?php echo $profile->getId()?>" >
                                <div class="form-group">
                                    <label for="customerName">Name</label>
                                    <input type="text" class="form-control" id="customerName"
                                           name="customerName" placeholder="Enter your name" value="<?php echo $profile->getFullName()?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="addressCustomer">Address</label>
                                    <input type="text" class="form-control" id="addressCustomer"
                                           name="addressCustomer" placeholder="Enter address" value="<?php echo $profile->getAddress()?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="emailCustomer">Email</label>
                                    <input type="text" class="form-control" placeholder="Summary" id="emailCustomer"
                                           name="emailCustomer" value="<?php echo $profile->getEmail();?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="phoneCustomer">Phone</label>
                                    <input type="number" class="form-control" id="phoneCustomer"
                                           name="phoneCustomer" placeholder="Enter phone number" value="<?php echo $profile->getPhone();?>" required>
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

