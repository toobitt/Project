<li class="common-list-data clear" id="r_{$v['id']}" name="{$v['id']}" orderid="{$v['video_order_id']}">
	<div class="common-list-left">
		<div class="common-list-item paixu">
			<div class="common-list-cell">
				<a class="lb"  name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  /></a>
			</div>
		</div>
		<div class="common-list-item thumb">
			<div class="common-list-cell">
				<a><img src="{$v['img']}"  width="40" height="30" id="img_{$v['id']}" /></a>
			</div>
		</div>
	</div>
	<div class="common-list-right">
		<div class="common-list-item show-date">
			<div class="common-list-cell">
				<a class="movie-time">{$v['starttime']}</a>
			</div>
		</div>
		<div class="common-list-item duration">
			<div class="common-list-cell">
				<a class="movie-time">{$v['duration']}</a>
			</div>
		</div>
		<div class="common-list-item sort">
			<div class="common-list-cell">
				<a style="width:80px;display:inline-block;color:{$v['vod_sort_color']}"  id="sortname_{$v['id']}" class="overflow">{$v['vod_sort_id']}</a>
			</div>
		</div>
		<div class="common-list-item source">
			<div class="common-list-cell">
				{$v['channel_name']}
			</div>
		</div>
		<div class="common-list-item mark">
			<div class="common-list-cell">
				<a class="overflow" onclick="return hg_goToCollect(this,{$v['auto_collect_id']});" href="./run.php?mid={$v['collect_mid']}&collect_id={$v['auto_collect_id']}&infrm=1">{$v['mark_count']}&nbsp;&nbsp;{$v['new_mark_name']}</a>
			</div>
		</div>
		<div class="common-list-item status">
			<div class="common-list-cell">
				<span class="fl"><span id="is_finish_{$v['id']}">{$v['is_finish']}</span></span>
			</div>
		</div>
	</div>
	<div class="common-list-biaoti">
		<div class="common-list-item biaoti-transition">
			<div class="common-list-cell">
				<a id="t_{$v['id']}"  href="./run.php?mid={$_INPUT['mid']}&a=video_mark&id={$v['id']}{$_pp}"  target="mainwin"  onclick="return hg_jumpEdit(this);">{$v['title']}</a>
			</div>
		</div>
	</div>
</li>

<!-- 

                <li class="clear"  id="r_{$v['id']}"    name="{$v['id']}"   orderid="{$v['video_order_id']}"  onmouseout="hg_row_interactive(this, 'out');" onmouseover="hg_row_interactive(this, 'on');">
                    	<span class="left nb2" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');" >
							<a class="lb"  name="alist[]" ><input type="checkbox" name="infolist[]"  value="{$v['id']}" title="{$v['id']}"  /></a>
							<a class="fl">{$v['starttime']}</a>
							<a class="fl w">{$v['duration']}</a>
							<a class="slt"><img src="{$v['img']}"   width="40" height="30"   id="img_{$v['id']}" /></a>
						</span>
	                        <span class="right nb2" onclick="hg_row_interactive('#r_{$v[id]}', 'click', 'cur');" style="width:550px">
								
								         <a class="fl"><em style="color:{$v['vod_sort_color']}"  id="sortname_{$v['id']}" class="overflow">{$v['vod_sort_id']}</em></a>
								<a class="fl overflow"><em>{$v['channel_name']}</em></a>
								<a class="fl overflow" style="width:160px;" onclick="return hg_goToCollect(this,{$v['auto_collect_id']});" href="./run.php?mid={$v['collect_mid']}&collect_id={$v['auto_collect_id']}&infrm=1"><em>{$v['mark_count']}&nbsp;&nbsp;{$v['new_mark_name']}</em></a>
								<a class="fl"><em id="is_finish_{$v['id']}">{$v['is_finish']}</em></a>
								<span id="hg_t_{$v['id']}" class="hg_t_time" style="display:none"></span>
								<span class="fb_column" style="display:none;"    id="fabu_{$v['id']}"   onmouseover="hg_fabu('fabu_{$v[id]}')"  onmouseout="hg_back_fabu('fabu_{$v[id]}')" ><span class="fb_column_l"></span>
									<span class="fb_column_r"></span><span class="fb_column_m"><em></em><span class="fsz" style="display:none;">发送至栏目：</span><a>点播</a></span>
								</span>
						   </span>
						
						<span class="title overflow"  style="cursor:pointer;">
							<a id="t_{$v['id']}"  href="./run.php?mid={$_INPUT['mid']}&a=video_mark&id={$v['id']}{$_pp}"  target="mainwin"  onclick="return hg_jumpEdit(this);">{$v['title']}</a>
						</span>

                        <div class="content_more clear" id="content_{$v['id']}"  style="display:none;">
                             <div id="show_list_{$v['id']}" class="pic_list_r">
								 <div class="img_box"></div>
								 <div class="img_box"></div>
								 <div class="img_box"></div>
								 <div class="img_box"></div>
								 <div class="img_box"></div>
								 <div class="img_box"></div>
								 <div class="img_box"></div>
								 <div class="img_box"></div>
								 <div class="img_box"></div>
							</div>
							
							 <div id="add_img_content_{$v['id']}"   class="add_img_content">
							   <div id="add_from_compueter_{$v['id']}"></div>
							 </div>
                        </div>
                    </li>  
 -->