{foreach $albums_photos['photos'] as $v}<li id="photo_{$v['p_id']}">	<p><img src="{$v['photo_info']['host']}126x126/{$v['photo_info']['filepath']}" width="126" height="126" /></p>	{if $_user['id'] == $albums_photos['user_id']}	<p><a href="javascript:;" m="drop_photo" pid="{$v['p_id']}" albumsid="{$albums_photos['albums_id']}">删除</a></p>	{/if}</li>{/foreach}