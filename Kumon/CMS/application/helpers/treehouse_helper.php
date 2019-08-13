<?php

//***********function to fetch TreeHouse website content********//
function getTreehouseContent()
{
    $result_final = array();
    
    $html = file_get_html('http://teamtreehouse.com/library'); //echo $html;die;
    
    foreach ($html->find('li.tablet-grid-50') as $e) { 

        foreach ($e->find('img') as $image) {
            $image = $image->src; //echo $image;die;

            $res['image_path'] = trim($image);
        }
        
        foreach($e->find('a.grid-30') as $link)
        { 
            $course_url =  $link->href;
            $res['courseUrl'] = "http://teamtreehouse.com/".$course_url;
            
            $course_html = file_get_html("http://teamtreehouse.com/".$course_url);
            $instructor = $course_html->find('h4');
            $instructor_name = implode($instructor);
            $res['instructor_name'] = trim(strip_tags($instructor_name));
            
            $about_course = $course_html->find('div.achievement-steps');
            $course_detail = implode($about_course);
            $res['course_full_detail'] = trim(strip_tags($course_detail));
          
            
            
        }

        foreach ($e->find('a.title') as $title) {

            $title = $title;
            $title_new = explode('</strong>', $title);
            $t = explode('</h3>',$title_new[1]);
                       //echo '<pre>';print_r($t);
            if(!empty($t[0]))
            {
            $course_title = $t[0];//echo $course_title;die; 
            
            
            $language_level = $t[1]; 
            }
            //echo $language_level;
            $course_title = strip_tags($course_title); //echo $course_title;die;  
            $res['course_title_new'] = trim($course_title);
            $level = strip_tags($language_level);//echo $level;
            $res['course_level'] = trim($level);

        $result_final[] = $res;

//            
        
    }
    
 }
  return $result_final;
 
}
    
    ?>
