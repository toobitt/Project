{template:head}
{code}
    if($id)
    {
        $optext="更新";
        $ac="update";
    }
    else
    {
        $optext="新增";
        $ac="create";
    }
{/code}
{if is_array($formdata)}
    {foreach $formdata as $key => $value}
        {code}
            $$key = $value; 
        {/code}
    {/foreach}
{/if}
{css:ad_style}
{css:column_node}
{js:column_node}
<script>
function hg_sort_del(id)
{
	if(confirm('确定删除该条记录？！'))
	{
		var url = './run.php?mid=' + gMid + '&a=getUser&id=' + id + '&infrm=1&ajax=1';
		hg_request_to(url);
	}
}

function hg_call_getUser(json)
{
	var jsonobj=eval('(' + json + ')');
	var leng = jsonobj.length;
	var html = '<ul>';
	for(var i =0 ;i<leng;i++)
	{
		html += '<li class="user-single" _id="' + jsonobj[i]['id'] + '">' + jsonobj[i]['user_name'] + '</li>';
		//console.log(jsonobj[i]);
	}
	html += '</ul>';
	$(".user-info").html(html).show();
	$('.bg').show();
	$('.user-single').click(function(){
		var user_list = '<li class="i user-list" _id="' + $(this).attr('_id') + '">' + $(this).html() + '<span class="user-del">X</span><input type="hidden" name="userinfo[]" value="' + $(this).attr('_id') + '--' + $(this).html() + '"/></li>';
		$('.form_ul').append(user_list);
		$('.order-top').css("height",$('li[class="i user-list"]').length*23+'px');
		$('.user-del').click(function(){
			$(this).parent('li').remove();
			$('.order-top').css("height",$('li[class="i user-list"]').length*23+'px');
		});
		hg_close_bg();
	});
}

function hg_close_bg() {
	$(".user-info").hide();
	$('.bg').hide();	
}
jQuery(function($){
	$('.order-top').css("height",$('li[class="i user-list"]').length*23+'px');
	$('.get-btn').click(function(){
		if(!$('.user-info').html())
		{
			var url = './run.php?mid=' + gMid + '&a=getUser&infrm=1&ajax=1';
			hg_request_to(url);
		}
		else
		{
			$(".user-info").show();
			$('.bg').show();
		}
	});
	$('.bg').click(function(){
		hg_close_bg();
	});
	$('.user-del').click(function(){
		$(this).parent('li').remove();
		$('.order-top').css("height",$('li[class="i user-list"]').length*23+'px');
	});
});

</script>
<style >
	.get-btn{font-size: 20px; padding: 0 0 2px 0px; border: 1px solid; background-color: rgb(121, 119, 119); color: white; border-radius: 4px; display: block; width: 100px; text-align: center; float: left; cursor: pointer;}
	.btn-help{float: right; font-size: 17px; font-weight: bold; margin-right: 50px; width: 22px; height: 22px; border: 1px solid; text-align: center; line-height: 22px; border-radius: 12px; cursor: help;}
	.user-info{position: absolute; border: 1px solid; width: 252px; height: 100px; overflow-y: auto; overflow-x: hidden; z-index: 999; background-color: rgb(223, 224, 223); left: 85px;display: none;}
    .user-single{width: 110px;float: left; border: 1px solid red; padding: 2px 0px 2px 13px;cursor: pointer;}
    .bg{width: 1000px; height: 1000px; background-color: rgb(241, 242, 244); position: absolute; z-index: 99; display: none;}
    .user-list{  border: 1px solid;  width: 72px;  border-radius: 3px;  margin: 5px 0px 0px 50px;}
    .user-del{display: inline-block; float: right;cursor: pointer;}
    .user-order{top: 111px;position: absolute; padding-left: 12px; overflow-y: visible;cursor: help;}
    .order-top{  border: 9px solid #F42828; height: 5px; width: 0px; border-radius: 2px; margin: 0px;}
    .order-down{margin: 5px auto; width: 0px; height: 0px; border: 20px solid transparent; border-top-color: #F42828; margin-left: -11px; margin-top: -1px;}
</style>

<div class='bg'></div>
<div id="channel_form" style="margin-left:40%;"></div>
<div class="wrap clear">
<div class="ad_middle">
<form class="ad_form h_l" action="./run.php?mid={$_INPUT['mid']}" enctype="multipart/form-data" method="post"   id="content_form">
<h2>{$optext}配置</h2>
<div class="user-info"></div>
<div class="user-order" title="审核的优先顺序由上而下">
	<div class="order-top"></div>
	<div class="order-down"></div>
</div>
<ul class="form_ul">
<li class="i">
	<div class="form_ul_div clear">
		<span class="title"></span>
		<span class="get-btn" onselectstart="return false">+</span>
		<div class="btn-help" title="审核的优先顺序由上而下">?</div>
	</div>
</li>
{if $info}
{foreach $info as $k => $v}
<li class="i user-list" _id="{$v['user_id']}">{$v['user_name']}<span class="user-del">X</span><input type="hidden" name="userinfo[]" value="{$v['user_id']}--{$v['user_name']}"/></li>
{/foreach}
{/if}
</ul>
<input type="hidden" name="a" value="{$ac}" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="html" value="1" />
<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
<input type="hidden" name="mmid" value="{$_INPUT['mid']}" />
<br />
<input type="submit" id="submit_ok" name="sub" value="{$optext}" class="button_6_14"/><input type="button" value="取消" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
</form>
</div>
<script>
jQuery(function($){});
	</script>
<div class="right_version">
    <h2><a href="run.php?mid={$_INPUT['mid']}&infrm=1">返回前一页</a></h2>
</div>
</div>
{template:foot}