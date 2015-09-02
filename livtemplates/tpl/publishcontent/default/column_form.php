{template:head}
{css:ad_style}
{css:column_node}
{css:column_form}

{js:column_node}
{js:jqueryfn/jquery.tmpl.min}
{js:common/ajax_upload}
{js:publishcontent/edit_column}

{code}
	//print_r($clients);
	$column_form[0] = $formdata['column'];
	//print_r($column_form[0]);
{/code}
<div id="hg_page_menu" class="head_op_program" {if $_INPUT['infrm']} style="display:none"{/if}>
	<a href="?mid={$_INPUT['mid']}&a=form{$_ext_link}" class="button_6" style="font-weight:bold;">添加栏目</a>
</div>
<div id="channel_form" style="margin-left:60%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="./run.php?mid={$_INPUT['mid']}" method="post"  class="ad_form h_l" enctype="multipart/form-data">
				<h2>{if $column_form[0]['id']}更新栏目{else}添加栏目{/if}</h2>
				<script>
				jQuery(function($){
				    $('.ext-tab').on('click', 'a', function(){
				        var cname = 'ext-current';
				        if($(this).hasClass(cname)){
				            return;
				        }
				        $(this).addClass(cname).siblings().removeClass(cname);
				    });
				});
				</script>
				<div class="ext-tab">
                    <a href="javascript:void(0)" onclick="mod_hidden_show('basic_info')" class="ext-current">基本设置 </a>
                    <a href="javascript:void(0)" onclick="mod_hidden_show('display_set')">栏目示意图</a>
                    <a href="javascript:void(0)" onclick="mod_hidden_show('pub_set')">发布</a>
                    <!--<a href="javascript:void(0)" onclick="mod_hidden_show('recommend_block')">发布设置 </a>-->
                    <!--<a href="javascript:void(0)" onclick="mod_hidden_show('column_page')">页面设置</a>-->
				</div>
				<div id="basic_info" >
					<ul class="form_ul">
					<!--
					<li class="i">
							<div class="form_ul_div">
								<span class="column_title" style="width:130px;">支持的模块：</span>
								{if $formdata['module']}
								{foreach $formdata['module'] as $k=>$v}
								<input type=checkbox name="support_module[]" value="{$v['id']}" {code}if(in_array($v['id'],explode(',',$column_form[0]['support_module']))) echo "checked";{/code} />{$v['name']}
								{/foreach}
								{else}
								无
								{/if}
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
					<li class="i">
							<div class="form_ul_div">
								<span class="column_title" style="width:130px;">支持的内容类型：</span>
								{if $formdata['content_type']}
								{foreach $formdata['content_type'] as $k=>$v}
								<input type=checkbox name="support_content_type[]" value="{$v['id']}" {code}if(in_array($v['id'],explode(',',$column_form[0]['support_content_type']))) echo "checked";{/code} />{$v['content_type']}
								{/foreach}
								{else}
								无
								{/if}
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						-->
						<!--<li class="i">
							<div class="form_ul_div">
								<span class="column_title" style="width:130px;">支持的客户端：</span>
								{foreach $formdata['site_support_client'] as $k=>$v}
								<input type=checkbox name="support_client[]" value="{$v}" 
								{code}
									if(!empty($column_form[0]['support_client']))
									{
									
										$sc = explode(',',$column_form[0]['support_client']);
										if(in_array($v,$sc))
										{
											echo "checked";
										}
									}
									
									echo "  />";
									
									echo empty($formdata['all_client'][$v]['name'])?'':$formdata['all_client'][$v]['name'];
								{/code} 
								{/foreach}
								&nbsp
								<span class="site_fill_tip">
								</span>
							</div>
						</li>-->
						<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">栏目名称：</span>
								<input type="text" value="{$column_form[0]['name']}" name='column_name' style="width:300px;">
								<span class="site_fill_tip">
								名称/简称最多30/4个汉字
								</span>
							</div>
						</li>
						<!--<li class="i">
							<div class="form_ul_div">
								<span  class="site_title">栏目简称：</span>
								<input type="text" value="{$column_form[0]['shortname']}" name='shortname' style="width:100px;">

								栏目排序：
								<input type="text" value="{$column_form[0]['column_sort']}" name='column_sort' style="width:60px;">
								
								<span class="site_fill_tip">
									一般用于导航链接
								</span>
							</div>
						</li>-->
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">关键字：</span>
								<input type="text" value="{$column_form[0]['keywords']}" name='keywords' style="width:450px;">
								<span class="site_fill_tip">
									用,号分隔每个关键字
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">描述：</span>
								<textarea  name='content' style="width:450px;">
								{if $column_form[0]['content']}{$column_form[0]['content']}{/if}
								</textarea>
								<span class="site_fill_tip">
									添加时注意把前面的空格删除，最多128个字页面描述
								</span>
							</div>
						</li>
                                                <li class="i">
							<div class="form_ul_div">
								<span class="site_title">Meta标题：</span>
								<input type="text" value="{$column_form[0]['shortname']}" name='shortname' style="width:450px;">
								<span class="site_fill_tip">
									SEO优化Title:{site_title}代替站点标题,{column_title}代替栏目标题
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="site_title">所属栏目：</span>
								{code}
									$hg_attr['node_en'] = 'column_node';
									$site_id = $column_form[0]['site_id'];
									$hg_attr['expand'] = array('site_id'=>$site_id);
								{/code}
								{template:unit/class,column_fid,$column_form[0]['fid'],$node_data}
							</div>
						</li>	
						
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">栏目目录：</span>
								<input type="text" value="{$column_form[0]['column_dir']}" name='column_dir' id="column_dir" style="width:100px;">
								<span class="site_fill_tip">
										不填写为默认自动，少于60个字母
								</span>
							</div>
						</li>
						
						{if $column_form[0]['site_weburl']}
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">栏目域名：</span>
								<input type="text" value="{$column_form[0]['childdomain']}" name='childdomain' id="childdomain" style="width:100px;">
								{code}echo $column_form[0]['site_weburl'];{/code}
								<input type="hidden" name="childdomain_suffix" id="childdomain_suffix" value="{code}echo $column_form[0]['site_weburl'];{/code}" />
								<span class="site_fill_tip">
									如未绑定请留空
								</span>
							</div>
						</li>
						{/if}
						<!--
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">内容生成短目录：</span>
								<input type="text" value="{$column_form[0]['custom_content_dir']}" name='custom_content_dir' style="width:300px;">
								<span class="site_fill_tip">
								相对站点目录
								</span>
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">主内容模型：</span>
								<select name='primarymode' value="{$site_form[0]['primarymode']}">
									{foreach $_configs['site_suffix'] as $k=>$v}
									<option value="{$k}" {code}if($site_form[0]['primarymode']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<span class="site_fill_tip">
									如未绑定请留空
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="site_title">辅助模型：</span>
								<input type=checkbox name="assistantmode[]">资讯模型
								<span class="site_fill_tip">
									辅助模型建议不要超过3个
								</span>
							</div>
						</li>
						-->
						<li class="i">
							<div class="form_ul_div" data-isoutlink="{$column_form[0]['is_outlink']}">
								<span class="site_title">是否外链：</span>
								<input type=radio value="1" name='is_outlink' {if $column_form[0]['is_outlink']}checked{/if}>是
								<input type=radio value="0" name='is_outlink' {if !$column_form[0]['is_outlink']}checked{/if}>否
								<span class="site_fill_tip">
									注意：若该栏目是外部链接，将不会显示在左栏栏目树中。
								</span>
							</div>
						</li>
						<li class="i lm {if !$column_form[0]['is_outlink']}hide{/if}">
							<div class="form_ul_div">
								<span class="site_title">栏目链接地址：</span>
								<input type="text" value="{$column_form[0]['linkurl']}" name='linkurl' style="width:450px;">
								<span class="site_fill_tip">
									注意：若该栏目是外部链接，将不会显示在左栏栏目树中。
								</span>
							</div>
						</li>
						<li class="i sy {if $column_form[0]['is_outlink']}hide{/if}">
							<div class="form_ul_div">
								<span class="column_title">栏目首页：</span>
								<select name='column_file' >
									{foreach $_configs['column_file'] as $k=>$v}
									<option value="{$k}" {code}if($column_form[0]['column_file']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
					</ul>
					</div>
					<div id="pub_set" style="display:none">
						<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">栏目首页文件名称：</span>
								<input type="text" value="{code}if($column_form[0]['colindex']) echo $column_form[0]['colindex'];else echo $_configs['defalult_column_index_name'];{/code}" name='colindex' style="width:100px;">.php
								<span class="site_fill_tip">
									不需要填写文件扩展名，少于16个字母
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">栏目生成方式：</span>
								<select name='maketype' id='maketype' value="{$column_form[0]['maketype']}">
									{foreach $_configs['column_produce_format'] as $k=>$v}
									<option value="{$k}" {code}if($column_form[0]['maketype']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<select name='suffix' id='suffix' value="{$column_form[0]['suffix']}">
									{foreach $_configs['column_suffix'] as $k=>$v}
									<option value="{$v}" {code}if($column_form[0]['suffix']==$v) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div" data-maketype="{$column_form[0]['col_con_maketype']}">
								<span class="column_title">内容生成方式：</span>
								<select name='col_con_maketype' id='col_con_maketype' value="{$column_form[0]['col_con_maketype']}">
									{foreach $_configs['column_produce_format'] as $k=>$v}
									<option value="{$k}" {code}if($column_form[0]['col_con_maketype']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i cf {if $column_form[0]['col_con_maketype'] == 1}hide{/if}">
							<div class="form_ul_div">
								<span class="column_title">动态生成时该栏目下内容页的文件名：</span>
								<input type="text" value="{$column_form[0]['contentfilename']}" name='contentfilename' style="width:100px;">
								<span class="site_fill_tip">
									不填写为默认自动，少于20个字母,无需扩展名
								</span>
							</div>
						</li>
<!--						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">栏目下内容缩略图宽：</span>
								<input type="text" value="{$column_form[0]['thumbwidth']}" name='thumbwidth' style="width:30px;">
								<span class="site_fill_tip">
									不填写则继承上级栏目，数字
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">栏目下内容缩略图高：</span>
								<input type="text" value="{$column_form[0]['thumbheight']}" name='thumbheight' style="width:30px;">
								<span class="site_fill_tip">
										不填写则继承上级栏目，数字
								</span>
							</div>
						</li>

						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">选择水印图片：</span>
								<select name='watermark' value="{$site_form[0]['watermark']}">
									{foreach $_configs['site_suffix'] as $k=>$v}
									<option value="{$k}" {code}if($site_form[0]['watermark']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<span class="site_fill_tip">
									默认继承栏目设置或站点设置
								</span>
							</div>
						</li>
-->
						<li class="i ff {if $column_form[0]['col_con_maketype'] == 2}hide{/if}">
							<div class="form_ul_div">
								<span class="column_title">内容目录格式：</span>
								<select name='folderformat' value="{$site_form[0]['folderformat']}">
									{foreach $_configs['column_folderformat'] as $k=>$v}
									<option value="{$k}" {code}if($column_form[0]['folderformat']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<span class="site_fill_tip">
										当内容多时，对内容进行分目录管理
								</span>
							</div>
						</li>
						<li class="i ffr {if $column_form[0]['col_con_maketype'] == 2}hide{/if}">
							<div class="form_ul_div">
								<span class="column_title">内容文件格式：</span>
								<input type=radio name="fileformatradio" value='1' {if $column_form[0]['fileformatradio']==1}checked{/if}/>
								<input type=text name="fileformattext" 
								value="{code}
								 if($column_form[0]['fileformatradio']==1 && $column_form[0]['fileformat'])
								 {
								 	echo $column_form[0]['fileformat'];
								 }
								{/code}" size=10 />{ID}
								{code}
								 if($column_form[0]['fileformatradio']==1 && $column_form[0]['fileformat'])
								 {
								 	echo $column_form[0]['fileformat'];
								 }
								{/code}
								<br>
								<input type=radio name="fileformatradio" value='2' style="margin-left:165px" {if $column_form[0]['fileformatradio']!=1}checked{/if}/>
								<select name='fileformat' value="{$column_form[0]['fileformat']}">
									{foreach $_configs['column_fileformat'] as $k=>$v}
									<option value="{$k}" {code}if($column_form[0]['fileformat']==$k) echo "selected";{/code}>
										{$v['brief']}
									</option>
									{/foreach}
								</select><br>
								<span class="site_fill_tip">
									选择内容命名格式
								</span>
							</div>
						</li>
                        <li class="i ff {if $column_form[0]['col_con_maketype'] == 2}hide{/if}">
							<div class="form_ul_div">
								<span class="column_title">内容客户端展现样式：</span>
                                                                <select name="cssid">
                                                                    <option value="3" {if $column_form[0]['cssid']==3}selected{/if}>标题索引图</option>
                                                                    <option value="1" {if $column_form[0]['cssid']==1}selected{/if}>只显示标题</option>
                                                                    <option value="2" {if $column_form[0]['cssid']==2}selected{/if}>标题加描述</option>
                                                                    <option value="4" {if $column_form[0]['cssid']==4}selected{/if}>标题加描述加索引图</option>
                                                                    <option value="5" {if $column_form[0]['cssid']==5}selected{/if}>标题加多图</option>
                                                                    <option value="6" {if $column_form[0]['cssid']==6}selected{/if}>大图加下标题加描述</option>
                                                                    <option value="7" {if $column_form[0]['cssid']==7}selected{/if}>大图加下标题</option>
                                                                    <!--
                                                                    <option value="8" {if $column_form[0]['cssid']==8}selected{/if}></option>
                                                                    -->
                                                                    <option value="9" {if $column_form[0]['cssid']==9}selected{/if}>左图+标题+描述</option>
                                                                    <option value="10" {if $column_form[0]['cssid']==10}selected{/if}>右图+标题+描述</option>
                                                                </select>
								<span class="site_fill_tip">
									
								</span>
							</div>
						</li>
						</ul>
					</div>
					<div id="recommend_block" style="display:none">
						<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">浏览器栏显示：</span>
								<input type="text" value="{$column_form[0]['titleformat']}" name='titleformat' style="width:300px;">
								<span class="site_fill_tip">
									显示在浏览器顶部的标题格式，可以使用提供的变量
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">是否需要内容统计：</span>
								{foreach $_configs['is_not'] as $k=>$v}
								<input type=radio name="needartstat" value="{$k}"  {code}if($column_form[0]['needartstat']==$k) echo "checked";{/code}/>{$v}
								{/foreach}
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">是否需要栏目统计：</span>
								{foreach $_configs['is_not'] as $k=>$v}
								<input type=radio name="needcolstat" value="{$k}" {code}if($column_form[0]['needcolstat']==$k) echo "checked";{/code} />{$v}
								{/foreach}
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">是否接收投稿：</span>
								{foreach $_configs['is_not'] as $k=>$v}
								<input type=radio name="needartadv" value="{$k}" {code}if($column_form[0]['needartadv']==$k) echo "checked";{/code} />{$v}
								{/foreach}
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">引用文章生成方式：</span>
								{foreach $_configs['article_maketype'] as $k=>$v}
								<input type=radio name="article_maketype" value="{$k}" {code}if($column_form[0]['article_maketype']==$k) echo "checked";{/code} />{$v}
								{/foreach}
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						
						
<!--						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">请选择文章的默认状态：</span>
								<select name='fileformat' value="{$site_form[0]['fileformat']}">
									{foreach $_configs['site_suffix'] as $k=>$v}
									<option value="{$k}" {code}if($site_form[0]['fileformat']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<span class="site_fill_tip">
									如不填写，则继承上级栏目
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">为该栏目选择一个流程：</span>
								<select name='fileformat' value="{$site_form[0]['fileformat']}">
									{foreach $_configs['site_suffix'] as $k=>$v}
									<option value="{$k}" {code}if($site_form[0]['fileformat']==$k) echo "selected";{/code}>
										{$v}
									</option>
									{/foreach}
								</select>
								<span class="site_fill_tip">
									选择内容命名格式
								</span>
							</div>
						</li>

						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">是否生成RSS：</span>
								<input type=radio name="needrss" />是
								<input type=radio name="needrss" />否
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">生成RSS条数：</span>
								<input type="text" value="{$column_form[0]['rssnum']}" name='rssnum' style="width:70px;">
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">是否加入首页生成：</span>
								<input type=radio name="toindex" />是
								<input type=radio name="toindex" />否
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">加入首页条数：</span>
								<input type="text" value="{$column_form[0]['toindexnum']}" name='toindexnum' style="width:70px;">
								<span class="site_fill_tip">
								</span>
							</div>
						</li>
-->
						</ul>
					</div>
					
					<div id="display_set" style="display:none">
					     <div class="special-bigpic">
					     {code}
					     $pic = $column_form[0]['pic'];
					     {/code}
					         {if $pic}
					             {code}
										$url = $pic['host'].$pic['dir'].'650x80/'.$pic['filepath'].$pic['filename'];
						       	  {/code}
					         {/if}
			                 {if !$pic}<span class="bigpic-flag">添加栏目示意图</span>{/if}
			                <img class="{if !$pic}hide{/if}" src="{$url}"  title="栏目示意图" />
			                <p class="client_title {if $pic}show{/if}">默认示意图</p>
			            </div>
			            <input type="file" name="Filedata" id="bigFiledata"  value="submit" style="display:none;">
					      <div class="special-bigpic-client client-box">
			                  <div class="title"><span class="client_logo_item_add client-index">添加更多终端示意图</span></div>
					          <div class="client-list">
								   <span class="client_all_list_pointer"></span>
								    <div class="items">
							        {foreach $clients[0] as $ck =>$cv}
										  <span class="client-item" id="client{$ck}" data-id="{$ck}" data-name="{$cv}">{$cv}</span>
								    {/foreach}
								     </div>
								     <input type=file style="display:none;" class="client-file-data" />
							   </div>
							   <div class="client_log_all">
				                    <!-- 多终端题图start -->
									  {if $column_form[0]['client_pic']}
									    {foreach $column_form[0]['client_pic'] as $tc => $tv}
									        {code}
										       	 $url = $tv['host'].$tv['dir'].'650x80/'.$tv['filepath'].$tv['filename'];
										       	 $value = serialize($tv);
						       	            {/code}
					                       <div class="client_logo_item" data-id="{$tc}" id="client_logo_item{$tc}">
					                            <p class="client_logo" data-name="{$clients[$tc]}" data-id="{$tc}"><img src="{$url}"/></p>
					                            <p class="client_title">{$clients[0][$tc]}</p>
					                            <span class="client_logo_delete">x</span>
					                            <input type="hidden"  name="client_top_pic[{$tc}]" value='{$value}'/>
					                       </div>
					                     {/foreach}
									  {/if}		                
					                  <!-- 多终端题图end -->
			                  </div>
               			</div>
					</div>
					
					<div id="column_page" style="display:none">
					{foreach $formdata['site_support_client'] as $k=>$v}
						<li class="i">
							<div class="form_ul_div">
								<span class="column_title">
								{code}
								echo empty($formdata['all_client'][$v]['name'])?'':$formdata['all_client'][$v]['name'];
								{/code}
								页面名称：</span>
								<input type=text name="{$k}-page_name" value="list">
								
							</div>
						</li>
					{/foreach}
					</div>
					
				<input type="hidden" name="a" value="{if $column_form[0]['id']}update{else}create{/if}" />
				<input type="hidden" name="site_id" value="{$formdata['site_id']}" />
				<input type="hidden" name="column_id" id="column_id" value="{$formdata['column_id']}" />
			<!--<input type="hidden" name="column_fid" value="{$formdata['column_fid']}" />-->
				<input type="hidden" name="ajax" value="1" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{if $column_form[0]['id']}更新{else}添加{/if}" class="button_6_14"/>
			</form>
		</div>
	<div class="right_version" style="display:none;"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}
<!-- client隐藏域 -->
<script type="text/x-jquery-tmpl" id="client-tpl">
      <div class="client_logo_item" data-id="${id}" id="client_logo_item${id}">
           <p class="client_logo" data-name="${client_name}" data-id="${id}"><img src="${url}"/></p>
           <p class="client_title">${client_name}</p>
           <span class="client_logo_delete">x</span>
      </div>
</script>
<script type="text/javascript">
$(window).load(function(){
	var head_height = parent.$('.column-lujin').height(),
		_height = head_height + $('html').height();
	parent.$('body').height(_height);
});
function mod_hidden_show(id){
	$('#basic_info,#pub_set,#display_set,#column_page,#recommend_block').css('display','none');
	$('#'+id).css('display','');
}
</script>