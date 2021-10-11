<?php

require_once('../app/model/model.php');

class modelAddress extends model
{
    public function list()
    {
        try {
            $sql = "Select * from address order by id asc";
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
            $sql = "Select *, uf.uf, uf.name as uf_name, city.city
                from address
                inner join city on address.city_id = city.id
                inner join uf on address.uf_id = uf.id
                where address.id = :id";
            $stmt = $this->dbconnection->prepare($sql);
            $stmt->execute(array('id' => $id));
            return $stmt->fetch();
        } catch (PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }
    }

    public function create($data)
    {
        try {
            $sql = "INSERT INTO address (address, city_id, uf_id, user_id) VALUES (:address, :city_id, :uf_id, :user_id);";
            $stmt = $this->dbconnection->prepare($sql);
            $stmt->execute($data);
            return ['status' => true];
        } catch (PDOException $e) {
            return ['status' => false, 'message' => 'ERROR: ' . $e->getMessage()];
        }
    }

    public function delete($userId)
    {
        try {
            $this->dbconnection->prepare("DELETE FROM address WHERE user_id= :user_id")->execute(['user_id' => $userId]);
            return ['status' => true];
        } catch (PDOException $e) {
            return ['status' => false, 'message' => 'ERROR: ' . $e->getMessage()];
        }
    }
}