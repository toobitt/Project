{template:head}
{css:2013/button}
{css:2013/form}
{css:2013/list}
{css:market-info}
{js:common/common_form}
{js:2013/ajaxload_new}
{js:jquery.form}
{js:page/page}
{js:common/ajax_upload}
{js:supermarket/market_member}
{code}
if($_relate_module)
{
	foreach($_relate_module AS $k => $v)
	{
		$tmp = explode('_',$v);
		$_relate_module[$k] = $tmp[1];
	}
}
$market_info = $market_info[0];
//print_r($list);
{/code}
<div class="wrap clear">
	<div class="market-wrap">
		<header class="m2o-header">
	    	<div class="market-inner">
		    	<div class="m2o-flex m2o-flex-center">
			    	<div class="market-title">
			    		{if $market_info['logo']}
			    		<img src="{$market_info['logo']}" />
			    		{else}
			    		<img src="{$RESOURCE_URL}market/default_logo_white.png" />
			    		{/if}
			    		<h3>{$market_info['market_name']}</h3>
			    	</div>
			    	<div class="m2o-m m2o-flex-one">
			    		<ul class="market-menu">
			    			{foreach $_relate_module AS $_m => $_b}
			    			<li class="market-index {if $_m == $_INPUT['mid']}selected{/if}"><a href="run.php?mid={$_m}&market_id={$_INPUT['market_id']}">{$_b}</a></li>
			    			{/foreach}
			    		</ul>
			    	</div>
			    	<div class="m2o-r">
			    		<a class="close-button2 option-iframe-back"></a>
			    	</div>
		    	</div>
	    	</div>
	    </header>
		<div class="m2o-inner"> 
			<div class="m2o-main m2o-flex" tid="t_02" _id="{$_INPUT['market_id']}">
				<section class="market-box m2o-flex-one">
				<form action="./run.php?mid={$_INPUT['mid']}&market_id={$market_info['id']}" method="post" enctype="multipart/form-data" class="market-list">
                   <div class="m2o-list">
				        <div class="m2o-title m2o-flex m2o-flex-center">
				        	<div class="choice-area m2o-flex-one">
				        		{code}
				                    $memb_item_source = array(
				                        'class' 	=> 'down_list',
				                        'show' 		=> 'state_show',
				                        'width'     => 88,
				                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
				                        'is_sub'	=>	1,
				                    );
				                    
				                    if($_INPUT['member_is_bind'])
				                    {
				                    	$memb_default = $_INPUT['member_is_bind'];
				                    }
				                    else
				                    {
				                    	$memb_default = -1;
				                    }
				                {/code}
				                {template:form/search_source,member_is_bind,$memb_default,$_configs['member_status'],$memb_item_source}
				        		<div class="key-search">
				        			<input type="text" name='k' class="search-k" value="{$_INPUT['k']}" speech="speech" x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate" placeholder="搜索" />
				        		</div>
				        		<input type="submit" class="serach-btn" value=""/>
				        	</div>
				        	<div class="member-ratio">已绑定会员/全部：{$market_info['bind_member']}/{$market_info['total_member']}</div>
				        	<div class="member-menu">
				        		<a class="mem-pink view-member">查看会员数</a>
				        		<a class="mem-pink add-member">新增会员</a>
				        		<a class="mem-pink lead-member">导入会员</a>
				        		<input type='file' class="file-lead" name="excelfile"/>
				        	</div>
				        </div>
						<div class="m2o-each-list">
							<div class="m2o-each m2o-flex m2o-flex-center m2o-each-title">
					        	<div class="m2o-item m2o-paixu" title="排序">
					        		<a title="排序模式切换/ALT+R" onclick="hg_switch_order('newslist');" class="common-list-paixu"></a>
					        	</div>
					        	<div class="m2o-item m2o-num" title="卡号">
					        	<Span class="m2o-common-title">卡号</Span></div>
					            <div class="m2o-item m2o-flex-one m2o-name" title="用户名">用户名</div>
					            <div class="m2o-item m2o-tel" title="手机号">手机号</div>
					            <div class="m2o-item m2o-state" title="状态">状态</div>
					            <div class="m2o-item m2o-operate" title="操作">操作</div>
					            <div class="m2o-item m2o-time" title="添加人/时间">添加人/时间</div>
					        </div>
					        {if $list}
						        {foreach $list AS $k => $v}
								<div class="m2o-each m2o-flex m2o-flex-center" _id="{$v['id']}" orderid="{$v['order_id']}">
								    <div class="m2o-item m2o-paixu">
								    	<input type="checkbox"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  name="infolist[]" class="m2o-check" />
									</div>
									<div class="m2o-item m2o-num search-item"><span>{$v['card_number']}</span></div>
								    <div class="m2o-item m2o-flex-one m2o-name search-item">
								    	<span>{$v['name']}</span>
							    	</div>
								    <div class="m2o-item m2o-tel search-item"><span>{$v['phone_number']}</span></div>
								    <div class="m2o-item m2o-state" _status="{$v['status']}" _id="{$v['id']}" style="color:{$_configs['status_color'][$v['status']]}">{$v['status_format']}</div>
						            <div class="m2o-item m2o-operate">
						            	<a class="m2o-edit"></a>
						            	<a class="m2o-unlock"></a>
						            	<a class="m2o-delete"></a>
						            </div>
								    <div class="m2o-item m2o-time">
								        <span class="name">{$v['user_name']}</span>
								        <span class="time">{$v['create_time']}</span>
								    </div>
								</div>
								{/foreach}
							{else}
							<div class="m2o-each m2o-flex m2o-flex-center">
								<div class="m2o-flex-one" style="color:#da2d2d;text-align:center;font-size:14px; font-family:Microsoft YaHei;">没有您要找的内容</div>
							</div>
							{/if}
						</div>
						<div class="m2o-bottom-opera">
							<div class="m2o-bottom m2o-flex m2o-flex-center">
					            <div class="m2o-item m2o-paixu">
					        		<input type="checkbox" name="checkall" class="checkAll" rowtag="m2o-item" title="全选"/>
					    		</div>
					    		<div class="m2o-item m2o-flex-one list-config">
					    		   <a _href="./content.php?a=drop" class="batch-delete">删除</a>
					    		</div>
					    		<div class="m2o-item m2o-page">
					    			<div id="page_size">{$pagelink}</div>
					    		</div>
					    	</div>
				    	</div>
				     </div>
				    </form>
				    <div class="view-box">
						<div class="view-title">绑定会员数<span class="view-close pop-close-button"></span></div>
						<div class="view-area">
							<div class="area-title m2o-flex m2o-flex-center"><div class="m2o-date m2o-flex-one">日期</div><div class="m2o-total">总数</div></div>
						</div>
						<div class="page_size clear"></div>
					</div>
				</section>
				<aside class="market-info">
					<div class="market-edit" data-id="{$_INPUT['market_id']}">
					</div>
				</aside>
				<span class="result-tip">发送消息成功！</span>
			</div>
		</div>
	</div>
</div>

<script type="text/x-jquery-tmpl" id="add-list-tpl">
	<div class="m2o-each m2o-flex m2o-flex-center" _id="${id}" orderid="${id}">
	    <div class="m2o-item m2o-paixu">
	    	<input type="checkbox"  value="${id}" title="${id}"  name="infolist[]" class="m2o-check" />
		</div>
		<div class="m2o-item m2o-num search-item"><span>${card_number}</span></div>
	    <div class="m2o-item m2o-flex-one m2o-name search-item">
	    	<span>${nname}</span>
    	</div>
	    <div class="m2o-item m2o-tel search-item"><span>${phone_number}</span></div>
	    <div class="m2o-item m2o-state" _status="${status}" _id="${id}" style="color:#8ea8c8">${status_format}</div>
        <div class="m2o-item m2o-operate">
        	<a class="m2o-edit"></a>
        	<a class="m2o-unlock"></a>
        	<a class="m2o-delete"></a>
        </div>
	    <div class="m2o-item m2o-time">
	        <span class="name">${user_name}</span>
	        <span class="time">${create_time}</span>
	    </div>
	</div>
</script>

<script type="text/x-jquery-tmpl" id="view-member-tpl">
	<div class="area-content m2o-flex m2o-flex-center"><div class="m2o-date m2o-flex-one">${date}</div><div class="m2o-total">${total}</div></div>
</script>

<script type="text/x-jquery-tmpl" id="no-member-tpl">
	<div class="area-content m2o-flex m2o-flex-center"><div class="no-member m2o-flex-one">暂无绑定会员数</div>
</script>

<script type="text/x-jquery-tmpl" id="info-member-tpl">
	<div class="member-info">
		<div class="member-profile">
			<img src='${avatar}' /><span>${member_name}</span><em class="more">详情</em>
		</div>
		<div class="member-more">
			<div class="mem-items">
				<label>邮&nbsp;&nbsp;&nbsp;箱：</label><span>${email}</span>
			</div>
			<div class="mem-items">
				<label>手机号：</label><span>${mobile}</span>
			</div>
		</div>
	</div>
</script>

<script type="text/x-jquery-tmpl" id="add-member-tpl">
  <form method="post" action="run.php?mid={$_INPUT['mid']}&market_id={$_INPUT['market_id']}" class="market-form">
	<div class="market-item market-mode">
		<label>${oper}会员</label>
		<input type="text" name='card_number' value="${card_number}" />
	</div>
	<div class="market-item">
		<label>姓名：</label>
		<input type="text" name='name' value="${nname}" />
	</div>
	<div class="market-item">
		<label>手机：</label>
		<input type="text" name='phone_number' value="${phone_number}" />
	</div>
	<div class="market-item market-tel">
		<label>邮箱：</label>
		<input type="text" name='email' value="${email}"/>
	</div>
	<div class="market-item market-birth">
		<label>生日：</label>
		<input type="text" name='birthday' value="${birthday}"/>
	</div>
	<div class="market-item market-barcode">
		<div class="barcode">
			<img src='${barcode_img_url}'>
		</div>
	</div>
	<div class="market-save">
		<input type="submit" value="${value}" class="save-pink"/>
		<input type="hidden" name="a" value="${method}" />
		<input type="hidden" name='id' value="${id}" />
		<input type="hidden" name="market_id" value="${market_id}">
	</div>
	</form>
	{{if dmemberInfo}}
	<div class="market-member" _id="${id}">
		{{tmpl($data["dmemberInfo"]) "#info-member-tpl"}}
		<div class="member-info member-content">
			<textarea name="memberinfo" placeholder="输入推送信息" cols="120" rows="4"></textarea>
		</div>
		<div class="member-info">
			<input type="submit" value="发送" class="send-btn"/>
		</div>
	</div>
	{{/if}}
</script>
