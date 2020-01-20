<?php

namespace App\Controller;

use App\Controller\AppController;

class CategoriesController extends AppController {

    public function initiliaze(){
        parent::initialize();
    }

    public function index(){
        $categories = $this->Categories->find('all')->toArray();
        $response['status'] = 200;
        $response['message'] = "success";
        $response['categories'] = $categories;
        return $this->json($response);
    }
}