<?php
include 'survey_data.php';
?>
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<form method="post" name="survey" action="http://10.0.1.40/livsns/api/survey/survey_update.php?a=update&id=<?php echo $data['id']?>">
<div id="xinqing">
        <h1><?php echo $data['title']?></h1>
        <div><?php echo $data['brief'];?></div>
        <div> 开始时间 ：<?php echo $data['start_time'];?>
        <br/> 结束时间 ：<?php echo $data['end_time'];?>
        <br/> 倒计时 ：<?php echo $data['use_hour'].'时'.$data['use_minute'].'分'.$data['use_second'].'秒'; ?>
        <br/> 分类 ：<?php echo $data['sort_name']; ?>
        <br/><br/><b>图片展示：</b><br/>
        <?php
        if(is_array($data['pictures']) && count($data['pictures'])>0)
        {
        	foreach ($data['pictures'] as $v)
            {
            	echo '<img width="100" src="'.$v['img_info'].'"/>';
            }
        }
        ?>
        <br/><br/><b>视频展示：</b><br/>
        <?php
        if(is_array($data['videos']) && count($data['videos'])>0)
        {
        	foreach ($data['videos'] as $v)
            {
            	echo '<div style="float:left;width:120px">';
            	echo '<a target="blank" href="'.$v['url'].'"><img width="100" src="'.$v['img_info'].'"/></a>';
            	echo '<br/>'.$v['title'].'<br/>';
            	echo '</div>';
            }
           echo '<br style="clear:both"/>';
        }
        ?>
        <br/><br/><b>其他展示：</b><br/>
        <?php
        if(is_array($data['publicontents']) && count($data['publicontents'])>0)
        {
        	foreach ($data['publicontents'] as $v)
            {
            	echo '<div style="float:left;width:40%">';
            	if($v['img_info'])
            	{
            		echo '<a target="blank" href="'.$v['content_url'].'"><img width="100" src="'.$v['img_info'].'"/></a>';
            	}
            	echo '<br/>'.'<a target="blank" href="'.$v['content_url'].'">'.$v['title'].'</a><br/>';
            	echo '<br/>'.$v['brief'].'<br/>';
            	echo '</div>';
            }
           echo '<br style="clear:both"/>';
        }
        ?>
        </div>
        <br/><br/>
        <div>
        <?php
        if(is_array($data['problems']) && count($data['problems'])>0)
        {
        	foreach ($data['problems'] as $num=>$problems)
        	{
        		$num = $num+1;
        		switch ($problems['type'])
        		{
        			case 1:
        				echo '<div>';
        				echo $num.'. '.$problems['title'];
        		        if($problems['is_required'])
        				{
        					$is_required='必填';
        				}
        				echo ' (单选，'.$is_required.')';
        				echo '<br/>';
        				if(is_array($problems['options']) && count($problems['options']>0))
        				{
        					foreach ($problems['options'] as $options)
        					{
        						echo '<input type="radio" name="answer['.$problems['id'].']" value="'.$options['id'].'"/>';
        					    echo '<label name="answer_'.$problems['id'].'">'.$options['name'].'</label>';
         					    echo '<br/>';
        					    echo '<br/>';
        					}
        				}
        				if($problems['is_other'])
        				{
        					echo '<input type="radio" name="answer['.$problems['id'].']" value="'.'-1'.'"/>';
        					echo '<label name="answer_'.$problems['id'].'">'.'其他'.'</label>';
        				    echo '<input type="text" name="other_answer['.$problems['id'].']" value=""/>';
        				}
        				
        				echo '</div><br/>';
        				break;
        			case 2:        				
        				echo '<div>';
        				echo $num.'. '.$problems['title'];
        		        if($problems['is_required'])
        				{
        					$is_required='必填';
        				}
        				if($problems['max_option'])
        				{
        					$max_option='最多'.$problems['max_option'].'项，';
        				}
        				if($problems['min_option'])
        				{
        					$min_option='最少'.$problems['min_option'].'项，';
        				}
        				echo ' (多选，'.$max_option.$min_option.$is_required.')';
        				echo '<br/>';
        				if(is_array($problems['options']) && count($problems['options']>0))
        				{
        					foreach ($problems['options'] as $options)
        					{
        						echo '<input type="checkbox" name="answer['.$problems['id'].'][]" value="'.$options['id'].'"/>';
        					    echo '<label name="answer_'.$problems['id'].'">'.$options['name'].'</label>';
        					    echo '<br/>';
        					}
        				}
        				        if($problems['is_other'])
        				        {
        				        	echo '<input type="checkbox" name="answer['.$problems['id'].'][]" value="'.'-1'.'"/>';
        					        echo '<label name="answer_'.$problems['id'].'">'.'其他'.'</label>';
        				        	echo '<input type="text" name="other_answer['.$problems['id'].']" value=""/>';
        				        }
        				echo '</div><br/>';
        				break;
        			case 3:
        				echo '<div>';
        				echo $num.'. ';
        				if(is_array($problems['options']) && count($problems['options']>0))
        				{
        					foreach ($problems['options'] as $options)
        					{
        					    echo '<label name="answer['.$problems['id'].'_'.$options['id'].']">'.$options['name'].'</label> ：';
        						echo '<input type="text" style="border:none;outline:none;border-bottom:1px solid #000;" name="answer['.$problems['id'].']['.$options['id'].']" value=""/>';
        					    echo '        ';
        					}
        				}
        		        if($problems['is_required'])
        				{
        					$is_required='必填';
        				}
        				if($problems['max_word'])
        				{
        					$max_word='最多'.$problems['max_word'].'字，';
        				}
        				if($problems['min_word'])
        				{
        					$min_word='最少'.$problems['min_word'].'字，';
        				}
        				echo ' (填空，'.$max_word.$min_word.$is_required.')';
        				echo '<br/>';
        				echo '</div><br/>';
        				break;
        			case 4:
        				echo '<div>';
        				echo $num.'. '.$problems['title'];;
        				if($problems['is_required'])
        				{
        					$is_required='必填';
        				}
        				if($problems['max_word'])
        				{
        					$max_word='最多'.$problems['max_word'].'字，';
        				}
        				if($problems['min_word'])
        				{
        					$min_word='最少'.$problems['min_word'].'字，';
        				}
        				echo ' (问答，'.$max_word.$min_word.$is_required.')';
        				echo '<textarea rows="5" cols="100"  name="answer['.$problems['id'].']">'.$problems['tips'].'</textarea>';
        				echo '<br/>';
        				echo '</div><br/>';
        				        				break;
        			default:break;
        		}
        	}
        }
        ?>
        </div>
        <div>
        林申，随云
        </div>
        <?php if($data['is_login'])
        {
        	echo '<input type="hidden" name="access_token"  value="'.$_REQUEST['access_token'].'"/>';
        }
        ?>
            <input type="submit"  value="提交"/>
        <?php //}?>
</div>
</form>