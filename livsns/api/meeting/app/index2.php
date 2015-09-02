<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>海宁大潮网</title>
<link  rel="stylesheet" href="http://www.dev.hogesoft.com:233/m2o/static/css/base.css" />
<link  rel="stylesheet"  href="http://www.dev.hogesoft.com:233/m2o/static/css/thickbox.css" />
<script src="http://www.dev.hogesoft.com:233/m2o/static/js/jquery.js"></script>
<script src="http://www.dev.hogesoft.com:233/m2o/static/js/jquery.cookie.js"></script>
<script src="http://www.dev.hogesoft.com:233/m2o/static/js/thickbox.min.js"></script>
</head>

<body>
<a href="javascript:void(0);" onclick= "m2oLogin();" >登陆</a>

<script>

    var m2oUser = {};

    function m2oLogin (){
        $.ajax({
             url : "http://sso1.habctv.com/index.php?s=user&c=login&a=login&login_type=m2o&member_name=helloworld&password=123456",
             dataType: "jsonp",  
             jsonp: "callback",//传递给请求处理程序或页面的，用以获得jsonp回调函数名的参数名(一般默认为:callback)  
             jsonpCallback:"flightHandler",
             success : function(data){
                console.dir(data);
                m2oUser = data;
                console.dir(m2oUser);
                $.cookie('m2ouser', m2oUser, {
                    expires : 1,
                    path : '/',
                    domain : 'liv.cn'
                });
                var cookieM2OUser = {}
                cookieM2OUser = $.cookie('m2ouser')
                for(var i in cookieM2OUser){
                    console.log( i + "==>" + cookieM2OUser.i);
                }
                console.dir(  cookieM2OUser.status);
            },
            error: function(error){
                console.dir(error)
            }

        });
    }
</script>
</body>
</html>
