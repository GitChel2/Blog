<?php

namespace Project\Models;

use Project\Services\Db;

abstract class ActiveRecordEntity
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $createdAt;

    /**
     * @param string $name
     * @param $value
     * @return void
     */
    public function __set(string $name, $value): void
    {
        $camelCaseName = $this->underscoreToCamelCase($name);
        $this->$camelCaseName = $value;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        $createdAt = $this->createdAt;

        $date = substr($createdAt, 0, 10);
        $time = substr($createdAt, -8, 5);

        $dateArray = explode('-', $date);
        $string = $dateArray[2] . '.' . $dateArray[1] . '.' . $dateArray[0] . ' ' . $time;

        return $string;
    }

    /**
     * @return array|null
     */
    public static function findAll(): ?array
    {
        $db = Db::getInstance();
        return $db->query('SELECT * FROM `' . static::getTableName() . '`;', [], static::class);

        if ($result === []) return null;

        return $result;
    }

    /**
     * @return array|null
     */
    public static function findAllFresh(): ?array
    {
        $db = Db::getInstance();
        return $db->query('SELECT * FROM `' . static::getTableName() . '` ORDER BY `created_at` DESC;', [], static::class);

        if ($result === []) return null;

        return $result;
    }


    /**
     * @param string $columnName
     * @param $value
     * @return array|null
     */
    public static function findAllByColumn(string $columnName, $value): ?array
    {
        $db = Db::getInstance();

        $result = $db->query('SELECT * FROM `' . static::getTableName() . '` WHERE `' . $columnName . '` = :value ;',
            [':value'=> $value],
            static::class);

        if ($result === []) return null;

        return $result;
    }

    /**
     * @param int $id
     * @return static|null
     */
    public static function getById(int $id): ?self
    {
        $db = Db::getInstance();

        $entities = $db->query('SELECT * FROM `' . static::getTableName() . '` WHERE `id` = :id;',
            [':id'=> $id],
            static::class);

        return $entities ? $entities[0] : null;
    }

    /**
     * @param string $columnName
     * @param $value
     * @return static|null
     */
    public static function findOneByColumn(string $columnName, $value): ?self
    {
        $db = Db::getInstance();

        $result = $db->query('SELECT * FROM `' . static::getTableName() . '` WHERE `' . $columnName . '` = :value LIMIT 1;',
            [':value'=> $value],
            static::class);

        if ($result === []) return null;

        return $result[0];
    }

    /**
     * @return void
     */
    public function save(): void
    {
        $mappedProperties = $this->mapPropertiesToDbFormat();
        if ($mappedProperties['id'] !== null) $this->update($mappedProperties);
            else $this->insert($mappedProperties);
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        $sql = 'DELETE FROM `' . static::getTableName() . '` WHERE `id` = :id;';
        $db = Db::getInstance();
        $db->query($sql, [':id' => $this->id], static::class);
        $this->id = null;
    }


    /**
     * @param array $mappedProperties
     * @return void
     */
    private function update(array $mappedProperties): void
    {
        $columns2params = [];
        $params2values = [];

        $index = 1;
        foreach ($mappedProperties as $column => $value)
        {
            $param = ':param' . $index; // :param$index
            $columns2params[] = '`' . $column . '` = ' . $param; // $column = $param$index
            $params2values[$param] = $value; // $param$index = $value
            $index++;
        }

        $sql = 'UPDATE `' . static::getTableName() . '` SET ' . implode(', ', $columns2params) . ' WHERE `id` = ' . $this->id . ';';
        $db = Db::getInstance();
        $db->query($sql, $params2values, static::class);
    }

    /**
     * @param array $mappedProperties
     * @return void
     */
    private function insert(array $mappedProperties): void
    {
        $filteredProperties = array_filter($mappedProperties);

        $columns = [];
        $paramsNames = [];
        $params2values = [];
        foreach ($filteredProperties as $columnName => $value)
        {
            $columns[] = '`' . $columnName . '`';
            $paramName = ':' . $columnName;
            $paramsNames[] = $paramName;
            $params2values[$paramName] = $value;
        }

        $sql = 'INSERT INTO `' . static::getTableName() . '` ( ' . implode(', ', $columns) . ' ) VALUES ( ' . implode(', ', $paramsNames) . ' );';

         $db = Db::getInstance();
         $db->query($sql, $params2values, static::class);
         $this->id = $db->getLastInsertId();
         $this->refresh();
    }

    /**
     * @return void
     */
    private function refresh(): void
    {
        $objectFromDb = static::getById($this->id);
        $reflector = new \ReflectionObject($objectFromDb);
        $properties = $reflector->getProperties();

        foreach ($properties as $property)
        {
            $property->setAccessible(true);
            $propertyName = $property->getName();
            $this->$propertyName = $property->getValue($objectFromDb);
        }

    }

    /**
     * @return array
     */
    private function mapPropertiesToDbFormat(): array
    {
        $reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties();

        $mappedProperties = [];
        foreach ($properties as $property)
        {
            $propertyName = $property->getName();
            $propertyNameAsUnderscore = $this->camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyNameAsUnderscore] = $this->$propertyName;
        }

        return $mappedProperties;
    }

    /**
     * @param string $source
     * @return string
     */
    private function underscoreToCamelCase(string $source): string
    {
        return lcfirst(str_replace('_', '', ucwords($source, '_')));
    }

    /**
     * @param string $source
     * @return string
     */
    private function camelCaseToUnderscore(string $source): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source));
    }

    /**
     * @return string
     */
    abstract protected static function getTableName(): string;

}