<?php
include('connect.php');

$displayProfileQuery = "SELECT userInfo.username, userInfo.firstName, userInfo.lastName, userInfo.profilePicture, userInfo.bio, cities.cityName, provinces.provinceName FROM userInfo LEFT JOIN addresses ON userInfo.addressID = addresses.addressID LEFT JOIN cities ON addresses.cityID = cities.cityID LEFT JOIN provinces ON addresses.provinceID = provinces.provinceID";
$showRecords = executeQuery($displayProfileQuery);
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Conversly</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="assets/img/favicon.ico" rel="icon" type="image/x-icon">
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar">
    <div class="container-fluid">
      <div class="row w-100"> <!-- Added align-items-center -->
        <div class="col-12 col-md-6 text-start"> <!-- Use col-auto to ensure only as much space as necessary is used -->
          <a class="navbar-brand" href="#"><img src="assets/img/conversly.png" class="logo" alt="Conversly Logo"></a>
        </div>
        <div class="col-12 col-md-6 text-end"> <!-- Use col-auto to keep the icon compact -->
          <a href="#"><i class="bi bi-plus-square-fill"></i></a>
          <a href="#"><i class="bi bi-people-fill"></i></a>
          <a href="#"><i class="bi bi-bell-fill"></i></a>
          <a href="#"><i class="bi bi-chat-heart-fill"></i></a>
          <a href="#"><i class="bi bi-three-dots-vertical"></i></a>
          <a href="#"><i class="bi bi-person-circle"></i></a>
        </div>
      </div>
    </div>
  </nav>

  <div class="header container-fluid d-flex justify-content-center align-items-center my-3">
    <div class="row">
      <!-- <img src="assets/img/cover.gif" class="cover"> -->
      <h1 class="display-1">Let's Find You Some Friends!</h1>
    </div>
  </div>

  <!-- SUGGESTION PANEL -->
  <div class="friend-suggestion container-fluid">
    <div class="row">
    <?php
      if (mysqli_num_rows($showRecords)) {
        while ($userProfile = mysqli_fetch_assoc($showRecords)) {
          ?>
            
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 col-xxl-3 my-2 p-0">
              <div class="card m-auto shadow">
                <div class="profile-picture">
                  <img src="assets/userProfilePictures/<?php echo $userProfile['profilePicture']?>">
                </div>
                <div class="card-content container-fluid">
                  <h6 class="username">@<?php echo $userProfile['username']?></h6>
                  <h6 class="name mt-2"><?php echo $userProfile['firstName'] . ' ' . $userProfile['lastName']?></h6>
                  <h6 class="bio"><?php echo $userProfile['bio']?></h6>
                  <h6 class="address"><?php echo $userProfile['cityName'] . ', ' . $userProfile['provinceName']?></h6>
                  <button class="btn">Add Friend</button>
                </div>
              </div>
            </div>
          <?php
        }
      }
    ?>
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