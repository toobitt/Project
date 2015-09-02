{template:head}
{css:2013/form}
{js:common/ajax_upload}
{js:program_plan}
<style type="text/css">
	.program{width: 640px;margin-left: 60px;}
	.program_ul{width: 208px;float: left;margin-right: 5px;}
	.program_dates{font-size: 14px;}
	.program_li{margin: 5px 0;cursor: pointer;}
	.program_time{}
	.program_theme{margin-left: 5px;}
	
	.plan{width: 640px;margin-left: 60px;}
	.plan_ul{}
	.plan_li{margin: 5px 0;cursor: pointer;}
	.plan_time{}
	.plan_name{margin: 0 5px;width: 65px;display: inline-block;}
	.plan_week{}
	.m2o-l, .m2o-r{width:250px;}
.m2o-r{margin-left:15px;}
.m2o-m .m2o-item{padding:10px;}
.m2o-m .m2o-item:hover{background:none;}
.m2o-main .m2o-m > div{padding:15px;}
.tt{float:left;font-size:14px;font-weight:bold;}
.error{color:red;margin-left:20px;}
	.plan-index{cursor:pointer;display:inline-block;width:120px;height:100px;border:1px solid #e0dcdd;background:url({$RESOURCE_URL}news/suoyin-default.png) center no-repeat;}
	.plan-index img{width:120px;height:100px;}
	.hide-bg{background:none;}
</style>

{if $a}
	{code}
		$action = $a;
	{/code}
{/if}

{if is_array($formdata)}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{code}
$channels = array();
foreach($channel_info as $k => $v)
{
	$channels[$v['id']] = $v['name'];
}
{/code}
<script>
	/*var record_start = {code} echo $start_time ? strtotime($start_time) : 0 ;{/code};	
	var record_end = {code} echo $end_time ? strtotime($end_time) : 0;{/code};*/
	jQuery(function($){
	    $('form').submit(function(){
	        var error = false;
	        $.each({
	            'title' : '标题',
	            'start_date' : '开始日期',
	            'start_time' : '计划开始时间'
	        }, function(i, n){
	            var val = $.trim($('input[name="'+ i +'"]').val());
                if(!val){
                    error = n;
                    return false;
                }
	        });
	        if(error){
	            jAlert(error + '不能为空', '提醒');
	            return false;
	        }
	    });
	});
</script>

<form class="m2o-form" action="run.php?mid={$_INPUT['mid']}" method="post" id="week_day_form">
    <header class="m2o-header">
        <div class="m2o-title m2o-inner m2o-flex m2o-flex-center">
            <h1 class="m2o-l">{$optext}计划</h1>
            <div class="m2o-m m2o-flex-one">
                <input class="m2o-m-title" name="title" placeholder="填写计划名称" value="{$title}"/>
            </div>
            <div class="m2o-btn m2o-r">
                <input type="submit" value="保存计划" class="m2o-save"/>
                <span class="m2o-close option-iframe-back"></span>
            </div>
        </div>
    </header>
    <div class="m2o-main m2o-inner m2o-flex">
        <aside class="m2o-l">

        </aside>

        <section class="m2o-m m2o-flex-one">
            <div class="clearfix">
                <label class="tt">频道：</label>
                <span style="display: inline-block;line-height: 24px;color: #333;cursor: default;">{$_INPUT['channel_name']}</span>
                <span class="error" id="channel_tips" style="display:none;"></span>
                <input id="channel_id" name="channel_id" value="{$_INPUT['channel_id']}" type="hidden"/>
            </div>
            
            <div class="clearfix">
                 {code}
                $img = json_decode($indexpic,1);
                $img_url = hg_bulid_img( $img, 120 ,100 );           
                {/code}
                <label class="tt">缩略图：</label>
                <span class="plan-index">
                	<img src="{$img_url}"/>
                </span>
                <input type="file" style="display:none;"  class="plan-file" />
                <input id="indexpic" name="indexpic" value='{$indexpic}' type="hidden"/>
            </div>

            <div class="clearfix">
               <label class="tt">日期：</label>
                {code}
                $type_source = array('other'=>' size="14" autocomplete="off" style="width:125px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;" onblur="hg_plan_check_day();"','name'=>'start_date','style'=>'width:140px;float: left;','type'=>'yyyy-MM-dd','focus' => "$('[lang=zh-cn]').hide();");
                $dates = $start_date ? date('Y-m-d',$start_time) : date('Y-m-d');
                {/code}
                {template:form/wdatePicker,start_date,$dates,'',$type_source}

                <span style="margin:0 10px;float:left;">－</span>

                {code}
                $type_source = array('other'=>' size="14" autocomplete="off" style="width:125px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;" onblur="hg_plan_check_day();"','name'=>'end_date','style'=>'width:140px;float: left;','type'=>'yyyy-MM-dd','focus' => "$('[lang=zh-cn]').hide();");
                $dates = $id ? ($toff ? date('Y-m-d',$start_time+$toff) : '') : '';
                {/code}
                {template:form/wdatePicker,end_date,$dates,'',$type_source}
                <span style="display: inline;height: 24px;line-height: 24px;padding-left: 10px;color: #ccc;">*  结束时间为空，计划无截止</span>
                <span class="error" id="day_tips"></span>
            </div>

            <div id="week_date" class="clear" {if !count($week_day) && $id}style="display:none;"{/if}>
                <p class="clear" style="margin-bottom:10px;display:none;">
                    <label><input class="n-h" type="checkbox" onclick="hg_plan_repeat(this);" {if count($week_day)}checked{/if}/><span>周期性节目</span></label>
                </p>
                {code}
                    $week_day_arr = array('1' => '星期一', '2' => '星期二', '3' => '星期三', '4' => '星期四', '5' => '星期五', '6' => '星期六', '7' => '星期日');
                {/code}
                <label style="visibility:hidden;">重复：</label>
                <label>
                    <input class="n-h" type="checkbox" onclick="hg_plan_repeat(this,1);" id="every_day" name="every_day" {if count($week_day)==7}checked{/if}/><span>每天</span>
                </label>
                {foreach $week_day_arr as $key => $value}
                    <label>
                        <input onclick="hg_plan_repeat(this,2);" class="n-h" type="checkbox" name="week_day[]" id="week_day_{$key}" {foreach $week_day as $k => $v}{if $v == $key}checked{/if}{/foreach} value="{$key}" /><span>{$value}</span>
                    </label>
                {/foreach}
            </div>

            <div class="clearfix">
                <span class="tt">时间：</span>
                {code}
                    $type_source = array('other'=>' size="14" autocomplete="off" style="width:125px;height: 18px;line-height: 20px;font-size:12px;padding-left:5px;float: left;border:none;" onblur="hg_plan_check_day();"','name'=>'start_time','style'=>'width:140px;float: left;','type'=>'HH:mm','focus' => "$('[lang=zh-cn]').hide();");
                    $default_start = $start_time ? date('H:i',$start_time) : '';
                {/code}
                {template:form/wdatePicker,start_time,$default_start,'',$type_source}
                <span id="toff" style="padding-left:10px;line-height:24px;">{$toff_decode}</span>
                <span style="padding-left:10px;line-height:24px;"></span>
                <span class="error" id="time_tips" style="display:none;"></span>
            </div>
        </section>

        <aside class="m2o-r">

        </aside>
    </div>

    <input type="hidden" name="a" value="{$action}"/>
    <input type="hidden" name="html" value="1"/>
    <input type="hidden" name="{$primary_key}" value="{$formdata['id']}" />
    <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
    <input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
</form>