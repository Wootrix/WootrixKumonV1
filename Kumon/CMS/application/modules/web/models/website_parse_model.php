<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class website_parse_model extends CI_Model {
   
    
    private $_tableName = 'tbl_new_articles';
    private $_title = "";
    private $_author = "";
    private $_description = "";
    private $_article_link = "";
    private $_publish_date = "";
    private $_category_id = "";
    private $_tag_id = "";
    private $_crawling_date = "";
    private $_media_id = "";
    private $_website_url = "";
    private $_status = "";
    private $_without_html_description = "";
    
    private $_media_type = "";
    private $_thumbnail_url = "";
    private $_content_url = "";
    

    public function get_tableName() {
        return $this->_tableName;
    }

    public function get_title() {
        return $this->_title;
    }

    public function get_author() {
        return $this->_author;
    }

    public function get_description() {
        return $this->_description;
    }

    public function get_article_link() {
        return $this->_article_link;
    }

    public function get_publish_date() {
        return $this->_publish_date;
    }

    public function get_category_id() {
        return $this->_category_id;
    }

    public function get_tag_id() {
        return $this->_tag_id;
    }

    public function get_crawling_date() {
        return $this->_crawling_date;
    }

    public function get_media_id() {
        return $this->_media_id;
    }

    public function get_website_url() {
        return $this->_website_url;
    }

    public function get_status() {
        return $this->_status;
    }

    public function set_tableName($_tableName) {
        $this->_tableName = $_tableName;
    }

    public function set_title($_title) {
        $this->_title = $_title;
    }

    public function set_author($_author) {
        $this->_author = $_author;
    }

    public function set_description($_description) {
        $this->_description = $_description;
    }

    public function set_article_link($_article_link) {
        $this->_article_link = $_article_link;
    }

    public function set_publish_date($_publish_date) {
        $this->_publish_date = $_publish_date;
    }

    public function set_category_id($_category_id) {
        $this->_category_id = $_category_id;
    }

    public function set_tag_id($_tag_id) {
        $this->_tag_id = $_tag_id;
    }

    public function set_crawling_date($_crawling_date) {
        $this->_crawling_date = $_crawling_date;
    }

    public function set_media_id($_media_id) {
        $this->_media_id = $_media_id;
    }

    public function set_website_url($_website_url) {
        $this->_website_url = $_website_url;
    }

    public function set_status($_status) {
        $this->_status = $_status;
    }
    
    public function get_media_type() {
        return $this->_media_type;
    }

    public function get_thumbnail_url() {
        return $this->_thumbnail_url;
    }

    public function get_content_url() {
        return $this->_content_url;
    }

    public function set_media_type($_media_type) {
        $this->_media_type = $_media_type;
    }

    public function set_thumbnail_url($_thumbnail_url) {
        $this->_thumbnail_url = $_thumbnail_url;
    }

    public function set_content_url($_content_url) {
        $this->_content_url = $_content_url;
    }
    
     public function get_without_html_description() {
        return $this->_without_html_description;
    }

    public function set_without_html_description($_without_html_description) {
        $this->_without_html_description = $_without_html_description;
    }


    //********function to insert achrNews rss content******************//
    public function insertAchrNewsContent() {
        
        //**********getting values using getter for rss*********//
        $title = $this->get_title();
        $author = $this->get_author();
        $description = $this->get_description();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description = $this->get_description();
        $without_html_description = $this->get_without_html_description();
        $media_url = $this->get_content_url();
        $media_type = $this->get_media_type(); 
        $type = explode('/',$media_type);
        if($type[0] == "image" || $type[0]!="")
        {
            $media_type1 = "0";
        }
        else
        {
            $media_type1 = "1";
        }
        
        if($description != '' )
        {
        //**********checking if media url is already exists in database or not********// 
        $query_media_check = $this->db->select('media_url')
                                ->from('tbl_media')
                                ->where('media_url',"$media_url")
                                ->get();
        $num_media = $query_media_check->num_rows();  
        
        if($num_media == 0)     //*******************if not exists***************//  
        {
        $query = $this->db->set('media_url',"$media_url")
                          ->set('media_type',"$media_type")
                          ->insert('tbl_media');
        
        if($query)
        {
            $media_id = $this->db->insert_id();
            
            $data = array(
            'title' => $title,
            'author' => $author,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url' => $website_url,
            'media_id'    => $media_id,
            'description_without_html' => $without_html_description,
                'image'               => "$media_url",
                'media_type'          => $media_type1
        ); 
         //echo '<pre>';print_r($data);die;  
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) { 
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE; 
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        }
    }
    }
    
    public function insertAmericanMachinistContent()
    {
        $title = $this->get_title();
        $description = $this->get_description();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $content_url = $this->get_content_url();
        $media_type = $this->get_media_type();
        $without_html_description = $this->get_without_html_description();
        $type = explode('/',$media_type);
        if($type[0] == "image" || $type[0]!="")
        {
            $media_type1 = "0";
        }
        else
        {
            $media_type1 = "1";
        }
      
        if($description != '' )
        {
        $query_media_check = $this->db->select('media_url')
                                ->from('tbl_media')
                                ->where('media_url',"$content_url")
                                ->get();
        $num_media = $query_media_check->num_rows();  
        
        if($num_media == 0)        
        {
        $query = $this->db->set('media_url',"$content_url")
                          ->set('media_type',"$media_type")
                          ->insert('tbl_media');
        
        if($query)
        {
            $media_id = $this->db->insert_id();
            
            $data = array(
            'title' => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url' => $website_url,
            'media_id'    => $media_id,
            'description_without_html' => $without_html_description,
                'image'               => "$content_url",
                'media_type'          => $media_type1
                
        );
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        }
     }
    }
    public function insertApplianceDesignContent()
    {
        $title = $this->get_title();
        $description = $this->get_description();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $media_url = $this->get_content_url();
        $media_type = $this->get_media_type();
        $author = $this->get_author();
        $without_html_description = $this->get_without_html_description();
        $type = explode('/',$media_type);
        if($type[0] == "image" || $type[0]!="")
        {
            $media_type1 = "0";
        }
        else
        {
            $media_type1 = "1";
        }
        if($description != '' )
        {
        $query_media_check = $this->db->select('media_url')
                                ->from('tbl_media')
                                ->where('media_url',"$media_url")
                                ->get();
        $num_media = $query_media_check->num_rows();  
        
        if($num_media == 0)        
        {
        $query = $this->db->set('media_url',"$media_url")
                          ->set('media_type',"$media_type")
                          ->insert('tbl_media');
        
        if($query)
        {
            $media_id = $this->db->insert_id();
            
            $data = array(
            'title' => $title,
            'author' => $author,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url' => $website_url,
            'media_id'    => $media_id,
            'description_without_html' => $without_html_description,
                'image'               => "$media_url",
                'media_type'          => $media_type1
        );
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        }
    }
    }
    public function insertAdhesivesMagContent()
    {
        $title = $this->get_title();
        $description = $this->get_description();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $media_url = $this->get_content_url();
        $media_type = $this->get_media_type();
        $html_without_desscription = $this->get_without_html_description();
        $type = explode('/',$media_type);
    
        if($type[0] == "image" || $type[0]!="")
        {
            $media_type1 = "0";
        }
        else
        {
            $media_type1 = "1";
        }
    
        
        $query_media_check = $this->db->select('media_url')
                                ->from('tbl_media')
                                ->where('media_url',"$media_url")
                                ->get();
        $num_media = $query_media_check->num_rows();  
        
        if($num_media == 0)        
        {
        $query = $this->db->set('media_url',"$media_url")
                          ->set('media_type',"$media_type")
                          ->insert('tbl_media');
        
        if($query)
        {
            $media_id = $this->db->insert_id();
            
            $data = array(
            'title'       => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url' => $website_url,
            'media_id'    => $media_id,
            'description_without_html' => $html_without_desscription,
                'image'               => "$media_url",
                'media_type'          => $media_type1
        );
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        }
    }

    
    public function insertAssemblyMagContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $media_url = $this->get_content_url();
        $media_type = $this->get_media_type();
        $description_without_html = $this->get_without_html_description(); 
        $type = explode('/',$media_type);
        if($type[0] == "image" || $type[0]!="")
        {
            $media_type1 = "0";
        }
        else
        {
            $media_type1 = "1";
        }
        if($description!='')
        {
        $query_media_check = $this->db->select('media_url')
                                ->from('tbl_media')
                                ->where('media_url',"$media_url")
                                ->get();
        $num_media = $query_media_check->num_rows();  
        
        if($num_media == 0)        
        {
        $query = $this->db->set('media_url',"$media_url")
                          ->set('media_type',"$media_type")
                          ->insert('tbl_media');
        
        if($query)
        {
            $media_id = $this->db->insert_id();
            
            $data = array(
            'title' => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url' => $website_url,
            'media_id'    => $media_id,
            'description_without_html' => $description_without_html,
                'image'               => "$media_url",
                'media_type'          => $media_type1
        );
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        }
      }
    } 
    public function insertAutomationContent()
    {
        $title = $this->get_title();
        $description = $this->get_description();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
        
      
            $data = array(
            'title' => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'description_without_html' => $description_without_html,
             'website_url'             => $website_url
            
        );
            //echo '<pre>';print_r($data);die;
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            
        
    }
    
    public function insertBulkSolidHandlingContent()
    {
        $title = $this->get_title();
        $description = $this->get_description();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
        
      
            $data = array(
            'title' => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'description_without_html' => $description_without_html,
                'website_url'         => $website_url
        );
            
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    public function insertCanedianMetalgContent()
    {
        $title = $this->get_title();
        $description = $this->get_description();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
        
      
            $data = array(
            'title' => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'description_without_html' => $description_without_html,
                'website_url'          => $website_url
            
        );
            //echo '<pre>';print_r($data);die;
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    public function insertChemicalProcessingContent()
    {
        $title = $this->get_title();
        $description = $this->get_description();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
        
        $data = array(
            'title' => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'description_without_html' => $description_without_html,
            'website_url'              => $website_url
            
        );
            //echo '<pre>';print_r($data);die;
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
        
    }
    
    public function insertConnectorSuplierContent()
    {
        $title = $this->get_title();
        $description = $this->get_description();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description();
        
        $data = array(
            'title' => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'description_without_html' => $description_without_html,
            'website_url'       => $website_url
            
        );
            //echo '<pre>';print_r($data);die;
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
        
    }
    
    public function insertCseMagContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'description_without_html' => $description_without_html,
            'website_url'          => $website_url
        );
            //echo '<pre>';print_r($data);die;
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
        
    }
    
    public  function insertControlEngContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'description_without_html' => $description_without_html,
            'website_url'             => $website_url
        );
            //echo '<pre>';print_r($data);die;
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    public function insertCteMagContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'description_without_html' => $description_without_html,
            'website_url' => $website_url
        );
            
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    public function  insertDesignNewsContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'description_without_html' => $description_without_html,
            'website_url'             => $website_url
        );
            
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    
    public function insertDrivesNControlsContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'description_without_html' => $description_without_html,
            'website_url'              => $website_url
        );
        //echo '<pre>';print_r($data);die;
            
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    
    public function insertEeTimesContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'description_without_html' => $description_without_html,
            'website_url'              => $website_url
        );
        //echo '<pre>';print_r($data);die;
            
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    public function insertEhsTodayContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $media_url = $this->get_content_url(); 
        $description_without_html = $this->get_without_html_description(); 
        $media_type  = $this->get_media_type();
        $type = explode('/',$media_type);
        if($type[0] == "image" || $type[0]!="")
        {
            $media_type1 = "0";
        }
        else
        {
            $media_type1 = "1";
        }
        
        $query_media_check = $this->db->select('media_url')
                                ->from('tbl_media')
                                ->where('media_url',"$media_url")
                                ->get();
        $num_media = $query_media_check->num_rows();  
        
        if($num_media == 0)        
        {
        $query = $this->db->set('media_url',"$media_url")
                          ->set('media_type',"$media_type")
                          ->insert('tbl_media');
        
        if($query)
        {
            $media_id = $this->db->insert_id();
            
            $data = array(
            'title' => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url' => $website_url,
            'media_id'    => $media_id,
            'description_without_html' => $description_without_html,
                'image'               => "$media_url",
                'media_type'          => $media_type1
        ); 
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        }
    }
    
    public function insertEcmagContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html,
            'website_url'              => $website_url
        );
        
            
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    
    public function insertElectronicSpecifierContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html,
            'website_url'              => $website_url
        );
         
        
            
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    
    public function insertElectronicDesignContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html      
        ); 
         
        
            
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    
    public function insertElectronicsWeeklyContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html      
        );
         
        
            
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    public function insertEngineerLiveContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html      
        );
         
        
            
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    
    public function insertEngineeringTvContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $media_url = $this->get_content_url(); 
        $description_without_html = $this->get_without_html_description(); 
        $media_type  = $this->get_media_type(); 
        $type = explode('/',$media_type);
        if($type[0] == "image" || $type[0]!="")
        {
            $media_type1 = "0";
        }
        else
        {
            $media_type1 = "1";
        }
        
        $query_media_check = $this->db->select('media_url')
                                ->from('tbl_media')
                                ->where('media_url',"$media_url")
                                ->get();
        $num_media = $query_media_check->num_rows();  
        
        if($num_media == 0)        
        {
        $query = $this->db->set('media_url',"$media_url")
                          ->set('media_type',"$media_type")
                          ->insert('tbl_media');
        
        if($query)
        {
            $media_id = $this->db->insert_id();
            
            $data = array(
            'title' => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url' => $website_url,
            'media_id'    => $media_id,
            'description_without_html' => $description_without_html,
                'image'               => "$media_url",
                'media_type'          => $media_type1
        ); 
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        }
    }
    
    
    public function insertFlexPackmagContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $media_url = $this->get_content_url(); 
        $description_without_html = $this->get_without_html_description(); 
        $media_type  = $this->get_media_type();
        $type = explode('/',$media_type);
        if($type[0] == "image" || $type[0]!="")
        {
            $media_type1 = "0";
        }
        else
        {
            $media_type1 = "1";
        }

       
        
        $query_media_check = $this->db->select('media_url')
                                ->from('tbl_media')
                                ->where('media_url',"$media_url")
                                ->get();
        $num_media = $query_media_check->num_rows();  
        
        if($num_media == 0)        
        {
        $query = $this->db->set('media_url',"$media_url")
                          ->set('media_type',"$media_type")
                          ->insert('tbl_media');
        
        if($query)
        {
            $media_id = $this->db->insert_id();
            
            $data = array(
            'title' => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url' => $website_url,
            'media_id'    => $media_id,
            'description_without_html' => $description_without_html,
                'image'               => "$media_url",
                'media_type'          => $media_type1
        ); 
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        }
    }
    
    
    public function insertFlowControlNetworkContent()
    {
        $title = $this->get_title(); 
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date(); 
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $media_url = $this->get_content_url(); 
        $description_without_html = $this->get_without_html_description(); 
        $media_type  = $this->get_media_type(); 
        $type = explode('/', $media_type);
        if ($type[0] == "image" || $type[0]!="")
        {
            $media_type1 = "0";
        }
        else
        {
            $media_type1 = "1";
        }

        $query_media_check = $this->db->select('media_url')
                                ->from('tbl_media')
                                ->where('media_url',"$media_url")
                                ->get();
        $num_media = $query_media_check->num_rows();  
        
        if($num_media == 0)        
        {
        $query = $this->db->set('media_url',"$media_url")
                          ->set('media_type',"$media_type")
                          ->insert('tbl_media');
        
        if($query)
        {
            $media_id = $this->db->insert_id();
            
            $data = array(
            'title' => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url' => $website_url,
            'media_id'    => $media_id,
            'description_without_html' => $description_without_html,
                'image'               => "$media_url",
                'media_type'          => $media_type1
        ); 
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        }
    }
    
    public function insertFoodandBeverAgePackagingContent()
    {
        $title = $this->get_title(); 
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date(); 
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $media_url = $this->get_content_url(); 
        $description_without_html = $this->get_without_html_description();
        $media_type  = $this->get_media_type(); 
        $type = explode('/', $media_type);
        if ($type[0] == "image" || $type[0]!="")
        {
            $media_type1 = "0";
        }
        else
        {
            $media_type1 = "1";
        }


        $query_media_check = $this->db->select('media_url')
                                ->from('tbl_media')
                                ->where('media_url',"$media_url")
                                ->get();
        $num_media = $query_media_check->num_rows();  
        
        if($num_media == 0)        
        {
        $query = $this->db->set('media_url',"$media_url")
                          ->set('media_type',"$media_type")
                          ->insert('tbl_media');
        
        if($query)
        {
            $media_id = $this->db->insert_id();
            
            $data = array(
            'title' => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url' => $website_url,
            'media_id'    => $media_id,
            'description_without_html' => $description_without_html,
                'image'               => "$media_url",
                'media_type'          => $media_type1
        ); 
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        }
    }
    
    
    public function insertHPACTvContent()
    {
        $title = $this->get_title(); 
        $description = $this->get_description(); 
        $article_link = $this->get_article_link(); 
        $publish_date = $this->get_publish_date(); 
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $media_url = $this->get_content_url(); 
        $description_without_html = $this->get_without_html_description(); 
        $media_type  = $this->get_media_type(); 
        $type = explode('/', $media_type);
        if ($type[0] == "image" || $type[0]!="")
        {
            $media_type1 = "0";
        }
        else
        {
            $media_type1 = "1";
        }

        $query_media_check = $this->db->select('media_url')
                                ->from('tbl_media')
                                ->where('media_url',"$media_url")
                                ->get();
        $num_media = $query_media_check->num_rows();  
        
        if($num_media == 0)        
        {
        $query = $this->db->set('media_url',"$media_url")
                          ->set('media_type',"$media_type")
                          ->insert('tbl_media');
        
        if($query)
        {
            $media_id = $this->db->insert_id();
            
            $data = array(
            'title' => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url' => $website_url,
            'media_id'    => $media_id,
            'description_without_html' => $description_without_html,
                'image'               => "$media_url",
                'media_type'          => $media_type1
        ); 
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        }
    }
    
    public function insertLabelandnArrowWebContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html      
        );
         //echo '<pre>';print_r($data);die;
         
        
            
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    public function insertMachineDesignContent()
    {
        $title = $this->get_title(); 
        $description = $this->get_description();  
        $article_link = $this->get_article_link(); 
        $publish_date = $this->get_publish_date(); 
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $media_url = $this->get_content_url(); 
        $description_without_html = $this->get_without_html_description(); 
        $media_type  = $this->get_media_type(); 
        $type = explode('/', $media_type);
        if($type[0] == "image" || $type[0]!="")
        {
            $media_type1 = "0";
        }
        else
        {
            $media_type1 = "1";
        }


        $query_media_check = $this->db->select('media_url')
                                ->from('tbl_media')
                                ->where('media_url',"$media_url")
                                ->get();
        $num_media = $query_media_check->num_rows();  
        
        if($num_media == 0)        
        {
        $query = $this->db->set('media_url',"$media_url")
                          ->set('media_type',"$media_type")
                          ->insert('tbl_media');
        
        if($query)
        {
            $media_id = $this->db->insert_id();
            
            $data = array(
            'title' => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url' => $website_url,
            'media_id'    => $media_id,
            'description_without_html' => $description_without_html,
                'image'               => "$media_url",
                'media_type'          => $media_type1
        ); 
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        }
    }
    
    public function insertMecatronicaatualContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $author      = $this->get_author();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'author'     =>  $author,
            'description' => trim($description),
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html      
        );
        
         
        
            
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    public function insertMetalFormingMagazineContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $author      = $this->get_author();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'description' => trim($description),
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html      
        );
         
         
        
            
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    
    public function insertMhlNewsContent()
    {
        $title = $this->get_title();
        $description = $this->get_description();  
        $article_link = $this->get_article_link(); 
        $publish_date = $this->get_publish_date(); 
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $media_url = $this->get_content_url(); 
        $description_without_html = $this->get_without_html_description(); 
        $media_type  = $this->get_media_type(); 
        $type = explode('/', $media_type);
        if($type[0] == "image" || $type[0]!="")
        {
            $media_type1 = "0";
        }
        else
        {
            $media_type1 = "1";
        }


        $query_media_check = $this->db->select('media_url')
                                ->from('tbl_media')
                                ->where('media_url',"$media_url")
                                ->get();
        $num_media = $query_media_check->num_rows();  
        
        if($num_media == 0)        
        {
        $query = $this->db->set('media_url',"$media_url")
                          ->set('media_type',"$media_type")
                          ->insert('tbl_media');
        
        if($query)
        {
            $media_id = $this->db->insert_id();
            
            $data = array(
            'title' => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url' => $website_url,
            'media_id'    => $media_id,
            'description_without_html' => $description_without_html,
                'image'               => "$media_url",
                'media_type'          => $media_type1
        ); 
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        }
    }
    
    
    public function insertMhwMagzineContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $author      = $this->get_author();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'description' => trim($description),
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html      
        );
         
         
        
            
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    
    public function insertTechnologyReviewContent()
    {
        $title = $this->get_title();
        $description = $this->get_description(); 
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'description' => trim($description),
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html      
        );
         
         //echo '<pre>';print_r($data);die;
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    public function insertMmdonlineContent()
    {
        $title = $this->get_title();
        $description = $this->get_description();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'description' => trim($description),
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html      
        );
         
         
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    public function insertMoldMakingTechnologyContent()
    {
        $title = $this->get_title();
        $description = $this->get_description();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'description' => trim($description),
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html      
        );
         
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    public function insertNASAContent()
    {
        $title = $this->get_title();
        $description = $this->get_description();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
       
        
        $data = array(
            'title'      =>  $title,
            'description' => trim($description),
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html      
        );
         
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    public function insertNRELContent()
    {
        $title = $this->get_title();
        $description = $this->get_description();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
        $author                   = $this->get_author();
       
        
        $data = array(
            'title'      =>  $title,
            'description' => trim($description),
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html,
            'author'                   => $author
        );
        
         
        
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
    }
    
    public function insertPaceTodayContent()
    {
        $title = $this->get_title();
        $description =  mysql_real_escape_string($this->get_description()); 
        $article_link = $this->get_article_link(); 
        $publish_date = $this->get_publish_date(); 
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $media_url = $this->get_content_url(); 
        $description_without_html = mysql_real_escape_string($this->get_without_html_description()); 
        $media_type  = $this->get_media_type(); 
        $type = explode('/', $media_type);
        if($type[0] == "image" || $type[0]!="")
        {
            $media_type1 = "0";
        }
        else
        {
            $media_type1 = "1";
        }


        $query_media_check = $this->db->select('media_url')
                                ->from('tbl_media')
                                ->where('media_url',"$media_url")
                                ->get();
        $num_media = $query_media_check->num_rows();  
        
        if($num_media == 0)        
        {
        $query = $this->db->set('media_url',"$media_url")
                          ->set('media_type',"$media_type")
                          ->insert('tbl_media');
        
        if($query)
        {
            $media_id = $this->db->insert_id();
            
            $data = array(
            'title' => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url' => $website_url,
            'media_id'    => $media_id,
            'description_without_html' => $description_without_html,
                'image'               => "$media_url",
                'media_type'          => $media_type1
            
        ); 
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        }    
        
            }
            
    public function insertPemMagContent()
      {
        $title = $this->get_title();
        $description = $this->get_description();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
        
       
        
        $data = array(
            'title'      =>  $title,
            'description' => trim($description),
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html,
            
        );
        
         
        
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }     
      }
      
      public function insertPlumbingAndHvacContent()
      {
        $title = $this->get_title();
        $description = $this->get_description();
        $article_link = $this->get_article_link();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
        
       
        
        $data = array(
            'title'      =>  $title,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html,
            
        );
        
         
        
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }   
      }
      
      
      public function insertPowerBulkSolidsContent()
      {
        $title = $this->get_title();
        $description = $this->get_description();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
        
       
        
        $data = array(
            'title'      =>  $title,
            'description' => trim($description),
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html,
            
        );
        
         //echo '<pre>';print_r($data);die;
        
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }     
      }
      
      public function insertProcessingMagzineContent()
      {
          $title = $this->get_title();
          $description = $this->get_description();
          $article_link = $this->get_article_link();
          $publish_date = $this->get_publish_date();
          $crawling_date = $this->get_crawling_date();
          $website_url = $this->get_website_url();
          $description_without_html = $this->get_without_html_description(); 
          $author = $this->get_author();
          $media_type = $this->get_media_type();
          $media_url = $this->get_content_url();
          $type = explode('/', $media_type);
            if($type[0] == "image" || $type[0]!="")
            {
                $media_type1 = "0";
            }
            else
            {
                $media_type1 = "1";
            }


        $query_media_check = $this->db->select('media_url')
                                ->from('tbl_media')
                                ->where('media_url',"$media_url")
                                ->get();
        $num_media = $query_media_check->num_rows();  
        
        if($num_media == 0)        
        {
        $query = $this->db->set('media_url',"$media_url")
                          ->set('media_type',"$media_type")
                          ->insert('tbl_media');
        
        if($query)
        {
            $media_id = $this->db->insert_id();
            
            $data = array(
            'title' => $title,
            'author' => $author,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url' => $website_url,
            'media_id'    => $media_id,
            'description_without_html' => $description_without_html,
                'image'               => "$media_url",
                'media_type'          => $media_type1
            
        ); 
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        }      
      }
      
      public function insertQualityMagContent()
      {
          $title = $this->get_title(); 
          $description = $this->get_description(); 
          $article_link = $this->get_article_link(); 
          $publish_date = $this->get_publish_date(); 
          $crawling_date = $this->get_crawling_date();
          $website_url = $this->get_website_url(); 
          $description_without_html = $this->get_without_html_description(); 
          $media_url = $this->get_content_url(); 
          $media_type = $this->get_media_type(); 
          $type = explode('/', $media_type);
        if($type[0] == "image" || $type[0]!="")
        {
            $media_type1 = "0";
        }
        else
        {
            $media_type1 = "1";
        }


        $query_media_check = $this->db->select('media_url')
                                ->from('tbl_media')
                                ->where('media_url',"$media_url")
                                ->get();
        $num_media = $query_media_check->num_rows();  
        
        if($num_media == 0)        
        {
        $query = $this->db->set('media_url',"$media_url")
                          ->set('media_type',"$media_type")
                          ->insert('tbl_media');
        
        if($query)
        {
            $media_id = $this->db->insert_id();
            
            $data = array(
            'title' => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url' => $website_url,
            'media_id'    => $media_id,
            'description_without_html' => $description_without_html,
                'image'               => "$media_url",
                'media_type'          => $media_type1
            
        ); 
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        }       
      }
      
      public function insertRenewGridContent()
      {
        $title = $this->get_title();
        $description = $this->get_description();
        $article_link = $this->get_article_link();
        $publish_date = $this->get_publish_date();
        $crawling_date = $this->get_crawling_date();
        $website_url = $this->get_website_url();
        $description_without_html = $this->get_without_html_description(); 
        
       
        
        $data = array(
            'title'      =>  $title,
            'description' => trim($description),
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html,
            
        );
        
         //echo '<pre>';print_r($data);die;
        
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }   
      }
      
      
      public function insertRewMagContent()
      {
          $title = $this->get_title();
          $description = $this->get_description();
          $article_link = $this->get_article_link();
          $publish_date = $this->get_publish_date();
          $crawling_date = $this->get_crawling_date();
          $website_url = $this->get_website_url();
          $description_without_html = $this->get_without_html_description(); 
        
       
        
        $data = array(
            'title'      =>  $title,
            'description' => trim($description),
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html,
            
        );
        
         //echo '<pre>';print_r($data);die;
        
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }   
      }
      
      
      public function insertRenewableEnergyWorldContent()
      {
          $title = $this->get_title();
          $description = $this->get_description();
          $article_link = $this->get_article_link();
          $publish_date = $this->get_publish_date();
          $crawling_date = $this->get_crawling_date();
          $website_url = $this->get_website_url();
          $description_without_html = $this->get_without_html_description(); 
          $author  = $this->get_author();
        
       
        
        $data = array(
            'title'      =>  $title,
            'author'     =>  "$author",
            'description' => trim($description),
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html,
            
        );
//         echo '<pre>';
//                  print_r($data);die;
         
        
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }  
      }
      
      public function insertSupplyChainBrainContent()
      {
          $title = $this->get_title();
          $description = mysql_real_escape_string($this->get_description());
          $article_link = $this->get_article_link();
          $crawling_date = $this->get_crawling_date();
          $website_url = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description()); 
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
//         echo '<pre>';
//                  print_r($data);die;
         
        
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }  
      }
      
      public function insertTestAndMeasurementContent()
      {
          $title        = $this->get_title();
          $description  = mysql_real_escape_string($this->get_description());
          $author       = $this->get_author(); 
          $publish_date = $this->get_publish_date();
          $article_link = $this->get_article_link();
          $crawling_date= $this->get_crawling_date();
          $website_url  = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description()); 
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'author'     =>  "$author",
            'publish_date' => $publish_date,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
        
         
        
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }  
      }
      
      
      public function insertBlogData($blog_data)
      {
          
          $query = $this->db->set($blog_data)
                            ->insert('blog');
          if($query)
          {
              return true;
          }
          else
          {
              return false;
          }
      }
      
      public function insertWeldingAndGasesTodayContent()
      {
          $title        = $this->get_title();
          $description  = mysql_real_escape_string($this->get_description());
          $author       = $this->get_author(); 
          $publish_date = $this->get_publish_date();
          $article_link = $this->get_article_link();
          $crawling_date= $this->get_crawling_date();
          $website_url  = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description()); 
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'author'     =>  "$author",
            'publish_date' => $publish_date,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
        
         
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                } 
      }
      
      
      public function insertWeldingDesignContent()
      {
          $title = $this->get_title(); 
          $description = $this->get_description(); 
          $article_link = $this->get_article_link(); 
          $publish_date = $this->get_publish_date(); 
          $crawling_date = $this->get_crawling_date();
          $website_url = $this->get_website_url(); 
          $description_without_html = $this->get_without_html_description(); 
          $media_url = $this->get_content_url(); 
          $media_type = $this->get_media_type(); 
          $type = explode('/', $media_type);
        if($type[0] == "image" || $type[0]!="")
        {
            $media_type1 = "0";
        }
        else
        {
            $media_type1 = "1";
        }


        $query_media_check = $this->db->select('media_url')
                                ->from('tbl_media')
                                ->where('media_url',"$media_url")
                                ->get();
        $num_media = $query_media_check->num_rows();  
        
        if($num_media == 0)        
        {
        $query = $this->db->set('media_url',"$media_url")
                          ->set('media_type',"$media_type")
                          ->insert('tbl_media');
        
        if($query)
        {
            $media_id = $this->db->insert_id();
            
            $data = array(
            'title' => $title,
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url' => $website_url,
            'media_id'    => $media_id,
            'description_without_html' => $description_without_html,
                'image'               => "$media_url",
                'media_type'          => $media_type1
            
        ); 
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        }       
      }
      
      public function insertWindPowerContent()
      {
          $title        = $this->get_title();
          $description  = $this->get_description();
          $author       = $this->get_author(); 
          $publish_date = $this->get_publish_date();
          $article_link = $this->get_article_link();
          $crawling_date= $this->get_crawling_date();
          $website_url  = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description()); 
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'author'     =>  "$author",
            'publish_date' => $publish_date,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
        
         
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                } 
      }
      
      public function insertEDNContent()
      {
          $title        = $this->get_title();
          $description  = $this->get_description();
          $author       = $this->get_author(); 
          $publish_date = $this->get_publish_date();
          $article_link = $this->get_article_link();
          $crawling_date= $this->get_crawling_date();
          $website_url  = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description()); 
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'publish_date' => $publish_date,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
        
         
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                } 
      }
      
      public function insertAutomationIsaContent()
      {
          $title        = $this->get_title();
          $description  = $this->get_description();
          $author       = $this->get_author(); 
          $publish_date = $this->get_publish_date();
          $article_link = $this->get_article_link();
          $crawling_date= $this->get_crawling_date();
          $website_url  = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description());
          
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'publish_date' => $publish_date,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
         //echo '<pre>';print_r($data);die;
         
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                } 
      }

      
      public function insertLinearMotionTipsContent()
      {
          $title        = $this->get_title();
          $description  = $this->get_description();
          $author       = $this->get_author(); 
          $publish_date = $this->get_publish_date();
          $article_link = $this->get_article_link();
          $crawling_date= $this->get_crawling_date();
          $website_url  = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description()); 
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'author'     =>  "$author",
            'publish_date' => $publish_date,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
         
         
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                } 
      }
      
      
      public function insertMotionControlTipsContent()
      {
          $title        = $this->get_title();
          $description  = $this->get_description();
          $author       = $this->get_author(); 
          $publish_date = $this->get_publish_date();
          $article_link = $this->get_article_link();
          $crawling_date= $this->get_crawling_date();
          $website_url  = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description()); 
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'author'     =>  "$author",
            'publish_date' => $publish_date,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
         
         
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                } 
      }
      
      public function insertPackwordContent()
      {
          $title        = $this->get_title();
          $description  = $this->get_description();
          $author       = $this->get_author(); 
          $publish_date = $this->get_publish_date();
          $article_link = $this->get_article_link();
          $crawling_date= $this->get_crawling_date();
          $website_url  = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description()); 
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'author'     =>  "$author",
            'publish_date' => $publish_date,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
         
         
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                } 
      }
      
      
      public function insertScienceDailyContent()
      {
          $title        = $this->get_title();
          $description  = $this->get_description();
          $publish_date = $this->get_publish_date();
          $article_link = $this->get_article_link();
          $crawling_date= $this->get_crawling_date();
          $website_url  = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description()); 
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'publish_date' => $publish_date,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
         
         
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                } 
      }
      
      public function insertSolarNovasContent()
      {
          $title        = $this->get_title();
          $description  = $this->get_description();
          $author       = $this->get_author(); 
          $publish_date = $this->get_publish_date();
          $article_link = $this->get_article_link();
          $crawling_date= $this->get_crawling_date();
          $website_url  = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description()); 
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'author'     =>  "$author",
            'publish_date' => $publish_date,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
         
         
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                } 
      }
      
      
      public function insertSupplyChain247Content()
      {
          $title        = $this->get_title();
          $description  = $this->get_description();
          $publish_date = $this->get_publish_date();
          $article_link = $this->get_article_link();
          $crawling_date= $this->get_crawling_date();
          $website_url  = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description()); 
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'publish_date' => $publish_date,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
         
         
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                } 
      }
      
      public function insertTheEngineerContent()
      {
          $title        = $this->get_title();
          $description  = $this->get_description();
          $publish_date = $this->get_publish_date();
          $article_link = $this->get_article_link();
          $crawling_date= $this->get_crawling_date();
          $website_url  = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description()); 
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'publish_date' => $publish_date,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
         
         
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                } 

      }
      
      public function insertControlGlobalContent()
      {
          $title        = $this->get_title();
          $description  = $this->get_description();
          $publish_date = $this->get_publish_date();
          $article_link = $this->get_article_link();
          $crawling_date= $this->get_crawling_date();
          $website_url  = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description()); 
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'publish_date' => $publish_date,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
         
         //echo '<pre>';print_r($data);die;
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                } 
      }
      
      public function insertSpectrumIEEEContent()
      {
          $title = $this->get_title(); 
          $author = $this->get_author(); 
          $description = $this->get_description(); 
          $article_link = $this->get_article_link(); 
          $publish_date = $this->get_publish_date();  
          $crawling_date = $this->get_crawling_date();
          $website_url = $this->get_website_url(); 
          $description_without_html = $this->get_without_html_description(); 
          $media_url = $this->get_content_url(); 
          $media_type = $this->get_media_type(); 
          $type = explode('/', $media_type);
        if($type[0] == "image" || $type[0]!="")
        {
            $media_type1 = "0";
        }
        else
        {
            $media_type1 = "1";
        }


        $query_media_check = $this->db->select('media_url')
                                ->from('tbl_media')
                                ->where('media_url',"$media_url")
                                ->get();
        $num_media = $query_media_check->num_rows();  
        
        if($num_media == 0)        
        {
        $query = $this->db->set('media_url',"$media_url")
                          ->set('media_type',"$media_type")
                          ->insert('tbl_media');
        
        if($query)
        {
            $media_id = $this->db->insert_id();
            
            $data = array(
            'title' => $title,
            'author' => "$author",
            'description' => $description,
            'article_link' => $article_link,
            'publish_date' => $publish_date,
            'crawling_date' => $crawling_date,
            'website_url' => $website_url,
            'media_id'    => $media_id,
            'description_without_html' => $description_without_html,
                'image'               => "$media_url",
                'media_type'          => $media_type1
            
        ); 
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return false;
            }
        }       
      }
      
      
      public function insertReliablePlantContent()
      {
          $title        = $this->get_title();
          $description  = $this->get_description();
          $publish_date = $this->get_publish_date();
          $article_link = $this->get_article_link();
          $crawling_date= $this->get_crawling_date();
          $website_url  = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description());
          $author       = $this->get_author();
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'author'     => "$author",
            'publish_date' => $publish_date,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
         
         //echo '<pre>';print_r($data);die;
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                } 
      }
      
      public function insertGlobalEnergyWorldContent()
      {
          $title        = $this->get_title();
          $description  = $this->get_description();
          $publish_date = $this->get_publish_date();
          $article_link = $this->get_article_link();
          $crawling_date= $this->get_crawling_date();
          $website_url  = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description());
          
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'publish_date' => $publish_date,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
         
         //echo '<pre>';print_r($data);die;
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                } 
      }
      
      
      public function insertGreenTechMediaContent()
      {
          $title        = $this->get_title();
          $description  = $this->get_description();
          $publish_date = $this->get_publish_date();
          $article_link = $this->get_article_link();
          $crawling_date= $this->get_crawling_date();
          $website_url  = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description());
          
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'publish_date' => $publish_date,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
         
         //echo '<pre>';print_r($data);die;
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                } 
      }
      
      
      public function insertAutomationWorldContent()
      {
          $title        = $this->get_title();
          $description  = $this->get_description();
          $publish_date = $this->get_publish_date();
          $article_link = $this->get_article_link();
          $crawling_date= $this->get_crawling_date();
          $website_url  = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description());
          $author                   = $this->get_author();
          
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'author'     =>  "$author",
            'publish_date' => $publish_date,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
         
         //echo '<pre>';print_r($data);die;
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                } 
      }
      
      public function insertFasterAndFixingContent()
      {
          $title        = $this->get_title();
          $description  = $this->get_description();
          $publish_date = $this->get_publish_date();
          $article_link = $this->get_article_link();
          $crawling_date= $this->get_crawling_date();
          $website_url  = $this->get_website_url();
          $description_without_html = mysql_real_escape_string($this->get_without_html_description());
          
          
         
        
       
        
        $data = array(
            'title'      =>  $title,
            'publish_date' => $publish_date,
            'description' => trim($description),
            'article_link' => $article_link,
            'crawling_date' => $crawling_date,
            'website_url'   => $website_url,
            'description_without_html' => $description_without_html
            
        );
         
         //echo '<pre>';print_r($data);die;
        
            //*************checking if title is exists in the db table or not***********// 
        $query_check = $this->db->select('title')
                ->from('tbl_new_articles')
                ->where('title', $title)
                ->where('website_url', $website_url)
                ->get();

        $num = $query_check->num_rows();

        //***********if  article title not exists**************//   
        if ($num == 0) {
            $query = $this->db->set($data)
                    ->insert('tbl_new_articles');
        } else { //*********if exists**************//
            $query = $this->db->set($data)
                    ->where('title', $title)
                    ->update('tbl_new_articles');
        }
        if ($query) {
                    return TRUE;
                } else {
                    return FALSE;
                } 
      }
}
