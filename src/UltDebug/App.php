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

    /**
     * 添加一个组
     *
     * @param string $group 组名
     * @param array  $data  组内结构
     *
     * @return $this|self
     */
    public function appendGroup(string $group = 'GROUP NAME', $data)
    {
        $this->groups[$group] = $data;

        return $this;
    }

    /**
     * 隐藏暂存框
     *
     * @param array $list 暂存框列表
     *
     * @return $this|self
     */
    public function hideEntities(array $list = [])
    {
        foreach ($list as $k => $v) {
            $this->hide_entities[$v] = 1;
        }

        return $this;
    }

    /**
     * 重置条件
     *
     * @param string $condition_if 条件
     *
     * @return $this|self
     */
    public function resetIf(string $condition_if = 'json.code == 2015')
    {
        $this->reset_condition = $condition_if;
        return $this;
    }

    /**
     * 成功条件
     *
     * @param string $condition_if_success 条件
     *
     * @return $this|self
     */
    public function successIf(string $condition_if_success = 'json.code == 10000')
    {
        $this->condition = $condition_if_success;
        return $this;
    }

    /**
     * 失败消息
     *
     * @param string $the_way_to_pick_msg 取出失败消息表达式
     *
     * @return $this|self
     */
    public function msgIfFail(string $the_way_to_pick_msg = 'json.msg')
    {
        $this->msg_if_fail = $the_way_to_pick_msg;
        return $this;
    }

    /**
     * 渲染模板
     *
     * @return View
     */
    public function render()
    {
        return View::make('debug_tool.index', (array)$this)->render();
    }
}
