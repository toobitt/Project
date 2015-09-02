<ul class="form_ul" style="margin-bottom:50px;">
	<li class="i">
		<div class="form_ul_div">
			<span  class="title">默认城市：</span>
			<input type="text" value="{$settings['define']['DEFAULT_CITY_NAME']}" name='define[DEFAULT_CITY_NAME]' style="width:200px;">
			<font class="important" style="color:red">添加场馆中地图默认显示城市</font>
		</div>
	</li>
	
	<li class="i">
        <div class="form_ul_div">
            <span  class="title">&nbsp;&nbsp;&nbsp;预售提示：</span>
            <input type="text" value="{$settings['base']['sale_tip']['sale_1']}" name="base[sale_tip][sale_1]" style="width:200px;">
            <font class="important" style="color:red">手机端演出预售提示</font>
        </div>
    </li>
    
    <li class="i">
        <div class="form_ul_div">
            <span  class="title">&nbsp;&nbsp;&nbsp;结束提示：</span>
            <input type="text" value="{$settings['base']['sale_tip']['sale_3']}" name="base[sale_tip][sale_3]" style="width:200px;">
        
            <font class="important" style="color:red">手机端演出结束提示</font>
        </div>
    </li> 
    
</ul>