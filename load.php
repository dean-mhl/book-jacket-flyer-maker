<?php
  require "config.php";
  $con = mysqli_connect($host,$user,$password,$dbname);
  if(!$con) {
    die('Not Connected To Server');
  }

  $sql = "SELECT DISTINCT f.id, f.name, f.timestamp FROM flyers f INNER JOIN covers c ON f.id = c.flyer_id ORDER BY f.timestamp DESC";

  $flyers = mysqli_query($con, $sql);
  $contents = '';
  while($row = mysqli_fetch_assoc($flyers)) {
    $contents .= '<tr id="'. $row["id"]  .'">';
    $contents .= '  <td class="flyer-name">' . $row["name"]  . '</td>';
    $contents .= '  <td class="operations">' . date("m/d/y, h:i a", $row['timestamp']) . '</td>';
    $contents .= '  <td class="operations">';
    $contents .= '<button type="button" class="edit" data-id="' . $row["id"] . '">view/edit</button> ';
    $contents .= '<button type="button" class="delete" data-id="' . $row["id"] . '">delete</button>';
    $contents .= '  </td>';
    $contents .= '</tr>';
   }

  mysqli_close($con);
  echo json_encode($contents);
?>

