<?php
/**
 * Created by PhpStorm.
 * User: Omotola
 * Date: 22/12/2017
 * Time: 17:11
 */

require_once 'Models/Database.php';
class PlaceAd
{
    protected $_dbConnection, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();


    }

    /**
     * insert products into the products table
     */

    public function insertAd()
    {
        $specialChar = '/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\?\\\]/';
        //$titlePattern ='/[^\~\>\<]/';
        $category = $_POST['adCategory'];
        $title = $this->test_input($_POST['adTitle']);
        $description = $this->test_input($_POST['adDescription']);
        $price = $this->test_input($_POST['adPrice']);
        $color = $_POST['adColor'];
        $date = date('Y/m/d');
        if (!isset($_POST['adSize'])) {
            $size = 'null';
        }else{
            $size = $_POST['adSize'];
        }
        if (!isset($_SESSION['logged_in'])) {
            $_SESSION['logged_in'] = false;
        }

        $target_dir = "images/advertImg/";
        $fileName = $_FILES["adPicture"]["name"];
        $target_file = $target_dir . basename($fileName);
        global $uploadOk;
        $uploadOk =1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if ($_SESSION['logged_in'] == true ) {
            if(preg_match($specialChar,$price) || preg_match('/[a-zA-Z]/',$price)){
                echo 'Only numbers allowed in price input box';
                exit();
            }
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
                exit();
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
                exit();
                // if everything is ok, try to upload file
            }else {
                if ($_FILES["adPicture"]["size"] > 500000) {
                    echo "Sorry, your file is too large.";
                    exit();
                } else if (file_exists($target_file)) { // Check if file already exists
                    echo "Sorry, file already exists.";
                    exit();
                } else if (move_uploaded_file($_FILES["adPicture"]["tmp_name"], $target_file)) {
                    $userID = $_SESSION['user_id'];
                    $sqlQuery = "INSERT INTO products (category,productTitle,productDes,price,productCol,productSize,productImg,publishDate, sellerID)
                    VALUES ('$category','$title','$description','$price','$color',$size,'$fileName','$date','$userID')";
                    $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
                    $statement->execute(); // execute the PDO statement
                    echo "Image uploaded";
                    exit();
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    exit();
                }
            }
        }else {
            echo "wrong seller information";
            exit();
        }


    }

    /**
     * Checks input from form and strips any unwanted characters making sure to remove sql injections
     * @param $data of unfiltered input
     * @return string of filtered input
     */

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = preg_replace("#[']#i", '', $data);
        return $data;
    }
}