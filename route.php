<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where to register web routes for application.
*/

use Hea\Controllers\IndexController;
use Hea\Controllers\Api\CommentController as ApiCommentController;
use Hea\Controllers\AdminController;
use Hea\Router\Request;

//$router->get('/', function () {
//    return <<<HTML
//  <h1>Hello world</h1>
//HTML;
//});

$router->get('/', function (Request $request) {
    $indexController = new IndexController();
    $indexController->index($request);
});

$router->get('/api/comments/read', function (Request $request) {
    $apiCommentController = new ApiCommentController();
    return $apiCommentController->read($request);
});

$router->post('/api/comments/create', function (Request $request) {
    $apiCommentController = new ApiCommentController();
    return $apiCommentController->create($request);
});

$router->post('/api/comments/update', function (Request $request) {
    $apiCommentController = new ApiCommentController();
    return $apiCommentController->update($request);
});

$router->post('/api/comments/delete', function (Request $request) {
    $apiCommentController = new ApiCommentController();
    return $apiCommentController->delete($request);
});

$router->post('/admin/login', function (Request $request) {
    $adminController = new AdminController();
    return $adminController->login($request);
});

$router->post('/admin/logout', function (Request $request) {
    $adminController = new AdminController();
    return $adminController->logout($request);
});
