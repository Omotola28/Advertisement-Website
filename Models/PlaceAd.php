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

    public function insertAd()
    {

        if (isset($_POST["publish"])) {
            $category = $_POST['category'];
            $title = $this->test_input($_POST['title']);
            $description = $this->test_input($_POST['description']);
            $currency = $_POST['currency'];
            $price = $this->test_input($_POST['amount']);
            $color = $_POST['color'];
            $country = $_POST['country'];
            $state = $_POST['state'];
            $date = $_POST['date'];
            $dateFormat = date("Y-m-d", strtotime($date));
            if (!isset($_SESSION['logged_in'])) {
                $_SESSION['logged_in'] = false;
            }
            if (!isset($_POST['size'])) {
                $size = 'null';
            }else{
                $size = $_POST['size'];
            }


            $target_dir = "images/";
            $fileName = $_FILES["file"]["name"];
            $target_file = $target_dir . basename($fileName);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


            // We know user email exists if the rows returned are more than 0
            if ($_SESSION['logged_in'] == false) {
                $_SESSION['adMessage'] = 'You need to register/login before advertisement is placed';
                header("location: placeAd.php");
                exit();
            } else if ($_SESSION['logged_in'] == true ) {
                // Allow certain file formats
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    $_SESSION['adMessage'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }
                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    $_SESSION['adMessage'] = "Sorry, your file was not uploaded.";
                    // if everything is ok, try to upload file
                } else {
                    // Check file size
                    if ($_FILES["file"]["size"] > 500000) {
                        $_SESSION['adMessage'] = "Sorry, your file is too large.";
                    } else if (file_exists($target_file)) { // Check if file already exists
                        $_SESSION['adMessage'] = "Sorry, file already exists.";
                    } else if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                        if(isset($_SESSION['user_id'])){
                            $userID = $_SESSION['user_id'];
                            $sqlQuery = "INSERT INTO products (category,productTitle,productDes, currency,price,productCol,productSize,productImg,publishDate, sellerID)
                          VALUES ('$category','$title','$description','$currency','$price','$color',$size,'$fileName','$dateFormat','$userID')";
                            $statement = $this->_dbConnection->prepare($sqlQuery); // prepare a PDO statement
                            $statement->execute(); // execute the PDO statement
                            echo $category, $title, $description, $currency, $price,$color, $country, $state,$size,$dateFormat,$fileName, $userID;

                        }
                        $_SESSION['adMessage'] = "The file " . basename($_FILES["file"]["name"]) . "/product details has been uploaded.";
                    } else {
                        $_SESSION['adMessage'] = "Sorry, there was an error uploading your file.";
                    }
                }
            } else
                $_SESSION['adMessage'] = "wrong seller information";


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