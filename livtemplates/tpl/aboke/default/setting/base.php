<!--
<ul class="form_ul">
    <li class="i">
        <div class="form_ul_div">
            <span class="title">&nbsp;&nbsp;&nbsp;Chinacache用户名：</span> 
            <input
                type="text" value="{$settings['base']['ChinaCache']['username']}"
                name='base[ChinaCache][username]' style="width: 200px;"> <font
                class="important" style="color: red"></font>
        </div>
    </li>
    <li class="i">
        <div class="form_ul_div">
            <span class="title">&nbsp;&nbsp;&nbsp;Chinacache密码：</span> <input
                type="text" value="{$settings['base']['ChinaCache']['password']}"
                name='base[ChinaCache][password]' style="width: 200px;"> <font
                class="important" style="color: red"></font>
        </div>
    </li>

</ul>
-->
<script>
    $(function(){
         $(".datepicker").datepicker();   
    })
</script>
<ul class="form_ul" style="margin-bottom:50px;">
    <!--
    <li class="i">
        <div class="form_ul_div">
            <span  class="title">转码服务器ID：</span>
            <input type="text" value="{$settings['define']['TRANSCODE_SERVER_ID']}" name='define[TRANSCODE_SERVER_ID]' style="width:200px;">
            <font class="important" style="color:red"></font>
        </div>
    </li>
    -->
    
    <li class="i">
        <div class="form_ul_div">
        <span class="title">&nbsp;&nbsp;&nbsp;转码服务器ID：</span>
        <input type="text" value="{$settings['base']['transcode_server_id']}" name='base[transcode_server_id]' style="width:200px;">
        
        <font class="important" style="color:red"></font>
        </div>
    </li>
        <li class="i">
        <div class="form_ul_div">
        <span class="title">&nbsp;&nbsp;&nbsp;用户创建最大专辑数：</span>
        <input type="text" value="{$settings['base']['createCategoryNumMax']}" name='base[createCategoryNumMax]' style="width:200px;">
        
        <font class="important" style="color:red"></font>
        </div>
    </li>
        <li class="i">
        <div class="form_ul_div">
        <span class="title">&nbsp;&nbsp;&nbsp;专辑最大视频数：</span>
        <input type="text" value="{$settings['base']['categoryVideoNumMax']}" name='base[categoryVideoNumMax]' style="width:200px;">
        
        <font class="important" style="color:red"></font>
        </div>
    </li>
</ul>

