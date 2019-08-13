<?php
function gettutsPlusContent()
{
  $result_final = array();
$i = 1;
while($i<=26)
{
$opts = array('http'=>array('method'=>"GET",'header'=>"Accept-language: en\r\n" ."User-Agent: not for you\r\n"));
$context = stream_context_create($opts);
$url = "http://tutsplus.com/courses?page=$i";
$html = file_get_html($url, false, $context);


    foreach($html->find('li.products__course') as $e)
     {
          
            foreach($e->find('img') as $image)
            {
                $image = $image->src ;
                $res['image_path'] = trim($image); 
            }
            
            foreach($e->find('h3.products__title') as $title)
            {
                $title = $title;
                $title = strip_tags(str_replace(' ', ' ', $title));
                $res['title'] = $title;
            }
            
            foreach($e->find('a.products__course-link') as $course_link)
            {
                $res['course_url'] = $course_link->href;
                
                $url = $course_link->href;
                $opts = array('http'=>array('method'=>"GET",'header'=>"Accept-language: en\r\n" ."User-Agent: not for you\r\n"));
                $context = stream_context_create($opts);                
                $course_html = file_get_html($url, false, $context);
                $author = $course_html->find('a.content-header__author-link');
                $author_name = implode($author);
                $res['instructor_name'] = strip_tags($author_name);
                
                $description = $course_html->find('p');
                $course_description = implode($description);
                $res['course_full_description'] = strip_tags($course_description);
            }
            
            foreach($e->find('div.products__primary-topic') as $topic_code)
            {
                $topic_code = $topic_code;
                $topic_code = strip_tags(str_replace(' ', ' ', $topic_code));
                $res['course_topic'] = $topic_code;
                
            }
            
            foreach($e->find('time.products__publication-date') as $release_date)
            {
                $release_date = $release_date->title;
                $release_date = strip_tags(str_replace(' ', ' ', $release_date));
                $res['released_date'] = $release_date; 
            }

            foreach($e->find('div.products__duration') as $length_of_course)
            {
                $length_of_course = $length_of_course;
                $length_of_course = strip_tags(str_replace(' ', ' ', $length_of_course));
                $res['course_length'] = $length_of_course;
            }
         
            $result_final[]= $res;
            
       } 
     $i++;
    }
     return $result_final;
}

                                      
?>
