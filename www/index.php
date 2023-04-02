<?php

try
{
    spl_autoload_register(function (string $className){
        require_once __DIR__ . '/../Core/' . str_replace('\\', '/', $className) . '.php';
    });

    $route = mb_strtolower($_GET['route'] ?? '');
    $routes = require __DIR__ . '/../Core/routes.php';

    $isRouteFound = false;
    foreach ($routes as $pattern => $controllerAndAction)
    {
        preg_match(mb_strtolower($pattern), $route, $matches);
        if (!empty($matches))
        {
            $isRouteFound = true;
            break;
        }
    }

    if (!$isRouteFound) throw new \Project\Exceptions\NotFoundException(' Страница не найдена! ');

    unset($matches[0]);
    $controllerName = $controllerAndAction[0];
    $actionName = $controllerAndAction[1];

    $controller = new $controllerName();
    $controller->$actionName(...$matches);
}

catch (\Project\Exceptions\DbException $exception)
{
    $view = new \Project\View\View(__DIR__ . '/../templates');
    $view->renderHtml('errors/505.php', ['title' => 'Ошибка БД', 'pageName' => 'Технические шоколадки','error' => $exception->getMessage(), 'user' => \Project\Models\Users\UsersAuthService::getUserByToken()], 500);
}

catch (\Project\Exceptions\NotFoundException $exception)
{
    $view = new \Project\View\View(__DIR__ . '/../templates');
    $view->renderHtml('errors/404.php', ['title' => 'Не найдено', 'pageName' => 'Технические шоколадки','error' => $exception->getMessage(), 'user' => \Project\Models\Users\UsersAuthService::getUserByToken()], 404);
}

catch (\Project\Exceptions\AnticipatedException $exception)
{
    $view = new \Project\View\View(__DIR__ . '/../templates');
    $view->renderHtml('errors/505.php', ['title' => 'Технические шоколадки', 'pageName' => 'Технические шоколадки','error' => $exception->getMessage(), 'user' => \Project\Models\Users\UsersAuthService::getUserByToken()], 505);
}

catch (\Project\Exceptions\UnauthorizedException $exception)
{
    $view = new \Project\View\View(__DIR__ . '/../templates');
    $view->renderHtml('errors/401.php', ['title' => 'Ошибка авторизации', 'pageName' => 'Технические шоколадки','error' => $exception->getMessage(), 'user' => \Project\Models\Users\UsersAuthService::getUserByToken()], 401);
}

catch (\Project\Exceptions\ForbiddenException $exception)
{
    $view = new \Project\View\View(__DIR__ . '/../templates');
    $view->renderHtml('errors/403.php', ['title' => 'Отказано в доступе', 'pageName' => 'Технические шоколадки','error' => $exception->getMessage(), 'user' => \Project\Models\Users\UsersAuthService::getUserByToken()], 403);
}

catch (Exception $exception)
{
    $view = new \Project\View\View(__DIR__ . '/../templates');
    $view->renderHtml('errors/505.php', ['title' => 'Технические шоколадки', 'pageName' => 'Технические шоколадки','error' => ' Возникла ошибка, вернитесь на главную страницу', 'user' => \Project\Models\Users\UsersAuthService::getUserByToken()], 505);
}


