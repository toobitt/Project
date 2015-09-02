<?php include('tpl/head.tpl.php');?>
 <h1>服务器管理</h1>
  <ul>
	<li style="float:right;margin-right:10px;"><a href="?action=addserver">增加服务器</a></li>
  </ul>
  <div style="clear:both;height:10px;"></div>
  <table width="98%" cellspacing="0" cellpadding="0" border="0">
	  <tr>
		  <th width="30">序号</th>		
		  <th width="120">类型</th>		
		  <th>服务器名称</th>
		  <th width="400">安装服务</th>
		  <th width="100">管理</th>
	  </tr>
  <?php
  foreach($servers AS $k => $serv)
  {
  ?>
  	  <tr>
		  <td><?php echo $k;?></td>	
		  <td height="30"><?php echo $Cfg['servertype'][$serv['type']]['name'];?>&nbsp;</td>	
		  <td><?php echo $serv['name'] . '(' . $serv['ip'] . ')';?><span style="float:right;">
		  <a href="index.php?action=df&id=<?php echo $serv['id']; ?>">查看分区</a>&nbsp;&nbsp;
		  <?php
			 if (is_array($Cfg['servertype'][$serv['type']]['conf']))
			{
				foreach ($Cfg['servertype'][$serv['type']]['conf'] AS $kk => $file)
				{
				?>
					<a href="man.php?action=getfile&id=<?php echo $serv['id']; ?>&file=<?php echo $kk; ?>"><?php echo $kk;?></a>&nbsp;&nbsp;
			  <?php
				}
			}
			?>
		  </span></td>	
		  <td>&nbsp;</td>	
		  <td><a href="?action=copy&id=<?php echo $serv['id']; ?>">复制</a>&nbsp;&nbsp;<a href="?action=editserver&id=<?php echo $serv['id']; ?>">修改</a>&nbsp;&nbsp;<a href="?action=deleteserver&id=<?php echo $serv['id']; ?>" onclick="return confirm('确认删除？');">删除</a></td>	
	  </tr>
  <?php
  }
  ?>
  </table>  
  <div align="center">
	<input type="button" name="s" value=" 开始安装 " style="width:100px;height:40px;color:#fff;font-size:16px;margin-top:20px;" onclick="document.location.href='index.php?action=install'" />
  </div>
<?php include('tpl/foot.tpl.php');?>