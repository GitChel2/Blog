<?php

namespace Project\Controllers;

use Project\Models\Articles\Article;
use Project\View\View;

class MainController extends AbstractController
{

    /**
     * @return void
     */
    public function main(): void
    {
        $this->view->renderHtml('main/main.php', ['title' => 'Code Chronicles by Dan', 'pageName' => 'Code Chronicles by Dan']);
    }

    /**
     * @return void
     */
    public function aboutMe(): void
    {
        $this->view->renderHtml('main/aboutMe.php', ['title' => 'Про меня', 'pageName' => 'Кто я такой?']);
    }

    /**
     * @return void
     */
    public function contactMe(): void
    {
        $this->view->renderHtml('main/contactMe.php', ['title' => 'Про меня', 'pageName' => 'Мои контакты']);
    }

}