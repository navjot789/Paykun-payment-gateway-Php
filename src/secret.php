<?php
/**
 *  Parameters requires to initialize an object of Payment are as follow.
 *  mid => Merchant Id provided by Paykun
 *  accessToken => Access Token provided by Paykun
 *  encKey =>  Encryption provided by Paykun
 *  isLive => Set true for production environment and false for sandbox or testing mode
 *  isCustomTemplate => Set true for non composer projects, will disable twig template
 */
$obj = new \Paykun\Checkout\Payment('YOUR-MID-HERE', 'API-TOKEN', 'API-SECRET', false, true);

?>