<?php

session_start();
/**
 * Created by PhpStorm.
 * User: Omotola
 * Date: 08/11/2017
 * Time: 00:15
 */
require_once('Models/PlaceAd.php');
require_once 'Models/ExpiredAD.php';
$view = new stdClass();
$view->pageTitle = 'PlaceAd';

//Checks to see if any item has reached threshold and then deletes it from the database
$runExpired = new ExpiredAD();
$runExpired->expiredAd();

//insert product into database
$placeAd = new PlaceAd();

if(isset($_POST['adBtn'])){
    $placeAd->insertAd();
}

require_once('Views/placeAd.phtml');