<?php

namespace Project\Models\Articles;

use Project\Models\ActiveRecordEntity;
use Project\Models\Users\User;
use Project\Exceptions\InvalidArgumentException;

class Article extends ActiveRecordEntity
{

     /** @var string */
    protected $name;

    /** @var string */
    protected $text;

    /** @var int */
    protected $authorId;


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return User::getById($this->authorId);
    }

    /**
     * @return array|null
     */
    public function getСomments(): ?array
    {
        return Comments::getСomments($this->id);
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @param User $author
     * @return void
     */
    public function setAuthorId(User $author): void
    {
        $this->authorId = $author->getId();
    }

    /**
     * @param array $fields
     * @param User $user
     * @return Article
     */
    public static function createFromArray(array $fields, User $user): Article
    {
        if (empty($fields['name']))
            throw new InvalidArgumentException('Не передано название статьи');

        if (mb_strlen(trim($fields['name'])) == 0)
            throw new InvalidArgumentException('Не передано название статьи');

        if (empty($fields['text']))
            throw new InvalidArgumentException('Не передан текст статьи');

        if (mb_strlen(trim($fields['text'])) == 0)
            throw new InvalidArgumentException('Не передано текст статьи');

        $article = new Article();

        $article->setAuthorId($user);
        $article->setName(htmlentities(trim($fields['name'])));
        $article->setText(htmlentities(trim($fields['text'])));

        $article->save();

        return $article;
    }

    /**
     * @param array $fields
     * @return $this
     * @throws InvalidArgumentException
     */
    public function updateFromArray(array $fields): Article
    {
        if (empty($fields['name']))
            throw new InvalidArgumentException('Не передано название статьи');

        if (mb_strlen(trim($fields['name'])) == 0)
            throw new InvalidArgumentException('Не передано название статьи');

        if (empty($fields['text']))
            throw new InvalidArgumentException('Не передан текст статьи');

        if (mb_strlen(trim($fields['text'])) == 0)
            throw new InvalidArgumentException('Не передано текст статьи');

        $this->setName(htmlentities(trim($fields['name'])));
        $this->setText(htmlentities(trim($fields['text'])));

        $this->save();

        return $this;
    }

    public static function possibilityChange(Article $article, User $user)
    {
        if ($user->isAdmin()) return true;
        if ($article->authorId == $user->getId()) return true;

        return false;
    }

    /**
     * @param string $str
     * @return array
     */
    public static function splittingEnter(string $str): array
    {
        return explode(PHP_EOL ,$str);
    }

    /**
     * @return string
     */
    protected static function getTableName(): string
    {
        return 'articles';
    }


}
