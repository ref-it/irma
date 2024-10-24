<?php

namespace App\Export\Mailman3;

use CurlHandle;

class RequestsSession {
    /**
     * @var array cookies-array
     */
    private $cookiesArray = [];

    /**
     * @var string cookies
     */
    private $cookies = "";

    /**
     * @var string referer-header
     */
    public $referer = "";

    /**
     * @var bool
     */
    public $ignoreSSLCert = false;

    /**
     * @var bool
     */
    public $httpBasicAuth = false;

    /**
     * @var string
     */
    public $httpBasicAuthCredString = '';

    public function __construct($referer = "", $ignoreSSLCert = false) {
        $this->referer = $referer;
        $this->ignoreSSLCert = $ignoreSSLCert;
    }


    /**
     * Initializes the curl-handler
     *
     * @return CurlHandle curl-handler
     */
    private function initCurl() {
        $curl = curl_init();

        $options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_MAXREDIRS => 10,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_REFERER => $this->referer
        ];

        if ($this->ignoreSSLCert) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        }

        if ($this->httpBasicAuth) {
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, $this->httpBasicAuthCredString);
        }

        curl_setopt_array($curl, $options);

        return $curl;
    }

    /**
     * Makes a curl-request with the cookies in the cookies var
     * and saves the results cookies back into the cookies var
     *
     * @param $url
     * @param string $method (GET, POST, PUT or PATCH)
     * @param array $postfields (for POST, PUT and PATCH only)
     * @return RequestsSessionResponse response
     */
    private function request($url, $method = 'GET', $postfields = []) {
        $curl = $this->initCurl();

        curl_setopt($curl, CURLOPT_URL, $url);

        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postfields));
        }

        if ($method == 'PUT') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postfields));
        }

        if ($method == 'PATCH') {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postfields));
        }

        curl_setopt($curl, CURLOPT_COOKIE, $this->cookies);

        $raw_content = curl_exec($curl);
        $header = curl_getinfo($curl);

        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        $header_content = substr($raw_content, 0, $header['header_size']);
        $body_content = trim(str_replace($header_content, '', $raw_content));
        $pattern = "#[Ss]et-[Cc]ookie:\\s+(?<cookie>[^=]+=[^;]+)#m";
        preg_match_all($pattern, $header_content, $matches);

        foreach ($matches['cookie'] as $cookieMatch) {
            preg_match('/^(\w+)/', $cookieMatch, $cookieNameMatches);
            $cookieName = $cookieNameMatches[1];
            $this->cookiesArray[$cookieName] = $cookieMatch;
        }

        $cookiesOut = implode("; ", $this->cookiesArray);

        $this->cookies = $cookiesOut;

        return new RequestsSessionResponse($status_code, $body_content);
    }

    /**
     * Makes a GET-Request
     *
     * @param $url
     * @return RequestsSessionResponse response
     */
    public function get($url) {
        return $this->request($url);
    }

    /**
     * Makes a POST-Request
     *
     * @param $url
     * @param $postfields
     * @return RequestsSessionResponse response
     */
    public function post($url, $postfields) {
        return $this->request($url, 'POST', $postfields);
    }

    /**
     * Makes a PUT-Request
     *
     * @param $url
     * @param $postfields
     * @return RequestsSessionResponse response
     */
    public function put($url, $postfields) {
        return $this->request($url, 'PUT', $postfields);
    }

    /**
     * Makes a PATCH-Request
     *
     * @param $url
     * @param $postfields
     * @return RequestsSessionResponse response
     */
    public function patch($url, $postfields) {
        return $this->request($url, 'PATCH', $postfields);
    }
}
