<?php

namespace Brander\Custom\FastFood\WebService;

class GetMenu extends \Brander\Custom\FastFood\WebService
{

    //protected $arrayTag = [];
    //protected $singleTag = ['Phones'];


    public function get($brand = '')
    {
        $response = $this->httpTransport->post('GetMenu', ['Brand' => $brand]);
        return $this->parseCollection($response['response']);
    }
}
