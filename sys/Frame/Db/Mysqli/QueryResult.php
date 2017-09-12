<?php
namespace Pcs\Frame\Db\Mysqli;

class QueryResult
{
    public $result;
    public $insertId;
    public $affectedRows;
    public $errno;
    public $error;

    public function __construct($conn, $result)
    {
        $this->insertId = $conn->insert_id;
        $this->affectedRows = $conn->affected_rows;
        $this->errno = $conn->errno;
        $this->error = $conn->error;
        $this->result = $result;
    }
}