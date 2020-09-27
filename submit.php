<?php
ob_start();
session_start();

require 'src/Payment.php';
require 'src/Validator.php';
require 'src/Crypto.php';

/**
echo '<pre>';
var_dump($_POST);
var_dump($_SESSION);
echo '<pre>';
*/

$fname          = $_SESSION['total_elements'][0];
$product_name   = $_POST['product_name'];
$email          = $_SESSION['total_elements'][1];
$amount         = $_POST['amount'];
$contact        = $_SESSION['total_elements'][2];
$country        = $_SESSION['total_elements'][3];
$state          = $_SESSION['total_elements'][4];
$city           = $_SESSION['total_elements'][5];
$postalcode     = $_SESSION['total_elements'][6];
$address        = $_SESSION['total_elements'][7];


/**
 *  Parameters requires to initialize an object of Payment are as follow.
 *  mid => Merchant Id provided by Paykun
 *  accessToken => Access Token provided by Paykun
 *  encKey =>  Encryption provided by Paykun
 *  isLive => Set true for production environment and false for sandbox or testing mode
 *  isCustomTemplate => Set true for non composer projects, will disable twig template
 */

//creating object
include_once "src/secret.php";

//redirection site url
$successUrl = str_replace("index.php","index.php", $_SERVER['HTTP_REFERER']);
$failUrl 	= str_replace("index.php","index.php", $_SERVER['HTTP_REFERER']);

// Initializing Order
$obj->initOrder(generateByMicrotime(), $product_name,  $amount, $successUrl,  $failUrl, 'INR');

// Add Customer
$obj->addCustomer($fname, $email, $contact);

// Add Shipping address
$obj->addShippingAddress('', '', '', '', '');

$obj->addBillingAddress('', '', '', '', '');
// Add Billing Address

//Enable if require custom fields
$obj->setCustomFields(array('udf_1' => 'Some Dummy text'));
//Render template and submit the form
echo $obj->submit();

/* Check for transaction status
 * Once your success or failed url called then create an instance of Payment same as above and then call getTransactionInfo like below
 *  $obj = new Payment('merchantUId', 'accessToken', 'encryptionKey', true, true); //Second last false if sandbox mode
 *  $transactionData = $obj->getTransactionInfo(Get payment-id from the success or failed url);
 *  Process $transactionData as per your requirement
 *
 * */


function generateByMicrotime() {
    $microtime = str_replace('.', '', microtime(true));
    return (substr($microtime, 0, 14));
}
?>