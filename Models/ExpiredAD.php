<?php
/**
 * Created by PhpStorm.
 * User: Omotola
 * Date: 30/01/2018
 * Time: 19:17
 */

require_once 'Models/Database.php';
class ExpiredAD
{

    protected $_dbConnection, $_dbInstance;

    public function __construct() {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();
    }

    /**
     *
     * Deletes ads that have been displayed for up to 14 days
     */
    public function expiredAd(){
        $now = time(); // or your date as well
        $query = "SELECT publishDate FROM products INNER JOIN users ON users.usersID = products.sellerID";
        $result = $this->_dbConnection->query($query);
        $result->execute();

        while ( $row = $result->fetch() ) {
            $adDate = strtotime($row['publishDate']); //take each date from the result
            $dateformat = date('Y-m-d', $adDate);
            $datediff = $now - $adDate;
            $duration = floor($datediff / (60 * 60 * 24));

            //if the item has been there for 14 or more days items should be deleted
            if($duration >= 14){
                $delQuery = "DELETE FROM products WHERE publishDate = '$dateformat'";
                $result = $this->_dbConnection->query($delQuery);
                $result->execute();

                $delQuery1 = "Delete from WishList where wishDate = '$dateformat'";
                $result = $this->_dbConnection->query($delQuery1);
                $result->execute();
            }
        }

    }
}