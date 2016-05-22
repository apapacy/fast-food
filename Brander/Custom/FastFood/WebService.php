<?php

namespace Brander\Custom\FastFood;

use DOMDocument;

abstract class WebService
{
    protected $httpTransport;
    protected $arrayTag = ['Item'];
    protected $domTag = ['string'];


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

        return $this->parse($dom, $options);
    }

    private function parse(&$node, $options = [], $collection = false, &$parent = [], $level = 0)
    {
      foreach ($node->attributes as $attribute) {
          $parent[$attribute->nodeName] = $attribute->nodeValue;
      }
        foreach ($node->childNodes as $child) {
            if ($child->hasChildNodes()) {
                if (in_array($child->nodeName, $this->domTag)) {
                  $this->parse($child, $options, false, $parent, $level + 1);
                } else if (in_array($child->nodeName, $this->arrayTag) || $level === 0) {
                    $this->parse($child, $options,true, $parent[], $level + 1);
                } else {
                    $this->parse($child, $options, true, $parent[$child->nodeName], $level + 1);
                }
            } else {
                if ($child->nodeName === '#text') {
                    if (in_array($node->nodeName, $this->domTag)) {
                        $this->parse(DOMDocument::loadXML($child->nodeValue), $options, false, $parent, 0);
                    } else {
                        $parent = $child->nodeValue;
                    }
                } else {
                    $item = [];
                    foreach ($child->attributes as $attribute) {
                        $item[$attribute->nodeName] = $attribute->nodeValue;
                    }
                    if (in_array($child->nodeName, $this->arrayTag) || $level === 0) {
                        $parent[] = $item;
                    } else {
                        $parent[$child->nodeName] = $item;
                    }
                }
            }
        }
        if ($collection) {
          return $parent;
        }  else {
          return $parent[0];
        }
    }

}
