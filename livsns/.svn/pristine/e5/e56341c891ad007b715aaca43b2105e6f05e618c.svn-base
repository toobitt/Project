<?php
class TVie_video_api
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
    * 
    * @return json decoded object
    */
    public function create_video($source="",
                                 $notify="",
                                 $transcoder = "TVie",
                                 $name="",
                                 $reference_id="",
                                 $thumbnail_url="",
                                 $video_still_url="",
                                 $short_description="",
                                 $description="",
                                 $tags=array())
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('video');
        
        $xml->writeElement('user_key', $this->write_token);
        $xml->writeElement('source', $source);
        $xml->writeElement('notify', $notify);
        $xml->writeElement('transcoder', $transcoder);
        $xml->writeElement('reference_id', $reference_id);
        $xml->writeElement('name', $name);
        $xml->writeElement('thumbnail_url', $thumbnail_url);
        $xml->writeElement('video_still_url', $video_still_url);
        $xml->writeElement('short_description', $short_description);
        $xml->writeElement('description', $description);
        $xml->writeElement('tags', implode(',', $tags));

        $xml->endElement();

        $xml->endDocument();

        $post_data = $xml->outputMemory();

        $ret = json_decode($this->post($this->api_server_base_url.'media/videos', $post_data));
        return $ret;
    }

	public function upload_video($source="",
	                             $notify="",
								 $reference_id="",
								 $video_name="",
								 $thumbnail_url="",
								 $video_still_url="",
								 $short_description="",
								 $description="",
								 $tags="",
								 $transcoder="TVie")
	{
		$post_data = array(
		             'user_key' => $this->write_token,
					 'source' => '@'.$source,
					 'notify' => $notify,
					 'reference_id' => $reference_id,
					 'name' => $video_name,
					 'thumbnail_url' => $thumbnail_url,
					 'video_still_url' => $video_still_url,
					 'short_description' => $short_description,
					 'description' => $description,
					 'tags' => $tags,
					 'transcoder' => $transcoder
		             );

        $ret = $this->post_file($this->api_server_base_url.'media/videos/upload', $post_data);
        return $ret;
	}

    /**
    *
    *
    *@return json decoded object
    */
    public function update_video($video_id = -1,
                                 $reference_id = "",
                                 $name = "",
                                 $thumbnail_url = "",
                                 $video_still_url = "",
                                 $short_description = "",
                                 $description = "",
                                 $tags = array())
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('video');
        
        $xml->writeElement('user_key', $this->write_token);
        $xml->writeElement('reference_id', $reference_id);
        $xml->writeElement('name', $name);
        $xml->writeElement('thumbnail_url', $thumbnail_url);
        $xml->writeElement('video_still_url', $video_still_url);
        $xml->writeElement('short_description', $short_description);
        $xml->writeElement('description', $description);
        $xml->writeElement('tags', implode(',', $tags));

        $xml->endElement();

        $xml->endDocument();

        $post_data = $xml->outputMemory();

        //$ret = $this->post($this->api_server_base_url.'media/videos/metadata/'.$video_id, $post_data);
              
        $ret = json_decode($this->post($this->api_server_base_url.'media/videos/metadata/'.$video_id, $post_data));
        return $ret;
    }

    /**
    *
    *@return
    */
    public function delete_video($video_id = -1)
    {
    	return $this->handle_video($video_id, 'delete');
    }
    
    public function publish_video($video_id = -1)
    {
    	return $this->handle_video($video_id, 'active');
    }
    
    public function unpublish_video($video_id = -1)
    {
    	return $this->handle_video($video_id, 'deactive');
    }

    /**
    * @param fields if fields equals '', return all information, else return the specified fields, field is divided by comma
    * @return false if error happened, else return a video object
    */
    public function find_video_by_id($video_id = -1)
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('video');
        
        $xml->writeElement('user_key', $this->read_token);
        $xml->writeElement('action', 'find_by_id');

        $xml->startElement('select');
        $xml->writeElement('video_id', $video_id);
        $xml->endElement();

        $xml->endElement();

        $xml->endDocument();


        $post_data = $xml->outputMemory();

        $ret = json_decode($this->post($this->api_server_base_url.'media/videos/find', $post_data));
        return $ret;    
    }

    //TODO: fill default $sort_order;
    public function get_all_videos($page_size=20, $offset=0, $sort_by='id', $sort_order='DESC', $status = 'all')
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('video');
        
        $xml->writeElement('user_key', $this->read_token);
        $xml->writeElement('action', 'find_all_videos');

        $xml->startElement('select');
        $xml->writeElement('page_size', $page_size);
        $xml->writeElement('offset', $offset);
        $xml->writeElement('sort_by', $sort_by);
        $xml->writeElement('sort_order', $sort_order);
        $xml->writeElement('status', $status);

        $xml->endElement();

        $xml->endElement();

        $xml->endDocument();

        $post_data = $xml->outputMemory();

        $ret = json_decode($this->post($this->api_server_base_url.'media/videos/find', $post_data));
        return $ret;
    }

    public function find_videos_by_names($and_name='',
                                        $or_name='',
                                        $page_size=20,
                                        $offset=0,
                                        $sort_by='name',
                                        $sort_order='DESC',
                                        $status = 'all')
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('video');
        
        $xml->writeElement('user_key', $this->read_token);
        $xml->writeElement('action', 'find_by_name');

        $xml->startElement('select');
        $xml->writeElement('and_name', $and_name);
        $xml->writeElement('or_name', $or_name);
        $xml->writeElement('page_size', $page_size);
        $xml->writeElement('offset', $offset);
        $xml->writeElement('sort_by', $sort_by);
        $xml->writeElement('sort_order', $sort_order);
        $xml->writeElement('status', $status);
        $xml->endElement();

        $xml->endElement();

        $xml->endDocument();


        $post_data = $xml->outputMemory();

        $ret = json_decode($this->post($this->api_server_base_url.'media/videos/find', $post_data));
        return $ret;
    }

    public function find_videos_by_tags($and_tags='',
                                       $or_tags='',
                                       $page_size=20,
                                       $offset=0,
                                       $sort_by='id',
                                       $sort_order='DESC',
                                       $status = 'all')
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('video');
        
        $xml->writeElement('user_key', $this->read_token);
        $xml->writeElement('action', 'find_by_tags');

        $xml->startElement('select');
        $xml->writeElement('and_tags', $and_tags);
        $xml->writeElement('or_tags', $or_tags);
        $xml->writeElement('page_size', $page_size);
        $xml->writeElement('page_number', $offset);
        $xml->writeElement('sort_by', $sort_by);
        $xml->writeElement('sort_order', $sort_order);
        $xml->writeElement('status', $status);
        $xml->endElement();

        $xml->endElement();

        $xml->endDocument();


        $post_data = $xml->outputMemory();

        $ret = json_decode($this->post($this->api_server_base_url.'media/videos/find', $post_data));
        return $ret;
    }

    public function find_videos_by_ref_id($reference_id='')
    {
        $xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('video');
        
        $xml->writeElement('user_key', $this->read_token);
        $xml->writeElement('action', 'find_by_reference_id');

        $xml->startElement('select');
        $xml->writeElement('reference_id', $reference_id);
        $xml->endElement();

        $xml->endElement();

        $xml->endDocument();

        $post_data = $xml->outputMemory();

        $ret = json_decode($this->post($this->api_server_base_url.'media/videos/find', $post_data));
        return $ret;
    }
    
    public function get_transcode_config()
    {
    	$ret = json_decode($this->get($this->api_server_base_url.'media/videos/config/'.$this->read_token));
    	return $ret;
    }
    
    public function add_transcode_config($output,
                                         $video_codec,
                                         $profile,
                                         $width,
                                         $height,
                                         $bitrate,
                                         $audio_bitrate,
                                         $fps,
                                         $gop
                                         )
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('config');
        
        $xml->writeElement('user_key', $this->write_token);

        $xml->startElement('format');
        $xml->writeElement('output', $output);
        $xml->writeElement('video_codec', $video_codec);
        $xml->writeElement('profile', $profile);
        $xml->writeElement('width', $width);
        $xml->writeElement('height', $height);
        $xml->writeElement('bitrate', $bitrate);
        $xml->writeElement('audio_bitrate', $audio_bitrate);
        $xml->writeElement('fps', $fps);
        $xml->writeElement('gop', $gop);
        $xml->endElement();

        $xml->endElement();

        $xml->endDocument();

        $post_data = $xml->outputMemory();
        
        $ret = json_decode($this->put($this->api_server_base_url.'media/videos/config/', $post_data));

    	return $ret;
    }
    
    public function edit_transcode_config($config_id,
                                          $output,
                                          $video_codec,
                                          $profile,
                                          $width,
                                          $height,
                                          $bitrate,
                                          $audio_bitrate,
                                          $fps,
                                          $gop
                                          )
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('config');
        
        $xml->writeElement('user_key', $this->write_token);
        $xml->writeElement('config_id', $config_id);

        $xml->startElement('format');
        $xml->writeElement('output', $output);
        $xml->writeElement('video_codec', $video_codec);
        $xml->writeElement('profile', $profile);
        $xml->writeElement('width', $width);
        $xml->writeElement('height', $height);
        $xml->writeElement('bitrate', $bitrate);
        $xml->writeElement('audio_bitrate', $audio_bitrate);
        $xml->writeElement('fps', $fps);
        $xml->writeElement('gop', $gop);
        $xml->endElement();

        $xml->endElement();

        $xml->endDocument();

        $post_data = $xml->outputMemory();

        $ret = json_decode($this->post($this->api_server_base_url.'media/videos/config/', $post_data));
    	return $ret;
    }
    
    public function delete_transcode_config($config_id)
    {
    	$ret = $this->handle_transcode_config($config_id, 'delete');
    	return $ret;
    }
    
    public function active_transcode_config($config_id)
    {
    	$ret = $this->handle_transcode_config($config_id, 'active');
    	return $ret;
    }
    
    public function deactive_transcode_config($config_id)
    {
    	$ret = $this->handle_transcode_config($config_id, 'deactive');
    	return $ret;
    }

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
    
    private function handle_video($video_id, $action)
    {
    	$xml = new XMLWriter();
        $xml->openMemory();
        $xml->setIndent(true);

        $xml->startDocument('1.0', 'utf-8');
        
        $xml->startElement('video');
        
        $xml->writeElement('user_key', $this->write_token);
        $xml->writeElement('action', $action);

        $xml->endElement();

        $xml->endDocument();

        $post_data = $xml->outputMemory();

        $ret = json_decode($this->post($this->api_server_base_url.'media/videos/handle/'.$video_id, $post_data));
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
        
        $ret = json_decode($this->post($this->api_server_base_url.'media/videos/handle_config/'.$config_id, $post_data));
        return $ret;
    }
}

?>