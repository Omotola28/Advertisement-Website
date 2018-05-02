<?php
/**
 * Created by PhpStorm.
 * User: Omotola
 * Date: 23/12/2017
 * Time: 23:39
 */
require_once 'Models/Database.php';
require_once 'Models/ProductData.php';
class ExtractProductData
{
    protected $_dbConnection, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();
    }

    /**
     * fetchAll displays the list of items on the listings page
     * @return string of the query to display the list of items on the website
     */

    public function fetchAll()
    {

        if (isset($_POST['applyBtn'])) {
            $category = $_POST['filterCat'];
            $location = $_POST['loc'];
            $maxPrice = $_POST['maxP'];
            $minPrice = $_POST['minP'];
            $color = $_POST['colorCat'];
            if(!isset($_POST['sizeCat']) ){
                $size = 'null';
            }else
                $size = $_POST['sizeCat'];
            $search = $this->test_input($_POST['searchFilter']);
            //$search = preg_replace('#[^a-z 0-9?!-]#i', '', $_POST['searchFilter']);

            $sqlQuery = "SELECT DISTINCT  productsID,category, productTitle, productDes, currency, price,
                          productCol,productSize,productImg,publishDate,products.sellerID,fullName,email,phonenumber,country, state FROM products, users, address ";
            $condition = []; //create a an array of conditions to be added to search query

            if($category != ""){
                $condition[] = "category='$category'";
            }
            if($location != ""){
                $condition[] = "country='$location'";
            }
            if($maxPrice != ""){
                $condition[] = "price<='$maxPrice'";
            }
            if($minPrice != ""){
                $condition[] = "price>='$minPrice'";
            }
            if($color != ""){
                $condition[] = "productCol='$color'";
            }
            if($size != "" && $size != 'null'){
                $condition[] = "productSize='$size'";
            }
           /* if($search != "" && $search !='%%'){
                $condition[] = "productDes like '%$search%'";
            }*/
            if($search != "" && $search !='%%'){
                $condition[] = "productTitle like '%$search%'";
            }

            if (count($condition) > 0) {
                $sqlQuery .= ' WHERE ' . implode(' OR ', $condition). ' AND products.sellerID = users.usersID 
                AND users.usersID = address.userID';
            }
            return $sqlQuery;
        }else{
            //query that runs if the search bar is not being used
            $sqlQuery = 'SELECT DISTINCT productsID,category, productTitle, productDes, currency, price,
             productCol,productSize,productImg,publishDate,products.sellerID,fullName,email,phonenumber,country, state FROM products, users, address WHERE 
             (products.sellerID = users.usersID) AND (users.usersID = address.userID)ORDER BY products.productsID ASC';

            return $sqlQuery;
        }


    }

    /**
     * @param $users takes userId for the seller inorder to display their products
     * @return array of products belonging to a particular seller
     */
    public function sellerProduct($users){
        $sql = "SELECT *
                FROM users
                INNER JOIN products ON products.sellerID= users.usersID
                INNER JOIN address ON address.userID = users.usersID
                AND users.usersID = $users;";
        $result = $this->_dbConnection->prepare($sql);
        $result->execute();

        $results = [];
        while ( $row = $result->fetch() ) {
            //store this array in $result[] below
            $results[]  = $row;
        }
        return $results;
    }

    /**
     * @param $sql
     * @return array an array of the results
     */
    public function executeQuery($sql){
        $result = $this->_dbConnection->prepare($sql);
        $result->execute();
        if ($result->rowCount() == 0)
            echo 'No products listed';
        $results = [];
        while ( $row = $result->fetch() ) {
            //store this array in $result[] below
            $results[]  = new ProductData($row);
        }
        return $results;
    }

    /**
     * @param $id of item to be deleted by admin from database
     */
    public function adminDeleteAd($id){
        $query = "DELETE FROM products WHERE productsID = $id";
        $result = $this->_dbConnection->prepare($query);
        $result->execute();

        $query = "DELETE FROM WishList WHERE wishID = $id";
        $result = $this->_dbConnection->prepare($query);
        $result->execute();
        header("location: admin.php");

    }


    public function liveSearch($str)
    {
        $data = [];
        $str = $this->test_input($str);
        $len = strlen($str);
        if ($len >=4 && $str !== "" && $len < 5) {
            $query = "SELECT productTitle, thumbImg, productsID
                      FROM products
	                  where productTitle like '%" . $str . "%' LIMIT 0,5";
            $result = $this->_dbConnection->prepare($query);
            $result->execute();
            if($result->rowCount() === 0){
                echo 'no suggestions';
                exit();
            }else{

                while ($row = $result->fetch(PDO::FETCH_ASSOC) ){
                    //$data[] = ['title' => $row['productTitle']];
                    //preg_split("/[,.-]+/", $row['productTitle']);
                   $data[] = ['title' => $row['productTitle'], 'thumbNail'=> $row['thumbImg'], 'ID' => $row['productsID']];
                }

                echo json_encode($data);
                exit();
            }

        }
        else{
            echo '';
            exit();
        }
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = preg_replace("#[']#i", '', $data);
        return $data;
    }
}