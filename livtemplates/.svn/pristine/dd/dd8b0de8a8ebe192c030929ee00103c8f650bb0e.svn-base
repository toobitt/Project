
<li _id="{$list['day']['id']}" _astroid="{$list['astroid']}" _fun="{$list['day']['fun']}" id="r_{$list['day']['id']}"
	name="{$list['day']['id']}" class="tv-each {if $full}num-equal{/if}">
	<div class="tv-profile m2o-flex">
		<div class="tv-img">
		    <h3>{$list['day']['astrofuncn']}</h3>
		     <div class="tv-endtime"><span class="fortuneinfotime">有效时间:<br>{$list['day']['fortuneinfostart']}</span></div>
			<img _src="{$list['day']['astrofunimg']}" alt="{$list['day']['astrofuncn']}" /> 
			<span class="edit">&nbsp;</span> <input type="file"
				style="display: none" class="video-file" name="videofile"
				data-id="{$list['day']['id']}" />
		</div>
		<img src="{$RESOURCE_URL}loading2.gif" class="loading loadr" />
		<div class="tv-brief m2o-flex-one">
			{code} 
			foreach($list['day'] as $k => $v) 
			{ 
			foreach ($v as $kk => $vv)
			{ 
			$astroday[] = $vv; 
			} 
			} 
			{/code}
			 {for $i=0;$i<10;$i++}
			 <div class="tv-adduser">
			<span class="formlist">{$astroday[$i]['title']}:</span>
			{if $astroday[$i]['rank']!=0}
			{for $j=0;$j<$astroday[$i]['rank'];$j++}
			<span class='star' style="
    display: inline-block; width: 21px; height: 23px; position: relative;
top: -4px;
">&nbsp;</span>
{/for}
			 {/if}
			 <span>{$astroday[$i]['value']}</span>
			 </div>
			   {/for}

		</div>

			<a class=re></a>           
		
	</div>
</li>
