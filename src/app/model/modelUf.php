<?php

require_once('../app/model/model.php');

class modelUf extends model
{
    public function list()
    {
        try {
            $sql = "Select * from uf order by uf.uf asc";
            $stmt = $this->dbconnection->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }
    }

    public function show($id)
    {
        try {
            $sql = "Select *
                from uf
                where uf.id = :id";
            $stmt = $this->dbconnection->prepare($sql);
            $stmt->execute(array('id' => $id));
            return $stmt->fetch();
        } catch (PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }
    }
}