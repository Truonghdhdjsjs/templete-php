<?php
class App
{
    protected $controller = "Home";
    protected $action = "index";
    protected $params = [];
    protected $routes = [];

    public function __construct()
    {
        $this->routes = require_once __DIR__ . "/../core/routes.php"; 
        $url = $this->getUrl();
        $this->matchPath($url);
    }

    public function getUrl()
    {
        if (isset($_SERVER['REQUEST_URI'])) {
            $url = $_SERVER['REQUEST_URI'];
            // Chia nhỏ và loại bỏ các phần tử không cần thiết
            $segments = explode('/', filter_var(trim($url, '/'), FILTER_SANITIZE_URL));
            // Loại bỏ phần tử không cần thiết
            $filteredSegments = array_filter($segments, function($segment) {
                return !empty($segment) && $segment !== 'public' && $segment !== 'index.php';
            });
            // Chuyển đổi thành mảng đánh chỉ số lại
            $result = array_values($filteredSegments);

            // Nếu mảng kết quả rỗng, trả về mảng chứa một phần tử rỗng
            return empty($result) ? [''] : $result;
        }
        return [];
    }

    protected function matchPath($url)
    {
        $path = '/' . implode('/', $url);

        // Kiểm tra xem đường dẫn có tồn tại trong routes không
        if (array_key_exists($path, $this->routes)) {
            $this->controller = $this->routes[$path]['controller'];
            $this->action = $this->routes[$path]['action'];

            // Kiểm tra xem tệp controller có tồn tại không và yêu cầu nó
            $controllerFile = __DIR__ . "/../controller/" . $this->controller . ".php";
            if (file_exists($controllerFile)) {
                require_once $controllerFile;

                // Kiểm tra xem class controller có tồn tại không
                if (class_exists($this->controller)) {
                    $newController = new $this->controller;

                    // Kiểm tra xem action có tồn tại trong controller không
                    if (method_exists($newController, $this->action)) {
                        // Gọi action
                        call_user_func_array([$newController, $this->action], $this->params);
                    } else {
                        throw new Exception("Action '{$this->action}' not found in controller '{$this->controller}'.");
                    }
                } else {
                    throw new Exception("Controller class '{$this->controller}' not found.");
                }
            } else {
                throw new Exception("Controller file '{$this->controller}.php' does not exist.");
            }
        } else {
            throw new Exception("Route '{$path}' not found.");
        }
    }
}
