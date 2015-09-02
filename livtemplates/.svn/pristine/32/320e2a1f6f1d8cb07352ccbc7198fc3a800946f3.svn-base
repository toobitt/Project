{template:head}
{code}
$formdata = $formdata[0];
$re = $formdata['sort_id']?$formdata['sort_name']:'请选择分类';
{/code}
{css:ad_style}
{css:column_node}

<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear" style="padding-bottom: 30px;">
		<div class="ad_middle"  style="width:900px">
			<form name="editform" action="run.php?mid={$_INPUT['mid']}" method="post" class="ad_form h_l">
			<h2>{if $_INPUT['id']}编辑地铁站点{else}新增地铁站点{/if}</h2>
				<ul class="form_ul">
						<li class="i">
							<div class="m2o-item site-form-title">
								<span  class="title" style="width:100px;">站点名称:</span>
								<input type="text" value="{$formdata['title']}" name='title' style="width:100px;">
								<span  class="title" style="width:100px;">站点标识:</span>
								<input type="text" value="{$formdata['sign']}" name='sign' style="width:100px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">描述备注：</span>
								<textarea rows="3" cols="80" name='brief'>{$formdata['brief']}</textarea>
							</div>
						</li>
						<!--<li class="i">
							<div class="form_ul_div">
								<span  class="title">景区地址：</span>
								<input type="text" value="{$list['address']}" id ='address' name='address' style="width:440px;">
							</div>
						</li>-->
						<li class="i" id="map">
							{code}
								$hg_map = array(
										'height'=>180,
										'width'=>600,							
										'longitude'=>$formdata['longitude'],        //经度
										'latitude'=>$formdata['latitude'], 			//纬度
										'zoomsize'=>13,          					//缩放级别，1－21的整数
										//'areaname'=>$formdata['address'],         //显示地区名称，纬度,经度与地区名称二选1
										'is_drag'=>1,            					//是否可拖动 1－是，0－否
										//'objid'=>'address', 
									);
							{/code}
							{template:form/google_map,longitude,latitude,$hg_map}
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">线路名称:</span>
								<input type="text" value="{$formdata['title']}" name='title' style="width:200px;">
							</div>
						</li>
		        		<li class="i">
							<div class="form_ul_div clear">
								<span class="title">示意图：</span>
								<div id="log_box" style="float:left;margin-top:10px;">
								{if is_array($pic) && count($pic) > 0}
									{foreach $pic as $k => $v}
										{code}
											$img='';
											if($v)
												$img = $v['host'] . $v['dir'] . '100x75/' . $v['filepath'] . $v['filename'];
										{/code}	
										{if $img}
											<div id="mateiral_{$v['id']}" class="material_log">
												<img src="{$img}" alt="" width="100" height="75" />
												<span class="material_del">X</span>
												<input type="hidden" name="log[]" value="{$pic_json[$k]}"/>
											</div>
										{/if}
									{/foreach}
								{/if}
								</div>
								<div id="circle_upload" style="float: left;"></div>
							</div>
						</li>
						<li class="i">
							<div class="form-dioption-sort form-dioption-item"  id="sort-box">
				                <label style="color:#9f9f9f;margin-right:15px;{if !$formdata['sort_id']}display:none;{/if}">模板分类</span></label><p class="sort-label" _multi="subway_sort" >{$re}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
									<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
					                <input name="sort_id" type="hidden" value="{$formdata['sort_id']}" id="sort_id" />
            				</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">标识名:</span>
								<input type="text" value="{$formdata['sign']}" name='sign' style="width:200px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">起始方向:</span>
								<input type="text" value="{$formdata['start']}" name='start' style="width:100px;"> －
								<input type="text" value="{$formdata['end']}" name='end' style="width:100px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span  class="title">运行时间:</span>
								<input type="text" value="{$formdata['runtime']}" name='runtime' style="width:200px;">
							</div>
						</li>
						<li class="i">
							<div class="form_ul_div clear">
								<span class="site_title">在建/运行:</span>
								<span class="site_radio">
								  <input type="radio" name="is_operate" value="1"  {if  $formdata['is_operate']==1}checked="checked"{/if}/> 是
								  <input type="radio" name="is_operate" value="0"  {if  $formdata['is_operate']==0}checked="checked"{/if}/> 否
							    </span>
							</div>
						</li>
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="site_id" value="{$_INPUT['site_id']}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br/>
					<div class="temp-edit-buttons">
					   <input type="submit" name="sub" value="确定" class="edit-button submit"/>
					   <input type="button" value="取消" class="edit-button cancel" onclick="javascript:history.go(-1);"/>
				    </div>
			</form>
		</div>
	</div>
{template:foot}