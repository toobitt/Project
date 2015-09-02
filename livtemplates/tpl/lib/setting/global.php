<h2>全局变量</h2>
<div>
	<form action="" method="post" enctype="multipart/form-data" class="ad_form h_l">
<?php 
	$gglobal = $settings['gglobal'];
	//print_r($gglobal);
	function show_father($data)
	{
		$nbsp = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data && is_array($data))
		{
			show_child($data,$nbsp);
		}else{
			echo $nbsp."|---".$data;
			echo key($data);
		}   		
	}
	function show_child($data,$nbsp){
		foreach ($data as $k=>$v)
		{
			
			if (is_array($v))
			{	
				echo $nbsp."|---[".$k."]";
				echo "<br/>";		
				show_child($v,$nbsp."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
			}else{
				echo "".$nbsp."|---[".$k."]=><input type='text' value='".$v."' style='height:10px;width:200px'/><br />";
			}
		}
	}		
	if ($gglobal && is_array($gglobal))
	{
		echo "|---[".key($gglobal)."]";
		echo "<br/>";
		show_father($gglobal);
	}

?>
		<input type="hidden" name="a" value="{$a}" />
		<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
		<input type="hidden" name="referto" value="{$_INPUT['referto']}" />
		<input type="hidden" name="infrm" value="{$_INPUT['infrm']}" />
		<br />
		<input type="submit" name="sub" value="更新配置" class="button_6_14"/>
	</form>

</div>

