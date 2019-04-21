<?php

namespace Hea\Router;

use Hea\Router\IRequest;

class Router
{
    private $request;
    private $supportedHttpMethods = ["GET", "POST"];

    public function __construct(IRequest $request)
    {
        $this->request = $request;
    }

    function __destruct()
    {
        $this->resolve();
    }

    public function __call($name, $arguments)
    {
        list($route, $method) = $arguments;

        if (!in_array(strtoupper($name), $this->supportedHttpMethods)) {
            $this->invalidMethodHandler();
        }

        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;

    }

    /**
     * Removes trailing forward slashes from the right of the route.
     * @param $route
     * @return string
     */
    private function formatRoute($route)
    {
        $result = rtrim($route, '/');
        if ($result === '') {
            return '/';
        }
        return $result;
    }

    private function invalidMethodHandler()
    {
        header("{$this->request->serverProtocol} 405 Method Not Allowed");
    }

    private function defaultRequestHandler()
    {
        header("{$this->request->serverProtocol} 404 Not Found");
    }

    /**
     * Resolves a route request
     */
    function resolve()
    {
        $methodDetection = $this->{strtolower($this->request->requestMethod)};
        $formattedRoute = $this->formatRoute($this->request->requestUri);
        $parseUrl = parse_url($formattedRoute);
        $method = key_exists('path', $parseUrl) ? $methodDetection[$parseUrl['path']] : $methodDetection[$formattedRoute];
        if (is_null($method)) {
            $this->defaultRequestHandler();
            return;
        }
        // execute method with corresponding request parameters
        echo call_user_func_array($method, array($this->request));
    }
}