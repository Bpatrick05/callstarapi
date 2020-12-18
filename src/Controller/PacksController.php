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

    public function processPacks($id = false, $msisdn = false){
        if($id & $msisdn){
            $options = array(
                'conditions' => array(
                    'id' => $id
                )
            );
            $pack = $this->Packs->find('all', $options)->toArray();
            if(count($pack) > 0){
               $resp= $this->Packs->processPack($pack, $msisdn);
               if(!empty($resp) && $resp['charStatusCode'] == 0 && $resp[0]['charStatusDesc'] = 'Success'){
                   $categories = $this->Packs->Categories->find('all')->toArray();
                   $response['status'] = 200;
                   $response['message'] = "success";
                   $response['categories'] = $categories;
                } else {
                    $response['status'] = 201;
                    $response['message'] = "Subscription failed";
                    $response['error_message'] = "subscription_failed";
                }
            } else {
                $response['status'] = 201;
                $response['message'] = "Pack not found";
                $response['error_message'] = "pack_not_found";
            }
        } else {
            $response['status'] = 201;
            $response['message'] = "pack id required";
            $response['error_message'] = "pack_id_required";
        }
        return $this->json($response);
    }
}