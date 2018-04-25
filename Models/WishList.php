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


    /**
     * insert item into the wishList table
     */
    public function  insertWishItem(){

            $id = $_GET["id"]; //ID FOR PRODUCT
            $userId = $_POST['userID'];
            echo $id, $userId;
            if($this->checkIfExist($id) != true){
                $sql = "INSERT INTO WishList (productID, usersID) VALUE ('$id','$userId')";
                $result = $this->_dbConnection->prepare($sql);
                $result->execute();
            }else
            {
                echo '<script>alert("Item Already Added")</script>';
            }
    }

    /**
     * @param $id for item to check if it already exist in table
     * @return bool true if exist false if not
     */
    public function checkIfExist($id){
        $sql = "select * from WishList where productID = $id";
        $result = $this->_dbConnection->query($sql);
        $result->execute();

        if($result->rowCount() == 1 && $result->rowCount() > 0 ){
            return true;
        }else{
            return false;
        }


    }

    public function getWishList($userId){
        $sql = "select * from WishList, products, address, users where WishList.usersID = $userId 
                                          and products.productsID = WishList.productID 
										  and products.sellerID = address.userID
                                          and address.userID = users.usersID;";
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