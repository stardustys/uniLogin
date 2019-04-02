<?php
    session_start();
?>
<html>
<head>
</head>
<body>
    <div id="appMainArea">
        post/get测试<br />
        数量<input type="number" v-model.number="num"/>
        url<input v-model="url"/>
        返回值类型：<input v-model="dataType"/>
        <div v-for="item in num">
            变量：<input v-model="bianliang[item-1]" placeholder="如：phone"/>
            值：<input v-model="vals[item-1]" placeholder="如：18312349876"/>
        </div>
        <br />
        <button @click="qItemClick('POST')">Post</button>
        <button @click="qItemClick('GET')">Get</button>
        <br />
        <div id="returnArea"></div>
    </div>
<script src="https://cdn.staticfile.org/jquery/2.2.4/jquery.min.js" charset="utf-8"></script>
<script src="https://cdn.staticfile.org/vue/2.5.17/vue.js"></script>
<script>
    new Vue({
        el: '#appMainArea',
        data: {
            num: 0,
            url: '/api/login/register.php',
            dataType: 'json',
            bianliang: {},
            vals: {},
            json: {}
        },
        methods: {
            qItemClick: function (qItem) {
                for (var i = 0; i < this.num; i++) {
                    this.json[this.bianliang[i]] = this.vals[i];
                }
                $.ajax({
                    type: qItem,
                    dataType: this.dataType,
                    //timeout : 3000,
                    //url: '/api/login/register.php',
                    url: this.url,
                    //data: {phone: 18111111111,pw: 111111},
                    data: this.json,
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        $('#returnArea').html('返回错误,在控制台-网络界面，查看');
                    },
                    success: function (data) {
                        $('#returnArea').html('返回值:' + obj2str(data) + '<br/>返回成功,详细在控制台-网络界面，查看');
                    }
                });
            }

        },
        computed: {

        }
    });

    function obj2str(o) {
        var r = [];
        if (typeof o == "string") {
            return "\"" + o.replace(/([\'\"\\])/g, "\\$1").replace(/(\n)/g, "\\n").replace(/(\r)/g, "\\r").replace(/(\t)/g, "\\t") + "\"";
        }
        if (typeof o == "object") {
            if (!o.sort) {
                for (var i in o) {
                    r.push(i + ":" + obj2str(o[i]));
                }
                if (!!document.all && !/^\n?function\s*toString\(\)\s*\{\n?\s*\[native code\]\n?\s*\}\n?\s*$/.test(o.toString)) {
                    r.push("toString:" + o.toString.toString());
                }
                r = "{" + r.join() + "}";
            } else {
                for (var i = 0; i < o.length; i++) {
                    r.push(obj2str(o[i]))
                }
                r = "[" + r.join() + "]";
            }
            return r;
        }
        return o.toString();
    }
</script>
</body>
</html>
