<?php
class airline
{
    public function search_airline($post_data)
	{
		if(!$post_data)
		{
			return false;
		}
		$data = postCurl(AIRLINE_API,$post_data);
		if(!$data)
		{
			return false;
		}
		return $data;
	}
}