<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta name="format-detection" content="telephone=no" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/bootstrap/3.3.7/css/bootstrap.min.css">
    <style>
        * {
            border-radius: 0 !important
        }

        .modal-footer {
            border-top: none;
        }

        select.form-control,
        select.form-control:hover,
        select.form-control:focus,
        select.form-control:active {
            line-height: normal;
            outline: 1px solid #D1D1D1;
            outline-offset: -1px;
            border: 0;
            box-shadow: inset 0 2px 1px rgba(0, 0, 0, .075);
            text-indent: 5px;
        }
    </style>
    <script> window.maps = {}; if ('undefined' == typeof window._timeout) window._timeout = 5000;</script>
</head>

<body>
    <div class="col-md-10 col-md-offset-1">
        <form method="POST" enctype="multipart/form-data">
            <h2>DEBUGGER</h2>
            <div class="form-group">
                <ul id="tabs" class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#profile" role="tab" id="profile-tab" data-toggle="tab" aria-controls="profile" aria-expanded="true">Live demo</a>
                    </li>
                    <li role="presentation" class="dropdown">
                        <a href="#" id="tabDrop1" class="dropdown-toggle" data-toggle="dropdown" aria-controls="expoort" aria-expanded="false">Document
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="tabDrop1" id="expoort">
                            <li>
                                <a href="javascript:void(0);">Markdown</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="fileds">
                <div class="col-md-8">
                    <div class="row">
                        @foreach ($groups as $group_name => $group_data)
                        <h4>{{$group_name}}</h4>
                        <div class="form-group">
                            @foreach ($group_data as $single_group)
                            <div class="btn-group" role="group">
                                @foreach ($single_group as $btn_name => $steps) {{($btn_key = 'e' . md5($group_name . '|' . $btn_name)) ? '' : ''}}
                                <button type="button" class="btn btn-default" data-key="{{$btn_key}}" onclick="ultimate_unit(this);">{{$btn_name}}</button>
                                @endforeach
                            </div>
                            @endforeach @foreach ($group_data as $single_group) @foreach ($single_group as $btn_name => $steps) {{($btn_key = 'e' . md5($group_name
                            . '|' . $btn_name)) ? '' : ''}}
                            <script>
                                var steps = {!!json_encode($steps)!!};
                                window.maps['{{$btn_key}}'] = [];
                                for (step_key in steps) {
                                    var step = steps[step_key];
                                    (
                                        function (step) {
                                            window.maps['{{$btn_key}}'].push(
                                                {
                                                    url: step.url,
                                                    data: step.data,
                                                    callback: function (param, json) {
                                                        var done = step.done;
                                                        for (let i = 0; i < done.length; i++) {
                                                            done_item = done[i];
                                                            eval('var v = ' + done_item.value);
                                                            window.setEntitie(done_item.key, v);
                                                        }
                                                    }
                                                }
                                            );
                                        }
                                    )(step);
                                }
                            </script> @endforeach @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-4">
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
        <p class="text-muted">
            <small>&copy; 2018 XIAOHUILAM's
                <a href="https://github.com/xiaohuilam/ultimate-debug-tool" target="_blank">ultimate_debug_tool</a>.</small>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/gh/fent/randexp.js@0.4.3/build/randexp.min.js"></script>
    <script>window.hide_entities = [];</script>
    <script>
        window.setEntitie = function (a, b) {
            window[a] = b;
            if ('undefined' != typeof window.hide_entities[a]) return;
            if (document.querySelectorAll('#' + a).length == 0) {
                var dom = document.createElement('input');
                dom.setAttribute('class', 'form-control');
                dom.setAttribute('style', 'border-right: 0');
                dom.setAttribute('id', a);
                dom.setAttribute('placeholder', a);
                dom.value = b;

                var str = [
                    '<div class="form-group">',
                    '    <div class="col-md-6">',
                    '        <div class="row xx">',
                    '        </div>',
                    '    </div>',
                    '    <button type="button" class="btn btn-default" onclick="save_auth();">保存</button>',
                    '</div>'
                ].join('');

                var div = document.createElement('div');
                div.innerHTML = str.trim();
                div.setAttribute('class', 'hide');
                d = div.firstChild;


                d.querySelector('.xx').append(dom);
                d.querySelector('button').innerHTML = '修改' + a;
                d.querySelector('button').setAttribute('data-key', a);
                d.querySelector('button').setAttribute('onclick', 'window.save_entities(this);');

                document.querySelector('.entities').append(d);
            } else {
                document.querySelector('#' + a).value = b;
            }
        };
        window.getEntitie = function (a) {
            return window[a];
        }
        window.save_entities = function (a) {
            window[a.getAttribute('data-key')] = a.getAttribute('data-key').value;
        }
        window.i = 0;
        var f = function (action, cb) {
            var auth = window.authorization;
            document.querySelector('.btn').setAttribute('disabled', 'disabled');

            fetch(
                action.url,
                request_hook(
                    {
                        method: 'undefined' == typeof action.method ? 'POST' : action.method,
                        body: JSON.stringify(action.data),
                        headers: {
                            "Authorization": 'Bearer ' + auth,
                            "Content-Type": "application/json"
                        },
                        dataType: 'json',
                        timeout: window._timeout,
                    }
                )
            ).then(
                function (response) {
                    response.json().then(
                        function (json) {
                            if ({!!$condition!!}) {
                        document.querySelector('#log').innerHTML = document.querySelector('#log').innerHTML + response.url + ' OK' + ' ' + '\r\n';
                    } else {
                        window.i = 0;
                        document.querySelector('#log').innerHTML = document.querySelector('#log').innerHTML + response.url + ' FAIL ' + {!!$msg_if_fail!!} + ' ' + '\r\n'; return;
                }

                cb(json);
        },
        function(reason) {
            window.i = 0;
        }
                    )
                },
        function(reason) {

        }
            );
        };
        var ultimate_unit = function (a) {
            var key = a.getAttribute('data-key');
            var actions = JSON.parse(JSON.stringify(window.maps[key]));

            if ('undefined' == typeof window.response) window.response = { data: {} };
            if ('undefined' == typeof actions[window.i]) {
                document.querySelectorAll('.btn').forEach(function (btn) {
                    btn.removeAttribute('disabled');
                });
                return window.i = 0;
            }
            var action = actions[window.i];

            action.callback = window.maps[key][window.i].callback;

            for (var j in action.data) {
                if ('object' == typeof action.data[j]) {
                    if (action.data[j].type == "Xiaohuilam\\UltDebug\\RegExp") {
                        action.data[j] = (new RandExp(new RegExp(action.data[j].regexp.replace(/(^\/)|(\/$)/g, '')))).gen();
                    } else if (action.data[j].type == "Xiaohuilam\\UltDebug\\StoreGet") {
                        action.data[j] = window.getEntitie(action.data[j].key);
                    }
                }
            }
            f(action, function (json) {
                if ('undefined' !== typeof action.callback) action.callback(action.data, json);
                if (window.i >= actions.length) bootbox.alert(JSON.stringify(json));
                window.i++;
                ultimate_unit(a);
            });
            if (window.i >= actions.length - 1) setTimeout(function () {
                document.querySelectorAll('.btn').forEach(function (btn) {
                    btn.removeAttribute('disabled');
                });
                window.i = 0;
            }, 2000);
        };
        window.onerror = function () {
            document.querySelectorAll('.btn').forEach(function (btn) {
                btn.removeAttribute('disabled');
            });
            window.i = 0;
        };
    </script>
    <script>
        var request_hook = function (payload) {
        };
    </script>
</body>
</html>
