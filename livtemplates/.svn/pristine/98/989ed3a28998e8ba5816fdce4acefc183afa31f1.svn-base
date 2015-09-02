{code}
  		  	   $full = $v['update_status'] == $v['playcount'] ? true : false;
  		  	{/code}
		<li _id="{$v['id']}" id="r_{$v['id']}" name="{$v['id']}" class="tv-each {if $full}num-equal{/if}">
  	     <div class="tv-profile m2o-flex">
  		  <div class="tv-img">
  			<img _src="{$v['logo']}" alt="{$v['astrocn']}" />
  			<span class="edit">&nbsp;</span>
  			 <input type="file" style="display:none" class="video-file" name="videofile" data-id="{$v['id']}"/>
  		  </div> 		  
  		  <img src="{$RESOURCE_URL}loading2.gif" class="loading loadr" />
  		  <div class="tv-brief m2o-flex-one">
  			<span class="constellationlist m2o-common-title">{$v['astrocn']}</span>
  			  <div class="tv-endtime"><span class="astrointroduction">黄道时间:</span><span>{$v['astrostart']}－{$v['astroend']}</span></div>
  			<div class="tv-status"><span class="astrointroduction">简介: </span>{$v['astrointroduction']}</div>
  			<!--  <div class="tv-endtime"><label>到期时间: </label><span>{$v['copyright_limit']}</span></div>
  			<div class="tv-adduser"><label>添加人: </label><span>{$v['user_name']}</span></div>
  			<div class="tv-addtime"><label>添加时间: </label><span>{$v['create_time']}</span></div>-->
  			
  		  </div>
  		  <a href="run.php?mid={$_INPUT['mid']}&a=form&id={$v['id']}&infrm=1" target="formwin" class="linking">&nbsp;</a>
  	     </div>
  	    
     	</li>