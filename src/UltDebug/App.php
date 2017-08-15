<?php
namespace Xiaohuilam\UltDebug;
class App{
    var $goups = [];
    var $hide_entities = [];

    public function appendGroup($group, $data){
        $this->groups[$group] = $data;
    }

    public function hideEntities($list){
        foreach($list as $k=>$v) $this->hide_entities[$v] = 1;
    }

    public function render(){
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
    <script> window.maps = {};</script>
</head>
<body>
    <div class="col-md-8 col-md-offset-2">
        <form method="POST" enctype="multipart/form-data">
            <h2>DEBUGGER</h2>
            <div class="fileds">
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
            <h4>请求体</h4>
                <div class="entities">

                </div>
                <div class="form-group">
                    <textarea class="form-control" rows="10" style="min-height: 220px; resize: vertical;" id="log"></textarea>
                </div>
            </div>
            <hr>
            <p class="text-muted"><small>&copy; $year XIAOHUILAM's <a href="https://github.com/xiaohuilam/ultimate-debug-tool" target="_blank">ultimate_debug_tool</a>.</small></p>
        </form>
    </div>
    <script src="//cdn.jsdelivr.net/jquery/1.12.4/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="//cdn.jsdelivr.net/bootbox/4.4.0/bootbox.min.js"></script>
    <script src="//cdnjs-shanghai.oss-cn-shanghai.aliyuncs.com/jsmock.js"></script>
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
    window[$(a).attr('data-key')] = $(a).val();
}
window.i = 0;
var f = function(action, cb){
    var auth = window.authorization;
    var xhr = $.ajax({
        type: 'post',
        url: action.url,
        data: action.data,
        headers: {
            Authorization: auth 
        },
        dataType: 'json',
        success: function(json, a, xhr){
            $('#log').text($('#log').text()+xhr.url+" OK"+" "+"\\r\\n");
            window.response = json;
            cb(json);
        },
        error: function(xhr, e) {
            $('#log').text($('#log').text()+xhr.url+" FAIL"+"\\r\\n");
            window.i = 0;
        }
    });
    xhr.url = action.url;
};
var ultimate_unit = function(a){
    var key = $(a).attr('data-key');
    var actions = $.extend(true, {}, window.maps[key]);
    if('undefined' == typeof window.response) window.response = {data:{}};
    if('undefined' == typeof actions[window.i]) return window.i=0;
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
    if(window.i >= actions.length - 1) setTimeout(function(){window.i = 0;}, 2000);
};
    </script>
</body>
</html>
HTML;
        return $output;
    }
}
