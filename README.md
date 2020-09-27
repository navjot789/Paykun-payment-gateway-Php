# Paykun-payment-gateway-Php
PayKun is an India based payment gateway that provides a hassle-free payment solution to startups, small, medium, and large enterprises. It has become well-known among all kinds of businesses and startups due to its fast onboarding process, free integration on websites/app, fast settlement, lowest pricing rate, and a variety of payment methods. Using the PayKun Android and iOS mobile application or the Desktop Dashboard Login, merchants can easily manage their transactions and monitor the growth of their business.

# Installation
Change the database setting by navigating to dbcontroller.php
````PHP
        private $host     = "localhost";
	private $user     = "username";
	private $password = "password";
	private $database = "database";
````

# Adding TEST API KEY and MID
Note: For testing purpose isLive => false by default. You can change it by navigating to src/Payment.php on line 60
````PHP
public function __construct($mid, $accessToken, $encKey, $isLive = false, $isCustomTemplate = false, $isWebView=true)
````

````PHP
/**
 *  Parameters requires to initialize an object of Payment are as follow.
 *  mid => Merchant Id provided by Paykun
 *  accessToken => Access Token provided by Paykun
 *  encKey =>  Encryption provided by Paykun
 *  isLive => Set true for production environment and false for sandbox or testing mode
 *  isCustomTemplate => Set true for non composer projects, will disable twig template
 */
$obj = new \Paykun\Checkout\Payment('YOUR-MID-HERE', 'API-TOKEN', 'API-SECRET', false, true);
````
To get the Test 
