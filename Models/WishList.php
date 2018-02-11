<?php
/**
 * Created by PhpStorm.
 * User: Omotola
 * Date: 03/01/2018
 * Time: 12:04
 */

require_once 'Models/Database.php';

class WishList
{

    protected $_dbConnection, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();
    }


    public function  insertWishItem(){
        if(isset($_POST['wishList'])) {
            $id = $_GET["id"];
            $title = $_POST['title'];
            $price = $_POST['price'];
            $color = $_POST['color'];
            $image = $_POST['image'];
            $date = $_POST['date'];
            //$sellerId = $_POST['sellerID'];
            $sellerName = $_POST['sellerfName'];
            $sellerEmail = $_POST['sellerEmail'];
            $sellerPhone = $_POST['phoneNo'];
            $location = $_POST['location'];
            $currency = $_POST['currency'];
            $userId = $_POST['userID'];
            if($_POST['size'] == ''){
                $size = 'null';
            }else{
                $size = $_POST['size'];
            }

            if($this->checkIfExist($id) != true){
                $sql = "INSERT INTO WishList (wishID,wishImg, wishColor, wishSize, wishLocation, wishCurrency, wishPrice, wishTitle, userID, wishSellerName, wishSellerEmail, wishSellerNo, wishDate) VALUE 
                ('$id','$image','$color',$size,'$location','$currency','$price','$title','$userId', '$sellerName', '$sellerEmail', '$sellerPhone', '$date')";
                $result = $this->_dbConnection->query($sql);
                $result->execute();
            }else
            {
                echo '<script>alert("Item Already Added")</script>';
            }


        }
    }

    public function checkIfExist($id){
        $sql = "select * from WishList where wishID = $id";
        $result = $this->_dbConnection->query($sql);
        $result->execute();

        if($result->rowCount() > 0){
            return true;
        }else{
            return false;
        }


    }

    public function getWishList($userId){
        $sql = "select * from WishList where userID = $userId";
        $result = $this->_dbConnection->query($sql);
        $result->execute();

        if($result->rowCount()== 0){
            $_SESSION['wishMessage'] = "No items added to wishList";
        }

        $results = [];
        while ( $row = $result->fetch() ) {
            //store this array in $result[] below
            $results[]  = $row;
        }
        return $results;
    }

    public function removeItem($itemId){
        $sql = "DELETE FROM WishList WHERE wishID = $itemId";
        $result = $this->_dbConnection->query($sql);
        $result->execute();

        echo '<script>window.location="userAccount.php"</script>';
    }

}