<?php
/**
 * Created by PhpStorm.
 * User: Omotola
 * Date: 23/12/2017
 * Time: 23:20
 */

class ProductData
{
    protected $product_id, $category, $productTitle, $productDes,$currency, $price,$productColor, $productSize,
    $productImg,$publishDate, $seller_id, $fName,$lName, $email, $country, $state, $phoneNumber;

    public function __construct($dbRow) {
        $this->product_id = $dbRow['productsID'];
        $this->category = $dbRow['category'];
        $this->productTitle= $dbRow['productTitle'];
        $this-> productDes = $dbRow['productDes'];
        $this->currency = $dbRow['currency'];
        $this->price=$dbRow['price'];
        $this->productColor = $dbRow['productCol'];
        $this-> productSize = $dbRow['productSize'];
        $this->productImg= $dbRow['productImg'];
        $this->publishDate = $dbRow['publishDate'];
        $this-> seller_id = $dbRow['sellerID'];
        $this->fName= $dbRow['firstName'];
        $this->lName = $dbRow['surName'];
        $this->email=$dbRow['email'];
        $this->country= $dbRow['country'];
        $this->state=$dbRow['state'];
        $this->phoneNumber=$dbRow['phonenumber'];

    }


    public function getProductId()
    {
        return $this->product_id;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getTitle() {
        return $this->productTitle;
    }

    public function getDescription() {
        return $this->productDes;
    }

    public function getCurrency() {
        return $this->currency;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getColor() {
        return $this->productColor;
    }

    public function getSize() {
        return $this->productSize;
    }

    public function getImg() {
        return $this->productImg;
    }

    public function getSellerId() {
        return $this->seller_id;
    }

    public function getDate()
    {
        return $this->publishDate;
    }

    public function getfName()
    {
        return $this->fName;
    }

    public function getlName()
    {
        return $this->lName;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getPhoneNo()
    {
        return $this->phoneNumber;
    }
}