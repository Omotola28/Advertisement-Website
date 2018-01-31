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
/*if(isset($_GET['action']) == 'logout'){
    if(isset($_SESSION["shopping_cart"]) && !empty($_SESSION["shopping_cart"])){
        //$sql = "";
        foreach($_SESSION["shopping_cart"] as $keys => $values) {
            $id = $values['id'];
            $title = $values['itemTitle'];
            $price = $values['itemPrice'];
            if($values['itemSize'] == ''){
                $size = 'null';
            }else
                $size = $values['itemSize'];
            $color = $values['itemColor'];
            $image = $values['itemImage'];
            $sellerId = $values['itemSellerID'];
            $location = $values['itemLocation'];
            $currency = $values['itemCurrency'];
            $user = $values['userid'];
            echo $id,$title,$price,$size,$color,$image,$sellerId,$location,$currency,$user;

            $sql = "INSERT INTO WishList (wishImg, wishColor, wishSize, wishLocation, wishCurrency, wishPrice, wishTitle, userID) VALUE
             ('$image','$color',$size,'$location','$currency','$price','$title','$user')";
            $wish = new WishList();
            $wish->insertWishItem($sql);
        }


    }
  //that is where the sessions are unset from
}*/

require_once('Views/index.phtml');
