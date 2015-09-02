
		<li _id="{$list['tomorrow']['id']}" _astroid="{$list['astroid']}" _fun="{$list['tomorrow']['fun']}" id="r_{$list['tomorrow']['id']}" name="{$list['tomorrow']['id']}" class="tv-each {if $full}num-equal{/if}">
  	     <div class="tv-profile m2o-flex">
  		  <div class="tv-img">
  		   <h3>{$list['tomorrow']['astrofuncn']}</h3>
  		    <div class="tv-endtime"><span class="fortuneinfotime">有效时间:<br>{$list['tomorrow']['fortuneinfostart']}</span></div>
  			<img _src="{$list['tomorrow']['astrofunimg']}" alt="{$list['tomorrow']['astrofuncn']}" />
  			<span class="edit">&nbsp;</span>
  			 <input type="file" style="display:none" class="video-file" name="videofile" data-id="{$list['tomorrow']['id']}"/>
  		  </div> 		  
  		  <img src="{$RESOURCE_URL}loading2.gif" class="loading loadr" />
  		  <div class="tv-brief m2o-flex-one">
  		  {code}
  			foreach($list['tomorrow'] as $k=>$v)
{
foreach($v as $kk=>$vv)
{
$astrotomorrow[]=$vv;

}
}
{/code}
 {for $i=0;$i<10;$i++}
 <div class="tv-adduser">
<span class="formlist">{$astrotomorrow[$i]['title']}:</span>
<span>{if $astrotomorrow[$i]['rank']!=0} {for $j=0;$j<$astrotomorrow[$i]['rank'];$j++}
			<span class='star' style="
    display: inline-block; width: 21px; height: 23px; position: relative;
top: -4px;
">&nbsp;</span>
{/for}{/if}<span>
<span>{$astrotomorrow[$i]['value']}<span>
 </div>
{/for}

  		<br><br>
				</div>     
				<a class=re></a>     
  	     </div>
     	</li>