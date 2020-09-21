<?php
declare(strict_types=1);

namespace Wumvi\Sqlite3Dao;

class DbManager
{
    private string $filename;
    private ?\SQLite3 $connection = null;
    private string $journalMode = '';


    public function __construct(string $url, string $journalMode = '')
    {
        $this->filename = $url;
        $this->journalMode = $journalMode;
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

        if ($this->journalMode !== '') {
            $this->connection->exec('PRAGMA journal_mode = ' . $this->journalMode);
        }

        return $this->connection;
    }
}
