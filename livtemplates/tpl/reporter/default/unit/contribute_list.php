<li class="common-list-data clear"  id="r_{$v['id']}"    name="{$v['id']}"  _id="{$v['id']}" order_id="{$v['order_id']}">
	<div class="common-list-left">
        <div class="common-list-item contribute-paixu">
            <div class="common-list-cell">
                <a class="lb" name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v[$primary_key]}" title="{$v[$primary_key]}"  /></a>
            </div>
        </div>
        <div class="common-list-item contribute-fengmian" onclick="hg_show_change({$v[$primary_key]})">
            <div class="common-list-cell">
            {code}
            	$p = array_filter($v['indexpic']);
            	$url = '';
            	if (!empty($p))
            	{
					$url = $v[indexpic]['host'].$v[indexpic]['dir'].'40x30/'.$v[indexpic]['file_path'].$v[indexpic]['file_name'];
				}
			{/code}		
			<!--  <img src="{$url}" id="img_{$v['id']}"  />-->
			<img src="{$url}" id="img_{$v[$primary_key]}"/>
            </div>
        </div>
        
    </div>
    <div class="common-list-right">
	    <div class="common-list-item contribute-fb wd80">
            <div class="common-list-cell">
                <span id="column_{$v[id]}">{foreach $v['column_id'] as $key=>$val}<span style="color: #9AAACC;text-decoration:underline">{$val}</span>&nbsp;&nbsp;{/foreach}</span>
            </div>
        </div>
        <div class="common-list-item contribute-bj wd60">
            <div class="common-list-cell">
                <a class="btn-box" href="./run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1"><em class="b2"></em></a>
            </div>
        </div>
        <div class="common-list-item contribute-sc wd60">
            <div class="common-list-cell">
                 <a class="btn-box" onclick="return hg_ajax_post(this, '删除', 1);" href="./run.php?mid={$_INPUT['mid']}&a=delete&id={$v['id']}"><em class="b3"></em></a>
            </div>
       </div>
       <div class="common-list-item contribute-fl wd80">
            <div class="common-list-cell">
                 <span class="overflow" id="contribute_sort_{$v['id']}">{$v['name']}</span>
            </div>
       </div>
       <div class="common-list-item contribute-zt wd60">
            <div class="common-list-cell">
                 <span id="contribute_audit_{$v['id']}">{$v['audit']}</span>
            </div>
       </div>
       <div class="common-list-item contribute-khd wd100">
            <div class="common-list-cell">
                 <span class="overflow" id="contribute_client_{$v['id']}">{$v['client']}</span>
            </div>
       </div>
       <div class="common-list-item contribute-blr wd120">
            <div class="common-list-cell">
                 <span class="common-user">{$v['user_name']}</span>
                 <span class="common-time">{$v['create_time']}</span>
            </div>
       </div>
   </div>
	<div class="common-list-biaoti ">
	    <div class="common-list-item  biaoti-transition">
			   <div class="common-list-cell">
                  <div class="title overflow"  style="cursor:pointer;padding-left:1px;" onclick="hg_show_opration_info({$v['id']})">
		<a  id="contribute_title_{$v['id']}">{$v['title']}&nbsp;&nbsp;
			{if $v['videolog']}
				<img src ="{$RESOURCE_URL}hg_play_go.png"/>
			{/if}
		</a>
		<span class="c_a" >							
			{if $v['pubinfo'][1]}
			<span class="lm">
				<em class="b"  id="conimg_lm_{$v['id']}" onmouseover="hg_conPub({$v['id']})" onmouseout="hg_back_conPub({$v['id']})"></em>
			</span>
			{/if}
			{if $v['pubinfo'][2]}
			<span class="sj">
				<em class="b"  id="conimg_sj_{$v['id']}" onmouseover="hg_conPhone({$v['id']})" onmouseout="hg_back_conPhone({$v['id']})"></em>
			</span>
			{/if}						
		</span>		
	</div>
	
	<div class="title" style="overflow:auto;position:static;">
		<span class="fb_column"  style="display:none;"   id="conPub_{$v['id']}" >
			<span class="fb_column_l"></span>
			<span class="fb_column_r"></span>
			<span class="fb_column_m"><em></em><span class="fsz">发送至网站：</span>
			{if $v['pubinfo'][1]}
				{foreach $v['pubinfo'][1] as $c}
					<a class="overflow">{$c}</a>
				{/foreach}
			{/if}
			</span>
		</span>
		<span class="fb_column phone"  style="display:none;"   id="conPhone_{$v['id']}" >
			<span class="fb_column_l"></span>
			<span class="fb_column_r"></span>
			<span class="fb_column_m"><em></em><span class="fsz" >发送至手机：</span>
				{if $v['pubinfo'][2]}
					{foreach $v['pubinfo'][2] as $c}
						<a class="overflow">{$c}</a>
					{/foreach}
				{/if}
			</span>
		</span>
	</div>
            </div>  
	    </div>
   </div>
   <!-- pic列表start -->
		        <div class="common-picList"  id="img_box_{$v[$primary_key]}">
			        {if $v['pic']}
			        {foreach $v['pic'] as $key=>$val}
			       	{code}
			       			$pic='';
			       			$pic = $val['host'].$val['dir'].'40x30/'.$val['filepath'].$val['filename'];
			       	{/code}
							<img src="{$pic}"  onclick="change_indexpic({$v[$primary_key]},{$val['material_id']}, this)" />
			        {/foreach}
			        {/if}
		        	<div class="uploadBtn" data-program_id="{$v[$primary_key]}">+</div>
		        </div>
        <!-- pic列表end -->
</li> 