
		<li _id="{$list['love']['id']}" _astroid="{$list['astroid']}" _fun="r_{$list['love']['fun']}" id="r_{$list['love']['id']}" name="{$list['love']['id']}" class="tv-each {if $full}num-equal{/if}">
  	     <div class="tv-profile m2o-flex">
  		  <div class="tv-img">
  		   <h3>{$list['love']['astrofuncn']}</h3>
  		    <div class="tv-endtime"><span class="fortuneinfotime">有效时间:<br>{$list['love']['fortuneinfostart']}<br>至<br>{$list['love']['fortuneinfoend']}</span></div>
  			<img _src="{$list['love']['astrofunimg']}" alt="{$list['love']['astrofuncn']}" />
  			<span class="edit">&nbsp;</span>
  			 <input type="file" style="display:none" class="video-file" name="videofile" data-id="{$list['love']['id']}"/>
  		  </div> 		  
  		  <img src="{$RESOURCE_URL}loading2.gif" class="loading loadr" />
  		  <div class="tv-brief m2o-flex-one">
  			  {code}
  			foreach($list['love'] as $k=>$v)
{
foreach($v as $kk=>$vv)
{
$astrolove[]=$vv;

}
}
{/code}
 {for $i=0;$i<3;$i++}
 <div class="tv-adduser">
<span class="formlist">{$astrolove[$i]['title']}:</span>
<span>{if $astrolove[$i]['rank']!=0} 
{for $j=0;$j<$astrolove[$i]['rank'];$j++}
			<span class='star' style="
    display: inline-block; width: 21px; height: 23px; position: relative;
top: -4px;
">&nbsp;</span>
{/for}
<br>{/if}<span>
<span>{$astrolove[$i]['value']}<span>
 </div>
{/for}
  			<br><br><br><br><br><br><br><br>
  		  </div>
  		  <a class=re></a>  
  	     </div>
  	     
     	</li>