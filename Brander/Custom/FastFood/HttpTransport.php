<?php
namespace Brander\Custom\FastFood;


class HttpTransport {

    const TRY_COUNT = 10;

    private $baseUrl;

    public function __construct($baseUrl = 'http://yaposhkacent.ddns.net:5000/FastOperator.asmx/')
    {
        $this->baseUrl = $baseUrl;
    }

    private function send($path, $postData) {
      $cURL = curl_init();
      curl_setopt($cURL, CURLOPT_URL, $this->baseUrl . $path);
      curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($cURL, CURLOPT_POST, true);
      curl_setopt($cURL, CURLOPT_POSTFIELDS, $this->postify($postData));
      try {
          $response = curl_exec($cURL);
          $info = curl_getinfo($cURL);
          curl_close($cURL);
      } catch (Exception $ex) {
        return [
          'status' => 'error',
          'message' => $ex->getMessage(),
          'error' => $ex
        ];
      }
      return [
        'status' => 'OK',
        'response' => $response,
        'info' => $info,
      ];
    }

    public function post($path, $postData = []) {
      for ($i = 1; $i < self::TRY_COUNT; ++$i) {
          $output = $this->send($path, $postData);
          if ($output['status'] === 'OK') {
              break;
          }
      }
      return $output;
    }

    private function postify($array) {
      $string = '';
      $i = 0;
      foreach ($array as $key => $value) {
        if ($i++ > 0) {
          $string .= '&';
        }
        $string .= rawurlencode($key).'='.rawurlencode($value);
      }
      return $string;
    }

}
