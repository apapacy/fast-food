<?php
spl_autoload_register(function ($class) {
    require_once str_replace('\\', '/', $class).'.php';
});


\Brander\Custom\FastFood\HttpTransport::setBaseUrl('http://yaposhkacent.ddns.net:5000/FastOperator.asmx/');


$service = new \Brander\Custom\FastFood\WebService\GetCities;
file_put_contents('cities.txt', print_r($service->get(), true));
die();
$cities = new \Brander\Custom\FastFood\WebService\GetCustomer;
//print_r($cities->get('test000@brander.com'));
$cities = new \Brander\Custom\FastFood\WebService\GetStreets;
//print_r($cities->get(100000000));
$cities = new \Brander\Custom\FastFood\WebService\GetMenu;
var_dump($cities->get());
