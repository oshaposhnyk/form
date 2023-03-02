<?php

namespace core;

class Router
{
    private Request $request;
    private array $routeMap = [];
    private Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get(string $url, $callback): void
    {
        $this->routeMap['get'][$url] = $callback;
    }

    public function post(string $url, $callback)
    {
        $this->routeMap['post'][$url] = $callback;
    }

    public function resolve(): string|null
    {
        $method = $this->request->getMethod();
        $url = $this->request->getUrl();
        $callback = $this->routeMap[$method][$url] ?? false;

        if (!$callback) {
            $this->response->statusCode(404);
            return 'Not Found';
        }

        if (is_string($callback)) {
            return $this->renderView($callback);
        }

        if (is_array($callback)) {
            $controller = new $callback[0];
            Application::$app->controller = $controller;
            $callback[0] = $controller;
        }

        return call_user_func($callback, $this->request);
    }

    public function renderView($view, $params = []): string
    {
        $layoutName = Application::$app->controller->layout;
        $viewContent = $this->renderViewOnly($view, $params);
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$layoutName.php";
        $layoutContent = ob_get_clean();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    public function renderViewOnly(string $view, array $params = []): string
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }


}