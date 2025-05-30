<?php

namespace App\Core;
class Router {
    public $routes = [];

    /**
     * Agregar una ruta al enrutador.
     *
     * @param string $path Ruta (ejemplo: '/about').
     * @param string $controllerAction Controlador y método (ejemplo: 'HomeController@about').
     */
    public function add(string $path, string $controllerAction): void {
        $this->routes[$path] = $controllerAction;
    }

    /**
     * Procesar la solicitud y despachar la ruta correspondiente.
     *
     * @param string $requestUri URI solicitado (ejemplo: '/about').
     */
    public function dispatch(string $requestUri): void {
        $path = parse_url($requestUri, PHP_URL_PATH);

        $path = explode("/TDG", $path)[1]; // Eliminar la parte de la URL que no es necesaria

        if (isset($this->routes[$path])) {
            $controllerAction = $this->routes[$path];
            $this->executeAction($controllerAction);
        } else {
            $this->handleNotFound();
        }
    }

    /**
     * Ejecutar el método del controlador correspondiente.
     *
     * @param string $controllerAction Controlador y método (ejemplo: 'HomeController@index').
     */
    private function executeAction(string $controllerAction): void {
        list($controllerName, $methodName) = explode('@', $controllerAction);

        $controllerFile = __DIR__ . '/../Controllers/' . $controllerName . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;

            $controllerName = 'App\\Controllers\\' . $controllerName; // Namespace completo

            if (class_exists($controllerName)) {
                $controller = new $controllerName();

                if (method_exists($controller, $methodName)) {
                    $controller->$methodName();
                } else {
                    $this->handleError("El método $methodName no existe en $controllerName.");
                }
            } else {
                
                $this->handleError("La clase $controllerName no existe.");
            }
        } else {
            $this->handleError("El archivo del controlador $controllerFile no se encuentra.");
        }
    }

    /**
     * Manejar rutas no encontradas (404).
     */
    private function handleNotFound(): void {
        http_response_code(404);
        echo "404 - Página no encontrada";
    }

    /**
     * Manejar errores generales.
     *
     * @param string $message Mensaje de error.
     */
    private function handleError(string $message): void {
        http_response_code(500);
        echo "Error del servidor: $message";
    }
}

?>