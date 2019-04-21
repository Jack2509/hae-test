<?php

namespace Hea\Router;

use Hea\Router\IRequest;

class Request implements IRequest
{
    const GET = 'GET';
    const POST = 'POST';
    public $requestMethod = null;
    public $body = [];

    public function __construct()
    {
        $this->bootstrapSelf();
    }

    private function toCamelCase($string)
    {
        $result = strtolower($string);

        preg_match_all('/_[a-z]/', $result, $matches);

        foreach ($matches[0] as $match) {
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }

        return $result;
    }

    private function bootstrapSelf()
    {
        foreach ($_SERVER as $key => $value) {
            //$this->requestMethod will be created from $_SERVER['REQUEST_METHOD']
            $this->{$this->toCamelCase($key)} = $value;
        }
    }

    public function getBody()
    {
        if ($this->requestMethod === self::GET) {
            foreach ($_GET as $key => $value) {
                $this->body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        if ($this->requestMethod == self::POST) {
            foreach ($_POST as $key => $value) {
                $this->body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $this->body;
    }
}