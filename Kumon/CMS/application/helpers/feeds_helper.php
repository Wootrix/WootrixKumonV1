<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
/* FOR POST FEED */

function post_feeds($sender_id, $feed, $type) {

    $CI = & get_instance();

    $user_query = $CI->db->query("SELECT id FROM tbl_users");
    $user_result = $user_query->result_array();
    foreach ($user_result as $value) {
        $sender_id = $value['id'];
        $data = array('user_id' => $sender_id, 'feed' => $feed, 'type' => $type);
        $sql = $CI->db->set('created_on', 'now()', FALSE)->insert('tbl_feeds', $data);
    }

   
}

/* Notification - Type [GENERERATE NOTIFICATION FOR USER] */

function post_notification($sender_id, $notification, $type, $note) {

    $CI = & get_instance();

    /* SENDER USER INFORMATION */
    $user_query = $CI->db->query("SELECT id,first_name,last_name FROM tbl_users WHERE id=$sender_id");
    $user = $user_query->row_array();

    @$fname = $user['first_name'];
    @$lname = $user['last_name'];
    @$course_title = "";

    if ($type == 1) {

        /* note creater info */
        $creater_query = $CI->db->query("SELECT a.user_id,a.course_id,a.note,b.course_title FROM tbl_user_notes as a
            LEFT JOIN tbl_courses_master as b ON a.course_id = b.id WHERE a.id=$note");
        $user_result = $creater_query->result_array();
        foreach ($user_result as $value) {
            $creater_id = $value['user_id'];
            $course_title = $value['course_title'];
        }
        /* MESSAGE HERE */        
        $message = $fname . " " . $lname . " " . 'like  your note' . " " . " on " . $course_title;
    } else if ($type == 2) {        
        /* note creater info */
        $creater_query = $CI->db->query("SELECT a.user_id,a.course_id,a.note,b.course_title FROM tbl_user_notes as a
            LEFT JOIN tbl_courses_master as b ON a.course_id = b.id WHERE a.id=$note");
        $user_result = $creater_query->result_array();
        foreach ($user_result as $value) {
            $creater_id = $value['user_id'];
            $course_title = $value['course_title'];
        }

        $message = $fname . " " . $lname . " " . 'comment on your note' . " " . " on " . $course_title;
    } else if ($type == 3) {        
        /* note creater info */
        $creater_query = $CI->db->query("SELECT a.user_id,a.course_id,a.note,b.course_title FROM tbl_user_notes as a
            LEFT JOIN tbl_courses_master as b ON a.course_id = b.id WHERE a.id=$note");
        $user_result = $creater_query->result_array();
        foreach ($user_result as $value) {
            $creater_id = $value['user_id'];
            $course_title = $value['course_title'];
        }

        $message = $fname . " " . $lname . " " . 'share a note' . " " . " on " . $course_title;
    } else {
        
        $message = $fname . " " . $lname . " " . 'following you';
        /* follower id */
        $creater_id = $note;
    }

    if ($type == 3) {     
       $data = array('user_id' => $creater_id, 'notification' => $notification, 'created_by' => $sender_id, 'notification_type' => $type, 'message' => $message, 'note_id' => $note);
           //$data = array('user_id' => $follower_id, 'notification' => $notification, 'created_by' => $sender_id, 'notification_type' => $type, 'message' => $message, 'note_id' => $note);
           $sql = $CI->db->set('created_on', 'now()', FALSE)->insert('tbl_user_notifications', $data);
        
    } else {
        $data = array('user_id' => $creater_id, 'notification' => $notification, 'created_by' => $sender_id, 'notification_type' => $type, 'message' => $message, 'note_id' => $note);
        $sql = $CI->db->set('created_on', 'now()', FALSE)->insert('tbl_user_notifications', $data);
    }
}

?>
