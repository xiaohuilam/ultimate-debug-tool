<?php
namespace Xiaohuilam\UltDebug;
class App{
    protected $condition;
    protected $msg_if_fail;
    protected $goups = [];
    protected $hide_entities = [];
    protected $reset_condition = 'false';

    public function appendGroup(string $group = 'GROUP NAME', $data){
        $this->groups[$group] = $data;
    }

    public function hideEntities(array $list = []){
        foreach($list as $k=>$v) $this->hide_entities[$v] = 1;
    }

    public function resetIf(string $condition_if = 'json.code == 2015'){
        $this->reset_condition = $condition_if;
    }

    public function successIf(string $condition_if_success = 'json.code == 10000'){
        $this->condition = $condition_if_success;
    }

    public function msgIfFail(string $the_way_to_pick_msg = 'json.msg'){
        $this->msg_if_fail = $the_way_to_pick_msg;
    }

    public function exportHtml() {

        $output = '';
        $output .= <<<HTML
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
    *{border-radius: 0!important}
    .modal-footer {border-top: none;}
    select.form-control,select.form-control:hover,select.form-control:focus,select.form-control:active { line-height: normal; outline: 1px solid #D1D1D1; outline-offset: -1px; border: 0; box-shadow: inset 0 2px 1px rgba(0,0,0,.075); text-indent: 5px; }
    </style>
    <script> window.maps = {};if('undefined' == typeof window._timeout) window._timeout = 5000;</script>
</head>
<body>
    <div class="col-md-8 col-md-offset-2">
HTML;
        foreach($this->groups as $group_name => $group_data)
        {
            $output .= <<<HTML
            <h4>$group_name</h4>
HTML;

            foreach($group_data as $single_group)
            {
                $output .= <<<HTML
                        <div>
HTML;
                foreach($single_group as $btn_name => $steps)
                {
                    $output .= <<<HTML
                    <h3 class="button">$btn_name</h3>
HTML;
                    foreach($steps as $step)
                    {
                        $url = $step['url'];
                        $output .= <<<HTML
                        <div class="panel panel-default">
                            <div class="panel-body">
                                  <div class="form-group">
                                      <p class="text-muted">URL:</p>
                                      <code>$url</code>
                                  </div>
                                  <div class="form-group">
                                      <p class="text-muted">请求参数:</p>
                                      <table class="table table-bordered">
                                          <thead>
                                              <tr>
                                                  <th>NAME</th>
                                                  <th>FORMAT</th>
                                              </tr>
                                          </thead>
                                          <tbody>
HTML;
                        if(!$step['data'])
                            $output .= '<tr><td colspan="2">none</td></tr>';

                        foreach($step['data'] as $key=>$value)
                        {
                            $output .= <<<HTML
<tr>
<th>$key</th>
HTML;
                            if($value instanceof RegExp)
                            {
                                $s = $value->getRegExp();
                                $output .= <<<HTML
<td>$s</td>
HTML;
                            }
                            else if($value instanceof StoreGet)
                            {
                                $s = '前面返回的'.$value->key;
                                $output .= <<<HTML
<td>$s</td>
HTML;
                            }
                            else
                            {
                                $s = ''.htmlspecialchars($value).'';
                                $output .= <<<HTML
<td>$s</td>
HTML;
                            }

                            $output .= "</tr>";
                        }
                        $output .= <<<HTML
                            </tbody>
                        </table>
                      </div>
                      <div class="form-group">
                          <p class="text-muted">响应:</p>
<pre>
HTML;
                        $data = [];
                        foreach($step['done'] as $key=>$value)
                        {
                            if($value instanceof StoreSet && preg_match('/^json\.data/', $value->value))
                            {
                                $kk = substr($value->value, 10);
                                $dd = explode('.', $kk);

                                $kl = [];

                                $now = &$data;
                                foreach($dd as $i => $d)
                                {
                                    if(!isset($now[$d])) $now[$d] = [];
                                    if($d == array_reverse($dd)[0]) $now[$d] = '';
                                    $now = &$now[$d];
                                }

                            }
                        }

                        $output .= json_encode(['code' => 1000, 'msg' => 'OK', 'data' => $data], JSON_PRETTY_PRINT);
                        $output .= 
                            <<<HTML
                        </pre>
                      </div>
                    </div>
                </div>
HTML;

                        /*
                        if(isset($step['done']) && is_array($step['done']))
                        {
                            foreach($step['done'] as $each)
                            {
                               if($each instanceOf StoreSet)
                               {
                               }
                            }
                        }
                         */
                $output .= <<<HTML
HTML;
                    }
                }
                $output .= <<<HTML
                </div>
HTML;
            }
        }
        
        return $output;
    }

    public function render(){
        if(isset($_GET['action'])){
            if($_GET['action'] == 'document' && @$_GET['format'] == 'html'){
                return $this->exportHtml();
            }
        }
        $script = '';
        $year = date('Y');
        $output = '';
        $output .= <<<HTML
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
    *{border-radius: 0!important}
    .modal-footer {border-top: none;}
    select.form-control,select.form-control:hover,select.form-control:focus,select.form-control:active { line-height: normal; outline: 1px solid #D1D1D1; outline-offset: -1px; border: 0; box-shadow: inset 0 2px 1px rgba(0,0,0,.075); text-indent: 5px; }
    </style>
    <script> window.maps = {};if('undefined' == typeof window._timeout) window._timeout = 5000;</script>
</head>
<body>
    <div class="col-md-8 col-md-offset-2">
        <form method="POST" enctype="multipart/form-data">
            <h2>DEBUGGER</h2>
            <div class="form-group">
                <ul id="tabs" class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#profile" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="true">Live demo</a></li>
                    <li role="presentation" class="dropdown">
                        <a href="#" id="tabDrop1" class="dropdown-toggle" data-toggle="dropdown" aria-controls="tabDrop1-contents" aria-expanded="false">Document <span class="caret"></span></a>
                        <ul class="dropdown-menu" aria-labelledby="tabDrop1" id="tabDrop1-contents">
                            <li><a href="?action=document&format=html" target="_blank">HTML</a></li>
                            <li><a href="?action=document&format=markdown" target="_blank">Markdown</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="fileds">
                <div class="col-md-6">
                <div class="row">
HTML;
        foreach($this->groups as $group_name => $group_data)
        {
            $output .= <<<HTML
            <h4>$group_name</h4>
HTML;
            $output .= <<<HTML
            <div class="form-group">
HTML;
            foreach($group_data as $single_group)
            {
                $output .= <<<HTML
                <div class="btn-group" role="group">
HTML;
                foreach($single_group as $btn_name => $steps)
                {
                    $btn_key = 'e'.md5($group_name.'|'.$btn_name);
                    $script .= <<<HTML
                    <script> window.maps['$btn_key'] = [
HTML;
                    foreach($steps as $step)
                    {
                        $script .= '{url: "'.$step['url'].'",data: {';
                        foreach($step['data'] as $key=>$value)
                        {
                            $script .= $key.':';
                            if($value instanceof RegExp)
                            {
                                $script .= $value->getRegExp();
                            }
                            else if($value instanceof StoreGet)
                            {
                                $script .=$value->get();
                            }
                            else
                            {
                                $script .= '"'.htmlspecialchars($value).'"';
                            }

                            $script .= ",";
                        }
                        $script .= '},callback:function(param, json){'; 

                        if(isset($step['done']) && is_array($step['done']))
                        {
                            foreach($step['done'] as $each)
                            {
                               if($each instanceOf StoreSet)
                               {
                                   $script .= $each->set();
                               }
                            }
                        }
                        $script .= '}},';
                    }
        
                    $script .= <<<HTML
                    ]; </script>
HTML;
                    $output .= <<<HTML
                    <button type="button" class="btn btn-default" data-key="$btn_key" onclick="ultimate_unit(this);">$btn_name</button>
HTML;
                }
                $output .= <<<HTML
                </div>
HTML;
            }
            $output .= <<<HTML
            </div>
HTML;
        }
        $output .= <<<HTML
            </div>
    </div>
    </div>
                <div class="col-md-6">
                <div class="row">
                <h4>请求体</h4>
                <div class="entities">

                </div>
                </div>
    </div>
                <div class="form-group">
                    <textarea class="form-control" rows="10" style="min-height: 220px; resize: vertical;" id="log"></textarea>
                </div>
        </form>
        <p class="text-muted"><small>&copy; $year XIAOHUILAM's <a href="https://github.com/xiaohuilam/ultimate-debug-tool" target="_blank">ultimate_debug_tool</a>.</small></p>
    </div>
    <script src="//cdn.jsdelivr.net/jquery/1.12.4/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="//cdn.jsdelivr.net/bootbox/4.4.0/bootbox.min.js"></script>
    <script src="//cdn.jsdelivr.net/gh/fent/randexp.js@0.4.3/build/randexp.min.js"></script>
HTML;
        $script .= '<script>window.hide_entities='.json_encode($this->hide_entities).';</script>';
        $output .= $script;

        $output .= <<<HTML

    <script>
window.setEntitie = function(a, b){
    window[a] = b;
    if('undefined' != typeof window.hide_entities[a]) return;
    if($('#'+a).length == 0){
        var dom = $('<input type="text" class="form-control" placeholder="" id="" style="border-right: 0;">');
        dom.attr({
            placeholder: a,
            id: a
        });
        dom.val(b);
        var str = [
            '<div class="form-group">',
            '    <div class="col-md-4">',
            '        <div class="row xx">',
            '        </div>',
            '    </div>',
            '    <button type="button" class="btn btn-default" onclick="save_auth();">保存</button>',
            '</div>'
        ].join('');
        d = $(str);
        d.find('.xx').append(dom);
        d.find('button').text('修改'+a);
        d.find('button').attr('data-key', a);
        d.find('button').attr('onclick', 'window.save_entities(this);');
        $('.entities').append(d);
    }else{
        $('#'+a).val(b);
    }
};
window.getEntitie = function(a){
    return window[a];
}
window.save_entities = function(a){
    window[$(a).attr('data-key')] = $('#'+$(a).attr('data-key')).val();
}
window.i = 0;
var f = function(action, cb){
    var auth = window.authorization;
    $('.btn').attr('disabled','disabled');
    var xhr = $.ajax({
        type: 'post',
        url: action.url,
        data: action.data,
        headers: {
            Authorization: auth 
        },
        dataType: 'json',
        timeout: window._timeout,
        complete: function(){
        },
        success: function(json, a, xhr){
            window.response = json;
HTML;
        
        $output .= '$("#log").scrollTop(50000); if('.$this->condition."){
            $('#log').text($('#log').text()+xhr.url+' OK'+' '+'\\r\\n');
        }else{
            if('.$this->reset_condition.'){
                $('.btn').removeAttr('disabled');
                window.i=0;
            }
            $('#log').text($('#log').text()+xhr.url+' FAIL '+".$this->msg_if_fail."+' '+'\\r\\n'); return;
        }";

        $output .= <<<HTML
            cb(json);
        },
        error: function(xhr, e) {
            $('.btn').removeAttr('disabled');
            window.i = 0;
            try{
                eval('json='+xhr.responseText);
                $('#log').text($('#log').text()+xhr.url+' FAIL '+".$this->msg_if_fail."+' '+'\\r\\n'); return;
            }catch(e){
                $('#log').text($('#log').text()+xhr.url+' FAIL'+' '+'\\r\\n');
            }
        }
    });
    xhr.url = action.url;
};
var ultimate_unit = function(a){
    var key = $(a).attr('data-key');
    var actions = $.extend(true, {}, window.maps[key]);
    if('undefined' == typeof window.response) window.response = {data:{}};
    if('undefined' == typeof actions[window.i]){
        $('.btn').removeAttr('disabled');
        return window.i=0;
    }
    var action = actions[window.i];
    for(var j in action.data){
        if('function' == typeof action.data[j]){
            action.data[j] = action.data[j]();
        }else{
            action.data[j] = (new RandExp(action.data[j])).gen();
        }
    }
    f(action, function(json){
        if('undefined' !== typeof action.callback) action.callback(action.data, json);
        if(window.i >= actions.length) bootbox.alert(JSON.stringify(json));
        window.i++;
        ultimate_unit(a);
    });
    if(window.i >= actions.length - 1) setTimeout(function(){
        $('.btn').removeAttr('disabled');
        window.i = 0;
    }, 2000);
};
window.onerror = function(){
    $('.btn').removeAttr('disabled');
    window.i = 0;
};
    </script>
</body>
</html>
HTML;
        return $output;
    }
}
