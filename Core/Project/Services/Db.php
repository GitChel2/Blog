<?php

namespace Project\Services;

use Project\Exceptions\DbException;

class Db
{
    /** @var Db */
    private static $instance;

    /** @var \PDO */
    private \PDO $pdo;

    private function __construct()
    {
        try
        {
            $dbOptions = (require  __DIR__ . '/../../settings.php')['db'];
            $this->pdo = new \PDO('mysql:host='. $dbOptions['host'] .';dbname=' . $dbOptions['dbname'],
                $dbOptions['user'],
                $dbOptions['password']
            );
            $this->pdo->exec('SET NAMES UTF8');
        }
        catch (\PDOException $exception)
        {
            throw new DbException(' Ошибка подключения к БД: ' . $exception->getMessage());
        }
    }

    /**
     * @param string $sql
     * @param array $param
     * @param string $className
     * @return array|null
     */
    public function query(string $sql, array $param = [], string $className = 'stdClass'): ?array
    {
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($param);

        if ($result === false) return null;

        return $stmt->fetchAll(\PDO::FETCH_CLASS, $className);
    }

    /**
     * @return Db
     */
    public static function getInstance(): Db
    {
        if (self::$instance === null) self::$instance = new self();

        return self::$instance;
    }

    /**
     * @return int
     */
    public function getLastInsertId(): int
    {
        return (int) $this->pdo->lastInsertId();
    }

}