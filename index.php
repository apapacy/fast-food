<?php
namespace Brander\Custom\FastFood;

spl_autoload_register(function ($class) {
    require_once str_replace('\\', '/', $class).'.php';
});


HttpTransport::setBaseUrl('http://yaposhkacent.ddns.net:5000/FastOperator.asmx/');

$service = new WebService\GetCities;
file_put_contents('cities.txt', print_r($service->get(), true));
$service = new WebService\GetCustomer;
file_put_contents('customers.txt', print_r($service->get('test00@brander.com'), true));
die();
$cities = new WebService\GetStreets;
//print_r($cities->get(100000000));
$cities = new WebService\GetMenu;
var_dump($cities->get());
