<li class="common-list-data clear" _id="{$v['id']}" id="r_{$v['id']}" name="{$v['id']}" order_id="{$v['order_id']}">
    <div class="common-list-left">
        <div class="common-list-item paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
    </div>
    <div class="common-list-right">
    	<div class="common-list-item wd60">
            <div class="common-list-cell">
                <a class="btn-box" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2"></em></a>
            </div>
        </div>
        <div class="common-list-item wd60">
            <div class="common-list-cell">
                 <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3"></em></a>
            </div>
        </div>
        <div class="common-list-item wd200"> 
        	<div class="common-list-cell">
        		<span style="display: inline">{$v['format_start_date']}~{$v['format_end_date']}</span>
        	</div>
        </div>
        <div class="common-list-item wd100"> 
        	<div class="common-list-cell">
        		{code}
        			$week = '';
        			if ($v['week_day'])
        			{
        				$week_day = explode(',', $v['week_day']);
        				if (count($week_day)==7)
        				{
        					$week = '每天';
        				}else{
        					foreach ($week_day as $w)
        					{
        						switch ($w)
        						{
        							case 1 : $week .= '一';break;
        							case 2 : $week .= '二';break;
        							case 3 : $week .= '三';break;
        							case 4 : $week .= '四';break;
        							case 5 : $week .= '五';break;
        							case 6 : $week .= '六';break;
        							case 7 : $week .= '日';break;
        						}
        					}
        				}
        			}
        		{/code}
        		<span>{$week}</span>
        	</div>
        </div>
        <div class="common-list-item wd100" style="cursor:pointer;">
                <span  onclick="change_status({$v['id']},{$v['is_open']});"   id="audit_{$v['id']}" {if $v['is_open']==1}style="color:green;"{/if}>{$v['open_status']}</span>
        </div>
        <div class="common-list-item wd120">
            <div class="common-list-cell">
                 <span class="common-user">{$v['user_name']}</span>
                 <span class="common-time">{$v['create_time']}</span>
            </div>
       </div>
    </div>
    <div class="common-list-biaoti" href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1">
        <div class="common-list-item biaoti-transition">
        	<a>
	        	<span class="common-list-overflow max-wd fz14 m2o-common-title" style="display:inline-block;">{$v['title']}</span>
            </a>
        </div>
    </div>
</li>