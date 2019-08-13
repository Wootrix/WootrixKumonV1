<?php

//***********function to fetch Novoed website content************//
function getNovoedContent() { 
    $result_final = array();
    $i = 1;

    while ($i <= 4) {

        $html = file_get_html("https://novoed.com/courses?page=$i");


        foreach ($html->find('article.course-box') as $e) {

            foreach ($e->find('img') as $image) {
                $image = $image->src;

                $res['image_path'] = trim($image);
            }

            foreach ($e->find('h2.coursetitle') as $title) {

                $title = $title;
                $title = strip_tags($title);
                $res['title'] = $title;

                foreach ($e->find('a') as $link) {

                    $course_link = "";
                    $url = "https://novoed.com";
                    $course_link = $link->href;
                    $res['course_url'] = $url .$course_link;
                    break;
                }

                $course_html = file_get_html($url . $course_link);
                $aa = array();
                $course_description = $course_html->find('p');
                $course_detail = implode($course_description);
                $description = explode('</p>', $course_detail);
                $full_description = $description[0];
                $res['course_full_description'] = trim(strip_tags($full_description)); //echo $res['course_full_description'];die;
                $course_date = $course_html->find('div.inline-block');
            }


            foreach ($e->find('div.university') as $university_name) {
                
                $university_name = $university_name;
                $university_name = strip_tags(str_replace(' ', ' ', $university_name));
                $res['university_name'] = $university_name;
            }

            foreach ($e->find('div.span10') as $start_time) { 
                
                $start_time2 = $start_time;
                $start_time1 = strip_tags(str_replace(',', ' ', $start_time));
                $res['start_time'] = $start_time;
            }

            foreach ($e->find('div.span10') as $instructor) {
                
                $instructor = $instructor;
                $inst = explode('By', $instructor);
                $only_instructor = explode('<', $inst[1]);
                if (!empty($only_instructor[0])) {
                    $instuct = $only_instructor[0];
                }


                $res['instructor_name'] = strip_tags($instuct);
            }

            $result_final[] = $res;
        }
        $i++;
    }
    
    return $result_final;
}

?>
