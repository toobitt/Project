	<div style="height:25px;background:#EAF5FB;border:1px solid #BDD2E3;">
		<div align=right style="margin-top:5px;">
		<a href="javascript:void(0)" onclick="hg_show_brower('close')"><img src='{$RESOURCE_URL}/close.gif'></a><br>
		</div>
		<div align=center style="margin-top:-20px;">
			<input type=text name="search_tem" id="search_tem" style="width:100px;height:17px;">
			<input type="button" value="" name="hg_search" onclick="hg_get_browse({$formdata['site_id']},'','{$formdata['content_type']}','1')"  style="padding:0;border:0;margin:0;background:url({$RESOURCE_URL}find.png) no-repeat;cursor:pointer;width:30px;" />
		</div>
		<div align=left style="margin-top:-18px;">
		<a href="javascript:void(0)" onclick="hg_get_browse({$formdata['site_id']},'{$formdata['backdata']['id']}','{$formdata['content_type']}','',{$formdata['client']})">返回</a><br>
		</div>
	</div>
	
	<div style="height:25px;background:#EAF5FB;border:1px solid #BDD2E3;">
		<form name="upload_tem_form" id="upload_tem_form" action="" method='POST'>
		<div align=right style="margin-top:5px;">
		<a href="javascript:void(0)" onclick="hg_ajax_submit('upload_tem_form')">确定</a><br>
		</div>
		<div style="margin-top:-20px;">
			<input type=file name="Fileda" id="Fileda" >
		</div>
		<div align=left style="margin-top:-18px;">
		
		<!--
		<a href="javascript:void(0)" onclick="hg_get_browse({$formdata['site_id']},'{$formdata['backdata']['id']}','{$formdata['content_type']}','',{$formdata['client']})">返回</a><br>
		-->
		</div>
		<input type="hidden" name="a" value="upload_template" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		<input type="hidden" name="mid" value="{$_INPUT['mid']}" />
		<input type=hidden id="sort_id" name="sort_id" value="{$formdata['sort_fid']}">
		<input type=hidden id="siteid" name="siteid" value="{$formdata['site_id']}">
		<input type=hidden id="client" name="client" value="{$formdata['client']}">
		<input type=hidden id="template_style" name="template_style" value="{$formdata['tem_style']}">
		</form>
	</div>
	<div id='template_use_record' name='template_use_record' style="height:245px;width:30%;margin-left:70%;position:absolute;OVERFLOW-y:auto;border:1px solid #A8A8A8;">
	
	</div>
	<div style="height:245px;width:70%;position:absolute;OVERFLOW-y:auto;border:1px solid #A8A8A8;">
	{code}
		if(!empty($formdata['sort']))
		{
			foreach($formdata['sort'] as $k=>$v)
			{
				echo "<img src='".$RESOURCE_URL."publish_folder.gif'><a href='javascript:void(0)' onclick=\"hg_get_browse(".$formdata['site_id'].",".$v['id'].",'".$formdata['content_type']."','',".$formdata['client'].")\">";
				echo $v['name'];
				echo "</a>";
				echo "<br>";
			}
		}
		if(!empty($formdata['template']))
		{
			foreach($formdata['template'] as $k=>$v)
			{
				echo "<img src='".$RESOURCE_URL."publish_file.gif'>";
				echo "<a href=\"javascript:void(0)\" onclick=\"hg_choose_tem('".$v['sign']."','".$v['title']."','".$formdata['site_id']."','".$v['sign']."')\">";
				echo $v['title'];
				echo "</a>";
				if(in_array($v['id'],$formdata['use_tem']))
				{
					echo "<div style='float:right;color:#5C99CF;'>使用中</div>";
				}
				echo "<br>";
			}
		}
	{/code}
	</div>
	<div style="height:26px;width:100%;border:1px solid #A8A8A8;margin-top:246px;">
		<a class="button_6" href="javascript:void(0)" onclick="hg_tem_sure('{$formdata['content_type']}')">确定</a>
		选择文件：
		<label id="c_tem" color=red></label>
	</div>
	<input type=hidden id="hidden_tem_id" name="hidden_tem_id" value="">					
	<input type=hidden id="hidden_tem_title" name="hidden_tem_title" value="">					
	<input type=hidden id="hidden_content_type" name="hidden_content_type" value="{$formdata['content_type']}">					
	<input type=hidden id="hidden_client" name="hidden_client" value="{$formdata['client']}">					
