<?php
include('connect.php');

$getDataQuery = "SELECT * FROM flightlogs";

if(isset($_GET['searchFlightNumber'])){
    $searchFlightNumber = $_GET['searchFlightNumber'];
    $getDataQuery = $getDataQuery . " WHERE flightNumber LIKE '%$searchFlightNumber%' ORDER BY flightNumber;";
}

$flightData = executeQuery($getDataQuery);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PUP AIRPORT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="assets/img/pupairport.png" rel="icon">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="navbar container-fluid p-0">
        <div class="row ps-3">
            <img src="assets/img/brand.png" alt="PUP AIRPORT" class="img-fluid">
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">

        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-2 p-3">
                <div class="filters card">

                </div>
            </div>
            <div class="col-10">
                <h3 class="mt-5 text-center">Looking for a particular flight? Try entering the flight number 🛩️</h3>
                <form method="GET">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-10">
                                <input type="text" class="form-control rounded-pill fs-5"
                                    placeholder="Example format (e.g. 1234)" name="searchFlightNumber" required>
                            </div>
                            <div class="col-1">
                                <button class="btn btn-danger rounded-pill fs-5" type="submit">Search</button>
                            </div>
                            <div class="col-1">
                                <a href="index.php"><button class="btn btn-danger rounded-pill fs-5" type="button">Reset</button></a>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card mt-4">
                    <div class="table-scroll">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" class="fields">Flight Number</th>
                                    <th scope="col" class="fields">Departure Time</th>
                                    <th scope="col" class="fields">Arrival Time</th>
                                    <th scope="col" class="fields">Airline</th>
                                    <th scope="col" class="fields">Passenger Count</th>
                                    <th scope="col" class="fields">Pilot</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($flightData) > 0) {
                                    while ($flightLogs = mysqli_fetch_assoc($flightData)) { ?>
                                        <tr>
                                            <th scope="row"><?php echo $flightLogs['flightNumber'] ?></th>
                                            <td><?php echo $flightLogs['departureDatetime'] ?></td>
                                            <td><?php echo $flightLogs['arrivalDatetime'] ?></td>
                                            <td><?php echo $flightLogs['airlineName'] ?></td>
                                            <td><?php echo $flightLogs['passengerCount'] ?></td>
                                            <td><?php echo $flightLogs['pilotName'] ?></td>
                                        </tr>
                                    <?php
                                    }
                                } else { ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No flight data available.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
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