<?php 
/* $Id: map.php 390 2011-07-26 05:35:00Z lijiaying $ */
?>
{template:head}
 <div class="content clear" id="equalize">
 	 <div class="content-left">
 	 	<div class="rounded-top"></div>
 	 	<div style="width:100%;"> 
    		<div id="map_canvas" style="width:612px; height: 600px;"></div>
    	</div>
    </div>
    <div class="content-right">
		<div class="rounded-top-right"></div>
		<div class="pad-all">{code} $user_info = $_user; {/code}
		{template:unit/userImage}
		{template:unit/userInfo}
		</div>
	</div>
    
</div>
{template:foot}
  