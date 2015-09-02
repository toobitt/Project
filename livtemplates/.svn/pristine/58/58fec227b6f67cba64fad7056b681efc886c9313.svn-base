<li order_id="${order_id}" _id="${id}" class="common-list-data clear"  id="r_${id}" name="${id}" _columnid="${column_id}" _bundleid="${bundle_id}" _moduleid="${module_id}" _fromid="${content_fromid}" _cid="${cid}" >
   <div class="common-list-left ">
	  <div class="common-list-item paixu">
	     <a class="lb" name="alist[]">
		   <input type="checkbox" name="infolist[]" value="${id}" title="${id}" />
	     </a>
	  </div>
   </div>
   <div class="common-list-right">
		<div class="common-list-item wd120">
		{{if pub}}
			{{if pub_url}}
		    {{each pub }} 
		    	{{if pub_url[$index] }}
		    		<a href="./redirect.php?id=${pub_url[$index]}" target="_blank"><span class="common-list-pub">{{= $value}}</span></a>		
		    	{{else}}
		    		<span class="common-list-pre-pub">{{= $value}}</span>
		    	{{/if}}  	
			{{/each}}
			{{/if}}
        {{/if}}
		</div>
		<div class="common-list-item wd120">
		     <span class="column-name">${column_name}</span>
		</div>
		<div class="common-list-item wd80">
		     <span>${module}</span>
		</div>
		<div class="common-list-item wd60 news-quanzhong ${weight} open-close">
			<div class="">
				<div class="" _level="${weight}">
					<div class="" _weight="${weight}">
						<span class="">${weight}</span>
					</div>
					
				</div>
			</div>
        </div>
        <div class="common-list-item wd60">
            <div class="common-switch-status">
		      <span _id="${id}" _state="${state}" id="statusLabelOf${id}" style="color:${state_color}">${status}</span>
		    </div>
		</div>
		<div class="common-list-item wd150">
		     <span class="common-name">${user_name}</span>
		     <span class="common-time">${create_time}</span>
		</div>
	</div>
   <div class="common-list-i" onclick="hg_show_opration_info(${id});"></div>
   <div class="common-list-biaoti min-wd">
	    <div class="common-list-item biaoti-transition">
	      <div class="common-list-overflow max-wd">
	      	<a title="${title}" data-edit="${source}" {{if source==1}}class="editor_href"{{/if}} _id="${id}" id=${id} data-column="${column_id}" {{if source!=1}}target="formwin" href="modify.php?app_uniqueid=${bundle_id}&mod_uniqueid=${module_id}&id=${content_fromid}&fromsource=1&backurl=${$item.getUrl()}"{{/if}}>	
					{{if host}}
					<img src="${url}" id="img_${id}" class="biaoti-img"/>
					{{/if}}
				
				 <span {{if source==1}}style="color:blue"{{/if}}>${title}</span>
			</a>
		   </div>
		</div>
   </div>
</li>