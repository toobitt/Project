<?php ?>
{template:head}
{css:2013/form}
{css:2013/button}
{css:hg_sort_box}
{css:tv_interact_form}
{js:hg_preview}
{js:hg_sort_box}
{js:ajax_upload}
{js:common/common_form}
{js:2013/ajaxload_new}
{js:live/my-ohms}
{js:tv_interact/tv_interact_form}
{code}
if ( is_array($formdata ) )
{  
	foreach ( $formdata as $k => $v ) 
	{
		$$k = $v;
	}
}		
if($id)
{
	$optext="更新";
	$ac="update";
}
else
{
	$optext="添加";
	$ac="create";
}
$currentSort[$sort_id] = ($sort_id ? $sort_name : '选择分类');
$markswf_url = RESOURCE_URL.'swf/';

$user_limit_num = $user_limit_num ? $user_limit_num : 1;
$is_user_limit = isset($is_user_limit) ? $is_user_limit : 1; 
{/code}
<body>
<form class="m2o-form" name="editform" action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data" id="tv_interact_form" data-id="{$id}">
   <div id="ohms-instance" style="position:absolute;display:none;"></div>
   {template:unit/bg_picture}
    <header class="m2o-header">
    <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$optext}电视互动</h1>
            <div class="m2o-m m2o-flex-one">
                 <input placeholder="输入电视互动名称" name="name" class="m2o-m-title need-word-count" title="{$name}" required value="{$name}" />
                 <input type="hidden" name="old_name" value="{$name}" />
            </div>
            <div class="m2o-btn m2o-r">
                <input type="submit" value="保存信息" class="m2o-save" name="sub" id="sub" data-target="run.php?mid={$_INPUT['mid']}&a={$ac}" data-method="{$ac}"/>
                <span class="m2o-close option-iframe-back"></span>
                <input type="hidden" name="a" value="{$ac}" />
                <input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
                <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
            </div>
        </div>
      </div>  
    </header>
    <div class="m2o-inner">
    <div class="m2o-main m2o-flex">
        <aside class="m2o-l m2o-aside">
        	<div class="m2o-item img-info" style="position:relative">
        		<div class="indexpic icon">
        			<img src="{$indexpic}" />
                    <span class="indexpic-suoyin {if $formdata['indexpic']}indexpic-suoyin-current{/if}"></span>
                 </div>
                 <input type="file" name="index_file" style="display:none;" class="file" id="photo-file" />
        	</div>
        	<div class="form-dioption-sort m2o-item"  id="sort-box">
	            <label style="color:#9f9f9f;">分类：</label>
	            <p style="display:inline-block;" class="sort-label" _multi="tv_interact_node"> {$currentSort[$sort_id]}<img class="common-head-drop" src="{$RESOURCE_URL}tuji/drop.png" style="position: relative;left:10px;bottom:2px;" /></p>
				<div class="sort-box-outer"><div class="sort-box-inner"></div></div>
	            <input name="sort_id" type="hidden" value="{$sort_id}" id="sort_id" />
	            <input name="fieldcontentdel" type="hidden" value="{$sort_id}" />
        	</div>
        </aside>
        <section class="m2o-m m2o-flex-one">
        	<div class="basic-info">
	        	<div class="m2o-item tv-info">
	            	<a class="tv-title active" data-type="basic">基本信息</a>
	            </div>
        		<div class="m2o-item cut-off">
	        		<label class="title">描述简介: </label>
	        		<textarea class="brief" name="brief" cols="120" rows="5" placeholder="描述简介">{$brief}</textarea>
	        	</div>
	        	<div class="m2o-item cut-off">
	        		<label class="title">日期条件: </label>
	        		<div class="info">
	        			<input type="text" name="start_time" class="date-picker" required value="{if $formdata['start_time'] && $formdata['start_time'] !=0 } {$formdata['start_time']} {/if}"/>
	        			<span>至</span>
	        			<input type="text" name="end_time" class="date-picker" required value="{if $formdata['end_time'] && $formdata['end_time']!=0 } {$formdata['end_time']} {/if}"/>
	        		</div>
	        		 <label class="title">时间条件: </label>
                        <div class="info">
                        	<span>
                        		<input type="text" class="way-time start w100" name="start_hour" value="{if $start_hour && $start_hour !==0 }{$start_hour}{/if}"/>
                            	<span>至</span>
                            	<input type="text" class="way-time end w100" name="end_hour" value="{if $end_hour && $end_hour !==0 }{$end_hour}{/if}"/>
                            </span>
                        </div>
	        	</div>
	        	<div class="m2o-item cut-off">
	        		<label class="title">延后时间: </label>
	        		<div class="info">
	        			<input type="number" min='0' name="delay_time" value="{$delay_time}" class="w50"/>
	        			<span>秒</span>
	        		</div>
	        	</div>
	        	<div class="m2o-item cut-off">
                        <div id="week_date" class="clear">
                        {code}
                        $week_day_arr = array('1' => '星期一', '2' => '星期二', '3' => '星期三', '4' => '星期四', '5' => '星期五', '6' => '星期六', '7' => '星期日');
                        {/code}
                        <span class="title">星期条件：</span>
                        <label>
                            <input class="n-h" type="checkbox"  id="every_day" class="every_day" name="every_day" {if count($week_day)==7}checked{/if}/><span>每天</span>
                        </label>
                        {foreach $week_day_arr as $key => $value}
                        <label>
                            <input class="n-h" type="checkbox" name="week_day[]" id="week_day_{$key}" {foreach $week_day as $k => $v}{if $v == $key}checked{/if}{/foreach} value="{$key}" /><span>{$value}</span>
                        </label>
                        {/foreach}
                    </div>
                    </div>
	        	<div class="m2o-item cut-off">
	        		<label class="title">链接跳转: </label>
	        		<div class="info">
	        			<input type="checkbox" name="link_switch" {if $link_switch}checked{/if} class="open-link-checkbox" /><span>开启</span>
	        		</div>
	        	</div>
	        	<div class="m2o-item cut-off open-link-item {if !$link_switch}hide{/if}">
	        		<label class="title">链接跳转地址: </label>
	        		<div class="info">
	        			<input type="text" name="link_address" class="w400" value="{$link_address}" />
	        		</div>
	        	</div>
	        	<div class="interact-info-box {if $link_switch}hide{/if}">
		        	<div class="m2o-item cut-off">
		        		<label class="title">积分限制: </label>
		        		<div class="info">
		        			<input type="text" name="score_limit" value="{$score_limit}" class="w200" placeholder="单次/每个活动周期积分总量"/>
		        		</div>
		        		<label class="title">单次积分: </label>
		        		<div class="info">
		        			<input type="text" name="score_min" value="{$score_min}" class="value-verify w50"   />
		        			<span>至</span>
		        			<input type="text" name="score_max" value="{$score_max}" class="value-verify w50"  />
		        		</div>
		        		<label class="title">已送积分: </label>
		        		<div class="info">
		        			<input type="text" name="current_score" value="{$current_score}" class="w80" />
		        		</div>
		        	</div>
		        	<div class="m2o-item cut-off">
		        		<label class="title">用户限制: </label>
		        		<div class="info">
		        			<div class="info-switch">
			        			<div class="switch">
			        				<input type="radio" name="is_user_limit" value=1 {if $is_user_limit}checked="checked"{/if}/>
			        				<p>开启</p>
			        			</div>
			        			<div class="switch">
			        				<input type="number" min='0' name="user_limit_num" class="w50" value="{$user_limit_num}"/>
			        				<p>次</p>
			        			</div>
			        			<div class="switch">
			        				<input type="radio" name="is_user_limit" value=0 {if !$is_user_limit}checked="checked"{/if}/>
			        				<p>关闭</p>
			        			</div>
			        		</div>	
		        		</div>
		        	</div>
		        	<div class="m2o-item cut-off">
		        		<label class="title">感应数: </label>
		        		<div class="info">
		        			<input type="number" min='0' name="sense_num" value="{$sense_num}" class="w50"/>
		        		</div>
		        	</div>
		        	<div class="m2o-item cut-off">
		        		<label class="title">未开始提示: </label>
		        		<div class="info">
		        			<div class="img-info">
		        				<div class="icon"><img src="{$un_start_icon}" /></div>
		        				<input type="file" name="un_start_file" class="file" style="display:none" />
		        				<a>提示图标</a>
		        			</div>
		        			<div class="info-tip">
		        				<input type="text" name="un_start_tip" value="{$un_start_tip}" class="w400" placeholder="请输入提示标题"/>
		        				<textarea name="un_start_desc" placeholder="请输入提示描述">{$un_start_desc}</textarea>
		        			</div>
		        		</div>
		        	</div>
		        	<div class="m2o-item cut-off">
		        		<label class="title">成功提示: </label>
		        		<div class="info">
		        			<div class="img-info">
		        				<div class="icon"><img src="{$sense_icon}" /></div>
		        				<input type="file" name="sense_file" class="file" style="display:none" />
		        				<a>提示图标</a>
		        			</div>
		        			<div class="info-tip">
		        				<input type="text" name="sense_tip" value="{$sense_tip}" class="w400" placeholder="请输入提示标题"/>
		        				<textarea name="sense_desc" placeholder="请输入提示描述">{$sense_desc}</textarea>
		        			</div>
		        		</div>
		        	</div>
		        	<div class="m2o-item cut-off">
		        		<label class="title">未中奖提示: </label>
		        		<div class="info">
		        			<div class="img-info">
		        				<div class="icon"><img src="{$un_win_icon}" /></div>
		        				<input type="file" name="un_win_file" class="file" style="display:none" />
		        				<a>提示图标</a>
		        			</div>
		        			<div class="info-tip">
		        				<input type="text" name="un_win_tip" value="{$un_win_tip}" class="w400" placeholder="请输入提示标题"/>
		        				<textarea name="un_win_desc" placeholder="请输入提示描述">{$un_win_desc}</textarea>
		        			</div>
		        		</div>
		        	</div>
		        	<div class="m2o-item cut-off">
		        		<label class="title">扣分提示: </label>
		        		<div class="info">
		        			<div class="img-info">
		        				<div class="icon"><img src="{$points_icon}" /></div>
		        				<input type="file" name="points_file" class="file" style="display:none" />
		        				<a>提示图标</a>
		        			</div>
		        			<div class="info-tip">
		        				<input type="text" name="points_tip" value="{$points_tip}" class="w400" placeholder="请输入提示标题"/>
		        				<textarea name="points_desc" placeholder="请输入提示描述">{$points_desc}</textarea>
		        			</div>
		        		</div>
		        	</div>
		        	<div class="m2o-item cut-off">
		        		<label class="title">下次预告: </label>
		        		<div class="info">
		        			<textarea name="next_predict" placeholder="请输入提示描述">{$next_predict}</textarea>
		        		</div>
		        	</div>
		        	<div class="m2o-item cut-off">
		        		<label class="title">活动规则: </label>
		        		<div class="info">
		        			<textarea name="activity_rule" placeholder="请输入提示描述">{$activity_rule}</textarea>
		        		</div>
		        	</div>
		        	<div class="m2o-item cut-off">
		        		<label class="title">活动说明: </label>
		        		<div class="info">
		        			<textarea name="activity_desc" placeholder="请输入提示描述">{$activity_desc}</textarea>
		        		</div>
		        	</div>
		        	<!-- 
		        	<div class="m2o-item cut-off">
		        		<label class="title">设备限制: </label>
		        		<div class="info">
		        			<div class="info-switch">
			        			<div class="switch">
			        				<input type="radio" name="is_equipment_limit" />
			        				<p>开启</p>
			        			</div>
			        			<div class="switch">
			        				<input type="number"  min="0" name="limit_time" class="w50"/>
			        				<p>次</p>
			        			</div>
			        			<div class="switch">
			        				<input type="radio" name="is_equipment_limit" />
			        				<p>关闭</p>
			        			</div>
			        		</div>	
		        		</div>
		        	</div>
		        	 -->
	        	</div>
        	</div>
        </section>
    </div>
</form>
</body>
