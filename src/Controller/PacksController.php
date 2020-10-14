<?php

namespace App\Controller;

use App\Controller\AppController;

class PacksController extends AppController {

    public function initiliaze(){
        parent::initialize();
    }

    public function index(){
        $pack = $this->Packs->find('all')->toArray();
        $response['status'] = 200;
        $response['message'] = "success";
        $response['packs'] = $pack;
        return $this->json($response);
    }
}