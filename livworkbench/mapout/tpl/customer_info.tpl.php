<?php include('tpl/head.tpl.php');?>
 <h2>申请授权</h2>
 <div style="color:red;"><?php echo $message;?></div>
<form name="editform" action="?action=install6" method="post" class="ad_form h_l">
<ul class="form_ul">
<li class="i">
<div class="form_ul_div clear">
<span class="title">客户名称：</span><input type="text" name="custom_name" value="<?php echo $_REQUEST['custom_name'];?>" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">　　简称：</span><input type="text" name="display_name" value="<?php echo $_REQUEST['display_name'];?>" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">客户标识：</span><input type="text" name="bundle_id" value="<?php echo $_REQUEST['bundle_id'];?>" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">授权域名：</span><input type="text" name="domain" value="<?php echo $_REQUEST['domain'];?>" />
</div>
</li>
<li class="i">
<div class="form_ul_div clear">
<span class="title">客户描述：</span><textarea name="custom_desc" style="width:400px;height:100px;" cols="60" rows="5"><?php echo $_REQUEST['custom_desc'];?></textarea>
</div>
</li>
</ul>
<br />
<input type="submit" name="sub" value="下一步" />
</form>
<?php include('tpl/foot.tpl.php');?>