<?php
session_start();
/**
 * Created by PhpStorm.
 * User: Omotola
 * Date: 11/11/2017
 * Time: 16:56
 */
require_once 'Models/ProductData.php';
require_once 'Models/itemData.php';
require_once 'Models/ExpiredAD.php';
$view = new stdClass();
$view->pageTitle = 'AboutListing';
$item = new itemData();

//Checks to see if any item has reached threshold and then deletes it from the database
$runExpired = new ExpiredAD();
$runExpired->expiredAd();

//Hides contact form if the item is for the seller
$view->doHide = "";

if(isset($_GET["item"]))
{
    //displays specific item
    $view->values = $item->specificItem($_GET["item"]);
    if(isset($_SESSION['user_id'])){
        //hides contact details of user if they are not logged in
        $view->hideForm = $item->hideContactForm($_SESSION['user_id']);
        foreach ($view->hideForm as $keys => $values){
            if($values["productsID"] === $_GET["item"]){
                $view->doHide = true;
            }
        }
    }

}
require_once('Views/aboutListing.phtml');