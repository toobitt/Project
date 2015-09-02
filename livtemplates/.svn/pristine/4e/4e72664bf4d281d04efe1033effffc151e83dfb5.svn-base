{template:head}
{css:hg_sort_box}
{js:common/auto_textarea}
{js:hg_sort_box}
{js:common/common_form}
{css:common/common_form}
{css:ad_style}
{if is_array($formdata) && $a == 'update'}
	{foreach $formdata as $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
<div id="channel_form" style="margin-left:40%;"></div>
	<div class="wrap clear">
		<div class="ad_middle">
			<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
					<h2>{$optext}议程</h2>
					<ul class="form_ul">
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">议程标题</span>
								<input type="text" value="{$title}" name='title' style="width:440px;" />
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">所属日期</span>
								{code}
									$attr_agenda_date = array(
                                		'class' => 'agenda_date down_list',
                                		'show'  => 'agenda_date_show',
                                		'width' => 160,
                                		'state' => 0,
                                		'is_sub'=>1,
                                    );
                                    
                                    if(!$date_id)
                                    {
                                    	$date_id = 0;
                                    }
                                    
								{/code}
								<div style="width:100%;height:30px;">
								{template:form/search_source,date_id,$date_id,$_configs['agenda_date'],$attr_agenda_date}
								</div>
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">所属专题</span>
								{code}
									$attr_special = array(
                                		'class' => 'special down_list',
                                		'show'  => 'special_show',
                                		'width' => 120,
                                		'state' => 0,
                                		'is_sub'=>1,
                                    );
                                    
                                    if(!$special_id)
                                    {
                                    	$special_default = 0;
                                    }
                                    else
                                    {
                                    	$special_default = $special_id;
                                    }
                                    $_special_sort[0] = '全部专题';
                                    if($special_sort)
                                    {
                                    	foreach($special_sort AS $kk => $vv)
                                    	{
                                    		$_special_sort[$vv['id']] = $vv['name'];
                                    	}
                                    }
								{/code}
								<div style="width:100%;height:30px;">
								{template:form/search_source,special_id,$special_default,$_special_sort,$attr_special}
								</div>
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">演讲人</span>
								{code}
									$attr_guest = array(
                                		'class' => 'guest down_list',
                                		'show'  => 'guest_show',
                                		'width' => 120,
                                		'state' => 0,
                                		'is_sub'=>1,
                                    );
                                    
                                    if(!$guest_id)
                                    {
                                    	$guest_default = 0;
                                    }
                                    else
                                    {
                                    	$guest_default = $guest_id;
                                    }
                                    $_all_guests[0] = '所有演讲人';
                                    if($all_guests)
                                    {
                                    	foreach($all_guests AS $kk => $vv)
                                    	{
                                    		$_all_guests[$vv['id']] = $vv['name'];
                                    	}
                                    }
								{/code}
								<div style="width:100%;height:30px;">
								{template:form/search_source,guest_id,$guest_default,$_all_guests,$attr_guest}
								</div>
							</div>
						</li>
											
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">议程简介</span>
								<textarea name="brief">{$brief}</textarea>
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">开始时间</span>
								<input type="text" value="{$stime}" name='stime' style="width:440px;" />
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">结束时间</span>
								<input type="text" value="{$etime}" name='etime' style="width:440px;" />
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">开始提醒</span>
								<input type="text" value="{$starttime}" name='starttime' style="width:440px;" />
							</div>
						</li>
						
						<li class="i">
							<div class="form_ul_div">
								<span  class="title">外链</span>
								<input type="text" value="{$url}" name='url' style="width:440px;" />
							</div>
						</li>
						
					</ul>
				<input type="hidden" name="a" value="{$a}" />
				<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
				<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
				<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
				<br />
				<input type="submit" name="sub" value="{$optext}" class="button_6_14"/>
				<input type="button" value="返回" class="button_6_14" style="margin-left:28px;" onclick="javascript:history.go(-1);"/>
			</form>
		</div>
	<div class="right_version"><h2><a href="{$_INPUT['referto']}">返回前一页</a></h2></div>
	</div>
{template:foot}