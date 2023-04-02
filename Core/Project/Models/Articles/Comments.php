<?php

namespace Project\Models\Articles;

use Project\Exceptions\InvalidArgumentException;
use Project\Models\ActiveRecordEntity;
use Project\Models\Users\User;

class Comments extends ActiveRecordEntity
{

    /** @var int */
    protected $articleId;

    /** @var int */
    protected $authorId;

    /** @var string */
    protected $text;


    /**
     * @param int $articleId
     * @return array|null
     */
    public static function getСomments(int $articleId): ?array
    {
        return self::findAllByColumn('article_id', $articleId);
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return User::getById($this->authorId);
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return int
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }

    /**
     * @param int $authorId
     * @return void
     */
    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @param int $articleId
     */
    public function setArticleId(int $articleId): void
    {
        $this->articleId = $articleId;
    }

    /**
     * @param array $fields
     * @param User $user
     * @param Article $article
     * @return Comments
     * @throws InvalidArgumentException
     */
    public static function createFromArray(array $fields, User $user, Article $article): Comments
    {
        if (empty($fields['text']))
            throw new InvalidArgumentException('Не передан текст комментария');

        if (mb_strlen(trim($fields['text'])) == 0)
            throw new InvalidArgumentException('Не передан текст комментария');

        $comment = new Comments();

        $comment->setAuthorId($user->getId());
        $comment->setArticleId($article->getId());
        $comment->setText(htmlentities($fields['text']));

        $comment->save();

        return $comment;
    }

    /**
     * @param array $fields
     * @return $this
     * @throws InvalidArgumentException
     */
    public function updateFromArray(array $fields): Comments
    {
        if (empty($fields['text']))
            throw new InvalidArgumentException('Не передан текст комментария');

        if (mb_strlen(trim($fields['text'])) == 0)
            throw new InvalidArgumentException('Не передан текст комментария');

        $this->setText(htmlentities($fields['text']));

        $this->save();

        return $this;
    }

    /**
     * @param Comments $comment
     * @param User $user
     * @return bool
     */
    public static function possibilityChange(Comments $comment, User $user): bool
    {
        if ($user->isAdmin()) return true;
        if ($comment->authorId == $user->getId()) return true;

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
    public static function getTableName(): string
    {
        return 'comments';
    }

}