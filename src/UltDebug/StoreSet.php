<?php
namespace Xiaohuilam\UltDebug;
class StoreSet{
    var $key = null;
    var $value = null;
    public function __construct($key, $value){
        $this->key = $key;
        $this->value = $value;
    }
    public function set(){
        return 'window.setEntitie("'.$this->key.'", '.$this->value.');';
    }
}
