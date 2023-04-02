<?php

namespace Project\Controllers;

use Project\Exceptions\ForbiddenException;
use Project\Exceptions\InvalidArgumentException;
use Project\Exceptions\NotFoundException;
use Project\Exceptions\UnauthorizedException;
use Project\Models\Articles\Article;
use Project\Models\Users\User;

class ArticlesController extends AbstractController
{
    /**
     * @return void
     * @throws NotFoundException
     */
    public function articles(): void
    {
        $articles = Article::findAllFresh();

        if ($articles === null) throw new NotFoundException(' Пока что тут нет статей! ');

        $this->view->renderHtml('articles/articles.php', ['title' => 'Статьи', 'pageName' => 'Все статьи' , 'articles' => $articles]);
    }

    /**
     * @param int $articleId
     * @return void
     * @throws NotFoundException
     */
    public function articleId(int $articleId): void
    {
        $article = Article::getById($articleId);

        if ($article === null) throw new NotFoundException(' Такой статьи нет! ');

        $this->view->renderHtml('articles/articlesId.php', ['title' => 'Статья', 'pageName' => 'Статья', 'article' => $article]);
    }

    /**
     * @param int $articleId
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function edit(int $articleId): void
    {
        if($this->user === null) throw new UnauthorizedException('Вы не авторизованы!');

        $article = Article::getById($articleId);

        if ($article === null) throw new NotFoundException('Такой статьи нет!');

        if (!Article::possibilityChange($article, $this->user))
            throw new ForbiddenException('Вы не автор статьи или вы не админ!');

        if(!empty($_POST))
        {
            try
            {
                $article->updateFromArray($_POST);
            }
            catch (InvalidArgumentException $exception)
            {
                $this->view->renderHtml('articles/edit.php', ['title' => 'Редактирование статьи', 'pageName' => 'Редактирование статьи', 'error' => $exception->getMessage(), 'article' => $article]);
                return;
            }
            Header('Location: /articles/Id/' . $article->getId());
            exit();
        }

        $this->view->renderHtml('articles/edit.php', ['title' => 'Редактирование статьи', 'pageName' => 'Редактирование статьи', 'article' => $article]);
    }

    /**
     * @return void
     * @throws UnauthorizedException
     */
    public function add(): void
    {

        if ($this->user === null) throw new UnauthorizedException('Вы не авторизованы!');

        if (!empty($_POST))
        {
            try
            {
                $article = Article::createFromArray($_POST, $this->user);
            }
            catch (InvalidArgumentException  $exception)
            {
                $this->view->renderHtml('articles/add.php', ['title' => 'Добавление новой статьи', 'pageName' => 'Добавление новой статьи', 'error' => $exception->getMessage()]);
                return;
            }
            Header('Location: /articles/Id/' . $article->getId());
            exit();
        }

        $this->view->renderHtml('articles/add.php', ['title' => 'Добавление новой статьи', 'pageName' => 'Добавление новой статьи']);
    }

    /**
     * @param int $articleId
     * @return void
     * @throws ForbiddenException
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function delete(int $articleId)
    {

        if($this->user === null) throw new UnauthorizedException('Вы не авторизованы!');

        $article = Article::getById($articleId);

        if ($article === null) throw new NotFoundException('Такой статьи нет!');

        if (!Article::possibilityChange($article, $this->user))
            throw new ForbiddenException('Вы не автор статьи или вы не админ!');

        $article->delete();

        $this->view->renderHtml('articles/delete.php', ['title' => 'Статья удалена', 'pageName' => 'Успех!', 'error' => 'Статья удалена']);

    }


}