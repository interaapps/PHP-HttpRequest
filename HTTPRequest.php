<?php

namespace modules\httprequest;


class HTTPRequest {

    private $requestMethod = "GET",
            $requestURL,
            $params = [],
            $header = [],
            $ch;

    public function __construct($url, $requestMethod){
        $this->requestMethod = $requestMethod;
        $this->requestURL = $url;
        $this->ch = curl_init();
    } 

    public function getCurl(){
        return $ch;
    }

    public function setRequestURL($url) {
        $this->requestURL = $url;
        return $this;
    }

    public function parameters($params){
        $this->params = $params;
        return $this;
    }

    public function parameter($param, $val){
        $this->params[$param] = $val;
        return $this;
    }
    
    public function headers($headers){
        $this->header = $headers;
        return $this;
    }
    
    public function header($header){
        array_push($this->header, $header);
        return $this;
    }

    public function send() : HTTPResponse {
        $headers = [];

        curl_setopt($this->ch, CURLOPT_HEADER, 1);

        switch ($this->requestMethod) {
            case "POST":
                curl_setopt($this->ch, CURLOPT_POST, 1);
                break;
            case "PUT":
                curl_setopt($this->ch, CURLOPT_PUT, 1);
                break;
            default:
                \curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $this->requestMethod);
                break;
        }

        

        if (is_array($this->params) && count($this->params) > 0 && $this->requestMethod != "GET")
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->params);
        else
            $this->requestURL .= "?".http_build_query($this->params);
        
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_URL, $this->requestURL);

        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->header);

        curl_setopt($this->ch, CURLOPT_HEADERFUNCTION, function($curl, $header) use (&$headers) {
            $len = strlen($header);
            $header = explode(':', $header, 2);
            if (count($header) < 2)
                return $len;
            $headers[strtolower(trim($header[0]))][] = trim($header[1])[0];
            return $len;
        });
        

        $response = curl_exec($this->ch);

        $info = curl_getinfo($this->ch);

    
        $body = substr($response, $info['header_size']);

        $res = new HTTPResponse;

        $res->setData($body);

        if (isset($info["primary_port"]))
            $res->setPort($info["primary_port"]);
        
        if (isset($info["scheme"]))
            $res->setScheme($info["scheme"]);
        
        if (isset($info["http_code"]))
            $res->setPort($info["http_code"]);
        
        if (isset($info["content_type"]))
            $res->setContentType($info["content_type"]);
        
        $res->setHeaders($headers);


        \curl_close($this->ch);

        return $res;
        
    }

    public static function get($url) : HTTPRequest {
        return new HTTPRequest($url, "GET");
    }

    public static function post($url) : HTTPRequest {
        return new HTTPRequest($url, "POST");
    }

    public static function put($url) : HTTPRequest {
        return new HTTPRequest($url, "PUT");
    }

    public static function options($url) : HTTPRequest {
        return new HTTPRequest($url, "OPTIONS");
    }

    public static function delete($url) : HTTPRequest {
        return new HTTPRequest($url, "DELETE");
    }

    public static function connect($url) : HTTPRequest {
        return new HTTPRequest($url, "CONNECT");
    }

    public static function trace($url) : HTTPRequest {
        return new HTTPRequest($url, "TRACE");
    }

}
