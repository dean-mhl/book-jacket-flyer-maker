<?php
require "config.php";

//Connection to MySQL
$con = mysqli_connect($host, $user, $password, $dbname);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

if (isset($_GET["url"])) {
    $url = $_GET["url"];
} else {
    //  print("no flyer specified");
    exit();
}

if (!$con) {
    die("Not Connected To Server");
}

$sql =
    "SELECT fh.id, fh.html, f.header_css from flyer_html AS fh INNER JOIN flyers AS f ON fh.id = f.id WHERE f.url = '" .
    $url .
    "'";

if ($result = mysqli_query($con, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        $row = $result->fetch_row();
        mysqli_close($con);
    } else {
        // print("connection error");
        exit();
    }
}

$id = $row[0];
$html = $row[1];
$font = $row[2]; // e.g., Bitter:400i

/* list all web-safe fonts that font-picker might use, even if we don't include them in index.php */
$system_fonts = [
    "Arial",
    "Courier New",
    "Georgia",
    "Tahoma",
    "Times New Roman",
    "Trebuchet MS",
    "Verdana",
];

$dir = "./fonts";
$local_font_files = array_slice(scandir($dir), 2); // nix the * and **

$local_fonts = [];
foreach ($local_font_files as $local_font_file) {
    $local_fonts[] = str_replace(".woff", "", $local_font_file);
}

$font_components = explode(":", $font);
$font_family = $font_components[0]; // e.g., Bitter

$head_style = "";
if ($font_family) {
    if (in_array($font_family, $local_fonts)) {
        $head_style =
            "<style> @font-face { font-family:'" .
            $font_family .
            "'; src:local('" .
            $font_family .
            "'), url('fonts/" .
            $font_family .
            ".woff') format('woff'); } </style>";
    } elseif (!in_array($font_family, $system_fonts)) {
        $head_style =
            "<link href='https://fonts.googleapis.com/css?family=" .
            str_replace(" ", "+", $font) .
            "&display=swap' rel='stylesheet' type='text/css'>";
    }
}

$dom = new domDocument();
$dom->loadHTML($html);
$dom->preserveWhiteSpace = false;

$header_div = $dom->getElementById("header");
$header_div_attribs = $header_div->getAttribute("style");
$new_header_div_attribs = trim(
    str_replace("height: 100px;", "", $header_div_attribs)
); // nix the inline height style
$header_div->removeAttribute("style");
$header_div->setAttribute("style", $new_header_div_attribs);

$header_div_span = $header_div->childNodes->item(0);
$header_div_span->removeAttribute("style"); // nix the inline font-size style
$header_text = $header_div_span->textContent;

$nodes = $dom->getElementsByTagName("li");

for ($curr = 0; $curr < $nodes->length; $curr++) {
    $li = $nodes->item($curr);
    $li->removeAttribute("style");
    $isbn = $li->getAttribute("id");
    $a = $dom->createElement("a");
    $a->setAttribute(
        "href",
        "https://mvlc.ent.sirsi.net/client/en_US/mvlc/search/results?te=&qu=ISBN=" .
            $isbn
    );
    $a->setAttribute("title", "click to view this item in the catalog");
    $images = $li->childNodes;
    foreach ($images as $image) {
        $orig_src = $image->getAttribute("src");
        $src = str_replace("MC.JPG", "LC.JPG", $orig_src); // get the larger images
        $image->removeAttribute("src");
        $image->setAttribute("src", $src);
        $image->setAttribute("alt", "cover of item with ISBN " . $isbn);
        $a_clone = $a->cloneNode();
        $image->parentNode->replaceChild($a_clone, $image);
        $a_clone->appendChild($image);
    }
    $li->removeAttribute("class");
    $li->removeAttribute("data-id");
    $li->removeAttribute("id");
}

$body = preg_replace(
    "/^<!DOCTYPE.+?>/",
    "",
    str_replace(
        ["<html>", "</html>", "<body>", "</body>"],
        ["", "", "", ""],
        $dom->saveHTML()
    )
);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?php echo $header_text; ?></title>
  <style>
    body {
      max-width: 960px;
      padding: 10px;
      margin-left: auto;
      margin-right: auto;
    }
    #header {
      font-size: calc(1.5em + 3vw);
      line-height: 1.15;
      text-align: center;
      padding-bottom: 16px;
      /* see https://www.smashingmagazine.com/2016/05/fluid-typography/ */
    }
    #sortable {
      list-style-type: none;
      text-align: center;
      padding: 0;
      margin: 0;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      grid-gap: 6px;
      align-items: start; /* start or center might be better than stretch */
    }
    img {
      max-width: 204px; /* should be equal or less than sum of grid-template-columns minmax value and grid-gap */
    }
  </style>
  <?php echo $head_style; ?>
</head>
<body>
  <?php echo $body; ?>
</body>
</html>
