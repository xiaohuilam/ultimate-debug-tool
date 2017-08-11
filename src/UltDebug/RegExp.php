<?php
namespace Xiaohuilam\UltDebug;
class RegExp{
    var $regexp = null;
    public function __construct($regexp){
        $this->regexp = $regexp;
    }
    public function getRegExp(){
        return $this->regexp;
    }
    public static function make($regex){
        return new self($regex);
    }
}
