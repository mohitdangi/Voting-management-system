<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['trmsaid']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $trmsaid = $_SESSION['trmsaid'];
        $tname = $_POST['tname'];
        $email = $_POST['email'];
        $mobnum = $_POST['mobilenumber'];
        $address = $_POST['address'];
        $fathername = $_POST['fathername'];
        $aadhaar = $_POST['aadhaar'];
        $voterID = $_FILES["voterID"]["name"];
        $extension = substr($voterID, strlen($voterID) - 4, strlen($voterID));
        $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif");

        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Voter ID has an invalid format. Only jpg / jpeg / png / gif format allowed');</script>";
        } else {
            $voterID = md5($voterID) . time() . $extension;
            move_uploaded_file($_FILES["voterID"]["tmp_name"], "voterID/" . $voterID);

            $sql = "INSERT INTO tblvoter (Name, Picture, Address, FatherName, AadhaarNumber, VoterID, MobileNumber, WardNumber) VALUES (:tname, :tpics, :address, :fathername, :aadhaar, :voterID, :mobilenumber, :wardnumber)";
            $query = $dbh->prepare($sql);
            $query->bindParam(':tname', $tname, PDO::PARAM_STR);
            $query->bindParam(':tpics', $voterID, PDO::PARAM_STR);
            $query->bindParam(':address', $address, PDO::PARAM_STR);
            $query->bindParam(':fathername', $fathername, PDO::PARAM_STR);
            $query->bindParam(':aadhaar', $aadhaar, PDO::PARAM_INT);
            $query->bindParam(':voterID', $voterID, PDO::PARAM_STR);
            $query->bindParam(':mobilenumber', $mobnum, PDO::PARAM_INT);
            $query->bindParam(':wardnumber', $wardnum, PDO::PARAM_INT);
            $query->execute();

            $LastInsertId = $dbh->lastInsertId();
            if ($LastInsertId > 0) {
                echo '<script>alert("Voter details have been added.")</script>';
                echo "<script>window.location.href ='add-voter.php'</script>";
            } else {
                echo '<script>alert("Something went wrong. Please try again.")</script>';
            }
        }
    }
}
?>

<!doctype html>
<html class="no-js" lang="en">

<head>

    <title>Voting Management System - Add Voters</title>

    <link rel="apple-touch-icon" href="apple-icon.png">

    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendors/themify-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="vendors/selectFX/css/cs-skin-elastic.css">

    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
</head>

<body>
    <!-- Left Panel -->
    <?php include_once('includes/sidebar.php'); ?>

    <div id="right-panel" class="right-panel">
        <!-- Header-->
        <?php include_once('includes/header.php'); ?>

        <div class="breadcrumbs">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Voter Details</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="dashboard.php">Dashboard</a></li>
                            <li><a href="add-voter.php">Voter Details</a></li>
                            <li class="active">Add</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="content mt-3">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header"><strong>Voter</strong> Details</div>
                            <form name="" method="post" action="" enctype="multipart/form-data">
                                <div class="card-body card-block">
                                    <div class="form-group">
                                        <label for="company" class="form-control-label">Voter Name</label>
                                        <input type="text" name="tname" value="" class="form-control" id="tname" required="true">
                                    </div>
                                    <div class="form-group">
                                        <label for="company" class="form-control-label">Father/Husband's Name</label>
                                        <input type="text" name="fathername" value="" class="form-control" id="fathername" required="true">
                                    </div>
                                    <div class="form-group">
                                        <label for="street" class="form-control-label">Aadhaar Number</label>
                                        <input type="text" name="aadhaar" value="" id="aadhaar" class="form-control" required="true">
                                    </div>
                                    <div class="form-group">
                                        <label for="city" class="form-control-label">Mobile Number</label>
                                        <input type="text" name="mobilenumber" id="mobilenumber" value="" class="form-control" required="true" maxlength="10" pattern="[0-9]+">
                                    </div>
                                    <div class="form-group">
                                        <label for="voterID" class="form-control-label">Voter ID</label>
                                        <input type="file" name="voterID" id="voterID" class="form-control-file" required="true">
                                    </div>
                                    <div class="form-group">
                                        <label for="city" class="form-control-label">Ward Number</label>
                                        <input type="text" name="wardnumber" id="wardnumber" value="" class="form-control" required="true">
                                    </div>
                                    <div class="form-group">
                                        <label for="city" class="form-control-label">Address</label>
                                        <textarea type="text" name="address" id="address" value="" class="form-control" rows="4" cols="12" required="true"></textarea>
                                    </div>
                                    
   <p style="text-align: center;">
                                        <button type="submit" class="btn btn-primary btn-sm" name="submit" id="submit">
                                            <i class="fa fa-dot-circle-o"></i> Add
                                        </button>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- .animated -->
        </div><!-- .content -->
    </div><!-- /#right-panel -->
  
    <!-- /#right-panel -->

    <script src="vendors/jquery/dist/jquery.min.js"></script>
                            <script src="vendors/popper.js/dist/umd/popper.min.js"></script>

                            <script src="vendors/jquery-validation/dist/jquery.validate.min.js"></script>
                            <script src="vendors/jquery-validation-unobtrusive/dist/jquery.validate.unobtrusive.min.js"></script>

                            <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
                            <script src="assets/js/main.js"></script>
</body>
</html>
<?php  ?> 