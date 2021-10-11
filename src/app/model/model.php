<?php


class model
{
    protected $dbconnection;

    public function __construct()
    {
        $this->dbconnection = $this->startDB();
    }

    public function startDB()
    {
        try
        {
            $config = parse_ini_file('../config.ini');
            $conn = new PDO("mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']}", $config['username'], $config['password']);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $conn;
        }
        catch
        (PDOException $e) {
            exit('ERROR: ' . $e->getMessage());
        }
    }
}