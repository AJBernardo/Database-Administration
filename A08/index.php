<?php
include('connect.php');

$getDataQuery = "SELECT * FROM flightlogs";
$airlineQuery = "SELECT DISTINCT airlineName FROM flightlogs";

$minPassenger = "";
$maxPassenger = "";
$airlineName = "";
$aircraftName = "";
$sort = "";
$order = "";

if (isset($_GET['searchFlightNumber'])) {
    $searchFlightNumber = $_GET['searchFlightNumber'];
    $getDataQuery = $getDataQuery . " WHERE (flightNumber LIKE '%$searchFlightNumber%') OR (departureAirportCode LIKE '%$searchFlightNumber%') ORDER BY flightNumber";
}

if (isset($_GET['btnSubmit'])) {
    $minPassenger = isset($_GET['minPassenger']) ? $_GET['minPassenger'] : "";
    $maxPassenger = isset($_GET['maxPassenger']) ? $_GET['maxPassenger'] : "";
    $airlineName = isset($_GET['airlineName']) ? $_GET['airlineName'] : "";
    $aircraftName = isset($_GET['aircraftName']) ? $_GET['aircraftName'] : "";
    $sort = isset($_GET['sortSelection']) ? $_GET['sortSelection'] : "";
    $order = isset($_GET['orderSelection']) ? $_GET['orderSelection'] : "";

    if ($minPassenger != "" || $maxPassenger != "" || $airlineName != "" || $aircraftName != "") {
        $getDataQuery = $getDataQuery . " WHERE";

        if ($minPassenger != "" && $maxPassenger == "") {
            $getDataQuery = $getDataQuery . " flightDurationMinutes > $minPassenger";
        }

        if ($minPassenger == "" && $maxPassenger != "") {
            $getDataQuery = $getDataQuery . " flightDurationMinutes < $maxPassenger";
        }

        if ($minPassenger < $maxPassenger && $minPassenger != "" && $maxPassenger != "") {
            $getDataQuery = $getDataQuery . " flightDurationMinutes BETWEEN $minPassenger AND $maxPassenger";
        }

        if ($airlineName != "" && ($minPassenger != "" || $maxPassenger != "")) {
            $getDataQuery = $getDataQuery . " AND";
        }

        if ($airlineName != "") {
            $getDataQuery = $getDataQuery . " airlineName = '$airlineName'";
        }

        if ($aircraftName != "" && (($minPassenger != "" || $maxPassenger != "") || $airlineName != "")) {
            $getDataQuery = $getDataQuery . " AND";
        }

        if ($aircraftName != "") {
            $getDataQuery = $getDataQuery . " aircraftType = '$aircraftName'";
        }
    }

    if($sort != "" || $order != ""){

        if ($sort != "" && $order == ""){
            $getDataQuery = $getDataQuery . " ORDER BY $sort ASC";
        }

        if ($sort != "" && $order != ""){
            $getDataQuery = $getDataQuery . " ORDER BY $sort $order";
        }

    }

}

$flightData = executeQuery($getDataQuery);
$airlines = executeQuery($airlineQuery);
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="assets/img/pupairport.png" rel="icon">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body>
    <div class="navbar container-fluid p-0">
        <div class="row ps-3">
            <div>
                <img src="assets/img/brand.png" alt="PUP AIRPORT" class="img-fluid">
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-12 col-sm-8 col-md-9 col-xl-10 ">
                <h3 class="mt-5 text-center">Looking for a particular flight? Try entering the flight number or flight
                    code 🛩️</h3>
                <form method="GET">
                    <div class="container-fluid">
                        <div class="row d-flex justify-content-center">
                            <div class="col-12 col-sm-7 col-md-8 col-xl-9 col-xxl-10">
                                <input type="text" class="form-control rounded-pill fs-5" placeholder="e.g. 1234 or ABC"
                                    name="searchFlightNumber" required>
                            </div>
                            <div class="button-pad col-4 col-sm-3 col-md-2  col-xl-2 col-xxl-1 text-center">
                                <button class="btn btn-danger rounded-pill fs-5" type="submit">Search</button>
                            </div>
                            <div class="button-pad col-4 col-sm-2 col-md-2 col-xl-1 col-xxl-1 text-center">
                                <a href="index.php"><button class="btn btn-danger rounded-pill fs-5"
                                        type="button">Reset</button></a>
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
                                    <th scope="col" class="fields">Departure Code</th>
                                    <th scope="col" class="fields">Departure Time</th>
                                    <th scope="col" class="fields">Airline</th>
                                    <th scope="col" class="fields">Aircraft Name</th>
                                    <th scope="col" class="fields">Passenger Count</th>
                                    <th scope="col" class="fields">Pilot</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($flightData) > 0) {
                                    while ($flightLogs = mysqli_fetch_assoc($flightData)) { ?>
                                        <tr>
                                            <th scope="row"><?php echo $flightLogs['flightNumber'] ?></th>
                                            <td><?php echo $flightLogs['departureAirportCode'] ?></td>
                                            <td><?php echo $flightLogs['departureDatetime'] ?></td>
                                            <td><?php echo $flightLogs['airlineName'] ?></td>
                                            <td><?php echo $flightLogs['aircraftType'] ?></td>
                                            <td><?php echo $flightLogs['passengerCount'] ?></td>
                                            <td><?php echo $flightLogs['pilotName'] ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else { ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No flight data available.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="filters-column col-12 col-sm-4 col-md-3 col-xl-2 p-3" id="filters-column">
                <div class="filters card">
                    <div>
                        <form method="GET">
                            <div class="row">
                                <h5><i class="bi bi-filter-left"></i>SORT</h5>
                                <div class="col-6">
                                    <select class="form-select" id="sortSelection" name="sortSelection">
                                        <option selected disabled value="">Sort ...</option>
                                        <option <?php if ($sort == 'flightNumber') {
                                            echo "selected";
                                        } ?>
                                            value="flightNumber">Flight Number</option>
                                        <option <?php if ($sort == 'departureDateTime') {
                                            echo "selected";
                                        } ?>
                                            value="departureDateTime">Departure Time</option>
                                        <option <?php if ($sort == 'pilotName') {
                                            echo "selected";
                                        } ?> value="pilotName">
                                            Pilot Name</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <select class="form-select" id="orderSelection" name="orderSelection">
                                        <option <?php if ($order == 'ASC') {
                                            echo "selected";
                                        } ?>
                                            value="ASC">ASC</option>
                                        <option <?php if ($order == 'DESC') {
                                            echo "selected";
                                        } ?>
                                            value="DESC">DESC</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <h5><i class="bi bi-funnel-fill"></i>FILTERS</h5>
                                <h6>FLIGHT DURATION</h6><span style="font-size: 0.85rem;">in Minutes</span>
                                <div class="col mt-3">
                                    <label for="min-passenger" class="form-label fs-6">Min</label>
                                    <input type="number" class="form-control" id="min-passenger" name="minPassenger"
                                        min="1" max="719">
                                </div>
                                <div class="col mt-3">
                                    <label for="max-passenger" class="form-label fs-6">Max</label>
                                    <input type="number" class="form-control" id="max-passenger" name="maxPassenger"
                                        min="2" max="720">
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-center mt-3">
                                <div class="flex-grow-1 border-top border-dark"></div>
                                <div class="px-3 fw-bold">OR</div>
                                <div class="flex-grow-1 border-top border-dark"></div>
                            </div>

                            <div class="row mt-4">
                                <label for="airlines" class="form-label">Airline Name</label>
                                <div class="px-3">
                                    <select class="form-select" id="airlines" name="airlineName">
                                        <option selected disabled value="">Choose Airline</option>
                                        <?php while ($airlinesList = mysqli_fetch_assoc($airlines)) { ?>
                                            <option <?php if ($airlineName == $airlinesList['airlineName']) {
                                                echo "selected";
                                            } ?> value="<?php echo $airlinesList['airlineName'] ?>">
                                                <?php echo $airlinesList['airlineName'] ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <label for="aircraft" class="form-label">Aircraft Name</label>
                                <div class="px-3">
                                    <select class="form-select" id="aircraft" name="aircraftName">
                                        <option selected disabled value="">Choose Aircraft</option>
                                        <option <?php if ($aircraftName == 'Airbus A320') {
                                            echo "selected";
                                        } ?>
                                            value="Airbus A320">Airbus A320</option>
                                        <option <?php if ($aircraftName == 'Boeing 737') {
                                            echo "selected";
                                        } ?>
                                            value="Boeing 737">Boeing 737</option>
                                        <option <?php if ($aircraftName == 'Embraer E190') {
                                            echo "selected";
                                        } ?>
                                            value="Embraer E190">Embraer E190</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <button class="btn btn-submit" name="btnSubmit">SUBMIT</button>
                            </div>
                        </form>
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