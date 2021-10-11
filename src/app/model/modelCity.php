<?php

require_once('../app/model/model.php');

class modelCity extends model
{
    public function list()
    {
        try {
            $sql = "Select * from city order by city.city asc";
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
            $sql = "Select city.id, city.city, uf.uf, uf.name as uf_name
                from city
                inner join uf on city.uf_id = uf.id
                where city.id = :id";
            $stmt = $this->dbconnection->prepare($sql);
            $stmt->execute(array('id' => $id));
            return $stmt->fetch();
        } catch (PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }
    }

    public function createOrFind($data)
    {
        try {
            $match = array('à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ü', 'ú', 'ÿ', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'O', 'Ù', 'Ü', 'Ú');
            $replace = array('a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'y', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U');
            $data['city'] = strtoupper(trim(str_replace($match, $replace, $data['city'])));

            $sql = "Select city.id, uf.id as uf_id from city
                inner join uf on city.uf_id = uf.id
                where city.city = :city AND uf.uf = :uf";
            $stmt = $this->dbconnection->prepare($sql);
            $stmt->execute(['city' => $data['city'], 'uf' => strtoupper($data['uf'])]);
            $city = $stmt->fetch();

            if($city == null) {

                $sqlUf = "select * from uf where uf.uf = :uf";
                $stmt = $this->dbconnection->prepare($sqlUf);
                $stmt->execute(['uf' => strtoupper($data['uf'])]);
                $uf = $stmt->fetch();

                $sql = "INSERT INTO city (city, uf_id) VALUES (:city, :uf_id);";
                $stmt = $this->dbconnection->prepare($sql);
                $stmt->execute(['city' => $data['city'], 'uf_id' => $uf['id']]);

                $stmt = $this->dbconnection->query("SELECT LAST_INSERT_ID()");
                $lastId = $stmt->fetchColumn();

                return ['status' => true, 'city' => $this->show($lastId)];
            }
            return ['status' => true, 'city' => $city];
        } catch (PDOException $e) {
            return ['status' => false, 'message' => 'ERROR: ' . $e->getMessage()];
        }
    }
}