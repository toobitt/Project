{template:head}
{css:news_list}
{code}
//print_r($deploy_search);
$list = $deploy_search[0]['tem_data'];
$sorts = $deploy_search[0]['sort_data'];
$hg_sorts = array();
foreach($sorts as $kk => $vv){
	$hg_sorts[$kk] = $vv['name'];
}
$sort_id = $_INPUT['sort_id'];
!$sort_id && ($sort_id = 0);
{/code}
<script>
$(window).load(function(){
	parent.$('#top-loading').hide();
});
$(function(){
	$('.one-template').on('click',function(){
		var frame = parent.$('#deployFrame');
		var _id = frame.attr('sid'),
		   _title = frame.attr('_title'),
		   id = $(this).attr('id'),
		   client_type = frame.attr('_client'),
		   content_type = frame.attr('content_type'),
		   template_name = $(this).find('.template-name').html();
		var url = './run.php?mid=' + gMid + '&a=update';
		$.post(url,{
			sid : _id,
			title : _title,
			id : id,
			client_type : client_type,
			content_type : content_type
		},function(){
			parent.$('.reelect').each(function(){
				if( $(this).hasClass('current') ){
				    var li = $(this).closest('li'),
				    	type = $(this).data('content-type'),
				    	name_box = li.find('.name'),
				    	li_name_box = li.closest('.m2o-each');
				    name_box.html(template_name);
				    li_name_box.find('[_type="'+ type +'"]').html( template_name );
				}
			});
			parent.$('.common-list-pub-title .close').trigger('click');
		});
	});
})
</script>
<body>
<div class="select-content">
	<div class="select-template-set">
	   	 <form name="searchform" id="searchform" action="" method="GET" style="padding-top:10px;padding-left:10px;">
	     <div class="select-search">
			{code}
				$attr_fenlei = array(
					'class'  => 'colonm down_list date_time',
					'show'   => 'app_show',
					'width'  => 104,
					'state'  => 0,
				);
			{/code}
	        {template:form/search_source,sort_id,$sort_id,$hg_sorts,$attr_fenlei}
		  </div>
	      <input type="text" name="k" class="search-k" value="" speech="speech" x-webkit-speech="x-webkit-speech" x-webkit-grammar="builtin:translate" placeholder="内容标题搜索" style="margin-left:10px;">
	      <input type="submit" value="搜索" />
	      <input type="hidden" value="{$_INPUT['mid']}" name="mid"/>
	      <input type="hidden" value="{$_INPUT['a']}" name="a" />
	      <input type="hidden" value="{$_INPUT['site_id']}" name="site_id" />
	      <input type="hidden" value="{$_INPUT['client_type']}" name="client_type" />
	      {$pagelink}
	      </form>
	</div>
		<ul class="template-list clear">
		  {foreach $list as $k => $v}
			<li class="one-template" id="{$v['id']}">
				<p>&nbsp;</p>
				<span class="template-name" title="{$v[file_name]}">{$v['title']}</span>
			</li>
		  {/foreach}				
		</ul>
		<div class="common-list-template clear">
			<p class="common-template-title"><input type="checkbox" name="same-template" /><span>同级栏目使用相同模板（包含5个独立模板的栏目）</span><em>红色栏目为已设置独立模板，如果保存，则会覆盖之前设置的模板</em></p>
			<ul>
			  <li><input type="checkbox" name="news_page"/><label>新闻首页</label></li>	
			  <li><input type="checkbox" name="news_page"/><label>知天下</label></li>
			  <li><input type="checkbox" name="news_page"/><label>微看点</label></li>
			  <li><input type="checkbox" name="news_page"/><label>视精彩</label></li>
			  <li><input type="checkbox" name="news_page"/><label>图新鲜</label></li>
			  <li><input type="checkbox" name="news_page"/><label>24小时</label></li>
			  <li><input type="checkbox" name="news_page"/><label>锐评论</label></li>
			  <li><input type="checkbox" name="news_page"/><label>专题汇</label></li>	
			  <li><input type="checkbox" name="news_page"/><label>关键词</label></li>
			  <li><input type="checkbox" name="news_page"/><label class="red">济南要闻</label></li>
			  <li><input type="checkbox" name="news_page"/><label class="red">济南</label></li>
			  <li><input type="checkbox" name="news_page"/><label class="red">泉天下原创</label></li>
			  <li><input type="checkbox" name="news_page"/><label class="red">泉天下看点</label></li>
			  <li><input type="checkbox" name="news_page"/><label>军事</label></li>
			  <li><input type="checkbox" name="news_page"/><label>网友关注</label></li>
			  <li><input type="checkbox" name="news_page"/><label>山东</label></li>
			  <li><input type="checkbox" name="news_page"/><label>微播报</label></li>
			  <li><input type="checkbox" name="news_page"/><label>国内</label></li>
			</ul>
		</div>					
</div>
</body>