<?php
include('navbar.php');
include('connect.php');

session_start();
$_SESSION['userID'] = 11;

$listAllFriends = "SELECT * FROM userInfo LEFT JOIN friends ON (friends.requesterID = userInfo.userInfoID OR friends.requesteeID = userInfo.userInfoID) WHERE (status = 'accepted' AND (requesterID = {$_SESSION['userID']} OR requesteeID = {$_SESSION['userID']}))AND NOT userInfo.userInfoID = {$_SESSION['userID']};";
$showFriendsList = executeQuery($listAllFriends);

$listPendingRequests = "SELECT * FROM userInfo LEFT JOIN friends ON friends.requesterID = userInfo.userID WHERE status = 'requested' AND requesteeID = {$_SESSION['userID']};";
$showPendingList = executeQuery($listPendingRequests);

if(isset($_GET['btnAccept'])){
    $requesterID = $_GET['requesterID'];

    $acceptQuery = "UPDATE `friends` SET `status` = 'accepted' WHERE (`requesterID` = $requesterID AND `requesteeID` = {$_SESSION['userID']});";
    executeQuery($acceptQuery);

    header("Location: friends.php?msg=Friend request accepted!");
    exit();
}

if(isset($_POST['btnUnfriend'])){
    $frequesterID = $_POST['frequesterID'];
    $frequesteeID = $_POST['frequesteeID'];

    

    $unfriendQuery = "DELETE FROM friends WHERE (requesterID = $frequesterID AND requesteeID = $frequesteeID) OR (requesterID = $frequesteeID AND requesteeID = $frequesterID)";
    executeQuery($unfriendQuery);

    header("Location: index.php");
    exit();
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Conversly | Friends</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: 'Montserrat', sans-serif;
        }
        .card{
            height: 500px;
        }

        .header{
            background-color: #a73333;
            border-radius: 0.375rem 0.375rem 0 0;
            height: 45px;
            display: flex;
            align-items: center;
        }

        .header h2{
            font-weight: 300;
            font-size: 30px;
            margin-bottom: 0px;
        }

        .profile-picture{
            padding: 0px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 3px solid #a73333;
            overflow-x: hidden;
            
        }

        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;

        }

        .btn-unfriend{
            width: 90px;
            font-size: 14px;
        }

        .btn-primary{
            width: 75px;
            font-size: 14px;
        }

        .bio, .username{
            font-size: 10px;
            color: gray;
        }

        .modal-header{
            background-color: #a73333;
            color: white;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }


        @media (max-width: 1200px) {
            .friend-list .picture, 
            .pending-requests .picture {
                display: flex;
                justify-content: center;
            }

            .header h2 {
                font-size: 24px;
            }
        }

        @media (max-width: 992px) {
            .friend-list .picture, 
            .pending-requests .picture {
                display: flex;
                justify-content: center;
            }
        }

        @media (max-width: 768px) {
            .friend-list .picture, 
            .pending-requests .picture {
                display: flex;
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .friend-list .picture, 
            .pending-requests .picture {
                display: flex;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    
    <div class="container">
            <div class="row my-4">
                <div class="friend-list col-12 col-lg-8 mb-5">
                    <div class="card">
                        <div class="container p-0">
                            <div class="header row w-100 m-0">
                                <h2 class="text-white">FRIENDS LIST</h2>
                            </div>
                            <div class="list row m-0 w-100">
                                <div class="container-fluid">
                                    <?php 
                                    if (mysqli_num_rows($showFriendsList) > 0){
                                        while ($userInfo = mysqli_fetch_assoc($showFriendsList)){
                                    ?>

                                    <div class="row border d-flex align-items-center">
                                        <div class="picture col-3 col-sm-2 col-md-2 col-xl-1 p-0 ">
                                            <div class="profile-picture m-2">
                                                <img src="assets/userProfilePictures/<?php echo $userInfo['profilePicture']?>" alt="<?php echo $userInfo['firstName'] . ' ' . $userInfo['lastName']?>'s Profile Picture" class="p-0">
                                            </div>
                                        </div>

                                        <div class="col-5 col-sm-7 col-md-6 col-xl-8 p-0">
                                            <div class="flex-column">
                                                <h3 class="m-0" style="font-size: 16px;"><?php echo $userInfo['firstName'] . ' ' . $userInfo['lastName']?></h3>
                                                <h6 class="bio m-0"><?php echo $userInfo['bio']?></h6>
                                            </div>
                                        </div>

                                        <div class="col-4 col-sm-3 col-md-4 col-xl-3 p-0 d-flex justify-content-center">

                                            <button type="button" class="btn btn-danger btn-unfriend" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                                Unfriend
                                            </button>

                                            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="staticBackdropLabel">WARNING</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are You Sure You Want To Unfriend <?php echo $userInfo['firstName'] . ' ' . $userInfo['lastName']?> ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <form method="POST">
                                                                <input type="hidden" value="<?php echo $userInfo['requesterID']?>" name="frequesterID">
                                                                <input type="hidden" value="<?php echo $userInfo['requesteeID']?>" name="frequesteeID">
                                                                <button class="btn btn-danger" type="submit" name="btnUnfriend">Unfriend</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <?php 
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pending-requests col-12 col-lg-4 mb-5">
                    <div class="card">
                        <div class="container p-0">
                            <div class="header row w-100 m-0">
                                <h2 class="text-white">PENDING REQUESTS</h2>
                            </div>
                            <div class="list row w-100 m-0">
                                <div class="container-fluid">
                                    <?php 
                                    if (mysqli_num_rows($showPendingList) > 0){
                                        while ($userRequest = mysqli_fetch_assoc($showPendingList)){
                                    ?>

                                    <div class="row border d-flex align-items-center">
                                        <div class="picture col-3 col-sm-2 col-md-2 col-lg-3 col-xl-2 p-0">
                                            <div class="profile-picture m-2">
                                                <img src="assets/userProfilePictures/<?php echo $userRequest['profilePicture']?>" alt="<?php echo $userRequest['firstName'] . ' ' . $userRequest['lastName']?>'s Profile Picture" class="p-0">
                                            </div>
                                        </div>

                                        <div class="col-6 col-sm-6 col-md-6 col-lg-5 col-xl-6 p-0">
                                            <h3 class="name m-0" style="font-size: 14px;"><?php echo $userRequest['firstName'] . ' ' . $userRequest['lastName']?></h3>
                                            <h6 class="username m-0">@<?php echo $userRequest['username']?></h6>
                                        </div>

                                        <div class="col-3 col-sm-4 p-0 d-flex justify-content-center">
                                            <form method="GET">
                                                <input type="hidden" value="<?php echo $userRequest['requesterID']?>" name="requesterID">
                                                <button class="btn btn-primary" name="btnAccept">Accept</button>
                                            </form>
                                        </div>
                                    </div>

                                    <?php 
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
</body>

</html>