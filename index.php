<?php
session_start();


require_once 'Models/WishList.php';
require_once 'Models/ExpiredAD.php';
$view = new stdClass();
$view->pageTitle = 'Homepage';
//Checks to see if any item has reached threshold and then deletes it from the database
$runExpired = new ExpiredAD();
$runExpired->expiredAd();
if(isset($_GET['action']) == 'logout'){
    session_unset();
    session_destroy();
}

require_once('Views/index.phtml');
