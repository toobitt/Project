{template:head}
{css:2013/iframe}
{css:2013/list}
{css:common/common}
{js:2013/list}
{js:2013/ajaxload_new}

{code}
$lottery_info = $list['lottery_info'];
$prize_info = $list['prize_info'];
$prizes = $list['prizes'];
$win_info = $list['win_info'];
echo '<pre>';
//print_r($prizes);
echo '</pre>';

if(!$_INPUT['win_status'])
{
	$_INPUT['win_status'] = 0;
}
{/code}
<style>
.right_2{display:block;}
.search-box{float:left;position:relative;}
.k-search{  display: block;height: 43px;width: 90px;text-align: center;line-height: 43px;margin-top: -25px;color: #727272;background: #fff;border-right: 1px solid #ccc;border-top: 1px solid #ccc;}
.search-item-box{  display:none;background: #fff;padding: 10px 30px 10px 15px;position: absolute; z-index: 9;border: 1px solid #ccc;left: -1px;border-radius:2px;}
.search-item{margin-bottom:8px;}
.search-item span{display:block;width:45px;height:25px;line-height:25px;}
.search-item input[type="text"]{width:120px;height:20px;border:1px solid #ccc;border-radius:2px;text-indent:5px;}
.search-item input[type="text"]:hover , .search-item input[type="text"]:focus{border:1px solid #5c99cf;}
.search-btn{width: 60px;height: 24px;background: #5c99cf;color: #fff;border: 1px solid #5c99cf;text-align: center;line-height: 24px;border-radius: 2px;margin-left: 45px;font-size: 12px;margin-top: 5px;padding: 0px;cursor: pointer;}
.search-btn:hover{opacity:0.8;border:1px solid #5c99cf;}
.search-box:hover .search-item-box{display:block;}  
</style>
<div style="display:none">
	<div class="controll-area fr mt5" id="hg_page_menu" style="display:none">
		<a class="button_6 appoint_award">指定中奖</a>
	</div>
</div>
<div class="search_a" id="info_list_search" style="display:none">
	<form name="searchform" id="searchform" action="" method="get" onsubmit="return hg_del_keywords();">
		<div class="right_1">
			{code}
			$attr_status = array(
			'class' => 'transcoding down_list',
			'show' => 'status_show',
			'width' => 104,/*列表宽度*/
			'state' => 0,/*0--正常数据选择列表，1--日期选择*/
			);
			{/code}
			{template:form/search_source,win_status,$_INPUT['win_status'],$_configs['win_status'],$attr_status}
			<input type="hidden" name="a" value="show" />
			<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
			<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
			<input type="hidden" name="lottery_id" value="{$_INPUT['lottery_id']}" />
			<input type="hidden" name="need_prize" value="1" />
			<input type="hidden" name="need_lottery" value="1" />
		</div>
		<div class="right_2">
			<div class="button_search">
				<input type="submit" value="" name="hg_search"  style="padding:0;border:0;margin:0;background:none;cursor:pointer;width:22px;" />
			</div>
			{template:form/search_input,k,$_INPUT['k']}
			<div class="search-box">
				<span class="overflow k-search">关键字搜索</span>
				<div class="search-item-box">
					<div class="search-item m2o-flex">
						<span>电话:</span>
						<input type="text" name="tel" value="{$_INPUT['tel']}" placeholder="请输入电话" />
					</div>
					<div class="search-item m2o-flex">
						<span>姓名:</span>
						<input type="text" name="name" value="{$_INPUT['name']}" placeholder="请输入姓名" />
					</div>
					<div class="search-item m2o-flex">
						<span>兑换码:</span>
						<input type="text" name="exchange_code" value="{$_INPUT['exchange_code']}" placeholder="请输入兑换码" />
					</div>
					<input type="submit" class="search-btn" value="搜索" />
				</div>
			</div>
		</div>
	</form>
</div>
<div class="wrap m2o-flex">
	<aside class="temp-nav">
		<div class="aside-info">
			<div class="suoyin">
				{if $lottery_info['host']}
				<img src="{$lottery_info['host']}{$lottery_info['dir']}102x102/{$lottery_info['filepath']}{$lottery_info['filename']}">
				{else}
				<img src="{$RESOURCE_URL}lottery/pic_detail.png">
				{/if}
			</div>
			<span class="nav-title">{$lottery_info['title']}</span>
			<div class="nav-item">
				<span class="m2o-status" style="background:{if $lottery_info['activ_status'] == 0}#c7d3df{else if $lottery_info['activ_status'] == 1}#5ac75a{else if $lottery_info['activ_status'] == 2}#ee7b80{/if};"></span>
				<span class="m2o-time">{$lottery_info['effective_time']}</span>
			</div>
		</div>
		{if $prizes}
		<span style="display: block;margin: 10px;">奖项详情:</span>
		<ul class="award-info-list">
			<li class="m2o-flex">
				<span class="m2o-flex-one">名称</span>
				<span class="w35">总数</span>
				<span class="w35">已中</span>
			</li>
			{foreach $prizes as $k => $v}
			<li class="m2o-flex">
				<span class="m2o-flex-one overhidden" title="{$v['prize']}">{$v['prize']}</span>
				<span class="w35">{$v['prize_num']}</span>
				<span class="w35">{$v['prize_win']}</span>
			</li>
			{/foreach}
		</ul>
		{/if}
	</aside>
	<section class="m2o-flex-one list-wrap" style="position: relative;">
		<form class="common-list-form" name="listform">
			<div class="m2o-list">
				<div class="m2o-title m2o-flex m2o-flex-center">
					<div class="m2o-item m2o-paixu">
						<a title="排序模式切换/ALT+R" onclick="hg_switch_order();" style="cursor:pointer;" class="common-list-paixu"></a>					
					</div>
					<div class="m2o-item m2o-flex-one m2o-bt">中奖用户</div>
					<div class="m2o-item m2o-phpne wd120">电话</div>
					<div class="m2o-item m2o-address wd160">姓名</div>
					<div class="m2o-item m2o-phpne wd120">兑换码</div>
					<div class="m2o-item m2o-reward wd160">奖项及奖品</div>
					<div class="m2o-item m2o-status wd60">状态</div>
					<div class="m2o-item m2o-time">中奖时间</div>
				</div>
				<div class="m2o-each-list" data-table_name="content-table">
				{if $win_info}
					{foreach $win_info as $v}
					<div id="r_{$v['id']}" class="m2o-each m2o-flex m2o-flex-center" data-id="{$v['id']}" orderid="{$v[order_id]}">
						<div class="m2o-item m2o-paixu">
							<a name="alist[]" title="{$v['id']}"><input type="checkbox" name="infolist[]" value="{$v['id']}" class="m2o-check"></a>
						</div>
						<div class="m2o-item m2o-flex-one m2o-bt">
							<div class="m2o-title-transition m2o-title-overflow">
								{if $v['avatar']}
								<img src="{$v['avatar']}" class="biaoti-img" id="img_{$v['id']}">
								{/if}			
								<a target="formwin" class="m2o-title-overflow max-width200" title="{$v['member_name']}" >
									<span class="m2o-common-title" >{$v['member_name']}({if $v['prize_id']}<a style="color:#f8a6a6;font-size:12px;">中奖用户</a>{else}未中奖{/if})</span>
								</a>
							</div>
						</div>
						
						<div class="m2o-item m2o-phpne wd120">{$v['phone_num']}</div>
						<div class="m2o-item m2o-address wd160">{$v['address']}</div>
						<div class="m2o-item m2o-phpne wd120">{$v['exchange_code']}</div>
						<div class="m2o-item m2o-reward wd160"><span class="provide-status" style="{if $v['provide_status']}color:#aebfdb;{/if}cursor:pointer;" _provide="{$v['provide_status']}">{if $v['prize_id']}{$v['provide']} |</span>{$v['name']} {$v['prize']}{/if}</div>
						<div class="m2o-item m2o-audit wd60"  _status="{$v['status']}" style="color:{$_configs['status_color'][$v['status']]};">{$v['audit']}</div>
						<div class="m2o-item m2o-time">{$v['create_time']}</div>
					</div>
					{/foreach}
				{else}
					<p class="common-list-empty">没有你要找的内容！</p>
				{/if}
				</div>
				<div class="m2o-bottom m2o-flex m2o-flex-center">
				  	 <div class="m2o-item m2o-paixu">
		        		<input type="checkbox" name="checkall" class="checkAll" rowtag="m2o-item" title="全选"/>
		    		</div>
		    		<div class="m2o-item m2o-flex-one">
		    		   <a class="batch-handle">审核</a>
		    		   <a class="batch-handle">打回</a>
		    		   <a class="batch-handle">删除</a>
		    		</div>
		    		<div id="page_size">{$pagelink}</div>
				</div>
			</div>
		</form>
		<!-- 排序模式打开后显示，排序状态的 -->
		<div id="infotip"  class="ordertip"></div>
	</section>
</div>
<script>
	var data = $.globalListData = {code}echo $list ? json_encode($list) : '{}';{/code};
    $.extend($.geach || ($.geach = {}), {
        data : function(id , status){
            var info;
            $.each(data, function(i, n){
               if(n['id'] == id){
                   info = {
                       id : n['id'],
                       status : n['status']
                   }
                   return false;
               }
            });
            return info;
        }
    });
    $('.m2o-each').geach();
	$('.m2o-list').glist();
</script>
<script text="text/javascript">
$(function(){
	$('.provide-status').on('click' , function( event ){
		var self = $( event.currentTarget ),
			id = self.closest('.m2o-each').data('id'),
			provide_status = self.attr('_provide'),
			url = './run.php?mid=' + gMid + '&a=provide_status';
		$.globalAjax( self, function(){
			 return $.getJSON( url, {id : id , provide_status : provide_status} , function(json){
				 if( json[0].provide_status ){
					 self.text('已发放 |').attr('_provide' , json[0].provide_status ).css('color' , '#aebfdb');
				 }else{
					 self.text('未发放 |').attr('_provide' , json[0].provide_status ).css('color' , '#999');
			     }
			 });
		});
	});
})
</script>
{template:unit/appoint_box}
{template:foot}