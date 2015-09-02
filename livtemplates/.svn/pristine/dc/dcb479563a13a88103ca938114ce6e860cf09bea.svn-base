{template:head}
{css:2013/iframe}
<script>
var href = location.href;
function replaceAction(href, action){
    return href.replace(/(mod_a=)wait_stream/, '$1' + action);
}
(function check(){
    $.when($.getJSON(replaceAction(href, 'check_stream'))).then(
        function(json){
            if(json[0].result > 0){
                location.href = replaceAction(href, 'form');
            }else{
                setTimeout(check, 500);
            }
        },
        function(){
            setTimeout(check, 500);
        }
    );
})();
</script>
<div class="wrap">
    <div style="padding-top:100px;text-align:center;font-size:40px;">正在启动信号中...</div>
</div>
{template:foot}