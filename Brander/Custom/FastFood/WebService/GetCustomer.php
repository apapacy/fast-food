<?php

namespace Brander\Custom\FastFood\WebService;



class GetCustomer extends \Brander\Custom\FastFood\WebService
{

    //protected $arrayTag = ['Address', 'Phone','Customer'];
    //protected $singleTag = ['Phones'];
    //protected $path = ['#document', 'string', '#document']

    public function get($login = '', $phone = '' )
    {
        return $this->getService('GetCustomer', ['Login'=>$login, 'Phone' => $phone]);
    }
}
