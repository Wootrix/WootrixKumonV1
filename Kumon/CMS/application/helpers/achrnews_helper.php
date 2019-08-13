<?php

function getACHRcontent()
{ 
    $rss = $this->rssparser->set_feed_url("http://www.achrnews.com/rss/19")->set_cache_life(30)->getFeed(6);

    foreach ($rss as $item)
    {
        echo $item['title'];
        
    }
    
//    $xml = simplexml_load_file("http://www.achrnews.com/rss/19");
//    $data  = xml2array($xml);//echo '<pre>';print_r($data);die;
//    //echo '<pre>';print_r($data['channel']['item'][0]);die;
//    // count($data['channel']['item']);die;
//    
//    foreach($data['channel']['item'] as $content)
//    {
//        $res['title'] = $content->title;
//        $res['author'] = $content->author;
//        $res['article_link'] = $content->link;
//        $res['publish_date'] = $content->pubDate;
//        $res['media_count'] = $content->mediaCount;
//        //$res['media']    = $content->media;
//         $final[] = $res;
//    }
//      echo '<pre>';print_r($final);
//     $xmlDoc = new DOMDocument();
//     $xmlDoc->load($xml);
//     
//     $channel=$xmlDoc->getElementsByTagName('channel')->item(0);echo '<pre>';print_r($channel);die;
//    	
//    foreach($xml>find('title') as $title)
//    {
//        $article_title = $title;echo $article_title;
//    }
//    die;
}


// function xml2array ($xmlObject, $out = array ())
//{
//    foreach ( (array) $xmlObject as $index => $node )
//        $out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;
//
//    return $out;
//}
?>