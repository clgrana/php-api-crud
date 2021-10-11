<?php


class address extends Controller
{

    public function index()
    {
        require_once('../app/model/modelAddress.php');
        $mdlAddress = new modelAddress();

        echo json_encode($mdlAddress->list());
    }

    public function show($params)
    {
        require_once('../app/model/modelAddress.php');
        $mdlAddress = new modelAddress();

        echo json_encode($mdlAddress->show($params[0]));
    }
}