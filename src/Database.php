<?php

class Database
{
    // Database connection credentials
    private $db_host = '';
    private $db_name = '';
    private $db_user = '';
    private $db_pass = '';
    protected $dbo;

    public function __construct()
    {
        $dsn = "mysql:host=".$this->db_host.";dbname=".$this->db_name.";charset=utf8";
        $this->dbo = new PDO(
            $dsn,
            $this->db_user,
            $this->db_pass,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'")
        );
    }

    public function query($sql, $params)
    {
        $stmt = $this->dbo->prepare($sql);
        $i = 0;
        foreach ($params as $p) {

            // Detect data type for bindValue
            if (is_int($p)) {
                $param = PDO::PARAM_INT;
            } elseif (is_bool($p)) {
                $param = PDO::PARAM_BOOL;
            } elseif (is_null($p)) {
                $param = PDO::PARAM_NULL;
            } elseif (is_string($p)) {
                $param = PDO::PARAM_STR;
            } else {
                $param = false;
            }

            $stmt->bindValue(++$i, $p, $param);
        }

        $result = $stmt->execute();
        if ($result) {
            return $stmt;
        } else {
            // Looks like there was a problem
            var_dump($stmt->errorInfo());

            return false;
        }

    }

    public function fetch($sql, $params = array())
    {
        $stmt = $this->query($sql, $params);
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return array();
        }
    }

}