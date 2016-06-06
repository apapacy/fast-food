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
        return $this->getService('GetCities');
    }
}
