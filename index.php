<?php
namespace Brander\Custom\FastFood;

spl_autoload_register(function ($class) {
    require_once str_replace('\\', '/', $class).'.php';
});


HttpTransport::setBaseUrl('http://yaposhkacent.ddns.net:5000/FastOperator.asmx/');

$service = new WebService\GetCities;
file_put_contents('cities.txt', print_r($service->get(), true));

$service = new WebService\GetCustomer;
file_put_contents('customers.txt', print_r($service->get('test@brander.com'), true));

$service = new WebService\GetStreets;
file_put_contents('streets.txt', "По коду Полтавы должен бый пустой ответ\n" . print_r($service->get('100000006'), true));
file_put_contents('streets.txt', print_r($service->get(), true), FILE_APPEND);
file_put_contents('streets.txt', print_r($service->get('100000000'), true), FILE_APPEND);

$service = new WebService\GetMenu;
file_put_contents('menu.txt', print_r($service->get(), true));
