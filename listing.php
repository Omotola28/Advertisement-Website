<?php
session_start();
/**
 * Created by PhpStorm.
 * User: Omotola
 * Date: 08/11/2017
 * Time: 00:20
 */
require_once('Models/ExtractProductData.php');
require_once('Models/Pagination.php');
require_once('Models/WishList.php');
require_once 'Models/ExpiredAD.php';
$view = new stdClass();
$view->pageTitle = 'Listing';

//Checks to see if any item has reached threshold and then deletes it from the database
$runExpired = new ExpiredAD();
$runExpired->expiredAd();

//these variables are passed via URL
$limit = ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : 6; //listing  per page
$page = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1; //starting page
$links = 5;

$extractProduct = new ExtractProductData();
$pagination = new Pagination($extractProduct->fetchAll()); //__constructor is called
$view->results = $pagination->getData( $limit, $page );

$view->status = false;
if(!isset($_SESSION["logged_in"])){
    $view->status = false;

}else if(isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true ){
    $view->status = true;
}

if(isset($_POST["wishList"]))
{

      if( $_SESSION['user_id'] == $_POST["sellerID"])
          echo '<script>alert("Item owned by you, cannot be added to watchList")</script>';
      else {

            $wishData = new WishList();
            $wishData->insertWishItem();
      }
}
require_once('Views/listing.phtml');