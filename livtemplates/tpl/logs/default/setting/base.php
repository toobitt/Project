<?php 
?>
<script>
    $(function(){
         $(".datepicker").datepicker();   
    })
</script>
<ul class="form_ul">
	<li class="i">
		<div class = "form_ul_div">
			<span class="title">设置时间：</span>
			<!--<input type="text" value="{$settings['define']['DELETE_DAYS']}" name='define[DELETE_DAYS]' style="width:200px;" class="datepicker">-->
			<input type="text" value="{$settings['define']['DELETE_DAYS']}" name='define[DELETE_DAYS]' style="width:100px;">天
			<font class="important" style="color:red">清除日志</font>
		</div>
	</li>
</ul>