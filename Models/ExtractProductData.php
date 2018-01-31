<?php
/**
 * Created by PhpStorm.
 * User: Omotola
 * Date: 23/12/2017
 * Time: 23:39
 */
require_once 'Models/Database.php';
class ExtractProductData
{
    protected $_dbConnection, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();
    }

    public function fetchAll() {

        if(isset($_POST['apply'])){
            $category = $_POST['category'];
            $location = $_POST['location'];
            $maxPrice = $_POST['maxNo'];
            $minPrice = $_POST['minNo'];
            $color = $_POST['colorCategory'];
            $size = $_POST['sizeCategory'];
            $search = preg_replace('#[^a-z 0-9?!-]#i', '',$_POST['search']);
            if(isset($_POST['category']) && $category != "Select Category"){
                $sqlQuery = "SELECT distinct  productsID,category, productTitle, productDes, currency, price,
             productCol,productSize,productImg,publishDate,products.sellerID,firstName,surName,email,country, state FROM products, users, address 
             WHERE  category like '%$category%' 
             and country like '%$location%'
             and (price >= $minPrice and price <= $maxPrice)
             and productCol like '%$color%'
             and (productSize like $size or productSize is null)
             and (users.usersID = products.sellerID )
		     and (users.usersID = address.userID) 
             and (productDes like '%$search%' or productTitle like '%$search%')";

            }else{
                $sqlQuery = "SELECT * FROM products, users, address WHERE (products.sellerID = users.usersID)
                         AND (users.usersID = address.userID) AND productTitle LIKE '%$search%' OR productDes LIKE '%$search'";
            }

        }else{
            $sqlQuery = 'SELECT productsID,category, productTitle, productDes, currency, price,
             productCol,productSize,productImg,publishDate,products.sellerID,firstName,surName,email,country, state FROM products, users, address WHERE 
             (products.sellerID = users.usersID) AND (users.usersID = address.userID)ORDER BY products.sellerID';
        }


        return $sqlQuery;
    }

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

}