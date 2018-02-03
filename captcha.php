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
$imgWidth= 70;
$imgHeight = 40;
$font ='./OpenSans-Bold.ttf';
header('Content-type:image/jpeg');
$img = @imagecreatetruecolor($imgWidth, $imgHeight)
       or die('Cannot Initialize new GD image stream');
imagecolorallocate($img, 255, 255, 255);
$text_color = imagecolorallocate($img, 0, 0, 0);
imagettftext($img,$fontSize,0, 15, 30, $text_color, $font,$captcha_string);
imagejpeg($img);


