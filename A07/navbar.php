<?php
include('connect.php');
session_start();

$_SESSION['requesterID'] = 11;
$profilePicQuery = "SELECT profilePicture FROM userinfo WHERE userInfoID = {$_SESSION['requesterID']}";
$data = executeQuery($profilePicQuery);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="assets/img/favicon.ico" rel="icon" type="image/x-icon">
    <link href="assets/css/navbar.css" rel="stylesheet">
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar p-0">
        <div class="container-fluid">
            <div class="row w-100">
                <div class="col-12 col-sm-4 col-md-6 text-start p-0 branding">
                    <a class="navbar-brand" href="index.php"><img src="assets/img/conversly.png" class="logo"
                            alt="Conversly Logo"></a>
                </div>
                <div class="col-12 col-sm-8 col-md-6 text-end menu p-0">
                    <a href="#"><i class="bi bi-plus-square-fill" data-bs-toggle="popover" data-bs-placement="bottom"
                            data-bs-content="Add new content"></i></a>
                    <a href="friends.php"><i class="bi bi-people-fill" data-bs-toggle="popover"
                            data-bs-placement="bottom" data-bs-content="View friends"></i></a>
                    <a href="#"><i class="bi bi-bell-fill" data-bs-toggle="popover" data-bs-placement="bottom"
                            data-bs-content="View notifications"></i></a>
                    <a href="#"><i class="bi bi-chat-heart-fill" data-bs-toggle="popover" data-bs-placement="bottom"
                            data-bs-content="Messages"></i></a>
                    <a href="#"><i class="bi bi-three-dots-vertical" data-bs-toggle="popover" data-bs-placement="bottom"
                            data-bs-content="More options"></i></a>
                    <?php if (mysqli_num_rows($data) > 0) {
                        while ($userInfo = mysqli_fetch_assoc($data)) {
                            ?>
                            <a href="#"><img src="assets/userProfilePictures/<?php echo $userInfo['profilePicture'] ?> "
                                    class="profilePic"></a>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <script>
        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => {
            return new bootstrap.Popover(popoverTriggerEl, {
                trigger: 'hover'
            });
        });
    </script>
</body>

</html>