<?php
namespace Xiaohuilam\UltDebug;

class StoreSet
{
    public $type = self::class;

    public $where = null;

    public $key = null;

    public $value = null;

    public $accessvalue = null;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;

        if (substr($this->value, 0, 5) == 'json.') {
            $this->where = 'json';
        } else {
            $this->where = 'param';
        }
        $this->accessvalue = $this->value;

        $this->accessvalue = preg_replace('/^json\./', '', $this->accessvalue);
        $this->accessvalue = preg_replace('/^param\./', '', $this->accessvalue);
    }
    public function set()
    {
        return 'window.setEntitie("' . $this->key . '", ' . $this->value . ');';
    }
}
