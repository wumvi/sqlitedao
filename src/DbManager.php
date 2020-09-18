<?php
declare(strict_types=1);

namespace Wumvi\Sqlite3Dao;

class DbManager
{
    private string $filename;

    /** @var \SQLite3|null */
    private $connection = null;

    public function __construct(string $url)
    {
        $this->filename = $url;
    }

    public function disconnect(): void
    {
        if ($this->connection !== null) {
            $this->connection->close();
        }
    }

    /**
     * @return \SQLite3
     */
    public function getConnection()
    {
        if ($this->connection !== null) {
            return $this->connection;
        }

        if (!is_file($this->filename)) {
            throw new DbException('File ' . $this->filename . ' is not readable');
        }

        $this->connection = new \SQLite3($this->filename);

        return $this->connection;
    }
}
