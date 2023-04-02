<?php

namespace Project\Models\Users;

use Project\Exceptions\InvalidArgumentException;
use Project\Models\ActiveRecordEntity;


class User extends ActiveRecordEntity
{

    /** @var string */
    protected $nickname;

    /** @var string */
    protected $email;

    /** @var bool */
    protected $isConfirmed;

    /** @var string */
    protected $role;

    /** @var string */
    protected $passwordHash;

    /** @var string */
    protected $authToken;


    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @return string
     */
    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }


    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->getRole() == 'admin' ? true : false;
    }

    /**
     * @param array $userData
     * @return User
     * @throws InvalidArgumentException
     */
    public static function signUp(array $userData): User
    {
        if (empty($userData['nickname']))
            throw new InvalidArgumentException('Не передан nickname');

        if (!preg_match('/^[a-zA-Z0-9]+$/', $userData['nickname']))
            throw new InvalidArgumentException('Nickname может состоять только из символов латинского алфавита и цифр');

        if (empty($userData['email']))
            throw new InvalidArgumentException('Не передан email');

        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL))
            throw new InvalidArgumentException('Email некорректен');

        if (empty($userData['password']))
            throw new InvalidArgumentException('Не передан password');

        if (!preg_match('/^[a-zA-Z0-9]+$/', $userData['password']))
            throw new InvalidArgumentException('Пароль может состоять только из символов латинского алфавита и цифр');

        if (empty($userData['passwordConfirmation']))
            throw new InvalidArgumentException('Не передан Password Confirmation');

        if (!preg_match('/^[a-zA-Z0-9]+$/', $userData['passwordConfirmation']))
            throw new InvalidArgumentException('Пароль может состоять только из символов латинского алфавита и цифр');

        if (mb_strlen($userData['password']) < 8)
            throw new InvalidArgumentException('Пароль должен быть не менее 8 символов');

        if (mb_strlen($userData['password']) !== mb_strlen($userData['passwordConfirmation']))
            throw new InvalidArgumentException('Пароли не совпадают!');

        if (static::findOneByColumn('nickname', $userData['nickname']) !== null )
            throw new InvalidArgumentException('Пользователь с таким nickname уже есть!');

        if (static::findOneByColumn('email', $userData['email']) !== null )
            throw new InvalidArgumentException('Пользователь с таким email уже есть!');

        // Если все ок
        $user = new User();
        $user->nickname = $userData['nickname'];
        $user->email = $userData['email'];
        $user->isConfirmed = false;
        $user->role = 'user';
        $user->passwordHash = password_hash($userData['password'], PASSWORD_DEFAULT);
        $user->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
        $user->save();

        return $user;
    }

    /**
     * @return void
     */
    public function activate(): void
    {
        $this->isConfirmed = true;
        $this->save();
    }

    /**
     * @param array $loginData
     * @return User
     * @throws InvalidArgumentException
     */
    public static function login(array $loginData): User
    {
        if (empty($loginData['email']))
            throw new InvalidArgumentException('Не передан email');


        if (empty($loginData['password']))
            throw new InvalidArgumentException('Не передан password');

        // Находим такого пользователя по мейлу
        $user = User::findOneByColumn('email', $loginData['email']);

        if ($user === null)
            throw new InvalidArgumentException('Нет пользователя с таким email');

        // Проверяем правильность пароля
        if (!password_verify($loginData['password'], $user->getPasswordHash()))
            throw new InvalidArgumentException('Неправильный пароль');


        if (!$user->isConfirmed)
            throw new InvalidArgumentException('Пользователь не подтверждён');

        $user->refreshAuthToken();
        $user->save();

        return $user;

    }

    /**
     * @return void
     */
    private function refreshAuthToken(): void
    {
        $this->authToken = sha1(random_bytes(100)) . sha1(random_bytes(100));
    }


    /**
     * @return string
     */
    protected static function getTableName(): string
    {
        return 'users';
    }

}