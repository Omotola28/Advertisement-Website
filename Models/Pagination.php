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

    public function getData($limit, $page){
        $this->_limit = $limit;
        $this->_page = $page;


        //echo ( ( $this->_page - 1 ) * $this->_limit );die;
        //create the query, limiting records from page, to limit
        $this->_row_start = ( ( $this->_page - 1 ) * $this->_limit );
        $this->query = $this->_query .
            //add to original query: ( minus one because of the way SQL works )
                " LIMIT {$this->_row_start}, $this->_limit";

        $result = $this->_dbConnection->prepare($this->query);
        print_r($result);
        $result->execute();
        if($result->rowCount()== 0){
            $_SESSION['errorM'] = "There was no result found";
        }

        $results = [];
        while ( $row = $result->fetch() ) {
            //store this array in $result[] below
            $results[]  = new ProductData($row);
        }
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

        echo '$total: ' . $this->_total . ' | '; //total rows
        echo '$row_start: ' . $this->_row_start . ' | '; //total rows
        echo '$limit: ' . $this->_limit . ' | '; //total rows per query
        echo '$start: ' . $start . ' | '; //start printing links from
        echo '$end: ' . $end . ' | '; //end printing links at
        echo '$last: ' . $last . ' | '; //last page
        echo '$page: ' . $this->_page . ' | '; //current page
        echo '$links: ' . $links . ' <br /> '; //links

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