<?php


class user extends Controller
{

    public function index()
    {
        require_once('../app/model/modelUser.php');
        $mdlUser = new modelUser();

        echo json_encode($mdlUser->list());
    }

    public function show($params)
    {
        require_once('../app/model/modelUser.php');
        $mdlUser = new modelUser();
        $id = $params[0];
        $r = $mdlUser->show($id);
        if (!$r['status'])
            http_response_code(400);
        echo json_encode($r);
    }

    public function store($params, $post)
    {
        $errors = $this->validateRequest($post);
        if(count($errors) > 0){
            http_response_code(423);
            echo json_encode($errors);
            return;
        }

        require_once('../app/model/modelUser.php');
        $mdlUser = new modelUser();

        $responseUser = $mdlUser->create($post);
        if (!$responseUser['status']) {
            http_response_code(400);
        }
        echo json_encode($responseUser);
    }

    public function update($params, $post)
    {
        $id = $params[0];

        $errors = $this->validateRequest($post, $id);
        if(count($errors) > 0){
            http_response_code(423);
            echo json_encode($errors);
            return;
        }

        require_once('../app/model/modelUser.php');
        $mdlUser = new modelUser();

        $responseUser = $mdlUser->update($id, $post);
        if (!$responseUser['status']) {
            http_response_code(400);
        }
        echo json_encode($responseUser);
    }

    public function delete($params)
    {
        require_once('../app/model/modelUser.php');
        $mdlUser = new modelUser();
        $id = $params[0];
        $responseUser = $mdlUser->delete($id);

        if (!$responseUser['status']) {
            http_response_code(400);
        }
        echo json_encode($responseUser);
    }

    public function city()
    {
        require_once('../app/model/modelUser.php');
        $mdlUser = new modelUser();
        $response = $mdlUser->byCity();
        if (!$response['status']) {
            http_response_code(400);
        }
        echo json_encode($response);
    }

    public function uf()
    {
        require_once('../app/model/modelUser.php');
        $mdlUser = new modelUser();
        $response = $mdlUser->byUf();
        if (!$response['status']) {
            http_response_code(400);
        }
        echo json_encode($response);
    }

    private function validateRequest($data, $userId = null)
    {
        require_once('../app/model/modelUser.php');
        $mdlUser = new modelUser();

        $errors = [];
        if (isset($data['name']) && strlen($data['name']) > 0) {
            if(strlen($data['name']) > 255){
                $errors['name'] = 'name must be less than 255 characters ';
            }
        } else {
            $errors['name'] = 'Name is required';
        }

        if (isset($data['email']) && strlen($data['email']) > 0) {
            $v = "/[a-zA-Z0-9_-.+]+@[a-zA-Z0-9-]+.[a-zA-Z]+/";
            if(strlen($data['email']) > 255){
                $errors['email'] = 'email must be less than 255 characters';
            }
        } else {
            $errors['email'] = 'email is required';
        }

        if(!isset($errors['email'])) {
            if($mdlUser->byEmail($data['email'], $userId) > 0){
                $errors['email'] = 'email already in use';
            }
        }

        if (isset($data['address']) && strlen($data['address']) > 0) {
            if(strlen($data['address']) > 255){
                $errors['address'] = 'address must be less than 255 characters ';
            }
        } else {
            $errors['address'] = 'address is required';
        }

        if (isset($data['city']) && strlen($data['city']) > 0) {
            if(strlen($data['city']) > 255){
                $errors['city'] = 'city must be less than 255 characters ';
            }
        } else {
            $errors['city'] = 'city is required';
        }

        if (isset($data['uf']) && strlen($data['uf']) > 0) {
            $state = array( "AC", "AL", "AM", "AP", "BA", "CE", "DF", "ES", "GO", "MA", "MT", "MS", "MG", "PA", "PB", "PR", "PE", "PI", "RJ", "RN", "RO", "RS", "RR", "SC", "SE", "SP", "TO" );
            if(!in_array(strtoupper($data['uf']), $state)){
                $errors['uf'] = 'invalid uf';
            }
        } else {
            $errors['uf'] = 'uf is required';
        }
        return $errors;
    }
}