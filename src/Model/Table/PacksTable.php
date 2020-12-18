<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class PacksTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->setTable('packs');
        $this->hasMany('Configurations');
        $this->hasMany('Categories');
    }

    public function processPack($pack, $msisdn) {

        $options = array(
            'conditions' => array(
                'type' => 'subscribe'
            )
        );
        $config = $this->Configurations->find('all', $options)->toArray();
        $url = $config[0]['url'];
        $payload = $this->getPayRoad($config, $pack, $msisdn);

        $xml = $this->sentCurlRequest($url, $payload);
        $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $xml);
        $response = $this->Configurations->ParseXMLRequest($xml);
        return $response;
    }

    public function sentCurlRequest($url, $xml){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $content = curl_exec($ch);
        return $content;
    }

    public function getPayRoad($config, $pack, $msisdn){
        $xml = '
        <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:char="http://xmlns.esf.mtn.com/xsd/ChargingDetails">
            <soapenv:Header xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                    <wsse:Security>
                            <wsse:UsernameToken>';
        $xml = $xml . '<wsse:Username>'. $config[0]['username'].'</wsse:Username>';
        $xml = $xml . '<wsse:Password>'. $config[0]['password']. '</wsse:Password>';
        $xml = $xml . '</wsse:UsernameToken>
                    </wsse:Security>
            </soapenv:Header>
            <soapenv:Body>
                    <char:ChargingRequest>';
        $xml = $xml . '<char:MSISDNNum>'. $msisdn. '</char:MSISDNNum>';
        $xml = $xml . '<char:ProcessingNumber>5135160155001</char:ProcessingNumber>
            <char:CPId>2500110002973</char:CPId>
            <char:CPName>Tracar</char:CPName>';
        $xml = $xml . '<char:ProductName>'. $pack[0]['ProductName']. '</char:ProductName>';
        $xml = $xml . '<char:OpCoID>RW</char:OpCoID>';
        $xml = $xml . '<char:Amount>'. $pack[0]['amount'] .'</char:Amount>';
        $xml = $xml . '</char:ChargingRequest>
            </soapenv:Body>
    </soapenv:Envelope>';

        return $xml;
    }
}