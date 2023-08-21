<?php
header('Content-type: application/json');
/* from comment on https://stackoverflow.com/questions/15005500/loading-cross-domain-endpoint-with-ajax */
$url=$_GET['url'];
if (is_string($url)) {
    $url = urldecode($url);
}
$json=file_get_contents($url);
echo $json;
?>
