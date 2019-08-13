<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
//********function for fetching the content of Creativelive website*******//

function getCreativeLiveContent() 
{
    $result_final = array();
    $i = 1;
    while ($i <= 22) 
        {
        $html = file_get_html("https://www.creativelive.com/catalog/all?page=$i");
        
        foreach ($html->find('div.course-tile') as $key => $e) 
            { 
            foreach ($e->find('a') as $key => $course_url) 
                { 
                $course_final_url = $course_url->href;
                $res['course_href'] = "https://www.creativelive.com" . $course_final_url;


                $title_url = "https://www.creativelive.com" . $course_final_url;
                if (!empty($title_url)) {

                    $course_html = file_get_html($title_url);
                    if (!empty($course_html)) {

                        $about_course = $course_html->find('div.course-description');
                        if (!empty($about_course)) {

                            $course_detail = implode($about_course);

                            $res['about_course_description'] = trim(strip_tags($course_detail));
                        }
                    }
                }
            }
            foreach ($e->find('img.rounded') as $key => $image) {
                $image = $image->src;

                $res['image_path'] = trim(str_replace('//', '', $image));
            }


            foreach ($e->find('div.course-tile-title') as $key => $title) {

                $title = $title;
                $title = strip_tags($title);
                $res['title'] = trim($title);
            }


            foreach ($e->find('span') as $key => $instructor) {
                $instructor = $instructor;
                $instructor = strip_tags($instructor);
                $res['instructor'] = trim($instructor);
            }
   
            $result_final[] = $res;
        }
        $i++;

    }
    
     return $result_final;
}

?>
