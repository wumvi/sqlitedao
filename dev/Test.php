<?php


class Test extends \Wumvi\Sqlite3Dao\DbDao
{
    public function fetchAll()
    {
        return $this->db->tableFetchAll('select * from test_table');
    }

    public function fetchFirst()
    {
        return $this->db->tableFetchFirst('select * from test_table');
    }

    public function insert()
    {
        $this->db->exec('insert into test_table (name) values ("test")');
    }
}
