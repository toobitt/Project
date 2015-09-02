<?php
class TVie_api
{
    private $read_token;
    private $write_token;

    private $api_server_base_url;
     
    public function __construct($config = array('read_token' => '', 'write_token' => '', 'api_server_name' => ''))
    {
        $this->read_token = $config['read_token'];
        $this->write_token = $config['write_token'];
        $http_schema = '/^\s*http:\/\//i';

        $api_server_name = $config['api_server_name'];
        if (preg_match($http_schema, $config['api_server_name']) == 0)
        {
            $api_server_name = 'http://'.$config['api_server_name'];
        }

        $this->api_server_base_url = rtrim($api_server_name, '/').'/api/service/';
    }
	
   /**
    * 通过频道号查询频道信息
    */
    public function get_channel_by_id($id)
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('find');
        
        $xml->writeElement('user_key', $this->read_token);
        $xml->writeElement('action', 'find_by_id');

        $xml->startElement('select');
        $xml->writeElement('channel_id', $id);
        $xml->writeElement('quick_mode', '0');
        $xml->endElement();

        $xml->endElement();

        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/channels/find', $post_data);
        $ret = json_decode($ret,true);
        return $ret;    
    }
    /**
     * 多个ID获取频道信息
     *
     */
    function get_channel_by_ids($ids = array())
    {
    	if(!$ids || !is_array($ids))
    	{
    		return array();
    	}
    	$channel_info = array();
    	foreach ($ids as $id)
    	{
    		$channel_info[$id] = $this->get_channel_by_id($id);
    	}
    	return $channel_info;
    }
    
   /**
    * 通过频道名称查询频道
    */
    public function get_channel_name_by_id($offset = 0, $count = 100)
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('find');
        
        $xml->writeElement('user_key', $this->read_token);
        $xml->writeElement('action', 'find_by_server_id');

        $xml->startElement('select');
        $xml->writeElement('and_name');
        $xml->writeElement('or_name');
        $xml->writeElement('page_size', $count);
        $xml->writeElement('offset', $offset);
        $xml->writeElement('sort_by', 'id');
        $xml->writeElement('sort_order', 'ASC');
        $xml->writeElement('quick_mode', '0');
        $xml->endElement();

        $xml->endElement();

        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/channels/find', $post_data);
        $ret = json_decode($ret,true);
        return $ret;    
    }
   /**
    * 通过参照 ID 获取频道信息
    */
    public function get_channel_reference_by_id($offset = 0, $count = 100)
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('find');
        
        $xml->writeElement('user_key', $this->read_token);
        $xml->writeElement('action', 'find_by_reference_id');

        $xml->startElement('select');
        $xml->writeElement('reference_id');
        $xml->writeElement('quick_mode', '0');
        $xml->endElement();

        $xml->endElement();

        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/channels/find', $post_data);
        $ret = json_decode($ret,true);
        return $ret;    
    }
	/**
	 * 获取所有媒体服务器
	 */
    public function get_all_servers($offset = 0, $count = 100)
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('find');
        
        $xml->writeElement('user_key', $this->read_token);
        $xml->writeElement('action', 'find_all_servers');

        $xml->startElement('select');
        $xml->writeElement('page_size', $count);
        $xml->writeElement('offset', $offset);
        $xml->writeElement('sort_by', 'id');
        $xml->writeElement('sort_order', 'ASC');
        $xml->endElement();

        $xml->endElement();

        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/servers/find', $post_data);
        $ret = json_decode($ret,true);
        return $ret;    
    }
	
   /**
    * 获取所有频道
    */
    public function get_all_channels($offset = 0, $count = 100)
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('find');
        
        $xml->writeElement('user_key', $this->read_token);
        $xml->writeElement('action', 'find_all_channels');

        $xml->startElement('select');
        $xml->writeElement('page_size', $count);
        $xml->writeElement('offset', $offset);
        $xml->writeElement('sort_by', 'id');
        $xml->writeElement('sort_order', 'ASC');
        $xml->writeElement('quick_mode', '0');
        $xml->endElement();

        $xml->endElement();

        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/channels/find', $post_data);
		echo $ret;
        $ret = json_decode($ret,true);
        return $ret;    
    }

    public function get_channel_epg()
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('find');
        
        $xml->writeElement('user_key', $this->read_token);
        $xml->writeElement('action', 'find_all_channels');

        $xml->startElement('select');
        $xml->writeElement('page_size', $count);
        $xml->writeElement('offset', $offset);
        $xml->writeElement('sort_by', 'id');
        $xml->writeElement('sort_order', 'ASC');
        $xml->writeElement('quick_mode', '0');
        $xml->endElement();

        $xml->endElement();

        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/channels/find', $post_data);
        $ret = json_decode($ret,true);
        return $ret;    
	}
	
   /**
    * 创建EPG
+-----------------+------------------------------------+------+-----+-------------------+----------------+
| Field           | Type                               | Null | Key | Default           | Extra          |
+-----------------+------------------------------------+------+-----+-------------------+----------------+
| id              | int(11)                            | NO   | PRI | NULL              | auto_increment | 
| name            | varchar(255)                       | NO   |     | NULL              |                | 
| type            | varchar(50)                        | NO   |     | default           |                | 
| description     | text                               | YES  |     | NULL              |                | 
| start_time      | datetime                           | NO   |     | NULL              |                | 
| end_time        | datetime                           | NO   |     | NULL              |                | 
| channel_id      | int(11)                            | NO   |     | NULL              |                | 
| uri             | varchar(255)                       | NO   |     | NULL              |                | 
| offset          | int(11)                            | YES  |     | 0                 |                | 
| duration        | int(11)                            | YES  |     | NULL              |                | 
| source_type     | enum('vod','live','time_shifting') | NO   |     | vod               |                | 
| uptodate        | smallint(1)                        | NO   |     | 1                 |                | 
| create_time     | timestamp                          | NO   |     | CURRENT_TIMESTAMP |                | 
| source_duration | int(11)                            | NO   |     | NULL              |                | 
+-----------------+------------------------------------+------+-----+-------------------+----------------+
name: 节目名称
type: 没有实际意义，默认为default
description: 节目描述
start_time: 节目开始时间，切播程序会使用，在这个时间点会切入当前这条记录的视频
end_time: 节目结束时间
channel_id: 要切播的频道ID
uri: 切播视频的地址，http/tvie/……
offset: 没有使用
duration: 结束时间减起始时间
source_type: 当前只能使用vod，其他几个暂时有问题
uptodate: 1为有效，0为无效
create_time: 没有使用
source_duration: 没有使用

    */
    public function create_channel_epg($channel_id, $start_time, $end_time, $uri, $name = '切播',  $uptodate = 1, $description = '')
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('channel_epg');
	        $xml->writeElement('user_key', $this->write_token);
	        $xml->writeElement('name', $name);
	        $xml->writeElement('description', $description);
	        $xml->writeElement('start_time', date('Y-m-d H:i:s', $start_time));
	        $xml->writeElement('end_time', date('Y-m-d H:i:s', $end_time));
	        $xml->writeElement('channel_id', $channel_id);
	        $xml->writeElement('uri', $uri);
	        $xml->writeElement('duration', ($end_time - $start_time));
	        $xml->writeElement('type', 'default');
	        $xml->writeElement('source_type', 'vod');
	        $xml->writeElement('uptodate', $uptodate);
	    $xml->endElement();
        
        $xml->endDocument();

       $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/channel_epg', $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }

    public function update_channel_epg($channel_id, $epgid, $start_time, $end_time, $uri, $name = '切播',  $uptodate = 1, $description = '')
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('channel_epg');
	        $xml->writeElement('user_key', $this->write_token);
	        $xml->writeElement('epgid', $epgid);
	        $xml->writeElement('name', $name);
	        $xml->writeElement('description', $description);
	        $xml->writeElement('start_time', date('Y-m-d H:i:s', $start_time));
	        $xml->writeElement('end_time', date('Y-m-d H:i:s', $end_time));
	        $xml->writeElement('channel_id', $channel_id);
	        $xml->writeElement('uri', $uri);
	        $xml->writeElement('duration', ($end_time - $start_time));
	        $xml->writeElement('type', 'default');
	        $xml->writeElement('source_type', 'vod');
	        $xml->writeElement('uptodate', $uptodate);
	    $xml->endElement();
        
        $xml->endDocument();

       $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/channel_epg/update', $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }
    public function delete_channel_epg( $epgid)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('channel_epg');
	        $xml->writeElement('user_key', $this->write_token);
	        $xml->writeElement('epgid', $epgid);
	    $xml->endElement();
        
        $xml->endDocument();

       $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/channel_epg/delete', $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }

    public function get_channel_epg_by_id($epgid)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('channel_epg');
	        $xml->writeElement('user_key', $this->read_token);
	        $xml->writeElement('epgid', $epgid);
	    $xml->endElement();
        
        $xml->endDocument();

       $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/channel_epg/get_epg_by_id', $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }

   /**
    * 创建频道
    */
    public function create_channel(
    								$channel_name = "",
    								$display_name = "",
    								$server_id = "",
    								$save_time = "",
    								$live_delay = "",
    								$type = "",
    								$name = "",
    								$recover_cache = "",
    								$source_name = "",
    								$uri = "",
    								$bitrate = "",
    								$drm = "",
    								$wait_relay = "",
    								$backstore = "",
    								$reference_id = "",
    								$uri_format = "",
    								$logo_active = "",
    								$logo_normal = ""
    								)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('channel');
        
	        $xml->writeElement('user_key', $this->write_token);
	        $xml->writeElement('channel_name', $channel_name);
	        $xml->writeElement('display_name', $display_name);
	        $xml->writeElement('reference_id', $reference_id);
	        $xml->writeElement('server_id', $server_id);
	        $xml->writeElement('save_time', $save_time);
	        $xml->writeElement('live_delay', $live_delay);
	        $xml->writeElement('logo_active', $logo_active);
	        $xml->writeElement('logo_normal', $logo_normal);
	        $xml->writeElement('type', $type);
	        $xml->writeElement('publish_state', 'ACTIVE');
	        $xml->startElement('streams');
	        	$xml->startElement('stream');
			        
			        $xml->writeElement('name', $name);
			        $xml->writeElement('recover_cache', $recover_cache);
			        $xml->writeElement('source_name', $source_name);
			        $xml->writeElement('uri', $uri);
			        $xml->writeElement('bitrate', $bitrate);
			        $xml->writeElement('drm', $drm);
			        $xml->writeElement('wait_relay', $wait_relay);
			        //$xml->writeElement('uri_format', $uri_format);
			        $xml->writeElement('backstore', $backstore);
			       // $xml->writeElement('uri_names', '');
	        
	        	$xml->endElement();
	        $xml->endElement();
	    $xml->endElement();
        
        $xml->endDocument();

       $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/channels', $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }
   /**
    * 给频道添加流
    */
    public function create_channel_stream(
    								$name = "",
    								$recover_cache = "",
    								$source_name = "",
    								$uri = "",
    								$drm = "",
    								$backstore = "",
    								$wait_relay = "",
    								$audio_only = "",
    								$bitrate = "",
    								$channel_id,
    								$uri_format = "",
    								$uri_names = ""
    								)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('stream');
      
	        $xml->writeElement('user_key', $this->write_token);
	   		$xml->writeElement('name', $name);
	        $xml->writeElement('recover_cache', $recover_cache);
	        $xml->writeElement('source_name', $source_name);
	        $xml->writeElement('uri', $uri);
	        $xml->writeElement('drm', $drm);
	        $xml->writeElement('backstore', $backstore);
	        $xml->writeElement('wait_relay', $wait_relay);
	 //       $xml->writeElement('uri_format', $uri_format);
	  //      $xml->writeElement('uri_names', $uri_names);
	        $xml->writeElement('audio_only', $audio_only);
        	$xml->writeElement('bitrate', $bitrate);
        	$xml->endElement();
        
        $xml->endDocument();

       	$post_data = $xml->outputMemory();
  
		$ret = $this->post($this->api_server_base_url.'media/channels/streams/' . $channel_id, $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }
   /**
    * 修改流
    */
    public function update_channel_stream(
    								$id,
    								$drm = "",
    								$backstore = "",
    								$recover_cache = "",
    								$uri = "",
    								$audio_only = "",
    								$wait_relay = "",
    								$source_name = "",
    								$save_time = "",
    								$uri_format = "",
    								$uri_names = ""
    								)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);


        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('stream');
      
	        $xml->writeElement('user_key', $this->write_token);
	   		$xml->writeElement('id', $id);
	        $xml->writeElement('recover_cache', $recover_cache);
	        $xml->writeElement('source_name', $source_name);
	        $xml->writeElement('uri', $uri);
	        $xml->writeElement('drm', $drm);
	        $xml->writeElement('backstore', $backstore);
	        $xml->writeElement('wait_relay', $wait_relay);
	 //       $xml->writeElement('uri_format', $uri_format);
	  //      $xml->writeElement('uri_names', $uri_names);
	        $xml->writeElement('audio_only', $audio_only);
        	$xml->writeElement('save_time', $save_time);
        	$xml->endElement();
        
        $xml->endDocument();

       	$post_data = $xml->outputMemory();
  
		$ret = $this->post($this->api_server_base_url.'media/channels/streams/metadata', $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }
    /**
     * 删除流
     */
    public function delete_stream($stream_id)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        
        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('handle');
        
        $xml->writeElement('user_key', $this->write_token);
        $xml->writeElement('action', 'delete');
	        
	    $xml->endElement();
        
        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/channels/streams/handle/' . $stream_id, $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }
    /**
     * 启动流
     */
    public function start_stream($stream_id)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        
        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('handle');
        
        $xml->writeElement('user_key', $this->write_token);
        $xml->writeElement('action', 'start');
	        
	    $xml->endElement();
        
        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/channels/streams/handle/' . $stream_id, $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }
	
    /**
     * 停止流
     */
    public function stop_stream($stream_id)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        
        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('handle');
        
        $xml->writeElement('user_key', $this->write_token);
        $xml->writeElement('action', 'stop');
	        
	    $xml->endElement();
        
        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/channels/streams/handle/' . $stream_id, $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }
	
    /**
     * 重启流
     */
    public function restart_stream($stream_id)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        
        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('handle');
        
        $xml->writeElement('user_key', $this->write_token);
        $xml->writeElement('action', 'restart');
	        
	    $xml->endElement();
        
        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/channels/streams/handle/' . $stream_id, $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }
	

    /**
     * 
     *修改频道
     */
    public function update_channel(
    								$display_name = "",
    								$save_time = "",
    								$live_delay = "",
    								$channel_id,
    								$reference_id = "",
    								$logo_active = "",
    								$logo_normal = ""
    								)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('channel');
        
	        $xml->writeElement('user_key', $this->write_token);
	        $xml->writeElement('reference_id', $reference_id);
	        $xml->writeElement('display_name', $display_name);
	        $xml->writeElement('save_time', $save_time);
	        $xml->writeElement('live_delay', $live_delay);
	        $xml->writeElement('logo_active', $logo_active);
	        $xml->writeElement('logo_normal', $logo_normal);
	        
	    $xml->endElement();
        
        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/channels/metadata/' . $channel_id, $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }
	
   	/**
     * 删除频道
     */
    public function delete_channel($channel_id)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        
        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('handle');
        
        $xml->writeElement('user_key', $this->write_token);
        $xml->writeElement('action', 'delete');
	        
	    $xml->endElement();
        
        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/channels/handle/' . $channel_id, $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }
	
   	/**
     * 发布频道
     */
    public function active_channel($channel_id)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        
        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('handle');
        
        $xml->writeElement('user_key', $this->write_token);
        $xml->writeElement('action', 'active');
	        
	    $xml->endElement();
        
        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/channels/handle/' . $channel_id, $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }
	
	
   	/**
     * 取消发布频道
     */
    public function deactive_channel($channel_id)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        
        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('handle');
        
        $xml->writeElement('user_key', $this->write_token);
        $xml->writeElement('action', 'deactive');
	        
	    $xml->endElement();
        
        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/channels/handle/' . $channel_id, $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }
    
  /**
    * 添加媒体服务服务器
    */
    public function create_server(
    								$name = "",
    								$type = "",
    								$ip_address = "",
    								$host_name = "",
    								$token = ""
    								)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('server');
      
	        $xml->writeElement('user_key', $this->write_token);
	   		$xml->writeElement('name', $name);
	        $xml->writeElement('type', $type);
	        $xml->writeElement('ip_address', $ip_address);
	        $xml->writeElement('host_name', $host_name);
	        $xml->writeElement('token', $token);
	        
        $xml->endElement();
        
        $xml->endDocument();

       	$post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/servers', $post_data);
		$ret = json_decode($ret);
        return $ret;
    }
	
  /**
    * 修改媒体服务器信息
    */
    public function update_server(
    								$name = "",
    								$type = "",
    								$ip_address = "",
    								$host_name = "",
    								$server_id
    								)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('server');
      
	        $xml->writeElement('user_key', $this->write_token);
	   		$xml->writeElement('name', $name);
	        $xml->writeElement('type', $type);
	        $xml->writeElement('ip_address', $ip_address);
	        $xml->writeElement('host_name', $host_name);
	        
        $xml->endElement();
        
        $xml->endDocument();

       	$post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/servers/update/' . $server_id, $post_data);
		$ret = json_decode($ret);
        return $ret;
    }
	
	/**
     * 删除媒体服务器
     */
    public function delete_server($server_id)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        
        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('server');
        
        $xml->writeElement('user_key', $this->write_token);
        $xml->writeElement('action', 'delete');
	        
	    $xml->endElement();
        
        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/servers/handle/' . $server_id, $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }
	
	/**
     * 启动媒体服务器守护进程
     */
    public function start_shepherd($server_id)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        
        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('server');
        
        $xml->writeElement('user_key', $this->write_token);
        $xml->writeElement('action', 'start_shepherd');
	        
	    $xml->endElement();
        
        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/servers/handle/' . $server_id, $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }
	
	/**
     * 停止媒体服务器守护进程
     */
    public function stop_shepherd($server_id)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        
        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('server');
        
        $xml->writeElement('user_key', $this->write_token);
        $xml->writeElement('action', 'stop_shepherd');
	        
	    $xml->endElement();
        
        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/servers/handle/' . $server_id, $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }
	
	/**
     * 更换License
     */
    public function update_license($data, $server_id)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        
        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('server');
        
        $xml->writeElement('user_key', $this->write_token);
        $xml->writeElement('action', 'update_license');
        $xml->writeElement('data', $data);
	        
	    $xml->endElement();
        
        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/servers/handle/' . $server_id, $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }
	
	/**
     * 根据 ID 查找媒体服务器
     */
    public function find_server_by_id($id)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        
        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('find');
        
        $xml->writeElement('user_key', $this->write_token);
        $xml->writeElement('action', 'find_server_by_id');
        $xml->writeElement('id', $id);
	        
	    $xml->endElement();
        
        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/servers/find', $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }
	
	/**
     * 查看媒体服务器系统资源使用情况
     */
    public function find_server_status_by_id($id)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        
        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('find');
        
        $xml->writeElement('user_key', $this->write_token);
        $xml->writeElement('action', 'find_server_status_by_id');
        $xml->writeElement('id', $id);
	        
	    $xml->endElement();
        
        $xml->endDocument();

        $post_data = $xml->outputMemory();
		$ret = $this->post($this->api_server_base_url.'media/servers/find', $post_data);
		$ret = json_decode($ret,true);
        return $ret;
    }
	
   /**
    * 
    */
   
	private function post_file($url, $post_data)
	{
		$ch = curl_init();

        //var_dump($post_data);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

        $ret = curl_exec($ch);

        //if any network problem encounted, return the same format of error message as studio api
        if ($ret == null)
        {
        	$ret = '{"error_type" : "1" , "message": "网络错误", "errors": "视频上传到流媒体服务器失败！"}';
        }
        return $ret;
	}

    private function post($url, $post_data)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml; charset=utf-8"));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         
        $ret = curl_exec($ch);

        //if any network problem encounted, return the same format of error message as studio api
        if ($ret == null)
        {
        	$ret = '{"error_type" : "1" , "message": "网络错误", "errors": "连不上服务器"}';
        }
        
        return $ret;
    }
    
    private function put($url, $put_data)
    {
    	fwrite($fh, $put_data);
    	rewind($fh);
    	$ch = curl_init();
    	
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_PUT, true);
    	curl_setopt($ch, CURLOPT_INFILE, $fh);
    	curl_setopt($ch, CURLOPT_INFILESIZE, strlen($put_data));
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	
    	$ret = curl_exec($ch);
        //if any network problem encounted, return the same format of error message as studio api
        if ($ret == null)
        {
        	$ret = '{"error_type" : "1" , "message": "网络错误", "errors": "连不上服务器"}';
        }

        return $ret;
    }
    
    private function get($url)
    {
    	$ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $ret = curl_exec($ch);

        //if any network problem encounted, return the same format of error message as studio api
        if ($ret == null)
        {
        	$ret = '{"error_type" : "1" , "message": "网络错误", "errors": "连不上服务器"}';
        }

        return $ret;
    }
    
  
    
    private function handle_transcode_config($config_id, $action)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('handle');
        
        $xml->writeElement('user_key', $this->write_token);
        $xml->writeElement('action', $action);

        $xml->endElement();

        $xml->endDocument();

        $post_data = $xml->outputMemory();
        $ret = $this->post($this->api_server_base_url.'media/videos/handle_config/'.$config_id, $post_data);
        $ret = json_decode($ret);
        return $ret;
    }
}

?>