<?php
session_start();
/**
 * Created by PhpStorm.
 * User: Omotola
 * Date: 07/11/2017
 * Time: 23:43
 */

require_once('Models/CustomerData.php');
require_once 'Models/ExpiredAD.php';
$view = new stdClass();
$view->pageTitle = 'Register';

//Checks to see if any item has reached threshold and then deletes it from the database
$runExpired = new ExpiredAD();
$runExpired->expiredAd();

$customerData = new CustomerData();
$customerData->insertIntoDB();


require_once('Views/register.phtml');