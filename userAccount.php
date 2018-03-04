<?php
session_start();
/**
 * Created by PhpStorm.
 * User: Omotola
 * Date: 10/11/2017
 * Time: 12:27
 */
require_once('Models/WishList.php');
require_once('Models/CustomerData.php');
require_once('Models/ExtractProductData.php');
require_once('Models/itemData.php');
require_once 'Models/ExpiredAD.php';
$view = new stdClass();
$view->pageTitle = 'Register';

//Checks to see if any item has reached threshold and then deletes it from the database
$runExpired = new ExpiredAD();
$runExpired->expiredAd();

$wishData = new WishList();
$view->getWish = $wishData->getWishList($_SESSION['user_id']);

$deleteItem = new itemData();
if(isset($_POST['removeItem'])){
    $delete = $_GET['item'];
    $deleteItem->removeItem($delete);

}

$userInfo = new CustomerData();
$view->info = $userInfo->getUserInfo($_SESSION['user_id']);

if(isset($_POST['update'])){
    $userInfo->updateInfo($_SESSION['user_id']);
}

if(isset($_POST['removeUser'])){
    $userInfo->deleteUser($_SESSION['user_id']);
}

$userProducts = new ExtractProductData();
$view->products = $userProducts->sellerProduct($_SESSION['user_id']);

if(isset($_POST['updateListing'])){
    $updateItem = new itemData();
    $updateItem->updateListing();
}

require_once('Views/userAccount.phtml');