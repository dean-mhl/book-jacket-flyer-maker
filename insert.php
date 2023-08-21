<?php

  function uniqidReal($length = 10) {
    // uniqid gives 13 chars, but you could adjust it to your needs.
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($length / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
        $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
    } else {
        throw new Exception("no cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $length);
  }


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

    $isbn_array = array();
    $imageids_arr = array();

    if(isset($_POST['isbns'])){
      $isbn_array = $_POST['isbns'];
    }

    if(isset($_POST['imageids'])){
      $imageids_arr = $_POST['imageids'];
    }

    if(isset($_POST['webpage'])){
      $webpage = $_POST['webpage'];
    }

    $url = "";
    if ($webpage == 1) {
      $url = uniqidReal();
    }

    if(isset($_POST['name'])){
      $name = $_POST['name'];
      $name = $con->real_escape_string($name);
    }

    if(isset($_POST['header'])){
      $header = $_POST['header'];
      $header = $con->real_escape_string($header);
    }

    if(isset($_POST['header_css'])){
      $header_css = $_POST['header_css'];
    }

    if(isset($_POST['html'])){
      $html = $_POST['html'];
      $html = $con->real_escape_string($html);
    }

    if(isset($_POST['id'])){
      $id = $_POST['id'];
    }


    if (count($isbn_array) > 0 && !empty($name)) {
      if ( !empty($id) ) {
        $t = time();
        $sql = "UPDATE flyers SET name='$name', has_url='$webpage', header='$header', header_css='$header_css', timestamp=$t WHERE id=$id";
        mysqli_query($con, $sql);
        $flyer_id = mysqli_insert_id($con);

        $sql = "DELETE FROM covers WHERE flyer_id=$id";
        mysqli_query($con, $sql);

        $sql = "DELETE FROM flyer_html WHERE id=$id";
        mysqli_query($con, $sql);

        $counter = 0;
        foreach ($isbn_array as $isbn) {
          $sql = "INSERT INTO covers (isbn, sort, flyer_id) VALUES ('$isbn', $counter, $id)";
          mysqli_query($con, $sql);
          $counter++;
        }

        $sql = "INSERT INTO flyer_html (id, html) VALUES ($id, '$html')";
        echo $sql;
        mysqli_query($con, $sql);

      } else {
        $t = time();
        $sql = "INSERT INTO flyers (name, has_url, url, header, header_css, timestamp) VALUES ('$name', '$webpage', '$url', '$header', '$header_css', $t)";
        mysqli_query($con, $sql);
        $flyer_id = mysqli_insert_id($con);

       $sql = "INSERT INTO flyer_html (id, html) VALUES ($flyer_id, '$html')";
       mysqli_query($con, $sql);

        $counter = 0;
        foreach ($isbn_array as $isbn) {
          $sql = "INSERT INTO covers (isbn, sort, flyer_id) VALUES ('$isbn', $counter, $flyer_id)";
          mysqli_query($con, $sql);
          $counter++;
        }
      }
    } else {
      echo "no isbns or no name";
    }
    mysqli_close($con);

?>
