<?php

//*******function to get content of lynda website*************//

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
function getLyndaContent() {

    $result_final = array();
    $html = file_get_html('http://www.lynda.com/subject/all');

    foreach ($html->find('div.letter') as $e) {
        

        foreach ($e->find('div.software-name') as $course) {
            $course_href = explode('<a href=', $course);
            $href = explode('>', $course_href[1]);
            $href_final = $href[0];
            $href_final_new = str_replace('"', '', $href_final); 
            $no_of_videos = $href[2]; 
            $only_videos_no = str_replace('(', '', $no_of_videos); 
            $only_videos_no_new = str_replace(')', '', $only_videos_no); 
            $page = $only_videos_no_new / 50;
            $page_count = ceil($page); 
            $i = 1;

            $result_final = array();
            while ($i <= $page_count) {
                
                $detail = file_get_html('http://www.lynda.com' . $href_final_new . '?page=' . $i); 
                
                foreach ($detail->find('ul.course-list') as $course_list) { 
                    $res = array();

                    foreach ($course_list->find('img') as $key => $image) {
                        $imageurl = $image->getAttribute('data-img-src'); 
                        if (!empty($imageurl)) {
                            $res[$key]['image_path'] = $imageurl;
                        }

                    }


                    foreach ($course_list->find('div.details-row') as $key => $title) {
//                        
                        $title_new = $title; 
                        $only_title = explode('<span class="author_name">', $title_new);

                        $only_title_new = $only_title[0];
                        $href = explode('"', $only_title_new); 
                        $final_href = $href[3];
                        $final_url = "http://www.lynda.com" . $final_href; 
                        $course_full_detail = file_get_html($final_url); 
                        $detail = $course_full_detail->find('div.course-meta');
                        $detail_new = implode($detail); 
//                        
                        $date = explode('<span>', $detail_new);
                        $course_date = $date[2]; 
//                                         
                        $res[$key]['course_start_date'] = $course_date; 

                        $res[$key]['course_title_url'] = $final_url;

//                        

                        $only_author = $only_title[1]; 
                        if (!empty($only_title_new)) {
                            $res[$key]['course_title'] = trim(strip_tags($only_title_new)); 
                        }
                        if (!empty($only_author)) {
                            $str = trim(strip_tags($only_author));
                            $res[$key]['author_name'] = trim(str_replace('with', '',$str));
                        }

                        $course_description = $course_full_detail->find('div.course-description');
                        $description_of_course = implode($course_description); 
                        $res[$key]['full_description'] = trim(strip_tags($description_of_course)); 
                    }


                    foreach ($course_list->find('div.meta') as $key => $course_detail) {

                        $duration = explode('</div> ', $course_detail);
                        $course_duration = $duration[0];
                        $course_level = $duration[1];

                        if (!empty($course_duration)) {
                            $res[$key]['course_duration_new'] = trim(strip_tags($course_duration));
                        }
                        
                        if (!empty($course_level)) {
                            $res[$key]['course_level'] = trim(strip_tags($course_level)); 
                        }
                    }
                    //echo '<pre>';print_r($res);die;
                    
                    $result_final[] = $res;
                }

                $i++;
                        
            }
              
        }
       
        
    }
    echo '<pre>';
        print_r($result_final);die;
     
}

?>