<?php

  $set = 1;

  if(isset($_POST['set'])){
      $set = $_POST['set'];
  }

  $page = (($set-1)*12) + 1;

  if(isset($_POST['link'])){
      $link = $_POST['link'];
  }

  $url = $link . "rw=" . $page  . "&isd=false";
  $ua = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';

  $curl = curl_init();

  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
  curl_setopt($curl, CURLOPT_USERAGENT, $ua);
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);

  $output = curl_exec($curl);

  curl_close($curl);

  $doc = new DOMDocument();
  @ $doc->loadHTML( $output );

  $xpath = new DOMXpath($doc);
  $images = $xpath->query("//img[contains(@class,'results_img')]");
  $inputs = $xpath->query("//input[contains(@class,'isbnValue')]/@value");

  $isbn_arr = array();

  foreach ($inputs as $input) {
   $isbn_arr[]=$input->nodeValue;
  }

  if (($images->length) < 12 ) {
    array_push($isbn_arr, "end");
  }

  echo json_encode($isbn_arr);

?>
