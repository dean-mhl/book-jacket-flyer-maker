<?php
  require "config.php";
  $con = mysqli_connect($host,$user,$password,$dbname);

  if ($con->connect_errno) {
    printf("connection failed: %s\n", $con->connect_error());
    exit();
  }

  if(isset($_POST['id'])){
    $id = $_POST['id'];
  }

  $query = "SELECT url FROM flyers ORDER BY id DESC LIMIT 0,1";

  if ($stmt = $con->prepare($query)) {

    $stmt->bind_param('i', $id);

    $stmt->execute();
    $stmt->bind_result($url);
    $stmt->fetch();

    echo "$url";

    $stmt->close();
  } else {
    echo "failed to fetch data";
  }

  mysqli_close($con);
?>

