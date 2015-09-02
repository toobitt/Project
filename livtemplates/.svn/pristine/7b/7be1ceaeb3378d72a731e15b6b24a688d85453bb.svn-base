{code}
$program = $formdata['program'];
$channel_info = $formdata['channel'];
{/code}

{template:head}
{js:jqueryfn/jquery.tmpl.min}
{js:2013/ajaxload_new}
{js:program_record/form}
{js:common}
{js:common/common_form}
{js:2013/list}
{js:2013/form}
{css:common/common}
{css:2013/form}
{css:time_shift}
<style>
.channel-list-box li{margin-bottom:5px;}
.m2o-m input[type="text"], .m2o-m textarea{padding:2px;height:24px;line-height:24px;width:98%;}
.m2o-m textarea{height:72px;display:block;}
.m2o-m input[name="keywords"]{width:400px;}
.m2o-m input[name="author"]{width:100px;}
.channel-list-box .program-list{width:550px;}
.program-item{float:left;width:190px;}
.program_dates{color:red;opacity:0.5;}
.program_time{padding-right:10px;}
.program_theme{padding-right:4px;}
.hidde_program,.hidde_program a{color:#ccc;cursor:default;}
.channel-item:hover,.program_li:hover{background:#f9f9f9;}
.on,.on:hover{background:#ccc;}
.content{display:none;}
</style>

<form class="m2o-form" action="run.php?mid={$_INPUT['mid']}" method="post">
    <header class="m2o-header">
      <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1 class="m2o-l">新增时移</h1>
            <div class="m2o-m m2o-flex-one">
                <input class="m2o-m-title" name="title" id="title" placeholder="填写节目名称"/>
            </div>
            <div class="m2o-btn m2o-r">
                <input type="submit" value="保存时移" class="m2o-save"/>
                <span class="m2o-close option-iframe-back"></span>
            </div>
        </div>
      </div>
    </header>
    <div class="m2o-inner">
    <div class="m2o-main m2o-flex">
        <aside class="m2o-l">
            <div class="m2o-item">
            	<span class="title">分类:</span>
                {code}
                    $item_source = array(
                        'class' 	=> 'down_list',
                        'show' 		=> 'item_show',
                        'state' 	=> 	0, /*0--正常数据选择列表，1--日期选择*/
                        'is_sub'	=>	1,
                    );
                    $default = -1;
                    $sort[$default] = '选择分类';
                    foreach($program_item[0] as $k =>$v)
                    {
                        $sort[$v['id']] = $v['name'];
                    }
                {/code}
                {template:form/search_source,item,$default,$sort,$item_source}
            </div>
            <div class="m2o-item">
   			        <span class="title">强制转码：</span>
   			        <div class="common-switch">
				       <div class="switch-item switch-left" data-number="0"></div>
				       <div class="switch-slide"></div>
				       <div class="switch-item switch-right" data-number="100"></div>
				    </div>
					<input type="radio" name="force_codec" value="1" />
					<input type="radio" name="force_codec" value="0" checked />
					<span class="error" id="force_codec_tips" style="display:none;"></span>
   			   </div>
   			   <div class="m2o-item">
   			        <span class="title">拆条：</span>
   			        <div class="common-switch common-switch-on">
				       <div class="switch-item switch-left" data-number="0"></div>
				       <div class="switch-slide"></div>
				       <div class="switch-item switch-right" data-number="100"></div>
				    </div>
					<input type="radio" name="is_mark" value="1" checked />
					<input type="radio" name="is_mark" value="0" />
					<span class="error" id="mark_tips" style="display:none;"></span>
   			   </div>
   			   <div class="m2o-item">
   			        <span class="title">审核通过：</span>
   			        <div class="common-switch {if $audit_auto}common-switch-on{/if}">
				       <div class="switch-item switch-left" data-number="0"></div>
				       <div class="switch-slide"></div>
				       <div class="switch-item switch-right" data-number="100"></div>
				    </div>
					<input type="radio" name="audit_auto" value="2" />
					<input type="radio" name="audit_auto" value="0" checked />
					<span class="error" id="mark_tips" style="display:none;"></span>
   			   </div>
   			   <div class="m2o-item">
        	        <a class="common-publish-button overflow" href="javascript:;" _default="发布至: 无" _prev="发布至: ">发布至</a>
					{template:unit/publish_for_form, 1, $columnid}
   			   </div>
        </aside>

        <section class="m2o-m m2o-flex-one">
            <div class="m2o-item">
            	 <span class="title">频道：</span>
                 <span id="default_value" class="default_value" {if !$channel_id}style="display:none;"{/if}>当前选取：<a id="channel_name">{$channels[$channel_id]}</a></span>
               	 <span class="m2o-channel-btn" id="show_span" onclick="hg_show_channel();">{if !$channel_id}选择频道{else}重新选择频道{/if}</span>
               	 <input id="channel_id" name="channel_id" value="{$channel_id}" type="hidden"/>
               	 <div class="channel-toggle">
               	  	  <div class="channel-list-box m2o-flex">
	               	       <div class="channel-list list">
	               	            <h3>频道列表</h3>
	               	            {if is_array($channel_info)}
								<ul>
									{foreach $channel_info as $key => $value}
									<li class="channel-item" data-id="{$value['id']}"><span class="name">{$value['name']}</span>{if $value['status']}启动{else}未启动{/if}</li>
									{/foreach}
								</ul>
								{/if}
	               	       </div>
	               	       <div class="program-list list">
	               	       		<h3>节目列表</h3>
	               	       		<div class="program-box">
	               	       			<ul class="date-list clear"></ul>
	               	       			<div class="content-list"></div>
	               	       			<span class="nodata" style="color: red;opacity: 0.5;">暂无内容</span>
	               	       		</div>
	               	       </div>
               	    </div>
               	  </div>
                <div>
                    {foreach $program AS $k => $v}
                        <div style="width:685px;margin-top:10px;border:1px dotted #449FF8;overflow-y:auto;margin-left:10px;padding-bottom:5px;">
                            <div style="width:98%;float:left;border:1px solid gray;text-align:center;margin-top:5px;background:#EBEBEB;font-weight:bold;margin-left:6px;">{$k}</div>
                            {foreach $v AS $kk => $vv}
                                    <div style="width:98%;float:left;margin-top:5px;">
                                        <div style="width:13%;height:100%;float:left;margin-top:6px;margin-left:5px;">{$kk}</div>
                                        <div style="width:80%;float:left;">
                                        {foreach $vv AS $kkk => $vvv}
                                            {if $vvv['display'] && !$vvv['now_display']}
                                            <span class="program_item overflow"" title="{$vvv['starttime']}<--->{$vvv['endtime']}"  st="{$vvv['starttime']}" et="{$vvv['endtime']}">{$vvv['start']} {$vvv['theme']} {if $vvv['now_display']}√{/if}</span>
                                            {else}
                                            <div class="hide_program_item overflow"" title="{$vvv['starttime']}<--->{$vvv['endtime']}"  st="{$vvv['starttime']}" et="{$vvv['endtime']}" >{$vvv['start']} {$vvv['theme']} {if $vvv['now_display']}√{/if}</div>
                                            {/if}
                                        {/foreach}
                                        </div>
                                    </div>
                            {/foreach}
                        </div>
                    {/foreach}
                </div>
            </div>
            <div class="m2o-item">
                <div class="m2o-flex">
                    <input type="text" name="start_time" id="start_time" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss'});" size="14" autocomplete="off" style="width:180px;" placeholder="节目开始时间">
                    <span style="margin:0 10px;">--</span>
                    <input type="text" name="end_time"   id="end_time"   onfocus="WdatePicker({skin:'whyGreen',dateFmt:'yyyy-MM-dd HH:mm:ss'});" size="14" autocomplete="off" style="width:180px;" placeholder="节目结束时间">
                </div>
            </div>
            <!--  
            <div class="m2o-item">
                <input type="text" name="subtitle" class="" placeholder="副标题" />
            </div>

            <div class="m2o-item">
                <textarea class="" name="comment"  placeholder="这里输入描述"></textarea>
            </div>

            <div class="m2o-item">
                <input type="text" name="keywords" class="" placeholder="关键字" />
                <input type="text" name="author" class="" placeholder="作者" />
            </div>
            -->
        </section>
    </div>
   </div>
    <input type="hidden" name="a" value="create" />
    <input type="hidden" name="referto" value="{$_INPUT['referto']}" />
</form>

{template:foot}
<script type="text/x-jquery-tmpl" id="date-tmpl">
	<li class="date-li">${date}</li>
</script>
<script id="movieTemplate" type="text/x-jquery-tmpl">
	<div class="content">
		{{each programeList}}
			<div class="content-each">
			<label> ${period} </label>
			<ul>
				{{tmpl($value['programe']) "#programe-tmpl"}}
			</ul>
			</div>
		{{/each}}
	</div>
</script>
<script type="text/x-jquery-tmpl" id="programe-tmpl">
	<li class="program_li shift {{if !display}}hidde_program{{/if}}" data-date="${dates}" data-start="${start}" data-end="${end}">
		<span class="program_time">${start}</span>
    	<a class="program_theme">${theme}</a>{{if now_display}}√{{/if}}
	</li>
</script>

<script type="text/x-jquery-tmpl" id="program-tpl">
<ul class="program_ul">
{{if lists}}
  {{each lists}}
        <li class="program_li shift {{if !$value.display}}hidde_program{{/if}}" data-date="{{= $value.dates}}" data-start="{{= $value.start}}" data-end="{{= $value.end}}">
        	<span class="program_time">{{= $value.start}}</span>
        	<a class="program_theme">{{= $value.theme}}</a>{{if $value.now_display}}√{{/if}}
        </li>	
  {{/each}}
{{/if}}
</ul>
</script>