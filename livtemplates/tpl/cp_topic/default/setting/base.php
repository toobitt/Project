<ul class="form_ul">
<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;测试配置1：</span>
<input type="text" value="{$settings['define']['DB_PREFIX']}" name='define[DB_PREFIX]' style="width:200px;">
<font class="important" style="color:red"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;测试配置2：</span>
<input type="text" value="{$settings['base']['testset']['host']}" name='base[testset][host]' style="width:200px;">

<font class="important" style="color:red"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;测试配置21：</span>
{template:form/radio,base[testset][open],$settings['base']['testset']['open'],$option}
<font class="important" style="color:red"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;测试配置3：</span>
<input type="text" value="{$settings['base']['testsetad']}" name='base[testsetad]' style="width:200px;">

<font class="important" style="color:red"></font>
</div>
</li>
<li class="i">
<div class="form_ul_div">
<span class="title">&nbsp;&nbsp;&nbsp;测试配置4：</span>
<input type="text" value="{$settings['base']['article_status'][1]}" name='base[article_status][1]' style="width:200px;">
<input type="text" value="{$settings['base']['article_status'][2]}" name='base[article_status][2]' style="width:200px;">
<input type="text" value="{$settings['base']['article_status'][3]}" name='base[article_status][3]' style="width:200px;">
<input type="text" value="{$settings['base']['article_status'][4]}" name='base[article_status][4]' style="width:200px;">

<font class="important" style="color:red"></font>
</div>
</li>
</ul>