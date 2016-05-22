<?php

namespace Brander\Custom\FastFood\WebService;

class GetStreets extends \Brander\Custom\FastFood\WebService
{
    public function get($cityCode = '')
    {
        $response = $this->httpTransport->post('GetStreets', ['CityCode'=>$cityCode]);
        return $this->parseCollection($response['response']);
    }
}
