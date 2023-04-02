<?php

return [

    '~^$~' => [\Project\Controllers\MainController::class, 'main'],
    '~^about-me~' => [\Project\Controllers\MainController::class, 'aboutMe'],
    '~^contact-me~' => [\Project\Controllers\MainController::class, 'contactMe'],

    '~^articles$~' => [\Project\Controllers\ArticlesController::class, 'articles'],
    '~^articles/Id/(\d+)$~' => [\Project\Controllers\ArticlesController::class, 'articleId'],
    '~^articles/Edit/(\d+)$~' => [\Project\Controllers\ArticlesController::class, 'edit'],
    '~^articles/Add$~' => [\Project\Controllers\ArticlesController::class, 'add'],
    '~^articles/Delete/(\d+)$~' => [\Project\Controllers\ArticlesController::class, 'delete'],

    '~^articles/Comment/Add/(\d+)$~' => [\Project\Controllers\CommentsController::class, 'add'],
    '~^articles/Comment/Edit/(\d+)$~' => [\Project\Controllers\CommentsController::class, 'edit'],
    '~^articles/Comment/Delete/(\d+)$~' => [\Project\Controllers\CommentsController::class, 'delete'],

    '~^users/Register$~' => [\Project\Controllers\UsersController::class, 'signUp'],
    '~^users/Activate/(\d+)/(.+)$~' => [\Project\Controllers\UsersController::class, 'activate'],
    '~^users/Login$~' => [\Project\Controllers\UsersController::class, 'login'],
    '~^users/Profile/Id/(\d+)$~' => [\Project\Controllers\UsersController::class, 'profile'],
    '~^users/Logout$~' => [\Project\Controllers\UsersController::class, 'logout'],
    '~^users/Articles/(\d+)$~' => [\Project\Controllers\UsersController::class, 'articles'],



];
