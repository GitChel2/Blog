<?php
/**
 * @var \Project\Models\Users\User $user
 */
?>
<!DOCTYPE html>
<html lang="ru">

<head>

    <!-- Meta Tag -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- SEO -->
    <meta name="description" content="150 words">
    <meta name="author" content="uipasta">
    <meta name="url" content="http://www.yourdomainname.com">
    <meta name="copyright" content="company name">
    <meta name="robots" content="index,follow">


    <title><?= $title ?? 'Code Chronicles by Dan' ?></title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="/images/favicon/favicon.ico">
    <link rel="apple-touch-icon" sizes="144x144" type="image/x-icon" href="/images/favicon/apple-touch-icon.png">

    <!-- All CSS Plugins -->
    <link rel="stylesheet" type="text/css" href="/css/plugin.css">

    <!-- Main CSS Stylesheet -->
    <link rel="stylesheet" type="text/css" href="/css/style.css">

    <!-- Google Web Fonts  -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:400,300,500,600,700">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!---->
<!--     HTML5 shiv and Respond.js support IE8 or Older for HTML5 elements and media queries -->
<!--    [if lt IE 9]>-->
<!--    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>-->
<!--    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>-->
<!--    <![endif]-->


</head>
<body>


    <!-- Preloader Start -->
    <div class="preloader">
        <div class="rounder"></div>
    </div>
    <!-- Preloader End -->



    <div id="main" class="main">
        <div class="container">
            <div class="row scaling">


                <!-- About Me (Left Sidebar) Start -->
                <div class="col-md-3">
                    <div class="about-fixed">


                        <div class="main-menu-link">
                            <div class="white-spacing">
                                <ul class="menu-link">
                                    <a href="javascript:void(0)" class="collapsed" data-target="#menu" data-toggle="collapse"><i class="icon-menu menu"></i></a>
                                    <li><a href="/articles" class="underline">Статьи</a></li>

                                    <?php if($user !== null): ?>
                                        <li><a href="/articles/Add" class="underline">Написать статью</a></li>
                                    <?php endif; ?>

                                </ul>
                            </div>
                        </div>




                        <div class="my-pic">
                            <div id="menu" class="collapse">
                                <ul class="menu-link">
                                    <li><a href="/about-me" class="underline">Про меня</a></li>
                                    <li><a href="/contact-me" class="underline">Связь со мной</a></li>
                                    <li><a href="https://github.com/GitChel2" class="underline" target="_blank">GitHub</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="my-detail">
                            <div class="white-spacing">
                                <a href="/"><h1>Code Chronicles by Dan</h1></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- About Me (Left Sidebar) End -->


                <!-- Blog Post (Right Sidebar) Start -->
                <div class="col-md-9">
                    <div class="col-md-12 page-body">
                        <div class="row" class="row">

                            <div class="sub-title">
                                <ul class="menu-link">

                                    <?php if($user === null): ?>

                                        <li><h4>Добро пожаловать!</h4></li>
                                        <li><a href="/users/Login" class="underline"><h4>Войти</h4></a></li>
                                        <li><a href="/users/Register" class="underline"><h4>Зарегистрироваться</h4></a></li>

                                    <?php else: ?>

                                        <li><h4>Привет, <?= $user->getNickname() ?>!</h4></li>
                                        <li><a href="/Users/Profile/Id/<?= $user->getId() ?>" class="underline"><h4>Профиль</h4></a></li>
                                        <li><a href="/users/Logout" class="underline"><h4>Выйти</h4></a></li>

                                    <?php endif; ?>

                                </ul>

                                <h2><?= $pageName ?? '' ?></h2>

                            </div>


                            <div class="col-md-12 content-page">