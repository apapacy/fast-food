<?php

namespace Brander\Custom\FastFood;

use DOMDocument;
use Exception;

abstract class WebService
{
    protected $httpTransport;
    protected $arrayTag = ['Item', 'Category', 'Address', 'Phone', 'Customer'];
    protected $arrayGroup = ['Category' => 'Categories'];
    protected $domTag = ['string'];
    protected $singleTag = ['#document', 'string', 'Menu', 'Path', 'Items', 'xml', 'Addresses', 'Phones'];
    protected $path = ['#document', 'string', '#document', 'xml'];

    public function __construct()
    {
        $this->httpTransport = new HttpTransport();
    }

    /**
     * Для операций связаных з обновлением данных на сервере задается одна попытка.
     */
    protected function getServiceOnce($serviceName, $options = [])
    {
        return $this->getService($serviceName, $options, 1);
    }

    protected function getService($serviceName, $options = [])
    {
        $service = $this->httpTransport->post($serviceName, $options);
        file_put_contents('soap.log', print_r($service, true), FILE_APPEND);
        if ($service['status'] !== 'OK' || $service['info']['http_code'] !== 200 || !$service['response']) {
            return false;
        }
        $dom = DOMDocument::loadXML($service['response'], LIBXML_NOWARNING);
        $array = $this->parse($dom);
        file_put_contents('soap.log', print_r($array, true), FILE_APPEND);
        foreach ($this->path as $property) {
            if (array_key_exists($property, $array)) {
                $array = $array[$property];
            } else {
                //return false;
                throw new Exception("array property '$property' is not set");
            }
        }

        return $array;
    }

    private function parse(&$node, $level = 0)
    {
        if ($level > 1000) {
            throw new Exception('too many reccursion');
        }
        $parent = [];
        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attribute) {
                if (!in_array($node->nodeName, $this->arrayTag)) {
                    $parent[$node->nodeName][$attribute->nodeName] = $attribute->nodeValue;
                } else {
                    $parent[$attribute->nodeName] = $attribute->nodeValue;
                }
            }
        }
        if (!$node->hasChildNodes()) {
            if (in_array($node->nodeName, $this->singleTag)
                && !in_array($node->nodeName, $this->arrayTag)
                || in_array($node->nodeName, $this->domTag)
            ) {
                $parent[$node->nodeName] = [];
            }

            return $parent;
        } else {
            foreach ($node->childNodes as $child) {
                if (in_array($node->nodeName, $this->domTag) && $child->nodeName === '#text') {
                    $child = DOMDocument::loadXML($child->nodeValue, LIBXML_NOWARNING);
                }
                if ($child->nodeName === '#text') {
                    continue;
                }
                if (in_array($node->nodeName, $this->arrayTag) && !in_array($child->nodeName, $this->singleTag)) {
                    if (isset($this->arrayGroup[$child->nodeName])) {
                        $parent[$this->arrayGroup[$child->nodeName]][] = $this->parse($child, $level + 1);
                    } else {
                        $parent[] = $this->parse($child, $level + 1);
                    }
                } elseif (in_array($node->nodeName, $this->arrayTag) && in_array($child->nodeName, $this->singleTag)) {
                    $parent[$child->nodeName] = $this->parse($child, $level + 1)[$child->nodeName];
                } elseif (in_array($child->nodeName, $this->singleTag)) {
                    $e = $this->parse($child, $level + 1)[$child->nodeName];
                    $parent[$node->nodeName][$child->nodeName] = $this->parse($child, $level + 1)[$child->nodeName];
                } else {
                    if (isset($this->arrayGroup[$child->nodeName])) {
                        $parent[$node->nodeName][$this->arrayGroup[$child->nodeName]][] = $this->parse($child);
                    } else {
                        $parent[$node->nodeName][] = $this->parse($child);
                    }
                }
            }
        }

        return $parent;
    }
}
