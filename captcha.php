<?php

session_start();
include 'conf/config.inc.php';

$font = PROJECT_ROOT . "/theme/application/fonts/Roboto-Bold.ttf";

$rnum = md5(uniqid(rand()));

$code = substr($rnum, 0, 5);

$_SESSION["captchaCode"] = $code;

$imgX = 90;

$imgY = 35;

$font_size = 20;

$angle = rand(-10, 10);

$image = imagecreate($imgX, $imgY);

//$image = imagecreatetruecolor($imgX, $imgY);

$backgr_col = imagecolorallocate($image, 255, 103, 103);

$text_col = imagecolorallocate($image, 255, 255, 255);

$noiceColor = imagecolorallocate($image, 255, 255, 255);

imagefilledrectangle($image, 0, 0, 90, 35, $backgr_col);

$box = imagettfbbox($font_size, $angle, $font, $code);

$x = (int) ($imgX - $box[4]) / 2;

$y = (int) ($imgY - $box[5]) / 2;

imagettftext($image, $font_size, $angle, $x, $y, $text_col, $font, $code);

//imagettftext($image, $font_size, $angle, $x + 2, $y + 2, $text_col, $font, $code);//sade

for ($i = 0; $i < 100; $i++) {

    imagesetpixel($image, rand() % 200, rand() % 50, $noiceColor);
}

for ($i = 0; $i < 10; $i++) {

    imageline($image, mt_rand(0, $imgX), mt_rand(0, $imgY), mt_rand(0, $imgX), mt_rand(0, $imgY), $noiceColor);
}

header("Content-type: image/png");

imagepng($image);

imagedestroy($image);
?>