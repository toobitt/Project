<?php 
/* $Id: list.php 18206 2013-03-20 02:07:46Z yizhongyue $ */
?>
{if DEVELOP_MODE}
<div style="padding:10px;color:red;font-size:14px">若有基础设置提供给用户，请增加模板tpl/{$app_uniqueid}/default/setting/base.php(此信息仅在开发模式下可见)</div>
<div style="font-size:14px;padding:4px;">模板规则如下</div>
<ul style="padding:4px;">
<li>1. 提供常量配置,配置表单name为define[常量名]，表单的value值为{$settings['define']['常量名']}</li>
<li>2. 提供变量配置,配置表单name为base[变量名]，表单的value值为{$settings['base']['变量名']}，支持二维数组</li>
<li>3. 若对变量修改前做处理，请修改接口{$app_uniqueid}/configuare.php中定义settings_process方法，此方法将input数据处理并覆盖input即可</li>
</ul>
事例如下：
<div style="padding:10px">
{$example}
</div>
{/if}