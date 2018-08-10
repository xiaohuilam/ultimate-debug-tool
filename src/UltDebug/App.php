<?php
namespace Xiaohuilam\UltDebug;

use Illuminate\Support\Facades\View;

class App
{
    protected $condition;
    protected $msg_if_fail;
    protected $goups = [];
    protected $hide_entities = [];
    protected $reset_condition = 'false';

    public function appendGroup(string $group = 'GROUP NAME', $data)
    {
        $this->groups[$group] = $data;
    }

    public function hideEntities(array $list = [])
    {
        foreach ($list as $k => $v) $this->hide_entities[$v] = 1;
    }

    public function resetIf(string $condition_if = 'json.code == 2015')
    {
        $this->reset_condition = $condition_if;
    }

    public function successIf(string $condition_if_success = 'json.code == 10000')
    {
        $this->condition = $condition_if_success;
    }

    public function msgIfFail(string $the_way_to_pick_msg = 'json.msg')
    {
        $this->msg_if_fail = $the_way_to_pick_msg;
    }

    public function render()
    {
        return View::make('debug_tool.index', (array) $this)->render();
    }
}
