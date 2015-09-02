<?php
include 'feedback_data.php';
$arr = array(
1=>'a',
2=>'b',
3=>'',
4=>'a',
);
?>

<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<form method="post" enctype="multipart/form-data" name="feedback" action="http://10.0.1.40/livsns/api/feedback/feedback_update.php?a=create&id=<?php echo $data['id']?>">
<div id="xinqing">
        <h1><?php echo $data['title']?></h1>
        <div><?php echo $data['brief'];?></div>
        <input name ="id" type="hidden" value="<?php echo $data['id'];?>"/>
      <?php
        if(is_array($data['forms']) && count($data['forms'])>0)
        {
        	foreach ($data['forms'] as $num=>$forms)
        	{
        		if($forms['is_required'])
        		{
        			$require =' required="ture" ';
        		}
        		else 
        		{
        			$require ='';
        		}
        		if ($forms['type'] == 'standard')
        		{
        			switch($forms['form_type'])
        			{
        				case 1 ://input框
        					echo '<label>'.$forms['name'].': </label> '. '<input type="text" '.$require.' style="width:'.$forms['width'].';height:'.$forms['height'].'" name ="form[standard_'.$forms['id'].']" value=""/>';
        					echo '<br/>';
        					break;
        				
        				case 2 ://多行文本
        					echo '<label>'.$forms['name'].': </label> <br/>'. '<textarea type="text" '.$require.' style="width:'.$forms['width'].';height:'.$forms['height'].'"  name ="form[standard_'.$forms['id'].']" ></textarea>';
        					echo '<br/>';
        					break;
        				case 3 :	//单选/多选
        					echo '<label>'.$forms['name'].': </label> <br/>';
        					if($forms['cor'] == 1)//单选
        					{
        						foreach ($forms['options'] as $options)
        						{
        							echo '<input type="radio" name="form[standard_'.$forms['id'].']" value="'.$options.'"/>'.$options.'<br/>';
        						}
        					}
        					if($forms['cor'] == 2)//多选
        					{
        						foreach ($forms['options'] as $options)
        						{
        							echo '<input type="checkbox" name="form[standard_'.$forms['id'].'][]" value="'.$options.'"/>'.$options.'<br/>';
        						}
        					}
        					break;
        				case 4 :	//下拉
        					echo '<label>'.$forms['name'].': </label> ';
        					echo '<select name="form[standard_'.$forms['id'].']">';
        					foreach ($forms['options'] as $options)
        					{
        						echo '<option value="'.$options.'"/>'.$options.'</option>';
        					}
        					echo '</select>';
        					echo '<br/>';
        					break;
        				case 5 ://上传附件
        					echo '<label>'.$forms['name'].': </label> <br/>';
        					echo '<input type="file" name="file_'.$forms['id'].'[]" value=""/><br/>';
        					echo '<input type="file" name="file_'.$forms['id'].'[]" value=""/><br/>';
        					echo '<input type="file" name="file_'.$forms['id'].'[]" value=""/><br/>';
        					break;
        				case 6 ://分割线
        					echo '<hr/>';
        					break;
        			}
        		}
        		if ($forms['type'] == 'fixed')
        		{
        			if($forms['fixed_id'] == 4 or $forms['fixed_id'] == 6) //地址和时间固定组件
        			{
        				echo '<label>'.$forms['name'].': </label> ';
        				foreach ($forms['element'] as $ele)
        				{
        					
        					if($ele['form_type'] == 4 )
        					{
        						echo '<select name ="form[fixed_'.$forms['id'].'][]">';
        						foreach ($ele['value'] as $vv)
        						{
        							echo '<option value="'.$vv.'"/>'.$vv.'</option>';
        						}
        						echo '</select>';
        				    }
        				    elseif ($ele['form_type'] == 1 )
        				    {
        				    	echo '<br/>';
        				    	echo '<input type="text" name ="form[fixed_'.$forms['id'].'][]" value=""/>'; 
        				    }   
        				}
        				echo '<br/>';
        			}
        			else 
        			{
        				if($forms['fixed_id'] == 2) //地址和时间固定组件
        				{
        					echo '<label>'.$forms['name'].': </label> '. '<input type="email"'.$require.' style="width:'.$forms['width'].';height:'.$forms['height'].'"  name ="form[fixed_'.$forms['id'].']" value=""/>';
        				    echo '<br/>';
        				}
        				elseif($forms['fixed_id'] == 3) //地址和时间固定组件
        				{
        					echo '<label>'.$forms['name'].': </label> '. '<input type="tel"'.$require.' style="width:'.$forms['width'].';height:'.$forms['height'].'"  name ="form[fixed_'.$forms['id'].']" value=""/>';
        				    echo '<br/>';
        				}
        				else 
        				{
        					echo '<label>'.$forms['name'].': </label> '. '<input type="text"'.$require.' style="width:'.$forms['width'].';height:'.$forms['height'].'"  name ="form[fixed_'.$forms['id'].']" value=""/>';
        				    echo '<br/>';
        				}
        			}
        		}
        	}
        }
        ?>
        </div>
        <?php 
        if($data['is_verifycode'])
        {
        	$url = 'http://www.dev.hogesoft.com:233/m2o/xcode/get.php?type='.$data['verifycode_type'];
        	$vvv = file_get_contents($url);
        $vvv = json_decode($vvv,1);
        if($vvv[0]['is_dipartite'])
        {
	        $is_dipartite = "区分大小写";
        }
        else
        {
	        $is_dipartite = "不区分大小写";
        }
        	echo '<img id="aa" src="'.$vvv[0]['img'].'"/>';
        	echo '<input type="text" name ="verify_code" value=""/>('.$is_dipartite.')';
        	echo '<input type="hidden" name ="session_id" value="'.$vvv[0]['session_id'].'"/>';
        }
        
        if($data['is_login'])
        {
        	echo '<input type="hidden" name ="access_token" value="'.$_REQUEST['access_token'].'"/>';
        }?>
        <br/>栏目id<input type="text" name="column_id" id="2">
            <input type="submit"  value="提交"/>
        <?php //}?>
</div>
</form>