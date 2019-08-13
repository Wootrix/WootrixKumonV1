<?php

//*************function for getting all the content of coursera website***********//

function getCourseraContent() {
    
    $result= array();
    
    //*****************json for getting course detail with language,shortDescription,instructor,imageUrl,length of course, universities categories and sessions*******//

    $json = file_get_contents('https://api.coursera.org/api/catalog.v1/courses?fields=language,aboutTheCourse,instructor,photo,estimatedClassWorkload&includes=universities,categories,sessions');

    $content_course = json_decode($json, TRUE);
    
    //**********loop for get full course content************//
    foreach ($content_course['elements'] as $key => $value) {
        

        $res['course_id'] = $value['id'];
        $res['course_title'] = $value['name'];
        $res['lang'] = $value['language'];
        $res['image_path'] = $value['photo'];
        $res['description'] = trim(html_entity_decode(utf8_decode($value['aboutTheCourse']), ENT_QUOTES, "UTF-8"));
        $res['instructor'] = $value['instructor'];
        $res['course_length'] = $value['estimatedClassWorkload'];

        $university = $value['links']['universities'];
        $category = $value['links']['categories'];
        $sessions = $value['links'] ['sessions'];

        $res_university = array_merge($university);
        $university_id = implode(',', $res_university);

        $res_categories = array_merge($category);
        $category_id = implode(',', $res_categories);
        $res['category_id'] = $category_id;

        $res_session = array_merge($sessions);
        $session_id = implode(',', $res_session);
        
        $json_category = "https://api.coursera.org/api/catalog.v1/categories?ids={$category_id}";
        $content_category = json_decode(file_get_contents($json_category),TRUE);
        
        foreach($content_category['elements'] as $key=>$value)
        {
            $res['category_name'] = $value['name']; 
            $res['category_shortname'] = $value['shortName'];
        }

        //*********json for getting usiversity detail for perticular university_id**************//

        $json_university = "https://api.coursera.org/api/catalog.v1/universities?ids={$university_id}";

        $content_university = json_decode(file_get_contents($json_university), TRUE);

        //*************json for getting course start date for perticular session_id***********//

        $json_session = "https://api.coursera.org/api/catalog.v1/sessions?fields=startDay,startMonth,startYear&ids={$session_id}";
        $content_sessions = json_decode(file_get_contents($json_session), TRUE);

        //*********loop for fetching university name**********//
        foreach ($content_university['elements'] as $key => $value) {

            $university[] = $value['shortName'];
        }

        $uni = implode(',', $university);
        $new = explode(',', $uni);
        $new_uni = $new[1];
        $res['university_name'] = $new_uni;
        
        //***********loop  for fetching course starting date***************//
        $course_date = array();
        foreach ($content_sessions['elements'] as $key => $value1) {

            $start_date = $value1['startDay'];
            $start_month = $value1['startMonth'];
            $start_year = $value1['startYear'];
            $course_date[] = $start_date . "/" . $start_month . "/" . $start_year;
        }

        $course_start = implode(',', $course_date);

        $res['course_start_date'] = $course_start;


        $result[] = $res;
    }
    
    return $result;
}
?>
   




