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
        if (isset($_POST['apply'])) {
            $category = $_POST['category'];
            $location = $_POST['location'];
            $maxPrice = $_POST['maxNo'];
            $minPrice = $_POST['minNo'];
            $color = $_POST['colorCategory'];
            if(!isset($_POST['sizeCategory']) ){
                $size = 'null';
            }else
                $size = $_POST['sizeCategory'];
            $search = preg_replace('#[^a-z 0-9?!-]#i', '', $_POST['search']);


            $sqlQuery = "SELECT DISTINCT  productsID,category, productTitle, productDes, currency, price,
                          productCol,productSize,productImg,publishDate,products.sellerID,fullName,surName,email,phonenumber,country, state FROM products, users, address ";
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
            if($search != "" && $search !='%%'){
                $condition[] = "productDes like '%$search%'";
            }
            if($search != "" && $search !='%%'){
                $condition[] = "productTitle like '%$search%'";
            }

            if (count($condition) > 0) {
                $sqlQuery .= ' WHERE ' . implode(' AND ', $condition). ' AND products.sellerID = users.usersID 
                AND users.usersID = address.userID';
            }
            echo $sqlQuery;
        }else{
            //query that runs if the search bar is not being used
            $sqlQuery = 'SELECT DISTINCT productsID,category, productTitle, productDes, currency, price,
             productCol,productSize,productImg,publishDate,products.sellerID,fullName,surName,email,phonenumber,country, state FROM products, users, address WHERE 
             (products.sellerID = users.usersID) AND (users.usersID = address.userID)ORDER BY products.productsID ASC';
        }
        return $sqlQuery;
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
        $result = $this->_dbConnection->query($sql);
        $result->execute();

        $results = [];
        while ( $row = $result->fetch() ) {
            //store this array in $result[] below
            $results[]  = $row;
        }
        return $results;
    }

    /**
     * @param $query to be executed
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



}