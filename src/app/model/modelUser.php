<?php

require_once('../app/model/model.php');

class modelUser extends model
{
    public function list()
    {
        try {
            $sql = "select * from users order by id asc";
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
            $sql = "Select users.*, address.address, city.city, uf.uf from users inner join address on users.id = 
                address.user_id inner join city on address.city_id = city.id inner join uf on address.uf_id = uf.id where users.id = :id";
            $stmt = $this->dbconnection->prepare($sql);
            $stmt->execute(array('id' => $id));
            $user = $stmt->fetch();
            if($user == null){
                return ['status' => false, 'message' => 'user not found'];
            }
            return ['status' => true, 'user' => $user];
        } catch (PDOException $e) {
            return 'ERROR: ' . $e->getMessage();
        }
    }

    public function create($data)
    {
        try {
            $sql = "INSERT INTO users (name, email) VALUES (:name, :email);";
            $stmt = $this->dbconnection->prepare($sql);
            $stmt->execute(['name' => $data['name'], 'email' => $data['email']]);
            $stmt = $this->dbconnection->query("SELECT LAST_INSERT_ID()");
            $lastUserId = $stmt->fetchColumn();

            require_once('../app/model/modelCity.php');
            $mdlCity = new modelCity();
            require_once('../app/model/modelAddress.php');
            $mdlAddress = new modelAddress();

            $city = $mdlCity->createOrFind(
                [
                    'city' => $data['city'],
                    'uf' => $data['uf'],
                ]
            );

            $mdlAddress->create(
                [
                    'address' => $data['address'],
                    'user_id' => $lastUserId,
                    'city_id' => $city['city']['id'],
                    'uf_id' => $city['city']['uf_id']
                ]
            );
            return ['status' => true, 'message' => 'success'];
        } catch (PDOException $e) {
            return ['status' => false, 'message' => 'ERROR: ' . $e->getMessage()];
        }
    }

    public function update($id, $data)
    {
        try {
            $sql = "UPDATE users SET name=:name, email=:email WHERE id=:id;";
            $this->dbconnection->prepare($sql)->execute(['name' => $data['name'], 'email' => $data['email'], 'id' => $id]);

            require_once('../app/model/modelCity.php');
            $mdlCity = new modelCity();
            require_once('../app/model/modelAddress.php');
            $mdlAddress = new modelAddress();

            $city = $mdlCity->createOrFind(
                [
                    'city' => $data['city'],
                    'uf' => $data['uf'],
                ]
            );

            $mdlAddress->delete($id);
            $mdlAddress->create(
                [
                    'address' => $data['address'],
                    'user_id' => $id,
                    'city_id' => $city['city']['id'],
                    'uf_id' => $city['city']['uf_id']
                ]
            );

            return ['status' => true, 'message' => 'success'];
        } catch (PDOException $e) {
            return ['status' => false, 'message' => 'ERROR: ' . $e->getMessage()];
        }
    }

    public function delete($id)
    {
        try {
            require_once('../app/model/modelAddress.php');
            $mdlAddress = new modelAddress();

            $mdlAddress->delete($id);
            $this->dbconnection->prepare("DELETE FROM users WHERE id= :id")->execute(['id' => $id]);
            return ['status' => true, 'message' => 'success'];
        } catch (PDOException $e) {
            return ['status' => false, 'message' => 'ERROR: ' . $e->getMessage()];
        }
    }

    public function byCity()
    {
        try {

            $sql = "select city_id, city.city, count(*) as total from address inner join city on address.city_id = city.id group by city_id, city.city order by city.city asc";
            $stmt = $this->dbconnection->prepare($sql);
            $stmt->execute();
            return ['status' => true, 'data' => $stmt->fetchAll()];
        } catch (PDOException $e) {
            return ['status' => false, 'message' => 'ERROR: ' . $e->getMessage()];
        }
    }

    public function byUf()
    {
        try {
            $sql = "select uf_id, uf.uf, count(*) as total from address inner join uf on address.uf_id = uf.id group by uf_id, uf.uf  order by uf.uf asc";
            $stmt = $this->dbconnection->prepare($sql);
            $stmt->execute();
            return ['status' => true, 'data' => $stmt->fetchAll()];
        } catch (PDOException $e) {
            return ['status' => false, 'message' => 'ERROR: ' . $e->getMessage()];
        }
    }

    public function byEmail($email, $userId = null)
    {
        try {
            if($userId == null){
                $sql = "select * from users where email = :email";
                $stmt = $this->dbconnection->prepare($sql);
                $stmt->execute(['email' => $email]);

            }else{
                $sql = "select * from users where email = :email and id = :id";
                $stmt = $this->dbconnection->prepare($sql);
                $stmt->execute(['email' => $email, 'id' => $userId]);
            }
            return count($stmt->fetchAll());
        } catch (PDOException $e) {
            return ['status' => false, 'message' => 'ERROR: ' . $e->getMessage()];
        }
    }
}