<?php
// Connect to database
require_once("DatabaseConnection.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_GET['q']) && isset($_GET['t'])) {
    $NAME = $_GET['q'];
    $TYPE = $_GET['t'];
    $QUERY = "SELECT * FROM animals WHERE $TYPE LIKE '%$NAME%'";
    $RESULT = $mysqli->query($QUERY);
    $OUTPUT = $RESULT->fetch_all(MYSQLI_ASSOC);
    print(json_encode($OUTPUT));
}
?>