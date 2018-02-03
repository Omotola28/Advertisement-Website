<?php
/**
 * Created by PhpStorm.
 * User: Omotola
 * Date: 03/02/2018
 * Time: 01:25
 */
session_start();
$captcha_string = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
$captcha_string = substr(str_shuffle($captcha_string), 0, 6);
$_SESSION['captcha'] = $captcha_string;
$fontSize = 30;
$imgWidth= 200;
$imgHeight = 40;
$font ='fonts/OpenSans-Light.ttf';
header('Content-type:image/jpeg');
$img = @imagecreatetruecolor($imgWidth, $imgHeight)
       or die('Cannot Initialize new GD image stream');
$white = imagecolorallocate($img, 255, 250, 250); //set background color
$black = imagecolorallocate($img, 0, 0, 0);
$blue = imagecolorallocate($img, 65, 105, 225);

/*for($i=1; $i < rand(1,5); $i++){
    $colour = rand(1,2) == 1 ? $black : $blue;
    imageline($img,rand(5,70), rand(5,20), rand(5,70)+5, rand(5,20)+5, $colour);
}*/
imagefill($img,0,0,$white);
imagettftext($img,$fontSize,-3, 20, 30, $black, $font,$captcha_string);
imagejpeg($img);
imagedestroy($img);

?>

