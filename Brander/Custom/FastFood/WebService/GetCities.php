<?php

namespace Brander\Custom\FastFood\WebService;

class GetCities extends \Brander\Custom\FastFood\WebService
{
    /**
     * Справочник городов (массив объектов).
     *
     * @return array[ object{ Code => integer, Name => string}, ...]
     */
    public function get()
    {
        $response = $this->httpTransport->post('GetCities');

        return parent::get($response['response']);
    }
}
