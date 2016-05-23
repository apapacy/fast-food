<?php

namespace Brander\Custom\FastFood;

use DOMDocument;

abstract class WebService
{
    protected $httpTransport;
    protected $arrayTag = ['Item', 'Category', 'Address', 'Phone', 'Customer'];
    protected $domTag = ['string'];
    protected $singleTag = ['#document', 'string', 'Menu', 'Path', 'Items', 'xml', 'Addresses', 'Phones'];

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
        if ($level > 1000) {
          throw new \Exception('too many reccursion');
        }
        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attribute) {
                if (!in_array($node->nodeName, $this->arrayTag) && !in_array($node->nodeName, $this->singleTag)) {
                    $parent[$node->nodeName][$attribute->nodeName] = $attribute->nodeValue;
                } else {
                    $parent[$attribute->nodeName] = $attribute->nodeValue;
                }
            }
        }
        if (!$node->hasChildNodes()) {

            return $parent;
        } else {
            foreach ($node->childNodes as $child) {
                if (in_array($node->nodeName, $this->domTag) && $child->nodeName === '#text') {
                    $child = DOMDocument::loadXML($child->nodeValue);
                }
                if ($child->nodeName === '#text') {
                    continue;
                }
                if (in_array($node->nodeName, $this->arrayTag) && !in_array($child->nodeName, $this->singleTag)) {
                  if ($child->nodeName === 'Category') {
                    $parent['Categories'][] = $this->parse($child, $level + 1);
                  } else {
                    $parent[] = $this->parse($child, $level + 1);
                  }
                } elseif (in_array($node->nodeName, $this->arrayTag) && in_array($child->nodeName, $this->singleTag)) {
                    $parent[$child->nodeName] = $this->parse($child, $level + 1)[$child->nodeName];
                } elseif (in_array($child->nodeName, $this->singleTag)) {
                    $parent[$node->nodeName][$child->nodeName] = $this->parse($child, $level + 1)[$child->nodeName];//[$child->nodeName];
                } else {
                  if ($child->nodeName === 'Category') {
                    $parent[$node->nodeName]['Categories'][] = $this->parse($child);
                  } else {
                    $parent[$node->nodeName][] = $this->parse($child);
                  }
                }
            }
        }

        return $parent;
    }
}
