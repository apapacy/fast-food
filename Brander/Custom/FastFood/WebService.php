<?php

namespace Brander\Custom\FastFood;

use DOMDocument;

abstract class WebService
{
    protected $httpTransport;
    protected $arrayTag = ['Item', 'Category', 'Address', 'Phone', 'Customer'];
    protected $domTag = ['string'];
    protected $singleTag = [];//['#document', 'string', 'Menu', 'Path', 'Items', 'xml', 'Addresses', 'Phones'];

    public function __construct()
    {
        $this->httpTransport = new HttpTransport();
    }

    protected function parseModel()
    {
    }

    protected function parseCollection($response, $options = [])
    {
        $dom = DOMDocument::loadXML($response);

        return $this->parse($dom);
    }

    private function parse(&$node, $level = 0)
    {
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
            //$parent[$node->nodeName]['#value'] = $node->nodeValue;

            return $parent;
        } else {
            foreach ($node->childNodes as $child) {
                if ($node->nodeName === 'string' && $child->nodeName === '#text') {
                    $child = DOMDocument::loadXML($child->nodeValue);
                }
                if ($child->nodeName === '#text') {
                    continue;
                }
                if (in_array($node->nodeName, $this->arrayTag)) {
                    $parent[] = $this->parse($child);
                } else {
                    $parent[$node->nodeName][] = $this->parse($child);
                }
            }
        }

        return $parent;
    }
}
