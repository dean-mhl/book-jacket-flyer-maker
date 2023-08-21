<?php
  //Connection to MySQL
  require "config.php";
  $con = mysqli_connect($host,$user,$password,$dbname);

  if(!$con) {
    die('Not Connected To Server');
  }

  //Connection to database
  if(!mysqli_select_db($con, $dbname)) {
    echo 'Database Not Selected';
  }

  // see this: https://makitweb.com/return-json-response-ajax-using-jquery-php/
  $return_arr = array();

  if(isset($_POST['flyer'])){
    $flyer = $_POST['flyer'];

    $get_name_header = mysqli_query($con,"SELECT header, name, has_url, url, header_css FROM flyers WHERE id=" . $flyer);
    $row = $get_name_header->fetch_assoc();

    $return_arr[] = $row;

    // Fetch images
    $fetch_images = mysqli_query($con,"SELECT * FROM covers WHERE flyer_id = ". $flyer . " ORDER BY sort ASC");

    while($row = mysqli_fetch_assoc($fetch_images)){
      $id = $row['flyer_id'];
      $isbn = $row['isbn'];
      $link = "https://secure.syndetics.com/index.aspx?type=xw12&client=MVLC&upc=&ocls=&isbn=" . $isbn . "/LC.JPG";
      $return_arr[] = array(
        "id" => $id,
        "isbn" => $isbn,
        "link" => $link
      );
    }

    echo json_encode($return_arr);

  } else {
    echo "no flyer";
  }

  mysqli_close($con);
?>
