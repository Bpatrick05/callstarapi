<?php

namespace App\Controller;

use App\Controller\AppController;

class CelebritiesController extends AppController {

    public function initiliaze(){
        parent::initialize();
    }

    public function index(){
        $celebrities = $this->Celebrities->find('all')->toArray();
        $response['status'] = 200;
        $response['message'] = "success";
        $response['categories'] = $celebrities;
        return $this->json($response);
    }

    public function getCelebritiesByCategory($category_id = false){

        if($category_id){
            $options = array(
                'conditions' => array(
                    'category_id' => $category_id
                )
            );
            $celebrities = $this->Celebrities->find('all', $options)->toArray();
            if(count($celebrities) > 0){
                $response['status'] = 200;
                $response['message'] = "success";
                $response['celebrities'] = $celebrities;
            } else {
                $response['status'] = 201;
                $response['message'] = "Not celebrities found";
                $response['error_message'] = "celebrities_not_found";
            }
        } else {
            $response['status'] = 201;
            $response['message'] = "the category id is Missing";
            $response['error_message'] = 'missing_category_id';
        }
        return $this->json($response);
    }
    public function getCelebrityById($id = false) {
        if($id){
            $options = array(
                'conditions' => array(
                    'id' => $id
                )
            );
            $celebrity = $this->Celebrities->find('all', $options)->toArray();
            if(count($celebrity) > 0){
                $response['status'] = 200;
                $response['message'] = "success";
                $response['celebrity'] = $celebrity;
            } else {
                $response['status'] = 201;
                $response['message'] = "No celebrity found";
                $response['error_message'] = "celebrity_not_found";
            }
        } else {
            $response['status'] = 201;
            $response['message'] = "the celebrity id is Missing";
            $response['error_message'] = 'missing_celebrity_id';
        }
        return $this->json($response);
    }
}