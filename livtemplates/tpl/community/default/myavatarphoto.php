<hr class="hr_pic_lv">{if $avatar_imgs}<ul class="my_pic_list" id="pic_list">	{code}$k = 0;{/code}	{foreach $avatar_imgs as $v}	<li{if $k==0} class="one"{/if}><a href="member.php?uid={$v['id']}"><img src="{$v['avatar']['host']}{$v['avatar']['dir']}126x126/{$v['avatar']['filepath']}{$v['avatar']['filename']}" /></a></li>	{/foreach}</ul>{else}<p>暂没有信息</p>{/if}