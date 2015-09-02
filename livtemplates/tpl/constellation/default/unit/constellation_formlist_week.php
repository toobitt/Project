
		<li _id="{$list['week']['id']}" _astroid="{$list['astroid']}"  _fun="{$list['week']['fun']}" id="{$list['week']['id']}" name="{$list['week']['id']}" class="tv-each {if $full}num-equal{/if}">
  	     <div class="tv-profile m2o-flex">
  		  <div class="tv-img">
  		   <h3>{$list['week']['astrofuncn']}</h3>
  		    <div class="tv-endtime"><span class="fortuneinfotime">有效时间:<br>{$list['week']['fortuneinfostart']}<br>至<br>{$list['week']['fortuneinfoend']}</span></div>
  			<img _src="{$list['week']['astrofunimg']}" alt="{$list['week']['astrofuncn']}" />
  			<span class="edit">&nbsp;</span>
  			 <input type="file" style="display:none" class="video-file" name="videofile" data-id="{$list['week']['id']}"/>
  		  </div> 		  
  		  <img src="{$RESOURCE_URL}loading2.gif" class="loading loadr" />
  		  <div class="tv-brief m2o-flex-one">
  			{code}
  			foreach($list['week'] as $k=>$v)
{
foreach($v as $kk=>$vv)
{
$astroweek[]=$vv;

}
}
{/code}
 <div class="tv-adduser">
<span class="formlist">{$astroweek[0]['title']}:</span>
<span>{if $astroweek[0]['rank']!=0}
{for $j=0;$j<$astroweek[0]['rank'];$j++}
			<span class='star' style="
    display: inline-block; width: 21px; height: 23px; position: relative;
top: -4px;
">&nbsp;</span>
{/for}<br>
{/if}</span>
<span>{$astroweek[0]['value']}</span>
 </div>
  <div class="tv-adduser">
<span class="formlist">{$astroweek[1]['title']}:</span><br>
<span class="formlist">{$astroweek[1]['beau']['yes']['title']}</span>
<span>{if $astroweek[1]['beau']['yes']['rank']!=0}{for $j=0;$j<$astroweek[1]['beau']['yes']['rank'];$j++}
			<span class='star' style="
    display: inline-block; width: 21px; height: 23px; position: relative;
top: -4px;
">&nbsp;</span>
{/for}<br>{/if}</span>
<span>{$astroweek[1]['beau']['yes']['value']}</span>
 </div>
   <div class="tv-adduser">
<span class="formlist">{$astroweek[1]['beau']['no']['title']}</span>
<span>{if $astroweek[1]['beau']['no']['rank']!=0}{for $j=0;$j<$astroweek[1]['beau']['no']['rank'];$j++}
			<span class='star' style="
    display: inline-block; width: 21px; height: 23px; position: relative;
top: -4px;
">&nbsp;</span>
{/for}<br>{/if}</span>
<span>{$astroweek[1]['beau']['no']['value']}</span>
 </div>
 {for $i=2;$i<=7;$i++}
 <div class="tv-adduser">
<span class="formlist">{$astroweek[$i]['title']}:</span>
<span>{if $astroweek[$i]['rank']!=0} 
{for $j=0;$j<$astroweek[$i]['rank'];$j++}
			<span class='star' style="
    display: inline-block; width: 21px; height: 23px; position: relative;
top: -4px;
">&nbsp;</span>
{/for}
<br>{/if}</span>
<span>{$astroweek[$i]['value']}</span>
 </div>
{/for}
  			
  		  </div>
  		  <a class=re></a>
  	     </div>
  	    
     	</li>