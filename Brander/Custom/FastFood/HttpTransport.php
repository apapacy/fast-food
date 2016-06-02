<?php

namespace Brander\Custom\FastFood;

use Exception;

class HttpTransport
{
    /**
     * Количество попыток соединения
     */
    const TRY_COUNT = 10;
    private $url = null;
    private static $baseUrl;

    /**
     * Адрес сервиса. Может быть переопределен в конструкторе
     * Может вызываться многократно с разными адресами.
     */
    public static function setBaseUrl($url)
    {
        static::$baseUrl = $url;
        return $url;
    }

    /**
     * @conctructor
     * @param string $uri если не задан определяется статическим методом setBaseUrl($url)
     */
    public function __construct($url = false)
    {
        if (!$url) {
            $this->url = static::$baseUrl;
        } else {
            $this->url = $url;
        }
    }

    /**
     * Отправляет POST запрос с использованием библиотеки curl.
     * При реализации защищенного соединения необходимо указать парамептры доступа (пользователь, пароль, сертификат и т.п.)
     * Для успешных http - запросов возвращает объект
     * @return  array
     * @property string status ==='OK'
     * @property string response ответ сервера
     * @property array info параметры ответа (content_type, http_code и т.д.
     * Успешный http-запрос может быть неуспешным с точки зерния вызова сервиса http_code = 500 и т.п.
     * Конкретная информация о неуспешном запросе зависит от реализации сервиса (спецификация отсутсвует)
     */
    private function send($path, $postData)
    {
        $cURL = curl_init();
        curl_setopt($cURL, CURLOPT_URL, $this->url.$path);
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
          'error' => $ex,
        ];
        }

        return [
        'status' => 'OK',
        'response' => $response,
        'info' => $info,
      ];
    }

    public function post($path, $postData = [], $tryCount = self::TRY_COUNT)
    {
        for ($i = 1; $i < $tryCount; ++$i) {
            $output = $this->send($path, $postData);
            if ($output['status'] === 'OK') {
                break;
            }
        }
print_r($output);
        return $output;
    }

    private function postify($array)
    {
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
