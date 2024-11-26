<?php
include('navbar.php');


$listAllFriends = "SELECT * FROM userInfo LEFT JOIN friends ON (friends.requesterID = userInfo.userInfoID OR friends.requesteeID = userInfo.userInfoID) WHERE (status = 'accepted' AND (requesterID = {$_SESSION['requesterID']} OR requesteeID = {$_SESSION['requesterID']}))AND NOT userInfo.userInfoID = {$_SESSION['requesterID']} GROUP BY userInfoID;";
$showFriendsList = executeQuery($listAllFriends);

$listPendingRequests = "SELECT * FROM userInfo LEFT JOIN friends ON friends.requesterID = userInfo.userID WHERE status = 'requested' AND requesteeID = {$_SESSION['requesterID']};";
$showPendingList = executeQuery($listPendingRequests);

if (isset($_GET['btnAccept'])) {
    $requesterID = $_GET['requesterID'];

    $acceptQuery = "UPDATE `friends` SET `status` = 'accepted' WHERE (`requesterID` = $requesterID AND `requesteeID` = {$_SESSION['requesterID']}) OR (`requesterID` = {$_SESSION['requesterID']}) AND `requesteeID` = $requesterID;";
    executeQuery($acceptQuery);

    header("Location: friends.php?msg=Friend request accepted!");
    exit();
}

if (isset($_POST['btnUnfriend'])) {
    $frequesterID = $_POST['frequesterID'];
    $frequesteeID = $_POST['frequesteeID'];



    $unfriendQuery = "DELETE FROM friends WHERE (requesterID = $frequesterID AND requesteeID = $frequesteeID) OR (requesterID = $frequesteeID AND requesteeID = $frequesterID)";
    executeQuery($unfriendQuery);

    header("Location: index.php");
    exit();
}

if (isset($_POST['btnMute'])) {
    $isMuted = $_POST['isMuted'];
    $frequesterID = $_POST['frequesterID'];
    $frequesteeID = $_POST['frequesteeID'];

    $muteQuery = "UPDATE `friends` SET `isMuted` = '$isMuted' WHERE requesterID = $frequesterID AND requesteeID = $frequesteeID";
    executeQuery($muteQuery);

    header("Location: friends.php");
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="assets/css/friends.css" rel="stylesheet">
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
                                if (mysqli_num_rows($showFriendsList) > 0) {
                                    while ($userInfo = mysqli_fetch_assoc($showFriendsList)) {
                                        ?>

                                        <div class="row border d-flex align-items-center">
                                            <div class="picture col-3 col-sm-2 col-md-2 col-xl-1 p-0 ">
                                                <div class="profile-picture m-2">
                                                    <img src="assets/userProfilePictures/<?php echo $userInfo['profilePicture'] ?>"
                                                        alt="<?php echo $userInfo['firstName'] . ' ' . $userInfo['lastName'] ?>'s Profile Picture"
                                                        class="p-0">
                                                </div>
                                            </div>

                                            <div class="col-5 col-sm-7 col-md-6 col-xl-8 p-0">
                                                <div class="flex-column">
                                                    <div class="d-flex align-items-center">
                                                        <h3 class="full-name m-0 me-2">
                                                            <?php echo $userInfo['firstName'] . ' ' . $userInfo['lastName']; ?>
                                                        </h3>
                                                        <?php if($userInfo['isMuted'] == 'Yes'){ ?>
                                                        <i class="bi bi-bell-slash fs-6 text-black"></i>
                                                        <?php }?>
                                                    </div>
                                                    <h6 class="bio m-0"><?php echo $userInfo['bio']; ?></h6>
                                                </div>
                                            </div>



                                            <div class="col-4 col-sm-3 col-md-4 col-xl-3 p-0 d-flex justify-content-center">

                                                <button type="button" class="btn btn-danger btn-unfriend" data-bs-toggle="modal"
                                                    data-bs-target="#staticBackdrop<?php echo $userInfo['userInfoID'] ?>">
                                                    Unfriend
                                                </button>

                                                <div class="modal fade" id="staticBackdrop<?php echo $userInfo['userInfoID'] ?>"
                                                    data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">WARNING
                                                                </h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are You Sure You Want To Unfriend
                                                                <?php echo $userInfo['firstName'] . ' ' . $userInfo['lastName'] ?>
                                                                ?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <form method="POST">
                                                                    <input type="hidden"
                                                                        value="<?php echo $userInfo['requesterID'] ?>"
                                                                        name="frequesterID">
                                                                    <input type="hidden"
                                                                        value="<?php echo $userInfo['requesteeID'] ?>"
                                                                        name="frequesteeID">
                                                                    <button class="btn btn-danger" type="submit"
                                                                        name="btnUnfriend">Unfriend</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="dropend">
                                                    <i class="bi bi-three-dots-vertical text-black fs-5 py-auto"
                                                        data-bs-toggle="dropdown" aria-expanded="false"></i>
                                                    <ul class="dropdown-menu text-black" id="dropdown-menu">
                                                        <li>
                                                            <form method="POST">
                                                                <?php if($userInfo['isMuted'] == 'No'){ ?>
                                                                    <input type="hidden" value="Yes" name="isMuted">
                                                                    <input type="hidden" value="<?php echo $userInfo['requesterID'] ?>" name="frequesterID">
                                                                    <input type="hidden" value="<?php echo $userInfo['requesteeID'] ?>" name="frequesteeID">
                                                                    <button type="submit" name="btnMute" class="dropdown-item">Mute</button>
                                                                <?php }else{?>
                                                                    <input type="hidden" value="No" name="isMuted">
                                                                    <input type="hidden" value="<?php echo $userInfo['requesterID'] ?>" name="frequesterID">
                                                                    <input type="hidden" value="<?php echo $userInfo['requesteeID'] ?>" name="frequesteeID">
                                                                    <button type="submit" name="btnMute" class="dropdown-item">Unmute</button>
                                                                <?php }?>                                                               
                                                            </form>
                                                        </li>
                                                    </ul>
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
                                if (mysqli_num_rows($showPendingList) > 0) {
                                    while ($userRequest = mysqli_fetch_assoc($showPendingList)) {
                                        ?>

                                        <div class="row border d-flex align-items-center">
                                            <div class="picture col-3 col-sm-2 col-md-2 col-lg-3 col-xl-2 p-0">
                                                <div class="profile-picture m-2">
                                                    <img src="assets/userProfilePictures/<?php echo $userRequest['profilePicture'] ?>"
                                                        alt="<?php echo $userRequest['firstName'] . ' ' . $userRequest['lastName'] ?>'s Profile Picture"
                                                        class="p-0">
                                                </div>
                                            </div>

                                            <div class="col-6 col-sm-6 col-md-6 col-lg-5 col-xl-6 p-0">
                                                <h3 class="name m-0" style="font-size: 14px;">
                                                    <?php echo $userRequest['firstName'] . ' ' . $userRequest['lastName'] ?></h3>
                                                <h6 class="username m-0">@<?php echo $userRequest['username'] ?></h6>
                                            </div>

                                            <div class="col-3 col-sm-4 p-0 d-flex justify-content-center">
                                                <form method="GET">
                                                    <input type="hidden" value="<?php echo $userRequest['requesterID'] ?>"
                                                        name="requesterID">
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
</body>

</html>