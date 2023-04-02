<?php

namespace Project\Controllers;

use Project\Exceptions\ForbiddenException;
use Project\Exceptions\InvalidArgumentException;
use Project\Exceptions\NotFoundException;
use Project\Exceptions\UnauthorizedException;
use Project\Models\Articles\Article;
use Project\Models\Articles\Comments;


class CommentsController extends AbstractController
{
    /**
     * @param int $articleId
     * @return void
     * @throws NotFoundException
     */
    public function add(int $articleId): void
    {
        $article = Article::getById($articleId);

        if ($article === null) throw new NotFoundException(' Такой статьи нет! ');

        if (!empty($_POST))
        {
            if ($this->user === null)
            {
                $this->view->renderHtml('articles/articlesId.php', ['title' => 'Статья', 'pageName' => 'Статья', 'article' => $article, 'error' => 'Вы не авторизованы!']);
                return;
            }

            try
            {
                $comment = Comments::createFromArray($_POST, $this->user, $article);
            }
            catch (InvalidArgumentException  $exception)
            {
                $this->view->renderHtml('articles/articlesId.php', ['title' => 'Статья', 'pageName' => 'Статья', 'article' => $article, 'error' => $exception->getMessage()]);
                return;
            }
            Header('Location: /articles/Id/' . $article->getId());
            exit();
        }

        $this->view->renderHtml('articles/articlesId.php', ['title' => 'Статья', 'pageName' => 'Статья', 'article' => $article]);

    }

    /**
     * @param int $commentId
     * @return void
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function edit(int $commentId): void
    {

        if($this->user === null) throw new UnauthorizedException('Вы не авторизованы!');

        $comment = Comments::getById($commentId);
        if ($comment === null) throw new NotFoundException(' Такого комментария нет! ');

        $article = Article::getById($comment->getArticleId());
        if ($article === null) throw new NotFoundException(' Такой статьи нет! ');

        if (!Comments::possibilityChange($comment, $this->user))
        {
            $this->view->renderHtml('articles/articlesId.php', ['title' => 'Статья', 'pageName' => 'Статья', 'article' => $article, 'error' => 'Вы не автор комментария или не админ']);
            return;
        }

        if (!empty($_POST))
        {
            try
            {
                $comment->updateFromArray($_POST);
            }
            catch (InvalidArgumentException  $exception)
            {
                $this->view->renderHtml('articles/commentEdit.php', ['title' => 'Статья', 'pageName' => 'Статья','article' => $article, 'error' => $exception->getMessage(), 'comment' => $comment]);
                return;
            }

            Header('Location: /articles/Id/' . $article->getId());
            exit();
        }

        $this->view->renderHtml('articles/commentEdit.php', ['title' => 'Статья', 'pageName' => 'Статья', 'article' => $article, 'comment' => $comment]);

    }

    /**
     * @param int $commentId
     * @return void
     * @throws NotFoundException
     * @throws UnauthorizedException
     */
    public function delete(int $commentId): void
    {

        if($this->user === null) throw new UnauthorizedException('Вы не авторизованы!');

        $comment = Comments::getById($commentId);

        if ($comment === null) throw new NotFoundException('Такого комментария нет');

        $article = Article::getById($comment->getArticleId());

        if ($article === null) throw new NotFoundException('Такой статьи нет!');

        if (!Comments::possibilityChange($comment, $this->user))
        {
            $this->view->renderHtml('articles/articlesId.php', ['title' => 'Статья', 'pageName' => 'Статья', 'article' => $article, 'error' => 'Вы не автор комментария или не админ']);
            return;
        }

        $comment->delete();

        $this->view->renderHtml('articles/articlesId.php', ['title' => 'Статья', 'pageName' => 'Статья', 'article' => $article]);

    }




}