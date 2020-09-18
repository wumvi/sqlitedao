<?php
declare(strict_types=1);

namespace Wumvi\Sqlite3Dao;

class PgFetch
{
    private DbManager $dbManager;
    private bool $isDebug;

    public function __construct(DbManager $dbManager, $isDebug = false)
    {
        $this->dbManager = $dbManager;
        $this->isDebug = $isDebug;
    }

    public static function escapeString(string $string): string
    {
        return \SQLite3::escapeString($string);
    }

    public function lastInsertRowID()
    {
        $connection = $this->dbManager->getConnection();

        return $connection->lastInsertRowID();
    }

    public function exec(string $sql): void
    {
        $connection = $this->dbManager->getConnection();
        $connection->exec($sql);
    }

    /**
     * @param string $sql
     * @param array<mixed> $vars
     *
     * @return array<mixed>
     *
     * @throws DbException
     */
    public function tableFetchFirst(string $sql, array $vars = []): array
    {
        $connection = $this->dbManager->getConnection();
        $fetch = $this->fetch($sql, $vars);
        $result = $fetch->fetchArray(SQLITE3_ASSOC);
        if ($result === false) {
            self::triggerError($connection, $sql, $vars, $this->isDebug);
            throw new DbException('error-to-sql-' . $sql);
        }

        return $result;

    }

    private function fetch(string $sql, array $vars = []): \SQLite3Result
    {
        $connection = $this->dbManager->getConnection();
        $stmt = $connection->prepare($sql);
        if ($stmt === false) {
            self::triggerError($connection, $sql, $vars, $this->isDebug);
            throw new DbException('error-to-sql-' . $sql);
        }
        array_map(fn($name, $value) => $stmt->bindValue(':' . $name, $value), array_keys($vars), $vars);
        return $stmt->execute();
    }

    /**
     * @param string $sql
     * @param array<mixed> $vars
     *
     * @return array<mixed>
     *
     * @throws DbException
     */
    public function tableFetchAll(string $sql, array $vars = []): array
    {
        $result = [];
        $fetch = $this->fetch($sql, $vars);
        if ($fetch->numColumns()) {
            while ($row = $fetch->fetchArray(SQLITE3_ASSOC)) {
                $result[] = $row;
            }
        }

        return $result;
    }

    /**
     * @param \SQLite3 $connection
     * @param string $sql
     * @param array<mixed> $vars
     * @param bool $isDebug
     */
    public static function triggerError(\SQLite3 $connection, string $sql, array $vars, bool $isDebug): void
    {
        if ($isDebug) {
            $msg = sprintf(
                "Msg: %s\nSql: %s\nVars: %s",
                $connection->lastErrorMsg(),
                $sql,
                var_export($vars, true)
            );
            trigger_error($msg);
        }
    }
}
