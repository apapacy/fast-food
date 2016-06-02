<?php

namespace Brander\Custom\FastFood\WebService;

class GetCities extends \Brander\Custom\FastFood\WebService
{

    public function get()
    {
        $response = $this->httpTransport->post('GetCities');

        return parent::get($response['response']);
    }
}
