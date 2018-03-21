<?php
/**
 * Created by PhpStorm.
 * User: Omotola
 * Date: 30/12/2017
 * Time: 13:52
 */

require_once 'Models/Database.php';
require_once 'Models/ProductData.php';

class itemData
{
    protected $_dbConnection, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();
    }

    /**
     * @param $seller_id identifies what seller is logged in so contact details can be hidden
     * @return array
     */
    public function hideContactForm($seller_id){
        $query = "SELECT productsID, sellerID FROM products where sellerID = $seller_id";
        $result = $this->_dbConnection->query($query);
        $result->execute();

        $results = [];
        while ( $row = $result->fetch() ) {
            //store this array in $result[] below
            $results[]  = $row;
        }
        return $results;
    }


    /**
     * @param $item collects id for specific item to be displayed
     * @return array
     */
    public function specificItem($item){

        $query = "SELECT productsID,category, productTitle, productDes, currency, price,
             productCol,productSize,productImg,publishDate,products.sellerID,fullName,surName,email,phonenumber,country, state
              FROM products,users,address WHERE users.usersID = address.userID and products.sellerID = users.usersID AND productsID = $item";

        $result = $this->_dbConnection->query($query);
        $result->execute();

        $results = [];
        while ( $row = $result->fetch() ) {
            //store this array in $result[] below
            $results[]  = new ProductData($row);
        }
        return $results;

    }

    /**
     * Deletes specific item from database
     */

    public function removeItem($itemId){
        $sql = "DELETE FROM products WHERE productsID = $itemId";
        $result = $this->_dbConnection->query($sql);
        $result->execute();

        echo '<script>window.location="userAccount.php"</script>';
    }

    /**
     * update an existing item by user
     */
    public function updateListing(){
            $id = $_POST['itemUpdate'];
            $title = $_POST['updateTitle'];
            $description = $_POST['updateDes'];
            if (($_POST['updateSize']) == 'none') {
                $size = 'null';
            }else{
            $size = $_POST['updateSize'];
            }
            $color = $_POST['updateColor'];
            $currency = 'KSh';
            $price = $_POST['updatePrice'];
            $country = $_POST['country'];
            $state = $_POST['state'];

            $target_dir = "images/websiteImg";
            $fileName = $_FILES["updateImage"]["name"];
            $target_file = $target_dir . basename($fileName);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


            echo $id,$title,$description,$size,$color,$currency,$price,$country,$state,$fileName;
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                $_SESSION['updateError'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $_SESSION['updateError'] = "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            } else {
                // Check file size
                if ($_FILES["updateImage"]["size"] > 500000) {
                    $_SESSION['updateError'] = "Sorry, your file is too large.";
                } else if (file_exists($target_file)) { // Check if file already exists
                    $_SESSION['updateError'] = "Sorry, file already exists.";
                } else if (move_uploaded_file($_FILES["updateImage"]["tmp_name"], $target_file)) {
                    $sql= "UPDATE products,users, address SET productTitle = '$title',productDes = '$description',
                    productImg = '$fileName',productSize = $size,productCol = '$color', currency='$currency',
                    price = $price, country = '$country', state = '$state' where products.sellerID =users.usersID and address.userID = users.usersID  
                    and products.productsID = $id";
                    $result = $this->_dbConnection->query($sql);
                    $result->execute();

                    echo '<script>alert("Item has been updated")</script>';
                    echo '<script>window.location="userAccount.php"</script>';
                } else {
                    $_SESSION['updateError'] = "Sorry, there was an error uploading your file.";
                }
            }

    }
}