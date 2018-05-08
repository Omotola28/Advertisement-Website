<?php
session_start();
/**
 * Created by PhpStorm.
 * User: Omotola
 * Date: 08/11/2017
 * Time: 00:15
 */
require_once('Models/CustomerData.php');
require_once 'Models/ExpiredAD.php';
$view = new stdClass();
$view->pageTitle = 'LogIn';

//Checks to see if any item has reached threshold and then deletes it from the database
$runExpired = new ExpiredAD();
$runExpired->expiredAd();


//login user
$customerData = new CustomerData();

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['token'])){
    if($_POST['token'] !== $_SESSION['token']){
        if(isset($_POST['loginBtn'])){
            $customerData->loginUser();
        }
    }else{
        die("error loading page" . $_SESSION['token']. "not same as". $_POST['token']);
    }

}


require_once('Views/login.phtml');