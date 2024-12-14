<?php
class Router
{
    // Mảng chứa tất cả các route (URL) và ánh xạ với controller, action
    private $routes = [];

    // Đăng ký một route
    public function add($route, $controllerAction)
    {
        $this->routes[$route] = $controllerAction;
    }

    // Kiểm tra và tìm kiếm route trong mảng
    public function match($url)
    {
        // Kiểm tra nếu route tồn tại trong mảng
        return isset($this->routes[$url]) ? $this->routes[$url] : false;
    }

    // Điều hướng URL tới controller và action tương ứng
    public function dispatch($url)
    {
        // Tìm route tương ứng với URL
        $route = $this->match($url);

        if ($route) {
            // Tách controller và action
            list($controller, $action) = explode('@', $route);

            // Viết hoa chữ cái đầu controller để tuân thủ chuẩn PSR
            $controller = ucfirst($controller) . 'Controller';

            // Kiểm tra xem controller có tồn tại không
            if (class_exists($controller)) {
                $controllerObj = new $controller; // Tạo đối tượng controller

                // Kiểm tra xem action có tồn tại không
                if (method_exists($controllerObj, $action)) {
                    $controllerObj->$action(); // Gọi action tương ứng
                } else {
                    echo "Action '$action' không tồn tại trong controller '$controller'.";
                }
            } else {
                echo "Controller '$controller' không tồn tại.";
            }
        } else {
            echo "Route '$url' không hợp lệ.";
        }
    }
}
