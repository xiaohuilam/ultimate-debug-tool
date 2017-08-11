<?php
namespace Xiaohuilam\UltDebug;
class StoreGet{
    var $key = null;
    public function __construct($key){
        $this->key = $key;
    }
    public function get(){
        return 'function(){return window.getEntitie("'.$this->key.'");}';
    }

}
