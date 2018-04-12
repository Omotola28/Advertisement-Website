<?php
/**
 * Created by PhpStorm.
 * User: Omotola
 * Date: 12/12/2017
 * Time: 11:38
 */
require_once 'Models/Database.php';

class CustomerData
{
    protected $_dbConnection, $_dbInstance;

    public function __construct()
    {
        $this->_dbInstance = Database::getInstance();
        $this->_dbConnection = $this->_dbInstance->getDbConnection();
    }

    /**
     * Inserts userinfo into the database table users and address these two tables
     * are linked by foreign key in the users tables
     */
    public function insertIntoDB()
    {
            $full_N = $_POST['name'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $hash = md5(rand(0, 1000));
            $phoneNo = $_POST['no'];
            $address1 = $_POST['addr1'];
            $address2 = $_POST['addr2'];
            $state = $_POST['state'];
            $country = $_POST['country'];
            $captcha = $_POST['captchaTxt'];
            $_SESSION['register'] = $_POST;

            // Check if user with that email already exists
            $sql = "SELECT * FROM users WHERE email='$email' OR phonenumber = '$phoneNo'";
            $result = $this->_dbConnection->prepare($sql);
            $result->execute();

            // We know user email exists if the rows returned are more than 0
            if ($result->rowCount() > 0) {
                echo 'User with this email/phoneNo already exists!';
                //header("location: register.php");
                exit();
            } else {
                if($captcha != $_SESSION['captcha']){
                    echo "Captcha text entered wrong";
                    exit();
                }
                else {
                    $sqlQuery1 = "INSERT INTO users (fullName,email, password,hash, phonenumber) VALUES ('$full_N','$email',
                        '$password','$hash','$phoneNo')";
                    $statement = $this->_dbConnection->prepare($sqlQuery1); // prepare a PDO statement
                    $statement->execute(); // execute the PDO statement
                    $last_id = $this->_dbConnection->lastInsertId();

                    $sqlQuery2 = "INSERT INTO address (addressLine1, addressLine2, country, state, userID) VALUES ('$address1',
                        '$address2', '$country', '$state',$last_id)";
                    $statement1 = $this->_dbConnection->prepare($sqlQuery2);
                    $statement1->execute();
                    echo 'Successfully registered';
                    exit();
                }
            }
    }

    /**
     * Logs in users into the advertisement system, checks and verifies password
     * if valid users is logged in and session is saved some user information like userid
     * and user firstName. If credentials do not match error is thrown
     */
    public function loginUser(){
            $email = $_POST['emailInput'];
            $pwd = $_POST['inputPwd'];
            $_SESSION['login'] = $_POST;

            // validate user input
            $sql = "SELECT * FROM users, address WHERE email='$email' and 
                    users.usersID = address.userID";
            $result = $this->_dbConnection->prepare($sql);
            $result->execute();
            if ($result->rowCount() == 0) {
                echo 'User does not exist';
                $_SESSION['logged_in'] = false;
                $_SESSION['login'] = $_POST;
                exit();
            }else{
                $user = $result->fetch(PDO::FETCH_ASSOC);
                if (password_verify($pwd, $user['password']) ) {
                    if($user['userRole']=== 'admin-false'){
                        echo 'success';
                        $_SESSION['fullName'] = $user['fullName'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['user_id'] = $user['usersID'];
                        $_SESSION['country'] = $user['country'];
                        $_SESSION['state'] = $user['state'];
                        $_SESSION['logged_in'] = true;
                        exit();
                    }else{
                        echo 'admin';
                        $_SESSION['fullName'] = $user['fullName'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['user_id'] = $user['usersID'];
                        $_SESSION['userRole'] = $user['userRole'];
                        $_SESSION['logged_in'] = true;
                        exit();
                    }

                }else{
                    echo "You have entered wrong password, try again!";
                    $_SESSION['logged_in'] = false;
                    $_SESSION['login'] = $_POST;
                    exit();
                }

                }
    }

    /**
     * @param $userId used to retrieved specific user information from the database
     * @return array of the results found from database
     * results is displayed in user account section
     */
    public function getUserInfo($userId){

        $sql = "SELECT * FROM users INNER JOIN address ON users.usersID=address.userID and usersID = $userId;";
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
     * @param $userID used to update specific user information in the database
     */
    public function updateInfo($userID){
            $fname = $_POST['fullName'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $hash = md5(rand(0, 1000));
            $number = $_POST['phoneNumber'];
            $addressL1 = $_POST['addressLine1'];
            $addressL2 = $_POST['addressLine2'];
            $country = $_POST['country'];
            $state = $_POST['state'];


            $sql = "update users, address set fullName = '$fname', email = '$email',password ='$password',
                    hash = '$hash', phonenumber ='$number', addressLine1 = '$addressL1', addressLine2 = '$addressL2', 
                    country = '$country', state = '$state' where users.usersID = $userID and address.userID = $userID";
            $result = $this->_dbConnection->query($sql);
            $result->execute();

    }

    public function deleteUser($userID){
        $sql = "delete from users where users.usersID = $userID";
        $result = $this->_dbConnection->query($sql);
        $result->execute();

        echo '<script>window.location="index.php?action=logout"</script>';

    }

}