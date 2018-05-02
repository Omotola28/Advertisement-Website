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


        $fileName = $_FILES["adPicture"]["name"];
        $target_dir = "images/advertImg/";
        $thumb_dir ="images/thumbImg/".$fileName;
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
                    $thumb = $this->createThumbnail($target_file, $thumb_dir, 100, 100,array(255,255,255));
                    $sqlQuery = "INSERT INTO products (category,productTitle,productDes,price,productCol,productSize,productImg,publishDate, sellerID, thumbImg)
                    VALUES ('$category','$title','$description','$price','$color',$size,'$fileName','$date','$userID','$thumb')";
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



    function createThumbnail($filePath, $thumbPath, $thumbW, $thumbH, $background = false)
    {
        global $newHeight, $newWidth;
        list($originalW, $originalH, $originalType) = getimagesize($filePath);
        if ($originalW > $originalH) {
            $newWidth = $thumbW;
            $newHeight = intval($originalH * $newWidth / $originalW);
        } else {
            $newHeight = $thumbH;
            $newWidth = intval($originalW * $newHeight / $originalH);
        }
        $dest_x = intval(($thumbW - $newWidth) / 2);
        $dest_y = intval(($thumbH - $newHeight) / 2);

        if ($originalType === 1) {
            $imgt = "ImageGIF";
            $imgcreatefrom = "ImageCreateFromGIF";
        } else if ($originalType === 2) {
            $imgt = "ImageJPEG";
            $imgcreatefrom = "ImageCreateFromJPEG";
        } else if ($originalType === 3) {
            $imgt = "ImagePNG";
            $imgcreatefrom = "ImageCreateFromPNG";
        } else {
            return false;
        }
        $old_image = $imgcreatefrom($filePath);
        $new_image = imagecreatetruecolor($thumbW, $thumbH); // creates new image, but with a black background

        // figuring out the color for the background
        if(is_array($background) && count($background) === 3) {
            list($red, $green, $blue) = $background;
            $color = imagecolorallocate($new_image, $red, $green, $blue);
            imagefill($new_image, 0, 0, $color);
            // apply transparent background only if is a png image
        } else if($background === 'transparent' && $originalType === 3) {
            imagesavealpha($new_image, TRUE);
            $color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
            imagefill($new_image, 0, 0, $color);
        }
        imagecopyresampled($new_image, $old_image, $dest_x, $dest_y, 0, 0, $newWidth, $newHeight, $originalW, $originalH);
        $imgt($new_image, $thumbPath);
        #return file_exists($thumbPath);
        return basename($thumbPath);
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