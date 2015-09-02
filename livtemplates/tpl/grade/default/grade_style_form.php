{template:head}
<div align="center">
<form action="run.php?mid={$_INPUT['mid']}&a=create" method="post" enctype="multipart/form-data">
	<h3>样式名称: </h3><input type="text" name="name"/><br/>
	<h3>索引图:</h3> 
	<input type="file" name="Filedata" value=""/>
	<h3>默认图:</h3> <input type="file" name="Filedata2"><br/>
 	<select name="points_system">
		  <option value ="5">5分制</option>
		  <option value ="10" selected="selected">10分制</option>
	</select><br/>
	<h3>1星:</h3>简称<input type="text" name="jiancheng[]">描述<input type="text" name="describe[]"/>
	<h3>2星:</h3>简称<input type="text" name="jiancheng[]">描述<input type="text" name="describe[]"/>
	<h3>3星:</h3>简称<input type="text" name="jiancheng[]">描述<input type="text" name="describe[]"/>
	<h3>4星:</h3>简称<input type="text" name="jiancheng[]">描述<input type="text" name="describe[]"/>
	<h3>5星:</h3>简称<input type="text" name="jiancheng[]">描述<input type="text" name="describe[]"/>
	<br/><br/><input type="submit" style="width:100px;height:40px;background:#ffd200" value="保存"/>
<form>
</div>
{template:foot}