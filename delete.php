<?php
require "config.php";

//Connection to MySQL
$con = mysqli_connect($host, $user, $password, $dbname);

if (!$con) {
    die("Not Connected To Server");
}

//Connection to database
if (!mysqli_select_db($con, $dbname)) {
    echo "Database Not Selected";
}

if (isset($_POST["id"])) {
    $id = $_POST["id"];

    $sql = "DELETE FROM flyers WHERE id = " . $id;
    mysqli_query($con, $sql);
    $sql = "DELETE FROM covers WHERE flyer_id = " . $id;
    mysqli_query($con, $sql);
    $sql = "DELETE FROM flyer_html WHERE id = " . $id;
    mysqli_query($con, $sql);
}

mysqli_close($con);

?>