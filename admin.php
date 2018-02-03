<?php
session_start();
/**
* Created by PhpStorm.
* User: Omotola
* Date: 08/11/2017
* Time: 00:20
*/
$view = new stdClass();
$view->pageTitle = 'Listing';

require_once('Views/admin.phtml');