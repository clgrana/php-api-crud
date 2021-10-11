<?php


class uf extends Controller
{

    public function index()
    {
        require_once('../app/model/modelUf.php');
        $mdlUf = new modelUf();

        echo json_encode($mdlUf->list());
    }

    public function show($params)
    {
        require_once('../app/model/modelUf.php');
        $mdlUf = new modelUf();

        echo json_encode($mdlUf->show($params[0]));
    }
}