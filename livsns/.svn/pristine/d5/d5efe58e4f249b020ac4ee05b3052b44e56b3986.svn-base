<?php
class content extends InitFrm
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __destruct()
	{
		parent::__destruct();
	}
	public function get_published_content($condi)
	{
		include_once(ROOT_PATH  . 'lib/class/publishcontent.class.php');
		$publish_server = new publishcontent();
		return $publish_server->get_content($condi);
	}
	public function get_published_content_byid($id)
	{
		include_once(ROOT_PATH  . 'lib/class/publishcontent.class.php');
		$publish_server = new publishcontent();
		return $publish_server->get_content_by_rid($id);
	}
	public function get_published_column($condition = '')
	{
		include_once(ROOT_PATH  . 'lib/class/publishconfig.class.php');
		$publish_server = new publishconfig();
		return $publish_server->get_column('*', $condition);
	}
	public function get_site()
	{
		include_once(ROOT_PATH  . 'lib/class/publishconfig.class.php');
		$publish_server = new publishconfig();
		return $publish_server->get_site();
	}
}