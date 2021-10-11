<?php


class city extends Controller
{

    public function index()
    {
        require_once('../app/model/modelCity.php');
        $mdlCity = new modelCity();

        echo json_encode($mdlCity->list());
    }

    public function show($params)
    {
        require_once('../app/model/modelCity.php');
        $mdlCity = new modelCity();

        echo json_encode($mdlCity->show($params[0]));
    }

}