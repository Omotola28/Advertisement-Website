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

$customerData = new CustomerData();
$customerData->loginUser();

require_once('Views/login.phtml');