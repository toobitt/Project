<?php include('tpl/head.tpl.php');?>
 <h1>服务器管理</h1>
  <ul>
	<li style="float:right;margin-right:10px;"><a href="index.php">服务器列表</a></li>
  </ul>
  <div style="clear:both;"></div>
 <?php
 if($configs)
 {
 ?>
  <form action="?action=<?php echo $doaction;?>" method="post" id="cform" name="cform" onsubmit="return confirm('确认无误保存吗？');">
  <div>
	<input type="submit" name="s1" value=" 保存 " style="width:100px;height:40px;color:#fff;font-size:16px;margin-bottom:4px;" />
  </div>
  <input type="hidden" name="file" value="<?php echo $_REQUEST['file'];?>" />
  <input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>" />
  <textarea rows="30" cols="120" name="content"><?php echo $configs;?></textarea>
  <div>
	<input type="submit" name="s" value="保存" style="width:100px;height:40px;color:#fff;font-size:16px;margin-top:4px;" />
   </div>
  </form>
 <?php
 }
 ?>
<?php include('tpl/foot.tpl.php');?>