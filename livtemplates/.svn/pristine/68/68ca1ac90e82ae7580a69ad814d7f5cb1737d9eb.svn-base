{template:head} {code} $attrs_for_edit = array('pub_url'); {/code}
{code} if(!isset($_INPUT['pay_status'])) { $_INPUT['pay_status'] = -1; }

if(!isset($_INPUT['trace_step'])) { $_INPUT['trace_step'] = -1; }

if(!isset($_INPUT['date_search'])) { $_INPUT['date_search'] = 1; }
{/code} {template:list/common_list} {css:news_list} {js:2013/cloud_pop}
{js:news/news_list}
{js:payments/express}



<!-- 这一部分会被推进父层框架，成为检索条件和添加、配置按钮 -->
<div {if $_INPUT['infrm']}style="display: none"{/if}>
	{template:unit/search}
	<div class="controll-area fr mt5" id="hg_page_menu" style="display: none">
	<a  class="add-button news mr10" href="./run.php?mid={$_INPUT['mid']}&a=download&infrm=1&pay_status={$_INPUT['pay_status']}&trace_step={$_INPUT['trace_step']}&date_search={$_INPUT['date_search']}&title={$_INPUT['title']}">导出</a>
	
	</div>
</div>

<!-- 记录列表 -->
<div class="common-list-content" style="min-height: auto; min-width: auto;">
	{if !$list}
	<p id="emptyTip"
		style="color: #da2d2d; text-align: center; font-size: 20px; line-height: 50px; font-family: Microsoft YaHei;">没有您要找的内容！</p>
	<script>hg_error_html('#emptyTip',1);</script>
	{else}
	<form method="post" action="" name="listform" class="common-list-form">
		<!-- 头部，记录的列属性名字 -->
		<ul class="common-list news-list">
			<li class="common-list-head public-list-head clear">
				<div class="common-list-left">
					<div class="common-list-item paixu open-close">
						<!-- 
                       <a title="排序模式切换/ALT+R" onclick="hg_switch_order('newslist');"  class="common-list-paixu">导出</a>
                       -->
						
					</div>
				</div>
				<div class="common-list-right">
					<div class="common-list-item news-fabu common-list-pub-overflow">收货人/联系人</div>
					<div class="common-list-item news-fenlei open-close wd70">下单时间</div>
					<div class="common-list-item news-quanzhong open-close wd60">商品费用</div>
					<div class="common-list-item news-zhuangtai open-close wd60">支付状态</div>
					<div class="common-list-item news-ren open-close wd100">配送状态</div>
				</div>
				<div class="common-list-biaoti">
					<div class="common-list-item">商品名称</div>
				</div>
			</li>
		</ul>
		<!-- 主题，记录的每一行 -->
		<ul class="news-list common-list public-list hg_sortable_list"
			id="newslist" data-table_name="article" data-order_name="order_id">
			{foreach $list as $k => $v} 
			{template:unit/OrderListRow}
			{/foreach}
		</ul>
		<!-- foot，全选、批处理、分页 -->
		<ul class="common-list public-list">
			<li class="common-list-bottom clear">
				<div class="common-list-left">
					<input type="checkbox" name="checkall" value="infolist" title="全选" rowtag="LI" />
					<!--<a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '审核', 1, 'id', '&audit=1', 'ajax', 'hg_change_status');" name="audit">审核</a>
                    <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'audit', '打回', 1, 'id', '&audit=0', 'ajax', 'hg_change_status');" name="back">打回</a>
                    <a style="cursor:pointer;" onclick="return hg_ajax_batchpost(this, 'delete', '删除', 1, 'id', '', 'ajax');" name="delete">删除</a>
                    <a style="cursor:pointer;" onclick="return hg_bacthpub_show(this);" name="publish">签发</a>
                    <a style="cursor:pointer;" onclick="return hg_bacthmove_show(this,'news_node');" data-node ='news_node'>移动</a>
                    <a style="cursor:pointer;" onclick="return hg_bacthspecial_show(this);" name="publish">专题</a>
                    <a style="cursor:pointer;" onclick="return hg_bacthblock_show(this);" name="block">区块</a>
                    -->
				</div> {$pagelink}
			</li>
		</ul>
	</form>
	{/if}
</div>
<script type="text/javascript">
<!--
	
	$(document).ready(function(){ 
		$('.common-list').find('.common-list-data').map(function(){
			var id = $(this).attr("_id"),
				title = $(this).find('.m2o-common-title').text(),
				target = $(this).find('option[selected="selected"]'),
				express_name = $(this).data('name') ? $(this).data('name') : '',
				express_no = $(this).data('no') ? $(this).data('no') : '',
				selectVal = target.val();
			if( selectVal && selectVal == 4 ){
				$.express( selectVal , express_name , express_no , id , title , $(this) , false );
			}
		});
		
		$('.trace_step').change(function( event ){
			var self = $( event.currentTarget ), 
				item = self.closest('.common-list-data'),
				v = self.val(),
				id = item.attr("_id"),
				express_name = item.data('name') ? item.data('name') : '',
				express_no = item.data('no') ? item.data('no') : '',
				title = item.find('.m2o-common-title').text();
			if( v== 4){
				$.express( v ,  express_name , express_no , id , title , item , true );
			}else{
				$('.express-info[_id='+ id +']').remove();
				$.ajax({
					url:'./run.php?mid={$_INPUT['mid']}',
					cache:false,
					type:'POST',
					data: {id : id,tracestep:v,a:'update_trace_step'},
					success:function(datas)
					{
						$('#get_orgs').html(datas);
					}
				});
			}
		});
	});
//-->
</script>

<div id="add_share"></div>
<!-- 排序模式打开后显示，排序状态的 -->
<div id="infotip" class="ordertip"></div>
<!-- 关于记录的操作和信息 -->
{template:unit/record_edit}
<!-- 移动框 -->
{template:foot}
