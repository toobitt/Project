<li class="common-list-data public-list clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['id']}">
	<div class="common-list-left">
        <div class="common-list-item common-paixu">
            <div class="common-list-cell">
                <a class="lb"  name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title=""  /></a>
            </div>
        </div>
        <div class="common-list-item template-slt">
            <div class="common-list-cell">
            	{code}
				 	$pic = '';
				  	if($v['pic'][0])
				  	{
				  		$pic = $v['pic'][0]['host'] . $v['pic'][0]['dir']  . $v['pic'][0]['filepath'] . $v['pic'][0]['filename'];
				  	}
				{/code}
				{if $pic}
					<img src="{$pic}" style="width:40px;height:30px;margin-right:10px;" />
				{else}
				{/if}
            </div>
        </div>
    </div>
	<div class="common-list-right">
		<div class="common-list-item wd80">
            <div class="common-list-cell">
                <span id="name_{$v['site_name']}">{$v['site_name']}</span>
            </div>
        </div>
        <div class="common-list-item wd80">
            <div class="common-list-cell">
                <span id="name_{$v['template_style']}">{$template_styles[$v['template_style']]}</span>
            </div>
        </div>
        <div class="common-list-item wd80">
            <div class="common-list-cell">
                <span id="name_{$v['client']}">{$v['client']}</span>
            </div>
        </div>
        <div class="common-list-item template-fl wd80">
            <div class="common-list-cell">
                <span id="name_{$v['sort_name']}">{$v['sort_name']}</span>
            </div>
        </div>
        <div class="common-list-item template-cz wd180">
            <div class="common-list-cell">
                <a title="更新" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&site_id={$v['site_id']}&sort_id={$v['sort_id']}&infrm=1" target="formwin">编辑</a>
				<a onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}">删除</a>
				<!--  <a title="编辑" href="./run.php?mid={$_INPUT['mid']}&a=edit&id={$v['id']}&infrm=1">编辑</a>	-->
				<a title="模板下载" href="./run.php?mid={$_INPUT['mid']}&a=download&id={$v['id']}" _id="{$v['id']}">下载</a>
				<!--<span title="模板预设" class="open_magic_view" href="./run.php?mid={$_INPUT['mid']}&a=preset&id={$v['id']}&site_id={$v['site_id']}&infrm=1">模板预设</span>-->
				{code}
					$ext = "site_id=" . $v['site_id'] . "&page_id=0&page_data_id=0&count_type=0&template_id=" . $v['id'];
					$ext = urlencode($ext);
				{/code}
       			<a href= "magic/main.php?gmid={$_INPUT['mid']}&ext={$ext}&bs=p" class="open_magic_view"  target="_blank" _href="./run.php?mid={$_INPUT['mid']}&a=search_cell&id={$v['id']}&site_id={$v['site_id']}&infrm=1">模板预设</a>
        		<!-- <a title="使用纪录" href="./run.php?mid={$_INPUT['mid']}&a=get_record&id={$v['id']}&site_id={$v['site_id']}&infrm=1">使用纪录</a> -->
        </div>
   </div>
   </div>
   <div class="common-list-biaoti">
	    <div class="common-list-item biaoti-transition">
			   <div class="common-list-cell">
		         <!-- <span class="common-list-overflow special-biaoti-overflow" onclick="hg_show_opration_info({$v['id']})"><a  id="share_title_{$v['title']}">{$v['title']}</a></span>-->
            	 <a class="common-list-overflow special-biaoti-overflow" id="name_{$v['title']}" href="./run.php?mid={$_INPUT['mid']}&a=show_tem&id={$v['id']}" target="_blank">
            	 <span class="m2o-common-title">{$v['title']}</span></a>
            </div>  
	    </div>
   </div>
   
</li>