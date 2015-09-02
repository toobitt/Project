<?php

class videoopXML {
    
    private $webSite        = '';
    private $email          = '';
    private $updatePeri     = '';
    private $recordContent  = array();
    
    public function __construct($webSite, $updatePeri, $email = '', $recordContent = array()) {
        $this->webSite = $webSite;
        $this->updatePeri = $updatePeri;
        $this->email = $email;
        $this->recordContent = $recordContent;
    }
    
    public function __destruct() {
        
    }
    
    public function getRecordXML() {
        $xml = "<?xml version=\"1.0\" encoding=\"utf-8\" ?>\n";
        $xml .= "<document>\n";
        $xml .= "<webSite>{$this->webSite}</webSite>\n";
        $xml .= "<webMaster>{$this->email}</webMaster>\n";
        $xml .= "<updatePeri>{$this->updatePeri}</updatePeri>\n";
        if (is_array($this->recordContent) && count($this->recordContent) > 0) {
            foreach ($this->recordContent as $record) {
                if (empty($record)) {
                    continue;
                }
                $record['playLink'] = $record['content_url'];
                $record['imageLink'] = hg_fetchimgurl($record['video']['indexpic']);
                $record['videoLink'] = $record['video']['host'] . $record['video']['dir'] . $record['video']['filepath'] .$record['video']['filename'];
                $record['tag'] = xml_filter($record['keywords']);
                $record['comment'] = xml_filter($record['brief']);
                $record['pubDate'] = date('Y-m-d H:i:s',$record['create_time']);
                $record['duration'] = $record['video']['duration'];
                $xml .= "<item>\n";
                   $xml .= "<op>{$record['op']}</op>\n";
                   if ($record['title'])
                        $xml .= "<title><![CDATA[ {$record['title']} ]]></title>\n";
                   if ($record['category'])
                        $xml .= "<category><![CDATA[ {$record['category']} ]]></category>\n";
                   if ($record['playLink'])
                        $xml .= "<playLink><![CDATA[ {$record['playLink']} ]]></playLink>\n";
                   if ($record['imageLink']) 
                        $xml .= "<imageLink><![CDATA[ {$record['imageLink']} ]]></imageLink>\n";
                   if ($record['videoLink'])
                        $xml .= "<videoLink><![CDATA[ {$record['videoLink']} ]]></videoLink>\n";
                   if ($record['userid']) 
                        $xml .= "<userid><![CDATA[ {$record['userid']} ]]></userid>\n";
                   if ($record['userurl']) 
                        $xml .= "<userurl><![CDATA[ {$record['userurl']} ]]></userurl>\n";
                   if ($record['playNum'])
                        $xml .= "<playNum><![CDATA[ {$record['playNum']} ]]></playNum>\n";
                   if ($record['definition'])     
                        $xml .= "<definition><![CDATA[ {$record['definition']} ]]></definition>\n";
                   if ($record['tag'])  { 
                        $tag = explode(",", $record['tag']);  
                        foreach($tag as $kkk => $vvv)
                        {
                            $item_list .="<tag><![CDATA[".$vvv."]]></tag>\n";
                        }                       
                   }
                   if ($record['comment'])     
                        $xml .= "<comment><![CDATA[ {$record['comment']} ]]></comment>\n";
                   if ($record['pubDate'])
                        $xml .= "<pubDate><![CDATA[ {$record['pubDate']} ]]></pubDate>\n";
                   if ($record['duration'])     
                        $xml .= "<duration><![CDATA[ {$record['duration']} ]]></duration>\n";
                   if ($record['avg_p_duration'])     
                        $xml .= "<avg_p_duration><![CDATA[ {$record['avg_p_duration']} ]]></avg_p_duration>\n";
                $xml .= "</item>\n";
            } 
        }
        $xml .= "</document>\n";
        return $xml;
    }
}




?>