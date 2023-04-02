<?php

namespace Project\Models\Users;

use Project\Services\Db;

class UserActivationService
{

    private const TABLE_NAME = 'users_activation_codes';

    /**
     * @param User $user
     * @return string
     * @throws \Exception
     */
    public static function createActivationCode(User $user): string
    {
        $code = bin2hex(random_bytes(16));

        $sql = 'INSERT INTO `' . self::TABLE_NAME . '` (`user_id`, `code`) VALUES (:user_id, :code);';
        $param = [':user_id' => $user->getId(), ':code' => $code];

        $db = Db::getInstance();
        $db->query($sql, $param);

        return $code;
    }

    /**
     * @param User $user
     * @param string $code
     * @return bool
     */
    public static function checkActivationCode(User $user, string $code): bool
    {
        $sql = 'SELECT * FROM `' . self::TABLE_NAME . '` WHERE `user_id` = :user_id AND `code` = :code;';
        $param = [':user_id' => $user->getId(), ':code' => $code];

        $db = Db::getInstance();
        $result = $db->query($sql, $param);

        return !empty($result);
    }

    /**
     * @param User $user
     * @param string $code
     * @return void
     */
    public static function deleteActivationCode(User $user, string $code): void
    {
        $sql = 'DELETE FROM `' . self::TABLE_NAME . '` WHERE `user_id` = :user_id;';
        $param = [':user_id' => $user->getId()];

        $db = Db::getInstance();
        $db->query($sql, $param);
    }



}