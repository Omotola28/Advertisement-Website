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

$extractProduct = new ExtractProductData();
$extractProduct = $extractProduct->fetchAll();

//Checks to see if any item has reached threshold and then deletes it from the database
$runExpired = new ExpiredAD();
$runExpired->expiredAd();

//these variables are passed via URL
$limit = ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : 6; //listing  per page
$page = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1; //starting page
$links = 5;

$pagination = new Pagination($extractProduct); //__constructor is called
$view->results = $pagination->getData( $limit, $page );

$view->status = false;
if(!isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == false){
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
          /*if (isset($_SESSION["shopping_cart"])) {
              $item_array_id = array_column($_SESSION["shopping_cart"], "id");
              if (!in_array($_GET["id"], $item_array_id)) {
                  $count = count($_SESSION["shopping_cart"]);
                  $item_array = array(
                      'id' => $_GET["id"],
                      'itemTitle' => $_POST["title"],
                      'itemPrice' => $_POST["price"],
                      'itemSize' => $_POST["size"],
                      'itemColor' => $_POST["color"],
                      'itemImage' => $_POST["image"],
                      'itemSellerID' => $_POST["sellerID"],
                      'itemLocation' => $_POST["location"],
                      'itemCurrency' => $_POST["currency"],
                      'userid' => $_POST["userID"]
                  );
                  $_SESSION["shopping_cart"][$count] = $item_array;
              } else {
                  echo '<script>alert("Item Already Added")</script>';
                  echo '<script>window.location="listing.php"</script>';
              }
          } else {
              $item_array = array(
                  'id' => $_GET["id"],
                  'itemTitle' => $_POST["title"],
                  'itemPrice' => $_POST["price"],
                  'itemSize' => $_POST["size"],
                  'itemColor' => $_POST["color"],
                  'itemImage' => $_POST["image"],
                  'itemSellerID' => $_POST["sellerID"],
                  'itemLocation' => $_POST["location"],
                  'itemCurrency' => $_POST["currency"],
                  'userid' => $_POST["userID"]
              );
              $_SESSION["shopping_cart"][0] = $item_array;
          }*/
      }
}
require_once('Views/listing.phtml');