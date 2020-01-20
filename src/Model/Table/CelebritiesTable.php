<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class CelebritiesTable extends Table {

    public function initialize(array $config) {
        parent::initialize($config);
        $this->setTable('celebrities');
    }
}