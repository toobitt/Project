{template:head}
{css:ad_style}
{css:column_node}
{js:interview}

{js:input_file}
<style type="text/css">
.source_item {cursor:pointer; border:1px solid #CCC; display:inline-block; padding:3px 5px; margin:5px;}
</style>
{code}
if( $formdata['id'] )
{
	$optext = '修改';
	$ac = 'update';
}
else
{
	$optext = '新增';
	$ac = 'create';
}

$areas 		= $key_value[0]['areas'];
$langs 		= $key_value[0]['langs'];
$persons 	= $key_value[0]['persons'];
$nodes 		= $key_value[0]['nodes'];

//演员id，转化为演员名字
$formdata['actor'] = explode(',', $formdata['actor']);
foreach ( $formdata['actor'] as $t )
	$actors[$t] = $persons[$t];
$formdata['actor'] = implode(',', $actors);

//导演转换
$formdata['director'] = explode(',', $formdata['director'] );
foreach ( $formdata['director'] as $t )
	$directors[$t] = $persons[$t];
$formdata['director'] = implode(',', $directors) ;

//语言转换
$formdata['lang_id'] = explode(',', $formdata['lang_id'] );
foreach ( $formdata['lang_id'] as $t )
	$lang_ids[$t] = $langs[$t];
$formdata['lang_id'] = implode(',', $lang_ids) ;


//分类转换
$formdata['movie_sort_id'] = explode(',', $formdata['movie_sort_id'] );
foreach ( $formdata['movie_sort_id'] as $t )
	$node_ids[$t] = $nodes[$t];
$formdata['movie_sort_id'] = implode(',', $node_ids) ;



{/code}
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form 
			action="run.php?mid={$_INPUT['mid']}&infrm=1&nav=1" 
			method="post" enctype="multipart/form-data" class="ad_form h_l">	
				<input type="hidden" value="{$formdata['id']}" name='id' style="width:440px;">
				<h2>{$optext}电影信息</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">电影名称：</span>
								<input type="text" value="{$formdata['name']}" name='name' style="width:440px;">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">电影别名：</span>
								<input type="text" value="{$formdata['other_name']}" name='other_name' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">选择封面：</span>
								<span class="file_input s " id="file_input" >选择文件</span>
								<span id="logo_img" style="float:right;"></span>
								<input name="icon" type="file" value="" style="width:85px;position: relative;left: -91px;opacity: 0;cursor: pointer;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title">电影简述：</span>
								<textarea rows="3" cols="80" name="brief">{$formdata['brief']}</textarea>
							</div>
						</li>
						<div class="form_ul_div">
							<span class="title">获得奖项：</span>
							<textarea rows="3" cols="80" name="film_awords">{$formdata['film_awords']}</textarea>
						</div>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">地区：</span>
								<select name="area_id">
									{if $areas[$formdata['area_id']]}
										<option value="{$formdata['area_id']}">{$areas[$formdata['area_id']]}</option>
									{/if}
									{foreach $areas as $k => $v}
										<option value="{$k}">{$v}</option>
									{/foreach}
								</select>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">分类：</span>
								<input id="qqq_node" type="text" value="{$formdata['movie_sort_id']}" name='movie_sort_id' style="width:440px;">
								<script type="text/javascript">
									function add_node( c )
									{
										if( document.getElementById("qqq_node").value == '')
										{
											document.getElementById("qqq_node").value = c ;
										}
										else
										{
											document.getElementById("qqq_node").value = document.getElementById("qqq_node").value + ',' + c ;
										}
									}
								</script>
								<select name="" onchange="add_node(this.options[this.selectedIndex].value)">
									{foreach $nodes as $k => $v}
										<option value="{$v}">{$v}</option>
									{/foreach}
								</select>
								</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">语言：</span>
								<input id="qqq_lang" type="text" value="{$formdata['lang_id']}" name='lang_id' style="width:440px;">
								<script type="text/javascript">
									function add_lang( c )
									{
										if( document.getElementById("qqq_lang").value == '')
										{
											document.getElementById("qqq_lang").value = c ;
										}
										else
										{
											document.getElementById("qqq_lang").value = document.getElementById("qqq_lang").value + ',' + c ;
										}
									}
								</script>
								<select name="" onchange="add_lang(this.options[this.selectedIndex].value)">
									{foreach $langs as $k => $v}
										<option value="{$v}">{$v}</option>
									{/foreach}
								</select>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">导演：</span>
								<input id="qqq_director" type="text" value="{$formdata['director']}" name='director' style="width:440px;">
								<script type="text/javascript">
									function add_director( c )
									{
										if( document.getElementById("qqq_director").value == '')
										{
											document.getElementById("qqq_director").value = c ;
										}
										else
										{
											document.getElementById("qqq_director").value = document.getElementById("qqq_director").value + ',' + c ;
										}
									}
								</script>	
								<select name="" onchange="add_director(this.options[this.selectedIndex].value)">
									{foreach $persons as $k => $v}
										<option onclick="add_director('{$v}')">{$v}</option>
									{/foreach}
								</select>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">主演：</span>
								<input id="qqq_actor" type="text" value="{$formdata['actor']}" name='actor' style="width:440px;">
								<script type="text/javascript">
									function add_actor( c )
									{
										if( document.getElementById("qqq_actor").value == '')
										{
											document.getElementById("qqq_actor").value = c ;
										}
										else
										{
											document.getElementById("qqq_actor").value = document.getElementById("qqq_actor").value + ',' + c ;
										}
									}
								</script>
								<select onchange="add_actor(this.options[this.selectedIndex].value)">
									{foreach $persons as $k => $v}
										<option onclick="add_actor('{$v}')">{$v}</option>
									{/foreach}
								</select>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">电影票房：</span>
								<input type="text" value="{$formdata['ticket_office']}" name='ticket_office' style="width:200px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">视频库id：</span>
								<input type="text" value="{$formdata['media_id']}" name='media_id' style="width:200px;">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div"><span class="title">上映时间：</span>
								<input type="text" name="release_time" value="{$formdata['release_time']}" style="width:220px;"  onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss'})">
								
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title overflow">创建时间：</span>
								<input type="text" name="create_time" value="{$formdata['create_time']}"  style="width:220px;"  onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss'})">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="title overflow">更新时间：</span>
								<input type="text" name="update_time" value="{$formdata['update_time']}"  style="width:220px;"  onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss'})">
								<font class="important" style="color:red">*</font>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">时长：</span>
								<input type="text" name="film_range" value="{$formdata['film_range']}"  style="width:200px;">
								
							</div>
						</li>
						{code}
						if( $formdata['id'] )
						{
						{/code}
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">下载次数：</span>
								<input type="text" value="{$formdata['download_num']}" name='download_num' style="width:440px;">	
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">点击次数：</span>
								<input type="text" value="{$formdata['click_num']}" name='click_num' style="width:440px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">分享次数：</span>
								<input type="text" value="{$formdata['share_num']}" name='share_num' style="width:440px;">
								
							</div>
						</li>
						{code}
						}
						{/code}
						<li class="i">
							<div class="form_ul_div clear">
								<span class="title">审核状态：</span>
								<input type="radio" name="status" value="3"  {if  $formdata['status']=='3'}checked="checked"{/if}/> 已审核
								<input type="radio" name="status" value="2"  {if  $formdata['status']=='2'}checked="checked"{/if}/> 未审核
								<input type="radio" name="status" value="1"  {if  $formdata['status']=='1'}checked="checked"{/if}/> 打回
							</div>
						</li>
					</ul>
				<br />
				<input type="hidden" name="a" value="{$ac}" />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}