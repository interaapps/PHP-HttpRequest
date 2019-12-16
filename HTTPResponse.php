<?php

namespace modules\httprequest;


class HTTPResponse {

    private $data,
            $code,
            $port,
            $scheme,
            $headers,
            $contentType;

    public function getData(){
        return $this->data;
    }

    public function setData($data){
        $this->data = $data;
    }

    public function getCode(){
        return $this->code;
    }

    public function setCode($code){
        $this->code = $code;
    }

    public function getPort(){
        return $this->port;
    }

    public function setPort($port){
        $this->port = $port;
    }

    public function getScheme(){
        return $this->scheme;
    }

    public function usingSSL(){
        return $this->scheme == "HTTPS";
    }

    public function setScheme($scheme){
        $this->scheme = $scheme;
    }

    public function getHeaders(){
        return $this->headers;
    }

    public function getHeader($name){
        return $this->headers[$name];
    }

    public function setHeaders($headers){
        $this->headers = $headers;
    }

    public function getContentType(){
        return $this->contentType;
    }

    public function setContentType($contentType){
        $this->contentType = $contentType;
    }


}