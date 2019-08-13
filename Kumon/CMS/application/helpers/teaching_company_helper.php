<?php
function getTeachingCompanyContent()
{
  $result_final = array();
  
    $html = file_get_html('http://www.thegreatcourses.com/courses'); //echo $html;die;
     
    foreach ($html->find('div.item-inner') as $e) {   
        
        foreach($e->find('a') as $link)
        {
            $course_url =  $link->href;
            $res['course_link'] = $course_url;
            
            $course_html = file_get_html($course_url);
            
            $course_length = $course_html->find('div.course-counters');
            
            $course_duration = implode($course_length);
            $course_duration_new = explode('</span>',$course_duration);
            $res['course_duration'] = trim(strip_tags($course_duration_new[0]));
            
            $course_description = $course_html->find('div.course-description');
            $course_detail = implode($course_description);
            $course_des = explode('View More',$course_detail);
            $res['course_full_description'] = trim(strip_tags($course_des[0]));
        }

        foreach ($e->find('img') as $image) {
            $image = $image->src; //echo $image;die;

            $res['image_path'] = trim($image);
        }

        foreach ($e->find('h2.product-name') as $title) {

            $title = $title;
            $title = strip_tags($title); //echo $title;die;
            $res['title'] = $title;
        }


        foreach ($e->find('span.professor-name') as $instructor) {
            $instructor = $instructor; //echo $instructor;die;
            $res['instructor_name'] = strip_tags($instructor);
                      
        }
        //echo '<pre>'; print_r($res);//die;


        $result_final[] = $res;
      
        
    }
    
    return $result_final;
}
    ?>
</html>