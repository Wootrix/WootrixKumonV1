<?php
    //***********function to get the data of edx website*****************//

 function getedxContent()
  {
     $result_final = array();
     $i = 0;
     while($i<=15)
    {
      //*************getting the webpage content by page no*********//
    $html=file_get_html("https://www.edx.org/course-list?page=$i");
        $res = array();
   //**********loop for fetching the main content********************//
    foreach($html->find('div.views-row') as  $e)
     {
             //*******loop for fetching the image_url**********//
            foreach($e->find('img.image-style-none') as $image)
            {
                $image = $image->src ;
                $res['image_path'] = trim($image); 
            }
            
            //**********loop for fetching the course_title************//
            foreach($e->find('h2.course-title') as $title)
            {
                $title = $title;
                $course_link = explode('<a href=',$title);
                $course_link_new = explode('"', $course_link[1]);
                $res['course_link'] = $course_link_new[1];
                $url = $course_link_new[1];
                $course_html = file_get_html($url);
                $about_course = $course_html->find('div.course-detail-about');
                $course_description = implode($about_course);
                $res['course_full_description'] = strip_tags(str_replace('About this Course','',$course_description));
                
                
                $instructor = $course_html->find('h4.staff-title');
                $course_instructor = implode(',',$instructor);
                $res['course_instructor'] = strip_tags($course_instructor);
               
                
                $title_new = strip_tags(str_replace(' ', ' ', $title));
                $res['course_title'] = $title_new;

            }
             
             //***********loop for fetching the course_description*******//
            foreach($e->find('div.course-subtitle') as $subtitle)
            {
                $subtitle = $subtitle ;
                $subtitle = strip_tags(str_replace(' ', ' ', $subtitle));
                $res['subtitle'] = $subtitle;
            }
              
            //*******loop for fetching course starting date**********//
            foreach($e->find('li.first') as $start_date)
            {
                $start_date = $start_date ;
                $start_date = strip_tags(str_replace('Starts:', ' ', $start_date));
                $res['start'] = $start_date;
            }
            
            //***********loop for fetching instructor name,university************//
            foreach($e->find('ul.clearfix') as $instructor)
            {
                
                $instructor = $instructor ;
                $ins = str_replace('Starts:', ' ', $instructor);
                $ins1 = str_replace('Instructors:', ' ', $ins);
                
                $a = explode('</span>',$ins1);
                $b = explode('</li>',$a[2]);
                $instruct = $b[0];
                $university = $b[1];
                
                
                $university_new = strip_tags($university);
                $res['university_name'] = $university_new;
                                
                
            }
                   
            $result_final[]= $res;        
            
     }
     $i++;
    }
     return $result_final;

  }