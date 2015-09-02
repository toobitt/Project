
		<li _id="{$list['month']['id']}" _astroid="{$list['astroid']}" _fun="r_{$list['month']['fun']}" id="r_{$list['month']['id']}" name="{$list['month']['id']}" class="tv-each {if $full}num-equal{/if}">
  	     <div class="tv-profile m2o-flex">
  		  <div class="tv-img">
  		   <h3>{$list['month']['astrofuncn']}</h3>
  		    <div class="tv-endtime"><span class="fortuneinfotime">有效时间:<br>{$list['month']['fortuneinfostart']}<br>至<br>{$list['month']['fortuneinfoend']}</span></div>
  			<img _src="{$list['month']['astrofunimg']}" alt="{$list['month']['astrofuncn']}" />
  			<span class="edit">&nbsp;</span>
  			 <input type="file" style="display:none" class="video-file" name="videofile" data-id="{$list['month']['id']}"/>
  		  </div> 		  
  		  <img src="{$RESOURCE_URL}loading2.gif" class="loading loadr" />
  		  <div class="tv-brief m2o-flex-one">
  			  {code}
  			foreach($list['month'] as $k=>$v)
{
foreach($v as $kk=>$vv)
{
$astromonth[]=$vv;

}
}
{/code}
 {for $i=0;$i<5;$i++}
 <div class="tv-adduser">
<span class="formlist">{$astromonth[$i]['title']}:</span>
<span>{if $astromonth[$i]['rank']!=0} 
{for $j=0;$j<$astromonth[$i]['rank'];$j++}
			<span class='star' style="
    display: inline-block; width: 21px; height: 23px; position: relative;
top: -4px;
">&nbsp;</span>
{/for}
<br>{/if}<span>
<span>{$astromonth[$i]['value']}<span>
 </div>
{/for}
  		<br><br><br><br><br><br><br><br>
  		  </div>
  		 <a class=re></a>   
  	     </div>
  	   
     	</li>