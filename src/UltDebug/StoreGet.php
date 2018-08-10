<?php
namespace Xiaohuilam\UltDebug;

class StoreGet{

    public $type = self::class;

    public $key = null;

    public function __construct($key){
        $this->key = $key;
    }
    public function get(){
        return 'function(){return window.getEntitie("'.$this->key.'");}';
    }

}
