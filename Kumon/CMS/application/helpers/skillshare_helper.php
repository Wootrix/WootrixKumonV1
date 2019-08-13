<?php

//*************function to fetch content of Skillshare website************//

function getSkillshareContent() {

    $result_final = array();

    $i = 0;
    while ($i <= 17) {
        $opts = array('http' => array('method' => "GET", 'header' => "Accept-language: en\r\n" . "User-Agent: not for you\r\n"));
        $context = stream_context_create($opts);
        $url = "http://www.skillshare.com/classes?page=$i";
        $html = file_get_html($url, false, $context);


        foreach ($html->find('li.class-row') as $e) {

            foreach ($e->find('img') as $image) {

                $imageurl = $image->src;

                $res['image_path'] = trim($imageurl);
            }


            foreach ($e->find('h2.class-title') as $title) {
                $course_title = $title;
                $res['title'] = strip_tags($course_title);
                $course_url = explode('<a href="', $course_title);
                $course_url_only = explode('"', $course_url[1]);
                $res['course_link'] = $course_url_only[0];

                $url = $course_url_only[0];
                $course_html = file_get_html($url);

                $about_course = $course_html->find('div.rich-content-wrapper');
                $course_detail = implode($about_course);
                $res['course_description'] = trim(strip_tags($course_detail));


                $course_teacher = $course_html->find('p.title');
                $author = implode($course_teacher);
                $res['author'] = strip_tags($author);
            }

            foreach ($e->find('a.level') as $level) {
                $course_level = $level;
                $res['course_level'] = strip_tags($course_level);
            }

            foreach ($e->find('a.secondary-tag') as $course_topic) {
                $topic = $course_topic;
                $res['course_topic'] = strip_tags($topic);
            }

            $result_final[] = $res;
        }
        $i++;
    }
    
    return $result_final;
}

?>