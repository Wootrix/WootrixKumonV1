<?php

//**************function to get content of open2study website****************//

function getOpen2StudyContent() {
    $result_final = array();

    $html = file_get_html('https://www.open2study.com/courses');

    foreach ($html->find('div.views-field-nothing') as $e) {

        foreach ($e->find('a') as $link) {
            $course_link = $link->href;
            $res['course_url'] = "https://www.open2study.com" . $course_link;

            $course_html = file_get_html("https://www.open2study.com" . $course_link);
            $instructor = $course_html->find('div#subject-teacher-tagline');
            $instructor_name = implode($instructor);
            $res['instructor_name'] = trim(str_replace('by', '', strip_tags($instructor_name)));

            $description = $course_html->find('div.full-body');
            $course_description = implode($description);
            $res['course_full_description'] = strip_tags($course_description);
        }

        foreach ($e->find('img') as $image) {
            $image = $image->src;

            $res['image_path'] = trim($image);
        }

        foreach ($e->find('h2.adblock_course_title') as $title) {

            $title = $title;
            $title = strip_tags($title);
            $res['title'] = $title;
        }


        foreach ($e->find('span.date-display-single') as $start_time) {
            $start_time = $start_time;
            $res['start'] = strip_tags($start_time);
        }

        $result_final[] = $res;
    }
    return $result_final;
}

?>
