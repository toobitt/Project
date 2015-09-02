{template:head}
{css:upload_vod}
{js:upload_vod}
{js:vod}
{js:vod_upload_pic_handler}
{code}
  $image_resource = RESOURCE_URL;
{/code}
<script type="text/javascript">
  hg_resize_nodeFrame();
  $(function(){
	var mid = '{$_INPUT['mid']}';
	upload_update_preview(mid);
  });
	
</script>
 
<div  id="updatelist"  name="updatelist" class="clear">
 <form action="./run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" name="vodform"  id="vodform"  onsubmit="return hg_ajax_submit('vodform', '');">
	<div class="right clear">
            <div class="bg_middle">
                <h2>编辑视频</h2>
                <div class="info">
                <div class="clear" style="height:156px">
                  <a class="info-bigimg"><img onclick="uploadimg_show({$formdata['vodid']},{$formdata['id']},{$_INPUT['mid']});"   src="{$formdata['source_img']}"   id="pic_face"    title="点击图片更换截图" /></a>
               	  <div class="info-left"><input type="text" name="title"  value="{$formdata['title']}"  class="info-title info-input-left"  onfocus="text_value_onfocus(this,'请输入标题');" onblur="text_value_onblur(this,'请输入标题');"/><textarea rows="2" class="info-description info-input-left t_c_b"  id="comment" name="comment"  onfocus="textarea_value_onfocus(this,'这里输入描述');" onblur="textarea_value_onblur(this,'这里输入描述');">{$formdata['comment']}</textarea></div>
                 </div>
				  <div id="info-img" class="clear" style="display:none">
					<span class="info-img-top"></span>
					  <dl id="add-img">
					        <div id="img_change">
							<dt>选择本标注的视频示意图</dt>
							<dd><a><img src="{$image_resource}loading6.gif"  width="117" height="88" /></a><span class="info-img-selected"></span></dd>                       
                            <dd><a><img src="{$image_resource}loading6.gif"  width="117" height="88" /></a><span class="info-img-selected"></span></dd>
                            <dd><a><img src="{$image_resource}loading6.gif"  width="117" height="88" /></a><span class="info-img-selected"></span></dd>
                            <dd><a><img src="{$image_resource}loading6.gif"  width="117" height="88" /></a><span class="info-img-selected"></span></dd>
                            <dd><a><img src="{$image_resource}loading6.gif"  width="117" height="88" /></a><span class="info-img-selected"></span></dd>
                            <dd><a><img src="{$image_resource}loading6.gif"  width="117" height="88" /></a><span class="info-img-selected"></span></dd>
                            <dd><a><img src="{$image_resource}loading6.gif"  width="117" height="88"  /></a><span class="info-img-selected"></span></dd>
                            <dd><a><img src="{$image_resource}loading6.gif"  width="117" height="88"  /></a><span class="info-img-selected"></span></dd>
                            <dd><a><img src="{$image_resource}loading6.gif"  width="117" height="88"  /></a><span class="info-img-selected"></span></dd>
                            </div>
                            <dd><a><div id="add_from_compueter"></div></a><span class="info-img-selected"></span></dd>
					  </dl>
                     
				  </div>
                </div>
                <div class="info">
                	<table width="100%" border="0" class="info-table">
                      <tr>
                        <td width="72%">副题</td>
                        <td width="28%">来源</td>
                      </tr>
                      <tr>
                        <td><input type="text" name="subtitle" id="subtitle"  value="{$formdata['subtitle']}" class="subtitle info-input-left"/></td>
                        <td><select id="laiyuan" name="laiyuan" class="subtitle info-input-right" style="width:180px;height:24px;border:1px solid #C5C4C4">
						 {foreach $formdata['source'] as $k => $v}
						  <option value="{$k}">{$v}</option>
						  {/foreach}
						 </select></td>
                      </tr>
                    </table>
              </div>
              <div class="info">
                	<table width="100%" border="0" class="info-table">
                      <tr>
                        <td width="72%" valign="middle">关键字</td>
                        <td width="28%">作者</td>
                      </tr>
                      <tr>
                        <td valign="middle"><input type="text" name="keywords" id="keywords" value="{$formdata['keywords']}" class="subtitle info-input-left"/></td>
                        <td><input type="text" name="author" id="author" value="{$formdata['author']}"   class="subtitle info-input-right"/></td>
                      </tr>
                    </table>
              </div>
              <div class="info_show">
                            <ul class="part clear">
                               <li id="show" class="show">
                               <ul id="column_1" class="clear">
                                   <li>幸福晚点名</li>
                                   <li>百姓聊斋</li>
                                   <li>非诚勿扰</li>
                               </ul>
                               <span id="edit" class="edit"></span><a onclick="show_column({$formdata['id']});">发布至网站栏目</a>
                        
                               </li>
                               <li id="column" class="clear shows" style="display:none">
         	<div id="all">
            	<div class="pub_div_bg clear" id="recommend_op"   style="height:236px;overflow-y:auto;">
                    <div class="pub_div clear" >
                     <ul>
                     	<li class="first"><span class="checkbox"></span><a href="##">最近使用<strong>&gt;&gt;</strong></a></li>
                          <li><input name="" type="checkbox" value="" class="checkbox" /><a href="##">新闻<strong>&gt;&gt;</strong></a> </li>
                          <li><input name="" type="checkbox" value="" class="checkbox" /><a href="##">财经<strong>&gt;&gt;</strong></a></li>
                          <li><input name="" type="checkbox" value="" class="checkbox" /><a href="##">体育<strong>&gt;&gt;</strong></a></li>
                          <li><input name="" type="checkbox" value="" class="checkbox" /><a href="##">财经<strong>&gt;&gt;</strong></a></li>
                          <li><input name="" type="checkbox" value="" class="checkbox" /><a href="##">汽车<strong>&gt;&gt;</strong></a></li>
                        <li><input name="" type="checkbox" value="" class="checkbox" /><a href="##">科技<strong>&gt;&gt;</strong></a></li>
                        
                        <li><input name="" type="checkbox" value="" class="checkbox" /><a href="##">女性<strong>&gt;&gt;</strong></a></li>
                        <li><input name="" type="checkbox" value="" class="checkbox" /><a href="##">时尚<strong>&gt;&gt;</strong></a></li>
                        <li><input name="" type="checkbox" value="" class="checkbox" /><a href="##">美食<strong>&gt;&gt;</strong></a></li>  
                     </ul>
                    </div>
                 <div class="pub_div">
                     <ul>
                        <li><input name="" type="checkbox" value="" class="checkbox" /><a href="###">高清首页<strong>&gt;&gt;</strong></a></li>
                        <li><input name="" type="checkbox" value="" class="checkbox" /><a href="###">电影<strong>&gt;&gt;</strong></a></li>
                        <li><input name="" type="checkbox" value="" class="checkbox" /><a href="###">时事<strong>&gt;&gt;</strong></a></li>
                         <li><input name="" type="checkbox" value="" class="checkbox" /><a href="###">大周五电影院<strong>&gt;&gt;</strong></a></li>
                          <li><input name="" type="checkbox" value="" class="checkbox" /><a href="###">直播<strong>&gt;&gt;</strong></a></li>
                     </ul>
                 </div>
             </div>
	 <div class="recent">
        描述信息内容描述信息内容描述信息内容描述信息内容描述信息内容描述信息内容
 	</div>    
 </div>
 <input type="hidden" value="" id="select_cols" name="all_col_target">
                               </li>
                            </ul>
                        	
              </div>
              <div class="info">
              	<table width="100%" border="0" class="info-table">
                      <tr>
                        <td colspan="3" valign="middle">选项</td>
                      </tr>
                      <tr>
                        <td colspan="3" valign="middle">
                        	<ul class="options">
                            	<li><span>开放评论</span><input name="comment2out" value="1" type="checkbox"  checked /></li>
                            	<li><span>自动台标</span><input type="checkbox" name="taibiao" value="2" checked /></li>
                            	<li><span>附加广告</span><input type="checkbox" name="guanggao" value="3" checked /></li>
                            	<li><span>允许打分</span><input type="checkbox" name="dafen" value="4" checked /></li>
                            	<li><span>观看心情</span><input type="checkbox" name="xinqing" value="5" checked /></li>
                            </ul>
                        </td>
                      </tr>
                    </table>
              </div>
              <div class="submit clear">
              	<span>
                <a>编辑：刘军</a>
              	<a>退出</a>
                </span>
              	<input class="fix"  type="submit" name="submit" id="submit_bianji" value="编辑完成">
                <a class="back">返回我的任务</a>
              	
              	
              </div>
            </div>
        </div>
		<div class="right_version"  style="overflow:hidden;" >
			<h2>历史版本</h2>
			<ul>
			 {if $formdata['update_copyright']}
     			{foreach $formdata['update_copyright'] as $v}
				<li  onclick="hg_get_copyright({$v['id']},{$_INPUT['mid']});"  style="cursor:pointer;">
					<span>{$v['update_time']}</span>
					<span>{$v['update_man']}编辑</span>
				</li>
			   {/foreach}
  			 {/if}	
			</ul>
		</div>
	  
	  	
	  <input type="hidden" name="img_src_cpu"  id="img_src_cpu"  value="" />
	  <input type="hidden" name="img_src"  id="img_src"  value=""   />
	  <input type="hidden" name="vod_leixing" value=0  />
	  <input type="hidden" value="{$a}" name="a" />
	  <input type="hidden" value="{$$primary_key}" name="{$primary_key}"  />
	  <input type="hidden" name="referto" value="{$_INPUT['referto']}"  />
</form>
</div>
{template:foot}













