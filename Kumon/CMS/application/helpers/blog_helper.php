<?php

function getBlogData()
{
  $html = file_get_html("http://www.techaheadcorp.com/blog"); 
            
            foreach($html->find('div.blogList') as $article)
            {
                foreach($article->find('img.wp-post-image') as $image)
                {
                    $res['image_url'] = $image->src;
                }
                
                foreach($article->find('h4') as $title)
                {
                    $res['title'] = strip_tags($title); 
                    
                   //echo $title;die;
                   $link = explode('<a href=',$title);
                   $link1 = explode('"',$link[1]);
                   $res['link'] = $link1[1]; 
                }
                
                foreach($article->find('div.blogContent') as $description)
                {
                    $res['description'] = trim(strip_tags($description));
                }
                
                $final[] = $res;
            } 
            
            return $final;
}
?>