<li _id="{$v['id']}" id="r_{$v['id']}" name="{$v['id']}" class="epaper-each m2o-each">
  	     <div class="epaper-profile m2o-flex">     
  		     <div class="epaper-img">
             <img src="{$v['index_pic']}" />  
  		     </div>		 	 		  
  		  <div class="epaper-brief m2o-flex-one">

  			<div class="epaper-status"><label><span>{$v['period_date']}</span><span style="margin-left:4px;">{$v['period_num']}期</span> </label>
  			<span  class="reaudit m2o-audit" _status="{$v['status']}" _id="{$v['id']}_{$v['period_id']}" style="color:{$_configs['status_color'][$v['status']]}">{$_configs['status_show'][$v['status']]}</span></div>
			<div class="epaper-endtime"><label>{$v['stack_num']}叠/{$v['page_num']}版</label></div>
  			<div class="epaper-addtime"><span>{$v['user_name']}</span><span style="margin-left:4px;">{$v['update_time']}</span></div>

  		  </div>
  	     </div>
  	     <a class="del"></a>
		    <div class="edit">
		        <div class="add-news"><a href="./run.php?a=relate_module_show&app_uniq=epaper&mod_uniq=period&mod_a=form&epaper_id={$v['id']}&epaper_name={$v['name']}&cur_stage={$v['cur_stage']}&cur_date={$v['cur_time']}&infrm=1"  target="mainwin" need-back>新增一期</a></div><br/>
				<div class="edit-news"><a href="./run.php?a=relate_module_show&app_uniq=epaper&mod_uniq=article&mod_a=news_edit&epaper_id={$v['id']}&period_id={$v['period_id']}&epaper_name={$v['name']}&cur_stage={$v['period_num']}&cur_date={$v['period_date']}&infrm=1" target="formwin">编辑新闻</a></div>
				<div class="edit-link"><a href="./run.php?a=relate_module_show&app_uniq=epaper&mod_uniq=article&mod_a=link_edit&epaper_id={$v['id']}&period_id={$v['period_id']}&epaper_name={$v['name']}&cur_stage={$v['period_num']}&cur_date={$v['period_date']}&infrm=1" target="formwin">编辑链接</a></div>
		   </div>
         <div class="news-logo">
		    <div class="logo">
		    {code}
		        $picture = $v['picture']['host'].$v['picture']['dir'].$v['picture']['filepath'].$v['picture']['filename'];
		    {/code}
		        <img src="{$picture}" style="width:73px;height:23px;">
		    </div>
			       <label class="news-status"><b>{$_configs['date_select'][$v['sort_id']]}</b></label>
            <a class="oldpaper" href="./run.php?a=relate_module_show&app_uniq=epaper&mod_uniq=period&epaper_id={$v['id']}&epaper_name={$v['name']}&cur_stage={$v['cur_stage']}&cur_date={$v['cur_time']}&infrm=1" target="mainwin" need-back></a>
		 </div>
     	</li>