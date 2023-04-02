<?php

namespace Project\Controllers;

use Project\Exceptions\AnticipatedException;
use Project\Exceptions\InvalidArgumentException;
use Project\Exceptions\NotFoundException;
use Project\Exceptions\UnauthorizedException;
use Project\Models\Articles\Article;
use Project\Models\Users\UserActivationService;
use Project\Models\Users\UsersAuthService;
use Project\Services\EmailSender;
use Project\Models\Users\User;

class UsersController extends AbstractController
{

    /**
     * @return void
     * @throws AnticipatedException
     */
    public function login(): void
    {
        if (!empty($this->user)) throw new AnticipatedException(' Данное действие сделать невозможно, вы уже авторизированы!');

        if (!empty($_POST))
        {
            try
            {
                $user = User::login($_POST);
                UsersAuthService::createToken($user);
            }
            catch (InvalidArgumentException $exception)
            {
                $this->view->renderHtml('users/login.php', ['title' => 'Авторизация', 'pageName' => 'Авторизация','error' => $exception->getMessage()]);
                return;
            }

            if ($user instanceof User)
            {
                Header('Location: /users/Profile/Id/' . $user->getId());
                return;
            }
        }

        $this->view->renderHtml('users/login.php', ['title' => 'Авторизация', 'pageName' => 'Авторизация']);

    }

    /**
     * @return void
     * @throws AnticipatedException
     */
    public function signUp(): void
    {
        if (!empty($this->user)) throw new AnticipatedException(' Данное действие сделать невозможно, вы уже авторизированы!');

        if (!empty($_POST))
        {
            try
            {
                $user = User::signUp($_POST);
            }
            catch (InvalidArgumentException $exception)
            {
                $this->view->renderHtml('users/signUp.php', ['title' => 'Регистрация', 'pageName' => 'Регистрация','error' => $exception->getMessage()]);
                return;
            }

            if ($user instanceof User)
            {
                $code = UserActivationService::createActivationCode($user);

                EmailSender::send($user, 'Активация', 'userActivation.php', ['userId' => $user->getId(), 'code' => $code]);

                $this->view->renderHtml('users/signUpSuccessful.php', ['title' => 'Регистрация', 'pageName' => 'Регистрация']);
                return;
            }
        }

        $this->view->renderHtml('users/signUp.php', ['title' => 'Регистрация', 'pageName' => 'Регистрация']);
    }

    /**
     * @param int $userId
     * @param string $activationCode
     * @return void
     * @throws AnticipatedException
     */
    public function activate(int $userId, string $activationCode): void
    {
        if (!empty($this->user)) throw new AnticipatedException(' Данное действие сделать невозможно, вы уже авторизированы!');

        $user = User::getById($userId);

        if ($user === null) throw new AnticipatedException(' Пользователь с таким id не найден! ');
        $isCodeValid = UserActivationService::checkActivationCode($user, $activationCode);

        if (!$isCodeValid) throw new AnticipatedException(' Такой код не найден! Или вы уже активировали аккаунт');

        $user->activate();
        UserActivationService::deleteActivationCode($user, $activationCode);
        $this->view->renderHtml('users/userActivationSuccessful.php', ['title' => 'Активация', 'pageName' => 'Успех']);
    }

    /**
     * @param int $userID
     * @return void
     */
    public function profile(int $userID): void
    {
        $user = User::getById($userID);

        if ($user === null)
        {
            $this->view->renderHtml('errors/404.php', ['title' => 'Профиль', 'error' => ' Такого пользователя нет! '], 404);
            return;
        }

        if ($this->user === null)
        {
            $this->view->renderHtml('users/profileId.php', ['title' => 'Профиль', 'userId' => $user]);
            return;
        }

        // Если это наш профиль
        if ($this->user->getId() == $user->getId())
            $this->view->renderHtml('users/profile.php', ['title' => 'Ваш профиль']);
        // Если это чужой профиль
        else
            $this->view->renderHtml('users/profileId.php', ['title' => 'Профиль', 'userId' => $user]);
    }

    /**
     * @param int $userId
     * @return void
     * @throws NotFoundException
     */
    public function articles(int $userId): void
    {
        $user = User::getById($userId);

        if ($user === null)
            throw new NotFoundException('Такой пользователь не найден!');

        $articles = Article::findAllByColumn('author_id', $user->getId());

        if ($articles === null)
            throw new NotFoundException('Статей не найдено!');

        $this->view->renderHtml('articles/articles.php', ['title' => 'Статьи пользователя', 'articles' => $articles]);

    }

    /**
     * @return void
     * @throws AnticipatedException
     */
    public function logout(): void
    {
        if (empty($this->user)) throw new AnticipatedException(' Данное действие сделать невозможно, вы уже вышли!');

        if (UsersAuthService::getUserByToken() === null) echo 'Вы не были авторизованы!';

        UsersAuthService::deleteToken();
        Header('Location: /');

    }



}