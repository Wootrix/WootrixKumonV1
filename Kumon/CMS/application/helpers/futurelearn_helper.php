<?php
//********function for fetching content for FutureLearn website**************//

function getFutureLearnContent()
{
    $result_final = array();
    
   $html=file_get_html('https://www.futurelearn.com/courses/upcoming');
  
    foreach($html->find('article.has-certificates') as $e)
     {       
            foreach($e->find('img') as $image)
            { 
                $imgUrl =  $image->src;
                $res['image_path'] = trim($imgUrl); 
            }

            foreach($e->find('a.title') as $title)
            {
                         $title_url = $title->href;
                         $res['course_url'] = "https://www.futurelearn.com".$title_url;
                         
                         $url = "https://www.futurelearn.com".$title_url;
                         $course_html = file_get_html($url);
                         $about_course = $course_html->find('section.small');
                         $course_detail = implode($about_course);
                         $res['course_description'] = trim(strip_tags($course_detail));
                         
                         $instructor = $course_html->find('div.names');
                         $instructor_name = implode($instructor);
                         $res['instructor_name'] = strip_tags($instructor_name);
                         
                         $title = $title;
                         $title = strip_tags($title);
                         $res['title'] = $title;

            } 
   
            
            foreach($e->find('header.header') as $university_name)
            {           
                foreach($university_name->find('h3.organisation') as $university)
                {
                         $university = $university;
                         $university = strip_tags(str_replace(' ', ' ', $university));
                         $res['university'] = trim($university);
                }
            }
            
            
           foreach($e->find('time') as $start_time)
            {
                $start_time = $start_time ;
                $start_time = strip_tags(str_replace(' ', ' ', $start_time));
                $res['start_time'] = $start_time;
            }
            
            foreach($e->find('span') as $course_length)
            {
                $course_length = $course_length ;
                $duration = explode("</i>", $course_length);    
                if(!empty($duration[2]))
                {
                    $course_length_new = $duration[2];
                }
                
                $res['course_length_new'] = strip_tags($course_length_new);

            }
                     
            $result_final[]= $res;                    
     } 
    return $result_final;
           
}                                   
?>
