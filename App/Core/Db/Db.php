<?php

namespace Core\Db;

use Core\AUtility;
use Exception;
use PDO;
use PDOException;

class Db extends AUtility
{

    private static array $conf;

    private PDO $connection;

    /**
     * Singleton instance
     * @var Db
     */
    private static Db $instance;

    private function __construct()
    {
        try {
            $conf = self::$conf;
            $this->connection = new PDO("mysql:host={$conf['host']}; port={$conf['port']}; dbname={$conf['name']};charset={$conf['charset']}",
                "{$conf['username']}",
                "{$conf['password']}",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
                ]);
        } catch (PDOException $ex) {
            $this->fatal('No DB Connection<br>' . $ex->getMessage(), 500);
        }
    }

    /**
     *  Insert
     * @param string $sql
     * @param DbParam[] $params
     * @return int
     * @throws Exception
     */
    public function execute(string $sql, array $params = []): int
    {
        // Statement prep
        $stmt = $this->connection->prepare($sql);
        // Param pass
        foreach ($params as $param) {
            $stmt->bindValue($param->name, $param->value, $param->type);
        }
        // Statement exec
        if (!$stmt->execute())
            throw new Exception("Statement {$sql} didn't pass");
        return $stmt->rowCount();
    }

    /**
     *    Return More rows
     *
     * @param string $sql
     * @param string $className
     * @param DbParam[] $params
     * @return array
     * @throws Exception
     */
    public function getAll(string $sql, string $className, array $params = []): array
    {
        $stmt = $this->connection->prepare($sql);
        foreach ($params as $param) {
            $stmt->bindValue($param->name, $param->value, $param->type);
        }
        if (!$stmt->execute())
            throw new Exception("Statement {$sql} didn't pass");
        $stmt->setFetchMode(PDO::FETCH_CLASS, $className);
        return $stmt->fetchAll();
    }

    /**
     * @param string $sql
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function getAssoc(string $sql, array $params = []): array
    {
        // Statement prep
        $stmt = $this->connection->prepare($sql);
        // Param pass
        foreach ($params as $param) {
            $stmt->bindValue($param->name, $param->value, $param->type);
        }
        // Statement exec
        if (!$stmt->execute())
            throw new Exception("Statement {$sql} didn't pass");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }

    /**
     * Return One row
     * @return int
     */
    public function getLastId(): int
    {
        return $this->connection->lastInsertId();
    }

    /**
     * @param string $sql
     * @param string $className
     * @param DbParam[] $params
     * @return mixed
     * @throws Exception
     */
    public function getOne(string $sql, string $className, array $params = []): mixed
    {
        // Statement prep
        $stmt = $this->connection->prepare($sql);
        // Param pass
        foreach ($params as $param) {
            $stmt->bindValue($param->name, $param->value, $param->type);
        }
        // Statement exec
        if (!$stmt->execute())
            throw new Exception("Statement {$sql} didn't pass");
        $stmt->setFetchMode(PDO::FETCH_CLASS, $className);
        $res = $stmt->fetch();
        if ($res === false)
            return null;
        return $res;
    }

    /**
     * @param string $sql
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function getValue(string $sql, array $params = []): mixed
    {
        // Statement prep
        $stmt = $this->connection->prepare($sql);
        // Param pass
        foreach ($params as $param) {
            $stmt->bindValue($param->name, $param->value, $param->type);
        }
        // Statement exec
        if (!$stmt->execute())
            throw new Exception("Statement {$sql} didn't pass");
        $stmt->setFetchMode(PDO::FETCH_NUM);
        $res = $stmt->fetch();
        if ($res === false)
            return null;
        return $res[0];
    }

    /**
     * Singleton getter
     * @return Db
     */
    public static function get(): Db
    {
        if (!isset(self::$instance)) {
            self::$instance = new Db();
        }
        return self::$instance;
    }

    public static function set(string $host, string $port, string $name, string $charset, string $username, string $password): void
    {
        self::$conf = [
            'host' => $host,
            'port' => $port,
            'name' => $name,
            'charset' => $charset,
            'username' => $username,
            'password' => $password,
        ];
    }

}