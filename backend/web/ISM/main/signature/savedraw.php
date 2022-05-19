<?php
//print_r($_POST);
$img = $_POST['base64'];
$img = str_replace('data:image/png;base64,', '', $img);
$fileData = base64_decode($img);
$fileName = uniqid().'.png';

file_put_contents($fileName, $fileData);

header("Location: ./index.html");

?>