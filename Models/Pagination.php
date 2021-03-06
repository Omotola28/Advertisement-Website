<?php
/**
 * Created by PhpStorm.
 * User: Omotola
 * Date: 29/12/2017
 * Time: 11:55
 */
require_once 'Models/Database.php';
require_once 'Models/ProductData.php';


class Pagination
{

    protected $_dbConnection, $_dbInstance;
    private $query;
    private $_limit; //records (rows) to show per page
    private $_page; //current page
    private $_query;
    private $_total;
    private $_row_start;

    public function __construct($query)
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();

        $this->_query = $query;
        $statement = $this->_dbConnection->prepare($this->_query); // prepare a PDO statement
        $statement->execute(); // execute the PDO statement
        $this->_total = $statement->rowCount();
    }

    /**
     * @param $limit on the number of items that can be shown on each page
     * @param $page number of current page
     * @param $status
     * @param $loggedUser
     * @return array
     */
    public function getData($limit, $page, $status, $loggedUser)
    {
        $this->_limit = $limit;
        $this->_page = $page;

        //create the query, limiting records from page, to limit
        $this->_row_start = (($this->_page - 1) * $this->_limit);
        $this->query = $this->_query .
            //add to original query: ( minus one because of the way SQL works )
            " LIMIT {$this->_row_start}, $this->_limit";

        $result = $this->_dbConnection->prepare($this->query);
        $result->execute();
        if ($result->rowCount() == 0) {
            if(isset($_POST['applyBtn'])) {
                echo "There was no result found";
                exit();
            }
        }

        $results = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            //store this array in $result[] below
            //$results[]  = new ProductData($row);
            $results[] =
                ["Title" => $row['productTitle'], "Des" => $row['productDes'],
                    "ID" => $row['productsID'], "currency" => $row['currency'], "price" => $row['price'],
                    "color" => $row['productCol'], "size" => $row['productSize'], "img" => $row['productImg'],
                    "date" => $row['publishDate'], "sellerID" => $row['sellerID'], "fname" => $row['fullName'],
                    "email" => $row['email'], "country" => $row['country'], "state" => $row['state'],
                    "phoneNo" => $row['phonenumber'], "loginStatus" => $status, "loggedUserID" => $loggedUser];

        }
        if(isset($_POST['applyBtn'])){
            echo json_encode($results);
            exit();
        }else
            return $results;

    }

    public function createLinks( $links, $list_class )
    {
        //get the last page number
        $last = ceil($this->_total / $this->_limit);

        //calculate start of range for link printing
        $start = (($this->_page - $links) > 0) ? $this->_page - $links : 1;

        //calculate end of range for link printing
        $end = (($this->_page + $links) < $last) ? $this->_page + $links : $last;

        //ul boot strap class - "pagination pagination-sm"
        $html = '<ul class="' . $list_class . '">';

        $class = ( $this->_page == 1 ) ? "disabled" : ""; //disable previous page link <<<

        //create the links and pass limit and page as $_GET parameters

        //$this->_page - 1 = previous page
        $previous_page = ( $this->_page == 1 ) ?
            '<a href=""><li class="' . $class . '">&laquo;</a></li>' : //remove link from previous button
            '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . ( $this->_page - 1 ) . '">&laquo;</a></li>';

        $html .= $previous_page;

        if ( $start > 1 ) { //print ... before (previous <<< link)
            $html .= '<li><a href="?limit=' . $this->_limit . '&page=1">1</a></li>'; //print first page link
            $html .= '<li class="disabled"><span>...</span></li>'; //print 3 dots if not on first page
        }
        for ( $i = $start ; $i <= $end; $i++ ) {
            $class = ( $this->_page == $i ) ? "active" : ""; //highlight current page
            $html .= '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . $i . '">' . $i . '</a></li>';
        }

        if ( $end < $last ) { //print ... before next page (>>> link)
            $html .= '<li class="disabled"><span>...</span></li>'; //print 3 dots if not on last page
            $html .= '<li><a href="?limit=' . $this->_limit . '&page=' . $last . '">' . $last . '</a></li>'; //print last page link
        }

        $class = ( $this->_page == $last ) ? "disabled" : ""; //disable (>>> next page link)

        //$this->_page + 1 = next page (>>> link)
        $next_page = ( $this->_page == $last) ?
            '<li class="' . $class . '"><a href="">&raquo;</a></li>' : //remove link from next button
            '<li class="' . $class . '"><a href="?limit=' . $this->_limit . '&page=' . ( $this->_page + 1 ) . '">&raquo;</a></li>';

        $html .= $next_page;
        $html .= '</ul>';

        return $html;
    }
}