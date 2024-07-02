<?php

namespace core;

use app\controllers\EditorController;
use app\controllers\HomeController;
use app\controllers\ImagesController;
use app\controllers\NotFoundController;
use ReflectionClass;

class Router
{
    private array $requestArray;

    private const ROUTES = [
        'home' => ['controller' => HomeController::class],
        'images' => ['controller' => ImagesController::class],
        'editor' => ['controller' => EditorController::class],
    ];

    private const NOT_FOUND = ['controller' => NotFoundController::class];

    public function __construct()
    {
        $request = $_SERVER['REQUEST_URI'] ?? '/';
        $this->requestArray = array_filter(explode('/', $request));
    }

    public function match(): void
    {
        $controllerName = self::ROUTES['home']['controller'];
        $method = 'index';

        if ($this->requestArray) {
            foreach (self::ROUTES as $key => $route) {
                if ($key === $this->requestArray[1]) {
                    $controllerName = $route['controller'];
                    $method = $this->requestArray[2] ?? 'index';

                    $paramNumber = count($this->requestArray);
                    $firstParamIndex = 3;

                    for ($i = $firstParamIndex; $i <= $paramNumber; $i++) {
                        $args[] = $this->requestArray[$i] ?? '';
                    }
                    break;
                }
                $controllerName = self::NOT_FOUND['controller'];
            }
        }

        $args = !empty($args) ? $args : [];

        $controller = new $controllerName();

        try {
            if (!method_exists($controller, $method)) {
                $wrongMethod = $method;
                $this->callNotFound($controller, $method);
                throw new \Exception("Method: " . $wrongMethod . " doesn't exist.");
            }

            $reflectionClass = new ReflectionClass($controller);
            $reflectionMethod = $reflectionClass->getMethod($method);
            $requiredParameterCount = $reflectionMethod->getNumberOfParameters();
            $providedArgumentCount = count($args);

            if ($requiredParameterCount !== $providedArgumentCount) {
                $wrongMethod = $method;
                $this->callNotFound($controller, $method);
                throw new \Exception("Parameter number for " . $wrongMethod . " method doesn't match.");
            }
        } catch (\Exception $exception) {
            error_log($exception->getMessage(), 0);
        } finally {
            $args ? $controller->$method(...$args) : $controller->$method();
        }
    }

    public function callNotFound(&$controller, &$method): void
    {
        $controllerName = self::NOT_FOUND['controller'];
        $controller = new $controllerName;
        $method = 'index';
    }
}
