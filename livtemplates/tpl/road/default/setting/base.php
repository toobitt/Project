<?php 
/* $Id: list.php 18206 2013-03-20 02:07:46Z yizhongyue $ */
?>
<ul class="form_ul">
    <li class="i">
        <div class="form_ul_div">
            <span  class="title">&nbsp;&nbsp;&nbsp;微博保留条数：</span>
            <input type="text" value="{$settings['define']['MAX_RECORD_NUM']}" name='define[MAX_RECORD_NUM]' style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span class="title">&nbsp;&nbsp;&nbsp;微博默认状态：</span>
            <input type="text" value="{$settings['base']['default_state']}" name='base[default_state]' style="width:200px;">
            <font class="important" style="color:red">0未审核 1已审核 2 已打回</font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span class="title">&nbsp;&nbsp;&nbsp;地图显示城市：</span>
            <input type="text" value="{$settings['base']['areaname']}" name='base[areaname]' style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>    
    
</ul>