<?php

namespace Brander\Custom\FastFood\WebService;

class GetStreets extends \Brander\Custom\FastFood\WebService
{

    public function get($cityCode = '')
    {
        return $this->getService('GetStreets', ['CityCode'=>$cityCode]);
    }
    
}
