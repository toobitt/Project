<li order_id="${id}" _id="r_${id}" class="common-list-data clear"  id="r_${id}" name="${id}">
   <div class="common-list-left ">
	  <div class="common-list-item paixu">
	     <a class="lb" name="alist[]">
		   <input type="checkbox" name="infolist[]" value="${id}" title="${id}" />
	     </a>
	  </div>
   </div>
   <div class="common-list-right">
        <!--  <div class="common-list-item wd80">
		     <span></span>
		</div>-->
		<div class="common-list-item wd80 overflow">
		     <span>${column_name}</span>
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
		<div class="common-list-item wd80">
		     <span>${module_name}</span>
		</div>
		<div class="common-list-item wd150">
		     <span class="common-name">${publish_user}</span>
		     <span class="common-time">${create_time}</span>
		</div>
		<div class="common-list-item wd50">
		     <span class="addBtn"></span>
		</div>
	</div>
   <div class="common-list-biaoti select-min-wd">
	    <div class="common-list-item biaoti-transition">
	      <div class="common-list-overflow select-max-wd">
	      	<a title="${title}">	
		       	 {{if host}}
					<img src="${url}" id="img_${id}" class="biaoti-img"/>
				 {{/if}}
				 <span>${title}</span>
			</a>
		   </div>
		</div>
   </div>
   <span id="info${id}" class="select_info" style="display:none;"></span>
</li>