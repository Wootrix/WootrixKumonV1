<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
//*************function to fetch the content of iversity website***************//

 function getIversityContent()
 {  
     $result_final =array();
   
   $html=file_get_html('https://iversity.org/courses');
    
    foreach($html->find('div.row') as $key=>$e)
     {    
           $res = array();
           
        foreach ($e->find('a.hover-link') as $key=>$link) {
                                
            $url = $link->href;
            $res[$key]['course_url'] = "https://iversity.org" . $url;
            
            $course_link = "https://iversity.org" . $url;

            if (!empty($course_link)) {

                $course_html = file_get_html($course_link);
           
                if (!empty($course_html)) {

                    $about_course = $course_html->find('p');
                      
                    if (!empty($about_course)) {

                        $course_detail = implode($about_course);
                        $course_description = explode('<p>', $course_detail);
                        $res[$key]['course_description'] = html_entity_decode(utf8_decode(strip_tags($course_description[3])), ENT_QUOTES, "UTF-8");
                        
                    }
                }
            }
        }


        foreach($e->find('img') as $key=>$image)
            { 
                $image = $image->src;
                if(!empty($image))
                {
                $res[$key]['image_path'] = trim($image); 
                }               
            }
            
            foreach($e->find('h2.truncate') as $key=>$title)
            {
                         $title = $title;
                         $title = strip_tags($title);
                         if(!empty($title))
                         {
                         $res[$key]['title'] = html_entity_decode(utf8_decode(strip_tags($title)), ENT_QUOTES, "UTF-8");
                         }
            } 

            
            foreach($e->find('p.instructors') as $key=>$instructors)
            {
                $instructors = $instructors;//echo $instructors;die;
                $instructors = strip_tags(str_replace(' ', ' ', $instructors));
                if(!empty($instructors))
                {
                $res[$key]['instructors'] = html_entity_decode(utf8_decode(strip_tags($instructors)), ENT_QUOTES, "UTF-8");
                }
            }
            
            foreach($e->find('div.ribbon-content') as $key=>$category)
            {
                $category_name = strip_tags($category);
                $res[$key]['course_category'] = $category_name;
            }
            

            foreach($e->find('ul.course-meta') as $key=>$start_time)
            { 
                $start_time = $start_time;
                $date = explode('</i>',$start_time);
                $start = $date[1];
                $language = $date[2];
                $start_time_new = strip_tags(str_replace(' ', ' ', $start));
                if(!empty($start_time_new))
                {
                $res[$key]['start_new'] = $start_time_new;
                }
                if(!empty($language))
                {
                    $res[$key]['course_language'] = strip_tags($language);
                }
                
            }
//            echo '<pre>';
//                        print_r($res);die;
            if(count($res)>1)
            {
            $result_final[]= $res;  
            }
     } 
//     echo '<pre>';
//                        print_r($result_final);die;
    return $result_final;
 }          
                                      
?>
