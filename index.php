<?php
spl_autoload_register(function ($class) {
    require_once str_replace('\\', '/', $class).'.php';
});

$cities = new \Brander\Custom\FastFood\WebService\GetCities;
print_r($cities->get());
$cities = new \Brander\Custom\FastFood\WebService\GetStreets;
print_r($cities->get(100000000));
