
		<li _id="{$list['year']['id']}" _astroid="{$list['astroid']}" _fun="r_{$list['year']['fun']}" id="r_{$list['year']['id']}" name="{$list['year']['id']}" class="tv-each {if $full}num-equal{/if}">
  	     <div class="tv-profile m2o-flex">
  		  <div class="tv-img">
  		   <h3>{$list['year']['astrofuncn']}</h3>
  		    <div class="tv-endtime"><span class="fortuneinfotime">有效时间:<br>{$list['year']['fortuneinfostart']}<br>至<br>{$list['year']['fortuneinfoend']}</span></div>
  			<img _src="{$list['year']['astrofunimg']}" alt="{$list['year']['astrofuncn']}" />
  			<span class="edit">&nbsp;</span>
  			 <input type="file" style="display:none" class="video-file" name="videofile" data-id="{$list['year']['id']}"/>
  		  </div> 		  
  		  <img src="{$RESOURCE_URL}loading2.gif" class="loading loadr" />
  		  <div class="tv-brief m2o-flex-one">
  				  {code}
  			foreach($list['year'] as $k=>$v)
{
foreach($v as $kk=>$vv)
{
$astroyear[]=$vv;

}
}
{/code}
 {for $i=0;$i<6;$i++}
 <div class="tv-adduser">
<span class="formlist">{$astroyear[$i]['title']}:</span>
<span>{if $astroyear[$i]['rank']!=0} 
{for $j=0;$j<$astroyear[$i]['rank'];$j++}
			<span class='star' style="
    display: inline-block; width: 21px; height: 23px; position: relative;
top: -4px;
">&nbsp;</span>
{/for}
<br>{/if}<span>
<span>{$astroyear[$i]['value']}<span>
 </div>
{/for}
  		
  			
  		  </div>
  		 <a class=re></a>  
  	     </div>
  	   
     	</li>