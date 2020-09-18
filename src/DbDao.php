<?php
declare(strict_types=1);

namespace Wumvi\Sqlite3Dao;

/**
 * @codeCoverageIgnore
 */
class DbDao
{
    protected PgFetch $db;

    public function __construct(DbManager $dbManager, bool $isDebug = false)
    {
        $this->db = new PgFetch($dbManager, $isDebug);
    }
}
