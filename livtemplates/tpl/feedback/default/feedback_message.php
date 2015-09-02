{template:head}
{css:2013/form}
{css:2013/button}
{css:feedback_form}
{if is_array($formdata)}
	{foreach $formdata AS $key => $value}
		{code}
			$$key = $value;			
		{/code}
	{/foreach}
{/if}
{code}
//print_r($formdata);
{/code}
     <header class="m2o-header">
      <div class="m2o-inner">
        <div class="m2o-title m2o-flex m2o-flex-center">
            <h1>消息回复</h1>
            <div class="m2o-m m2o-flex-one m2o-m-title">
               {$title}
            </div>
            <div class="m2o-btn">
                <span class="m2o-close option-iframe-back"></span>
            </div>
        </div>
      </div>
    </header>
    <div class="m2o-inner">
     <div class="m2o-main m2o-flex">
         <section class="m2o-m m2o-flex-one feedback-attach">
         	<div class="info-box">
         	{foreach $messages[$session_info['id']] as $k=>$msg}
         	{code}{/code}
         		{if($users[$msg['send_uid']]['utype'] == 'admin')}
					<li class="feedback-flex">
					{if $users[$msg['send_uid']]['uavatar_url']}
					<img class="service-provider-avatar" src="{$users[$msg['send_uid']]['uavatar_url']}" />
					{/if}
					<div class="feedback-flex-one">
					<span class="msg service-provider">{$msg['message']}</span>
					</div>
					</li>
				{else}
					<li>
					{if $users[$msg['send_uid']]['uavatar_url']}
					<img class="service-provider-avatar" src="{$users[$msg['send_uid']]['uavatar_url']}" />
					{/if}
					<div class="feedback-flex-one">
					<span class="msg user">{$msg['message']}</span>
					</div>
					</li>
				{/if}
         	{/foreach}
         	</div>
			<form class="m2o-form" action="run.php?mid={$_INPUT['mid']}" method="post" enctype="multipart/form-data">	
         	<div class="replay feedback-flex">
					<input class="feedback-flex-one" type="text" name=message placeholder="在此输入回复内容">
					{if $formdata}
					<input class="feedback-flex-one" type="hidden" name="a" value="send_message">
					{else}
					<input class="feedback-flex-one" type="hidden" name="a" value="add_message">
					{/if}
					<input type="hidden" name="{$primary_key}" value="{$$primary_key}" />
					<input type="submit" value="发送"/>
					<span class="replay-btn">发送</span>
				</div>
				</form>
         </section>
        </div>
     </div>
{template:foot}
