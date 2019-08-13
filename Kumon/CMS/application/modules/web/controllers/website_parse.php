<?php
header('Content-Type:text/html; charset=UTF-8');
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class website_parse extends MX_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->library('simple_html_dom');
        $this->load->library('Rssparser');
        $this->load->helper('achrnews_helper');
        $this->load->helper('blog_helper');
        $this->load->helper('bigbasket_helper');
        $this->load->helper('basket_helper');
        $this->load->helper('bigbasket1_helper');
        
        
    }

    public function index() {
       

    }
    
    //****************function for getting content of arch news website rss*************//
     public function AchrNews()
     { 
         $obj = $this->load->model('website_parse_model');//*********loading the model*********//
         $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.achrnews.com/rss"); //***taking the rss url for getting all rss url on that page*******//
         
         foreach($html->find('div.record') as $rss) //***********parsing a perticular div for all rss link*************//
         { 
             foreach($rss->find('a') as $link)
             {
                
                 $url = $link->href;
                 
                 if (strpos($url, 'rss'))
                {
                     if (strpos($url, "www.achrnews.com") == false) {
                        $url_new = str_replace($url, "http://www.achrnews.com$url", $url); 
                    } else {
                        $url_new = $url;
                        
                    }

         //************taking the rss url data by using rss library**********//
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        { 
            
          $title = $item['title'];
          $author = $item['author'];
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $media_url = $item['enclosure']['url'];
          $media_type = $item['enclosure']['type'];
          
          
          $description_html = file_get_html($link);
          $full_description = $description_html->find('div.body');
          $description      = implode($full_description);//***********description with html*******//
          $final_description = $description;
          
          $description2 =preg_replace("/&nbsp;/",'',$final_description);
          
          $without_html_description = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $without_html_description);
          $final_description2 = $this->convert_ascii($final_description1); //***********description without html********//
          
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.achrnews.com";
          $crawling_date = date("Y-m-d");      
          
          //**********setting values using setter for rss*********//
          $obj->set_title($title);
          $obj->set_author($author);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($final_description);
          $obj->set_without_html_description($final_description2);
          $obj->set_content_url($media_url);
          $obj->set_media_type($media_type);
          
          //********calling model function to insert rss content into the database*********//
          $insert_data = $obj->insertAchrNewsContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
               }
              
         }  
    }
   }
   
   //****************function for getting content of AmericanMachinist website rss*************//
   
   public function AmericanMachinist()
   {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
          
         
         $rss = $this->rssparser->set_feed_url("http://americanmachinist.com/rss.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        { //echo '<pre>';print_r($item);die;
            
          $title = $item['title'];
          $link = $item['link'];
          
          $html = file_get_html($link);
                
          $description = $html->find('div.article-body');
          $full_description = implode($description);
          $description2 =preg_replace("/&nbsp;/",'',$full_description);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1);
          $publish_date = $item['pubDate'];
          
  
          if(isset($item['content_url']['medium']))
          {
               $media_type = $item['content_url']['medium'];
          }
          else
          {
               $media_type = $item['content_url']['type'];
          }
          $content_url =  $item['content_url']['url'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://americanmachinist.com";
          $crawling_date = date("Y-m-d");      
          

          $obj->set_title($title);
          $obj->set_media_type($media_type);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_content_url($content_url);
          $obj->set_without_html_description($final_description2);
          
          $insert_data = $obj->insertAmericanMachinistContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
                
   
   }
   //****************function for getting content of ApplianceDesign website rss*************//
   public function ApplianceDesign()
   {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.appliancedesign.com/rss");
         
         foreach($html->find('div.record') as $rss)
         { 
             foreach($rss->find('a') as $link)
             {
                
                 $url = $link->href;//echo $url."<br>";
                 
                 if (strpos($url, 'rss'))
                { 
                     if (strpos($url, "www.appliancedesign.com") == false) { 
                        $url_new = str_replace($url, "http://www.appliancedesign.com$url", $url); //echo $url_new."<br>";
                    } else { //echo $url;die;
                        
                        $url_new = $url;//echo $url_new."<br>";
                        
                    }
                
              //echo $url_new;die;
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link'];
         
          $author = $item['author'];
          
          
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.appliancedesign.com/";
          $crawling_date = date("Y-m-d"); 
          $media_url = $item['enclosure']['url'];
          $media_type = $item['enclosure']['type'];
          
          $html = file_get_html($link);
          $description = $html->find('div.body');
          $full_description  = trim(implode($description));
          $description2 =preg_replace("/&nbsp;/",'',$full_description);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1);
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($final_description2);
          $obj->set_media_type($media_type);
          $obj->set_content_url($media_url);
          $obj->set_author($author);
          $obj->set_without_html_description($final_description2);
          
          $insert_data = $obj->insertApplianceDesignContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".mysql_error().'<br>';
          }
        
        }
               }
              
         }  
    }
   }
   
   //****************function for getting content of ApplianceDesign website rss*************//
   public function AdhesivesMag()
   {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.adhesivesmag.com/rss");
         
         foreach($html->find('div.record') as $rss)
         { 
             foreach($rss->find('a') as $link)
             {
                
                 $url = $link->href;//echo $url."<br>";
                 
                 if (strpos($url, 'rss'))
                { 
                     if (strpos($url, "www.adhesivesmag.com/") == false) { 
                        $url_new = str_replace($url, "http://www.adhesivesmag.com$url", $url); //echo $url_new."<br>";
                    } else { //echo $url;die;
                        
                        $url_new = $url;//echo $url_new."<br>";
                        
                    }
                
              //echo $url_new;die;
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.adhesivesmag.com/";
          $crawling_date = date("Y-m-d"); 
          $media_url = $item['enclosure']['url']; 
          $media_type = $item['enclosure']['type']; 
          
          $html = file_get_html($link);
          $description = $html->find('div.body');
          $full_description  = trim(implode($description)); 
          $full_description1 = str_replace('src="','src="http://www.adhesivesmag.com' , $full_description);
          $description2 =preg_replace("/&nbsp;/",'',$full_description1);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1);
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description1);
          $obj->set_media_type($media_type);
          $obj->set_content_url($media_url);
          $obj->set_without_html_description($final_description2);
          
          
          $insert_data = $obj->insertAdhesivesMagContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".mysql_error().'<br>';
          }
        
        }
               }
              
         }  
    }
   }
   
   //****************function for getting content of AssemblyMag website rss*************//
   public function AssemblyMag()
   {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.assemblymag.com/rss");
         
         foreach($html->find('div.record') as $rss)
         { 
             foreach($rss->find('a') as $link)
             {
                
                 $url = $link->href;
                 
                 if (strpos($url, 'rss'))
                { 
                     if (strpos($url, "www.assemblymag.com/") == false) { 
                        $url_new = str_replace($url, "http://www.assemblymag.com$url", $url); //echo $url_new."<br>";
                    } else { //echo $url;die;
                        
                        $url_new = $url;//echo $url_new."<br>";
                        
                    }
                
              //echo $url_new;die;
            //************getting the website rss data*********************//
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.assemblymag.com";
          $crawling_date = date("Y-m-d"); 
          $media_url = $item['enclosure']['url'];
          $media_type = $item['enclosure']['type'];
          
          $html = file_get_html($link);
          $description = $html->find('p');
          $description1 = implode($description);
          $description_full = explode('</p>',$description1);
          $description_only = $description_full[0];
          $description2 =preg_replace("/&nbsp;/",'',$description_only);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1);
          $final_description3 = str_replace('MORE','',$final_description2);
          
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($description_only);
          $obj->set_media_type($media_type);
          $obj->set_content_url($media_url);
          $obj->set_without_html_description($final_description3);
          
          
          $insert_data = $obj->insertAssemblyMagContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
               }
              
         }  
    }
   }
   
   //****************function for getting content of AssemblyMag website rss*************//
   public function Automation()
   { 
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
          
     
         //$html = file_get_html("http://www.automation.com/automation-news/rss");
         
            
              //echo $url_new;die;
            //************getting the website rss data*********************//
         $rss = $this->rssparser->set_feed_url("http://www.automation.com/automation-news/rss")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  // echo '<pre>';print_r($item);die;
           
          $title = $item['title'];
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.automation.com/";
          $crawling_date = date("Y-m-d"); 
          //$media_url = $item['enclosure']['url'];
          //$media_type = $item['enclosure']['type'];
          
          $html = file_get_html($link);
          $description = $html->find('div.info-block');
          $full_description  = trim(implode($description));
          $full_description1 = str_replace('<img src="','<img src="http://www.automation.com',$full_description);
          $description2 =preg_replace("/&nbsp;/",'',$full_description1);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1);

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description1);
          $obj->set_without_html_description($final_description2);
          
          
          
          $insert_data = $obj->insertAutomationContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
         }  
    
   }
   
   //*************function for getting the content of bulkSolid Handling website rss**************//
   public function BulkSolidHandling()
   {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
          
     
            //************getting the website rss data*********************//
         $rss = $this->rssparser->set_feed_url("http://www.bulk-solids-handling.com/?q=rss.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  // echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; //echo $link;die;
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.bulk-solids-handling.com/";
          $crawling_date = date("Y-m-d"); 
          $description = $item['description']; 
          $description2 =preg_replace("/&nbsp;/",'',$description);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1);
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($description);
          $obj->set_without_html_description($final_description2);
          
          
          
          $insert_data = $obj->insertBulkSolidHandlingContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
         }  
    
   }
   
   
   ///********function for getting the content of canedian Metal website rss**************//
   public function CanedianMetal()
   {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://www.canadianmetalworking.com/feed")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  // echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.canadianmetalworking.com/";
          $crawling_date = date("Y-m-d"); 
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('div.the-content');
          $full_description = implode($description);
          $description2 =preg_replace("/&nbsp;/",' ',$full_description);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1);
          $final_description3 =  str_replace('&nbsp;',' ',$final_description2);
          
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description3);
          
          
          
          $insert_data = $obj->insertCanedianMetalgContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        } 
         
   }
         
   //************funtion for getting the content of chemical processing website rss******//
   public  function ChemicalProcessing()
   {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://www.chemicalprocessing.com/home/rss/")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  // echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.chemicalprocessing.com/";
          $crawling_date = date("Y-m-d"); 
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('div.Content');
          $full_description = implode($description);
          $description2 =preg_replace("/&nbsp;/",' ',$full_description);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1);
          $final_description3 =  str_replace('&nbsp;',' ',$final_description2);
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description3);
          
          
          
          $insert_data = $obj->insertChemicalProcessingContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        } 
   }
   
   public function ConnectorSuplier()
   {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://www.connectorsupplier.com/feed/")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.connectorsupplier.com/";
          $crawling_date = date("Y-m-d"); 
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('div.pf-content');
          $full_description = implode($description);
          $description2 =preg_replace("/&nbsp;/",' ',$full_description);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace(""," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description3);
          
          
          
          $insert_data = $obj->insertConnectorSuplierContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        } 
   }
   
   public function CseMag()
   {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.csemag.com/industry-news/rss-feeds.html");
         
         foreach($html->find('div.tx-chnewsfeeds-pi1') as $rss)
         { 
             foreach($rss->find('a') as $link)
             {
                
                 $url = $link->href;
                 
                 if (strpos($url, 'rss'))
                { 
                     if (strpos($url, "http://www.csemag.com/") == false) { 
                        $url_new = str_replace($url, "http://www.csemag.com/$url", $url); //echo $url_new."<br>";
                    } else { //echo $url;die;
                        
                        $url_new = $url;//echo $url_new."<br>";
                        
                    }
                
                
              //echo $url_new;die;
            //************getting the website rss data*********************//
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.csemag.com/";
          $crawling_date = date("Y-m-d"); 
          
          
          $html = file_get_html($link);
          $description = $html->find('div.news-single-item');
          $description1 = implode($description);
          
          $description2 =preg_replace("/&nbsp;/",'',$description1);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1);
          $final_description3 = str_replace('&nbsp;',' ',$final_description2);
          
          
          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($description1);
          $obj->set_without_html_description($final_description3);
          
          
          $insert_data = $obj->insertCseMagContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
               }
              
         }  
    }
   }
   
   public function ControlEng()
   {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.controleng.com/industry-news/rss-feeds.html");
         
         foreach($html->find('div.tx-chnewsfeeds-pi1') as $rss)
         { 
             foreach($rss->find('a') as $link)
             {
                
                 $url = $link->href;
                 
                 if (strpos($url, 'rss'))
                { 
                     if (strpos($url, "http://www.controleng.com/") == false) { 
                        $url_new = str_replace($url, "http://www.controleng.com/$url", $url); //echo $url_new."<br>";
                    } else { //echo $url;die;
                        
                        $url_new = $url;//echo $url_new."<br>";
                        
                    }
                
                
              //echo $url_new;die;
            //************getting the website rss data*********************//
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.controleng.com/";
          $crawling_date = date("Y-m-d"); 
          
          
          $html = file_get_html($link);
          $description = $html->find('div.news-single-item');
          $description1 = implode($description);
          
          $description2 =preg_replace("/&nbsp;/",'',$description1);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1);
          $final_description3 = str_replace('&nbsp;',' ',$final_description2); 
          
          
          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($description1);
          $obj->set_without_html_description($final_description3);
          
          
          $insert_data = $obj->insertControlEngContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
               }
              
         }  
    }
   }
      
     public function CteMag()
     {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://www.ctemag.com/rss.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.ctemag.com/";
          $crawling_date = date("Y-m-d"); 
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('p');
          $full_description = implode($description); 
          $full_description1 = str_replace('© Copyright 1995-2014. Cutting Tool Engineering. All rights reserved.','',$full_description); 
          $description2 =preg_replace("/&nbsp;/",' ',$full_description1);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2);
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description1);
          $obj->set_without_html_description($final_description3);
          
          
          
          $insert_data = $obj->insertCteMagContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        } 
 }
 
 public function DesignNews()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://www.designnews.com/rss_simple.asp")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.designnews.com/";
          $crawling_date = date("Y-m-d"); 
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('p');
          $full_description = implode($description); 
          $full_description1 = str_replace('Related posts:','',$full_description); 
          $description2 =preg_replace("/&nbsp;/",' ',$full_description1);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2);
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description1);
          $obj->set_without_html_description($final_description3);
          
          
          
          $insert_data = $obj->insertDesignNewsContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        } 
 }
 
 
 public function DrivesNControls()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://www.drivesncontrols.com/news/rss.php/feed.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.designnews.com/";
          $crawling_date = date("Y-m-d"); 
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('p');
          $full_description = implode($description); 
          $full_description1 = explode('<p class="Body-P-P0">',$full_description);
          $full_description2 = $full_description1[0];
          $description2 =preg_replace("/&nbsp;/",' ',$full_description2);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description2);
          $obj->set_without_html_description($final_description3);
          
          
          
          $insert_data = $obj->insertDrivesNControlsContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        } 
 }
 
 
 public function EeTimes()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://www.eetimes.com/rss_simple.asp")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.eetimes.com/";
          $crawling_date = date("Y-m-d"); 
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('div.grayshowlinks');
          $full_description = implode($description);
          $description2 =preg_replace("/&nbsp;/",' ',$full_description);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description3);
          
          
          
          $insert_data = $obj->insertEeTimesContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        } 
 }
 
 public function EhsToday()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://ehstoday.com/rss.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $content_url = $item['content_url']['url'];
          $media_type  = $item['content_url']['medium'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://ehstoday.com/";
          $crawling_date = date("Y-m-d"); 
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('p');
          $full_description = implode($description); 
          $full_description1 = explode('<iframe',$full_description);
          $full_description2 = $full_description1[0];
          $description2 =preg_replace("/&nbsp;/",' ',$full_description2);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_content_url($content_url);
          $obj->set_without_html_description($final_description3);
          $obj->set_media_type($media_type);
          
          
          
          $insert_data = $obj->insertEhsTodayContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        } 
 }
 
 
 public function Ecmag()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://www.ecmag.com/articles_rss.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.ecmag.com/";
          $crawling_date = date("Y-m-d"); 
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('div.field-items');
          $full_description = implode($description); 
          $full_description1 = str_replace('Bimonthly supplement speaks to integrated systems','',$full_description);
          $description2 =preg_replace("/&nbsp;/",' ',$full_description1);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description1);
          $obj->set_without_html_description($final_description3);
          
          
          
          $insert_data = $obj->insertEcmagContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        } 
 }
 
 
 public function ElectronicSpecifier()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://www.electronicspecifier.com.br/?format=rss")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.electronicspecifier.com/";
          $crawling_date = date("Y-m-d"); 
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('div.row-fluid');
          $full_description = implode($description);
           $description1     = explode('<strong>', $full_description);
           $description2     = $description1[4];
           $description3     = explode('<div class="videoEmbed">',$description2);
           $description4     = $description3[0];
          $description2 =preg_replace("/&nbsp;/",' ',$description4);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($description4);
          $obj->set_without_html_description($final_description3);
          
          
          
          $insert_data = $obj->insertElectronicSpecifierContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        } 
 }
 
 
 public function ElectronicDesign()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://electronicdesign.com/rss.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; //echo $link;
          $publish_date = $item['pubDate'];
          $description  = $item['description'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://electronicdesign.com";
          $crawling_date = date("Y-m-d"); 
         
          //***********getting the full description from the article link*******//
          $description1 = str_replace('read more','',$description);
          $description2 =preg_replace("/&nbsp;/",' ',$description1);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($description);
          $obj->set_without_html_description($final_description3);
          
          
          
          $insert_data = $obj->insertElectronicDesignContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        } 
 }
 
 
 public function ElectronicsWeekly()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://www.electronicsweekly.com/news/feed/")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.electronicsweekly.com/";
          $crawling_date = date("Y-m-d"); 
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('div.clearfix');
           $full_description  = implode($description);
           $description1     = explode('<div class="social-media clearfix">', $full_description); 
           $description2     = $description1[0];
           $description3     = explode('<p>',$description2);
           $description4     = array_shift($description3);
           $final_des        = implode($description3);
          $description2 =preg_replace("/&nbsp;/",' ',$final_des); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($final_des);
          $obj->set_without_html_description($final_description3);
          
          
          
          $insert_data = $obj->insertElectronicsWeeklyContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        } 
 }
 
 
 public function EngineerLive()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://www.engineerlive.com/rss.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.engineerlive.com/";
          $crawling_date = date("Y-m-d"); 
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('div.field-items');
           $full_description  = implode($description);
           $description1     = explode('<div class="field-item even">', $full_description); //echo '<pre>';print_r($description1);die;
           $description     = $description1[0];
          $description2 =preg_replace("/&nbsp;/",' ',$description); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($description);
          $obj->set_without_html_description($final_description3);
          
          
          
          $insert_data = $obj->insertEngineerLiveContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        } 
 }
 
 public function EngineeringTv()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://www.engineeringtv.com/feed/recent.rss")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.engineeringtv.com/";
          $crawling_date = date("Y-m-d"); 
          $media_url = $item['enclosure']['url']; 
          $media_type = $item['enclosure']['type'];
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('div.magnify-player-description');
           $full_description  = implode($description);
           $description1     = explode('<div class="field-item even">', $full_description); //echo '<pre>';print_r($description1);die;
           $description     = $description1[0];
          $description2 =preg_replace("/&nbsp;/",' ',$description); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($description);
          $obj->set_without_html_description($final_description3);
          $obj->set_media_type($media_type);
          $obj->set_content_url($media_url);
          
          
          
          $insert_data = $obj->insertEngineeringTvContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        } 
 }
 
 public function FlexPackmag()
 {
          $obj = $this->load->model('website_parse_model');
          $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.flexpackmag.com/rss");
         
         foreach($html->find('div.record') as $rss)
         { 
             foreach($rss->find('a') as $link)
             {
                
                 $url = $link->href;//echo $url."<br>";
                 
                 if (strpos($url,'rss'))
                { 
                     if (strpos($url, "www.flexpackmag.com/") == false) { 
                        $url_new = str_replace($url, "http://www.flexpackmag.com$url", $url); //echo $url_new."<br>";
                    } else { //echo $url;die;
                        
                        $url_new = $url;//echo $url_new."<br>";
                        
                    }
                
              //echo $url_new;die;
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.flexpackmag.com";
          $crawling_date = date("Y-m-d"); 
          $media_url = $item['enclosure']['url']; 
          $media_type = $item['enclosure']['type']; 
          
          $html = file_get_html($link);
          $description = $html->find('div.body');
          $full_description  = trim(implode($description)); 
          $full_description1 = explode('<span style="',$full_description);
          $description2 =preg_replace("/&nbsp;/",'',$full_description1[0]);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1);
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description1[0]);
          $obj->set_media_type($media_type);
          $obj->set_content_url($media_url);
          $obj->set_without_html_description($final_description2);
          
          
          $insert_data = $obj->insertFlexPackmagContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
               }
              
         }  
    }
 }
 
 
 public function FlowControlNetwork()
 {
          $obj = $this->load->model('website_parse_model');
          $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.flowcontrolnetwork.com/rss");
         
         foreach($html->find('div.record') as $rss)
         { 
             foreach($rss->find('a') as $link)
             {
                
                 $url = $link->href;//echo $url."<br>";
                 
                 if (strpos($url,'rss'))
                { 
                     if (strpos($url,"www.flowcontrolnetwork.com") == false) { 
                        $url_new = str_replace($url, "http://www.flowcontrolnetwork.com$url", $url); //echo $url_new."<br>";
                    } else { //echo $url;die;
                        
                        $url_new = $url;//echo $url_new."<br>";
                        
                    }
                
              //echo $url_new;die;
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "www.flowcontrolnetwork.com";
          $crawling_date = date("Y-m-d"); 
          $media_url = $item['enclosure']['url']; 
          $media_type = $item['enclosure']['type']; 
          
          $html = file_get_html($link);
          $description = $html->find('div.body');
          $full_description  = trim(implode($description)); //echo $full_description;die; 
          $full_description1 = explode('<div class="headline">',$full_description);//echo '<pre>';print_r($full_description1);die;
          $full_description2 = explode('<a href="mailto:FlowControl@GrandViewMedia.com">FlowControl@GrandViewMedia.com</a>.', $full_description1[1]);//echo '<pre>';print_r($full_description2);die;
          $description2 =preg_replace("/&nbsp;/",'',$full_description2[1]);//echo $full_description2[1];die;
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1);
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description2[1]);
          $obj->set_media_type($media_type);
          $obj->set_content_url($media_url);
          $obj->set_without_html_description($final_description2);
          
          
          $insert_data = $obj->insertFlowControlNetworkContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';die;
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
               }
              
         }  
    }
 }
 
 
 public function FoodandBeverAgePackaging()
 {
          $obj = $this->load->model('website_parse_model');
          $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.foodandbeveragepackaging.com/rss");
         
         foreach($html->find('div.record') as $rss)
         { 
             foreach($rss->find('a') as $link)
             {
                
                 $url = $link->href;//echo $url."<br>";
                 
                 if (strpos($url,'rss'))
                { 
                     if (strpos($url,"www.foodandbeveragepackaging.com") == false) { 
                        $url_new = str_replace($url, "http://www.foodandbeveragepackaging.com$url", $url); //echo $url_new."<br>";
                    } else { //echo $url;die;
                        
                        $url_new = $url;//echo $url_new."<br>";
                        
                    }
                
              //echo $url_new;die;
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link']; //echo $link;
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "www.foodandbeveragepackaging.com";
          $crawling_date = date("Y-m-d"); 
          $media_url  =  $item['enclosure']['url']; 
          $media_type = $item['enclosure']['type']; 
          
          $html = file_get_html($link);
          $description = $html->find('div.body');
          $full_description  = trim(implode($description)); //echo $full_description;die; 
          $full_description1 = explode('<div class="headline">',$full_description);//echo '<pre>';print_r($full_description1);die;
          $full_description2 = explode('<blockquote> ', $full_description1[0]);//echo '<pre>';print_r($full_description2);die;
          $full_description3 = explode('<div class="editorial-content__body body">',$full_description2[0]);
          $description2 =preg_replace("/&nbsp;/",'',$full_description3[0]);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description3[0]);
          $obj->set_media_type($media_type);
          $obj->set_content_url($media_url);
          $obj->set_without_html_description($final_description2);
          
          
          $insert_data = $obj->insertFoodandBeverAgePackagingContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
               }
              
         }  
    }
 }
 
 public function GlobalEnergyWorld() {
        $obj = $this->load->model('website_parse_model');
        $obj = new website_parse_model();


        $html = file_get_html("http://www.globalenergyworld.com/rss.asp");

        foreach ($html->find('table') as $rss) {
            
            foreach ($rss->find('a') as $link) {

                $url = $link->href; //echo $url."<br>";

                if (strpos($url, 'rss')) {

              if (strpos($url,"www.globalenergyworld.com") == false) { 
                        $url_new = str_replace($url, "http://www.globalenergyworld.com$url", $url); //echo $url_new."<br>";
                    } else { //echo $url;die;
                        
                        $url_new = $url;//echo $url_new."<br>";
                        
                    }

                //echo $url_new;die;
                $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

                foreach ($rss as $item) {

                    $title = $item['title'];
                    $link = $item['link'];// echo $link;
                    $publish_date = $item['pubDate'];
                    $pub_newdate = $this->change_date($publish_date);
                    $website_url = "http://www.globalenergyworld.com";
                    $crawling_date = date("Y-m-d");
                   
                    $link1 = str_replace('"','',$link);
                    $html = file_get_html($link1);
                    $description = $html->find('div#content_news');
                    $full_description = trim(implode($description)); 
                    $full_desc = explode('Source',$full_description);
                    $full_description1 =  $full_desc[0]; 
                    $full_description2 = str_replace('<img src="','<img src="http://www.globalenergyworld.com',$full_description1); 
                    $full_description3 = str_replace('click to enlarge','',$full_description2);
                    $description2 = preg_replace("/&nbsp;/", '',$full_description3);
                    $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8')))); //****************description without html******************//
                    $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
                    $final_description2 = $this->convert_ascii($final_description1); 


                    $obj->set_title($title);
                    $obj->set_article_link($link);
                    $obj->set_publish_date($pub_newdate);
                    $obj->set_website_url($website_url);
                    $obj->set_crawling_date($crawling_date);
                    $obj->set_description($full_description2);
                    
                    $obj->set_without_html_description($final_description2);


                    $insert_data = $obj->insertGlobalEnergyWorldContent();
                    if ($insert_data) {
                        echo "inserted successfully" . '<br>';
                    } else {
                        echo "data not inserted successfully" . '<br>';
                    }
                }
            }
            }
        }
    }

    public function HPAC()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://hpac.com/rss.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate']; 
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://hpac.com";
          $crawling_date = date("Y-m-d"); 
          $media_url =  $item ['content_url']['url']; 
          $media_type = $item['content_url']['medium'];
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('div.article-body');
           $full_description  = implode($description); //echo $full_description;die;
//           $full_description1 = explode('<a href="#" class="readMoreLink">More</a>',$full_description); //echo '<pre>';print_r($full_description1);die;
//           $full_description2 = $full_description1[1]; //echo $full_description2;die;
//           $searchArray = array("Share Image", "EMAIL", "fullscreen");
//           $full_description3 = str_replace($searchArray, '', $full_description2);//echo $full_description3;die; 
//           $full_description4 = strip_tags($full_description3,'<ul>');//echo $full_description4;die;
          
          $description2 =preg_replace("/&nbsp;/",' ',$full_description); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); //echo $final_description3;die;
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description3);
          $obj->set_media_type($media_type);
          $obj->set_content_url($media_url);
          
          
          
          $insert_data = $obj->insertHPACTvContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';//die;
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        } 
 }
 
 public function LabelandnArrowWeb()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://www.labelandnarrowweb.com/feedcreator/rss")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.labelandnarrowweb.com";
          $crawling_date = date("Y-m-d"); 
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('div#contentBody');
           $full_description  = implode($description); 
           
          $description2 =preg_replace("/&nbsp;/",' ',$full_description); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description3);
          
          
          
          
          $insert_data = $obj->insertLabelandnArrowWebContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }  
 }
 
 
 public function MachineDesign()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://machinedesign.com/rss.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://machinedesign.com";
          $crawling_date = date("Y-m-d"); 
          $media_url =  $item['content_url']['url']; 
          $media_type = $item['content_url']['medium'];
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('div.clear-block');
          $full_description  = implode($description); //echo $full_description;die;
          $full_description1 = explode('<div class="inside panels-flexible-region-inside panels-flexible-region-2-footer_site_features-inside panels-flexible-region-inside-first">',$full_description); 
          $full_description2 = $full_description1[0]; 
          $full_description3 = str_replace('<img alt="" src="','<img alt="" src="http://machinedesign.com',$full_description2);
          $description2 =preg_replace("/&nbsp;/",' ',$full_description2); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description3);
          $obj->set_without_html_description($final_description3);
          $obj->set_media_type($media_type);
          $obj->set_content_url($media_url);
          
          
          
          
          $insert_data = $obj->insertMachineDesignContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }  
 }
 
 public function Mecatronicaatual()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://www.mecatronicaatual.com.br/?format=feed&type=rss")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $author = $item['author'];
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.mecatronicaatual.com.br";
          $crawling_date = date("Y-m-d"); 
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('div.item-page');
          $full_description  = implode($description); 
          $full_description1 = explode('<div class="itp-share">',$full_description); 
          $full_description2 = $full_description1[1]; 
          $full_description3 = strip_tags($full_description2,'<script>');
          $full_description4 = str_replace('Tweet','',$full_description3); 
          $full_description5 = str_replace('Share on Thumblr','',$full_description4);
         
          $description2 =preg_replace("/&nbsp;/",' ',$full_description5); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_author($author);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description5);
          $obj->set_without_html_description($final_description3);
          
          
          
          
          $insert_data = $obj->insertMecatronicaatualContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';//die;
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }  
 }
 
 public function MetalFormingMagazine()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://www.metalformingmagazine.com/rss/hot_off_the_press.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.metalformingmagazine.com";
          $crawling_date = date("Y-m-d"); 
          $full_description =  $item['description'];
          
         
          //***********getting the full description from the article link*******//
          
          $description1 = str_replace('[Read more]','',$full_description);
          $description2 =preg_replace("/&nbsp;/",' ',$description1); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2);
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description3);
          
          
          
          
          $insert_data = $obj->insertMetalFormingMagazineContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }  
 }
 
 public function MhlNews()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         $rss = $this->rssparser->set_feed_url("http://mhlnews.com/rss.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link'];
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://mhlnews.com";
          $crawling_date = date("Y-m-d"); 
          $media_url =  $item['content_url']['url']; 
          $media_type = $item['content_url']['medium'];
          
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('div.article-body');
          $full_description  = implode($description); 
          $description2 =preg_replace("/&nbsp;/",' ',$full_description); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2);
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description3);
          $obj->set_content_url($media_url);
          $obj->set_media_type($media_type);
          
          
          
          
          $insert_data = $obj->insertMhlNewsContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }  
 }
 
 public function MhwMagzine()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://mhwmagazine.co.uk/rss.asp")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link'];
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://mhwmagazine.co.uk";
          $crawling_date = date("Y-m-d"); 
          
          
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('div.bodytext');
          $full_description  = implode($description); 
          $description2 =preg_replace("/&nbsp;/",' ',$full_description); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2);
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description3);
          
          
          
          
          $insert_data = $obj->insertMhwMagzineContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
        
        
    }
    
    public function TechnologyReview()
        {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
          
     
         $rss = file_get_html("http://www.technologyreview.com/connect/#rss");
         
          
             foreach($rss->find('a.rss') as $link)
             {
                
                 $url = $link->href;//echo $url."<br>";
                 
                 $url_new = "http://www.technologyreview.com".$url;//echo $url_new;die;
                
              //echo $url_new;die;
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link']; //echo $link;
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.technologyreview.com";
          $crawling_date = date("Y-m-d"); 
          
          
          $html = file_get_html($link);
          $description = $html->find('section.body');
          $full_description  = trim(implode($description));
          $description2 =preg_replace("/&nbsp;/",'',$full_description);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description2);
          
          
          $insert_data = $obj->insertTechnologyReviewContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
              
              
     }  
    
 }
 
 public function Mmdonline()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://www.mmdonline.com/feed/")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link'];
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.mmdonline.com";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('div.the-content');
          $full_description  = implode($description); 
          $description2 =preg_replace("/&nbsp;/",' ',$full_description); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2);
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description3);
          
          
          
          
          $insert_data = $obj->insertMmdonlineContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
 }
 
 
 public function MoldMakingTechnology()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://www.moldmakingtechnology.com/rss/blog")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link'];
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.moldmakingtechnology.com";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('div.text');
          $full_description  = implode($description); 
          $full_description1 = str_replace('<img alt="" src="','<img alt="" src="http://www.moldmakingtechnology.com',$full_description);
          $description2 =preg_replace("/&nbsp;/",' ',$full_description1); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description1);
          $obj->set_without_html_description($final_description3);
          
          
          $insert_data = $obj->insertMoldMakingTechnologyContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
 }
 
 public function NASA()
 {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.nasa.gov/rss/#.VHQWYnWSw8o");
         
          foreach($html->find('ul.rssxml') as $rss)
          {
             foreach($rss->find('a') as $link)
             {
                
                 $url = $link->href;//echo $url."<br>";
                 
                 if (strpos($url, 'rss'))
                {  
                     if (strpos($url, "nasa") == false ) { 
                        $url_new = str_replace($url, "http://www.nasa.gov$url", $url); //echo $url_new."<br>";//die;
                    } else { //echo $url;die;
                        
                        $url_new = $url;//echo $url_new."<br>";
                        
                    }
               
                
              //echo $url_new;die;
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.nasa.gov/";
          $crawling_date = date("Y-m-d"); 
          
          
          $html = file_get_html($link);
          $description = $html->find('div.field-items');
          $full_description  = trim(implode($description)); 
          $full_description1 = explode('<div class="field-label">',$full_description);
          $description2 =preg_replace("/&nbsp;/",'',$full_description1[0]);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description1[0]);
          $obj->set_without_html_description($final_description2);
          
          
          $insert_data = $obj->insertNASAContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
    }
  }
  }
 }
 
 public function NREL()
 {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://www.nrel.gov/news/press/rss/rss.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link'];
          $author = $item['author'];
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.nrel.gov";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      = $description_html->find('div#column-center');
          $full_description  = implode($description); 
          $full_description1 = explode('<h3>',$full_description);
          $full_description2 = explode('Visit NREL',$full_description1[1]);
          $description2 =preg_replace("/&nbsp;/",' ',$full_description2[0]); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description2[0]);
          $obj->set_without_html_description($final_description3);
          $obj->set_author($author);
          
          
          $insert_data = $obj->insertNRELContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
        
        
    }
    
    public function PaceToday()
        {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://www.pacetoday.com.au/rss.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $media_url = $item['enclosure']['url'];
          $media_type = $item['enclosure']['type'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.pacetoday.com.au";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      =  $description_html->find('div.content');
          $full_description  = implode($description);
          $full_description1 = preg_replace('#<div class="latestnews">(.*?)</div>#', '', $full_description);
          $description2 =preg_replace("/&nbsp;/",' ',$full_description1); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description1);
          $obj->set_without_html_description($final_description3);
          $obj->set_media_type($media_type);
          $obj->set_content_url($media_url);
          
          
          
          $insert_data = $obj->insertPaceTodayContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
    }
    
    public function PackagingDigest()
    {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
        $rss = $this->rssparser->set_feed_url("http://www.packagingdigest.com/rss.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.packagingdigest.com";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      =  $description_html->find('div.content');
          $full_description  = implode($description); 
          $full_description1 = preg_replace('#<div class="latestnews">(.*?)</div>#', '', $full_description);
          $description2      = preg_replace("/&nbsp;/",' ',$full_description1); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description1);
          $obj->set_without_html_description($final_description3);
          
          
          
          $insert_data = $obj->insertPackagingDigestContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
    }
    
    public function PlantEngineering()
    {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.plantengineering.com/industry-news/rss-feeds.html");
         
         foreach($html->find('div.tx-chnewsfeeds-pi1') as $rss)
         { 
             foreach($rss->find('a') as $link)
             {
                
                 $url = $link->href;
                 
                 if (strpos($url, 'rss'))
                { 
                     if (strpos($url, "http://www.plantengineering.com") == false) { 
                        $url_new = str_replace($url, "http://www.plantengineering.com/$url", $url); //echo $url_new."<br>";
                    } else { //echo $url;die;
                        
                        $url_new = $url;//echo $url_new."<br>";
                        
                    }
                
                
              //echo $url_new;die;
            //************getting the website rss data*********************//
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.plantengineering.com";
          $crawling_date = date("Y-m-d"); 
          
          
          $html = file_get_html($link);
          $description = $html->find('div.news-single-item');
          $description1 = implode($description);
          
          $description2 =preg_replace("/&nbsp;/",'',$description1);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1);
          $final_description3 = str_replace('&nbsp;',' ',$final_description2); 
          
          
          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($description1);
          $obj->set_without_html_description($final_description3);
          
          
          $insert_data = $obj->insertControlEngContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
               }
              
         }  
    }
  }
  
  public function PemMag()
  {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://www.pem-mag.com/rss")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.pem-mag.com";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      =  $description_html->find('div.itemBody');
          $full_description  = implode($description); 
          $description2 =preg_replace("/&nbsp;/",' ',$full_description); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description3);
         
          
          
          
          $insert_data = $obj->insertPemMagContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
  }
  
  public function PlumbingAndHvac()
  {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://plumbingandhvac.ca/top5news_rss.cfm")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $website_url = "http://plumbingandhvac.ca";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      =  $description_html->find('p');
          $full_description  = implode($description); 
          $a = array( 'Monthly Newsletter', 'The Magazine', 'Promotional Offers'); 
          $full_description1 = str_replace($a,'',$full_description); 
          $description2 =preg_replace("/&nbsp;/",' ',$full_description1); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description1);
          $obj->set_without_html_description($final_description3);
         
          
          
          
          $insert_data = $obj->insertPlumbingAndHvacContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
  }
  
  public function PowderBulkSolids()
  {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://www.powderbulksolids.com/rss.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.powderbulksolids.com";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      =  $description_html->find('p');
          $full_description  = implode($description); 
          $full_description1 = explode('For related articles',$full_description);
         
          $description2 =preg_replace("/&nbsp;/",' ',$full_description1[0]); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description1[0]);
          $obj->set_without_html_description($final_description3);
         
         
          
          
          
          $insert_data = $obj->insertPowerBulkSolidsContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
  }
  
  public function ProcessingMagzine()
  {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.processingmagazine.com/rss");
         
         foreach($html->find('div.record') as $rss)
         { 
             foreach($rss->find('a') as $link)
             {
                
                 $url = $link->href;
                 
                 if (strpos($url, 'rss') && (!strpos($url, 'topic')))
                { 
                     if (strpos($url, "www.processingmagazine.com") == false) { 
                        $url_new = str_replace($url, "http://www.processingmagazine.com$url", $url); //echo $url_new."<br>";
                    } else { //echo $url;die;
                        
                        $url_new = $url;//echo $url_new."<br>";
                        
                    }
                
                
              //echo $url_new;die;
//            //************getting the website rss data*********************//
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link']; 
          $author = $item['author'];
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.processingmagazine.com";
          $crawling_date = date("Y-m-d"); 
          $media_url = $item['enclosure']['url'];
          $media_type = $item['enclosure']['type'];
          
          
          $html = file_get_html($link);
          $description = $html->find('div.body');
          $description1 = implode($description); 
          $description_full = preg_replace('#<div class="editorial-content__body body">(.*?)</div>#', '', $description1);
          $description_full1 = str_replace('OAS_AD("Frame1");','',$description_full);
          $description2 =preg_replace("/&nbsp;/",'',$description_full1);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1);
          $final_description3 = str_replace('&nbsp;',' ',$final_description2); 
          
          
          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($description_full);
          $obj->set_without_html_description($final_description3);
          $obj->set_author($author);
          $obj->set_media_type($media_type);
          $obj->set_content_url($media_url);
          
          
          $insert_data = $obj->insertProcessingMagzineContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
               }
              
         }  
    }
  }
  
  public function QualityMag()
  {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.qualitymag.com/rss");
         
         foreach($html->find('div.record') as $rss)
         { 
             foreach($rss->find('a') as $link)
             {
                
                 $url = $link->href;
                 
                 if (strpos($url, 'rss'))
                { 
                     if (strpos($url, "www.qualitymag.com") == false) { 
                        $url_new = str_replace($url, "http://www.qualitymag.com$url", $url); //echo $url_new."<br>";
                    } else { //echo $url;die;
                        
                        $url_new = $url;//echo $url_new."<br>";
                        
                    }
                
                
              //echo $url_new;die;
//            //************getting the website rss data*********************//
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "www.qualitymag.com";
          $crawling_date = date("Y-m-d"); 
          $media_url = $item['enclosure']['url'];
          $media_type = $item['enclosure']['type'];
          
          
          $html = file_get_html($link);
          $description = $html->find('div.body');
          $description1 = implode($description); 
          $description_full = preg_replace('#<div class="editorial-content__body body">(.*?)</div>#', '', $description1);
          $description2 =preg_replace("/&nbsp;/",'',$description_full);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1);
          $final_description3 = str_replace('&nbsp;',' ',$final_description2); 
          
          
          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($description_full);
          $obj->set_without_html_description($final_description3);
          $obj->set_media_type($media_type);
          $obj->set_content_url($media_url);
          
          
          $insert_data = $obj->insertQualityMagContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
               }
              
         }  
    }
  }
  
  public function RenewGrid()
  { 
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://www.renew-grid.com/rss/rss.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.renew-grid.com";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      =  $description_html->find('td.content_body');
          $full_description  = implode($description);  
          
          $description2 =preg_replace("/&nbsp;/",' ',$full_description); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description3);
         
         
          
          
          
          $insert_data = $obj->insertRenewGridContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
  }
  
  public function RewMag()
  {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://www.rewmag.com/RSS.ashx")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.rewmag.com";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      =  $description_html->find('div#article-body');
          $full_description  = implode($description);  
          
          $description2 =preg_replace("/&nbsp;/",' ',$full_description); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2);
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description3);
         
          
          $insert_data = $obj->insertRewMagContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
  }
  
  public function RenewableEnergyWorld()
  {
         $obj = $this->load->model('website_parse_model');
         $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.renewableenergyworld.com/rea/news/syndicate");
         
         foreach($html->find('ul.feedList') as $rss)
         { 
             foreach($rss->find('a') as $link)
             {
                
                 $url = $link->href;
                 
                 if (strpos($url, 'rss'))
                { 
                     if (strpos($url, "www.renewableenergyworld.com/") == false) { 
                        $url_new = str_replace($url, "http://www.renewableenergyworld.com$url", $url); //echo $url_new."<br>";
                    } else { //echo $url;die;
                        
                        $url_new = $url;//echo $url_new."<br>";
                        
                    }
                
                
              //echo $url_new;die;
//            //************getting the website rss data*********************//
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link']; 
          $author = $item['creator']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.renewableenergyworld.com";
          $crawling_date = date("Y-m-d"); 
          
          
          
          $html = file_get_html($link);
          $description = $html->find('div.contentBody');
          $description1 = implode($description); 
          
          $description2 =preg_replace("/&nbsp;/",'',$description1);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1);
          $final_description3 = str_replace('&nbsp;',' ',$final_description2); 
          
          
          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($description1);
          $obj->set_without_html_description($final_description3);
          $obj->set_author($author);
          
          $insert_data = $obj->insertRenewableEnergyWorldContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
               }
              
         }  
    }
  }
  
  public function SupplyChainBrain()
  {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://www.supplychainbrain.com/content/index.php?id=277")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link']; 
          
          $website_url = "http://www.supplychainbrain.com";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      =  $description_html->find('p');
          $full_description  = implode($description);  
          $full_description1 = explode('Read Full Article',$full_description); 
          $description2 =preg_replace("/&nbsp;/",' ',$full_description1[0]); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2);
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description1[0]);
          $obj->set_without_html_description($final_description3);
         
          
          $insert_data = $obj->insertSupplyChainBrainContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
  }
  
  public function TestAndMeasurement()
  {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://www.testandmeasurementtips.com/feed/")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link'];
          $author = $item['creator']; 
          $content = $item['content_encoded'];
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.testandmeasurementtips.com";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
          
          $description2 =preg_replace("/&nbsp;/",' ',$content); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2);
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_author($author);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($content);
          $obj->set_without_html_description($final_description3);
          $obj->set_publish_date($pub_newdate);
         
          
          $insert_data = $obj->insertTestAndMeasurementContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
  }
  
  public function blog()
  {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
            
            $rss = $this->rssparser->set_feed_url("http://www.techaheadcorp.com/feed")->set_cache_life(30)->getFeed();
            
            $data = getBlogData();
            
            foreach($rss as $publishdate)
                {
                    $pub_date[] = $publishdate['pubDate'];
                }
                //echo '<pre>';print_r($data);die;
            foreach($data as $key=>$value)
            {
                
                $image_url = $value['image_url'];
                $title   = $value['title'];
                $link    = $value['link'];
                $description = $value['description'];
                
                $blog_data = array(
                    
                    'title' => $title,
                    'link'  => $link,
                    'image_url' => $image_url,
                    'description' => $description,
                    'publish_date' => $pub_date[$key]
                    
                );
                //echo '<pre>';print_r($blog_data);//die;
                
                $insert = $obj->insertBlogData($blog_data);
                if($insert)
                {
                    echo "inserted sucessfully";
                }
            }
            
            
  }
  
  
  public function WeldingAndGasesToday()
  {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://www.weldingandgasestoday.org/?feed=rss2")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link'];
          $author = $item['creator']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.weldingandgasestoday.org";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      =  $description_html->find('div.entry-content');
          $full_description  = implode($description);  
          $full_description1 = str_replace('Read more here.','',$full_description);
          $description2 =preg_replace("/&nbsp;/",' ',$full_description1); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_author($author);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description3);
          $obj->set_publish_date($pub_newdate);
         
          
          $insert_data = $obj->insertWeldingAndGasesTodayContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
  }
  
  public function WeldingDesign()
  {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://weldingdesign.com/rss.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title = $item['title']; 
          $link = $item['link'];
          $publish_date = $item['pubDate'];
          $media_url    = $item['content_url']['url']; 
          $media_type   = $item['content_url']['medium']; 
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://weldingdesign.com";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);//echo $description_html;die;
          $description      =  $description_html->find('div.article-body');
          $full_description  = implode($description);
          $description2 =preg_replace("/&nbsp;/",' ',$full_description); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_content_url($media_url);
          $obj->set_media_type($media_type);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description3);
          $obj->set_publish_date($pub_newdate);
         
          
          $insert_data = $obj->insertWeldingDesignContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';//die;
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
  }
  
  public function WindPower()
  {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://www.windpowerengineering.com/feed")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title        = $item['title']; 
          $link         = $item['link'];
          $author = $item['creator']; 
          $publish_date = $item['pubDate'];
          $description  = $item['content_encoded']; 
          $pub_newdate  = $this->change_date($publish_date);
          $website_url  = "http://www.windpowerengineering.com";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
          
          $description2      = preg_replace("/&nbsp;/",' ',$description); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($description);
          $obj->set_without_html_description($final_description3);
          $obj->set_publish_date($pub_newdate);
          $obj->set_author($author);
         
          
          $insert_data = $obj->insertWindPowerContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
  }
  
  public function EDN()
  {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://www.edn.com/rss/all")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  //echo '<pre>';print_r($item);die;
           
          $title        = $item['title']; 
          $link         = $item['link']; 
          $publish_date = $item['publish'];
          $description  = $item['content_encoded']; 
          $pub_newdate  = $this->change_date($publish_date);
          $website_url  = "http://www.edn.com";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      =  $description_html->find('div.detail_body');
          $full_description  = implode($description); 
          $description2      = preg_replace("/&nbsp;/",' ',$full_description); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description3);
          $obj->set_publish_date($pub_newdate);
         
         
          
          $insert_data = $obj->insertEDNContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
  }
  
  public function AutomationIsa()
  {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://feeds.feedburner.com/isa_interchange")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title        = $item['title']; 
          $link         = $item['link']; 
          $publish_date = $item['pubDate']; 
          $description  = $item['content_encoded'];
          $pub_newdate  = $this->change_date($publish_date);
          $website_url  = "http://automation.isa.org";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
//          $description_html = file_get_html($link);
//          $description      =  $description_html->find('div.detail_body');
//          $full_description  = implode($description); echo $full_description;die;
          $full_description  = explode('About the Author',$description);
          $description2      = preg_replace("/&nbsp;/",' ',$full_description[0]); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description[0]);
          $obj->set_without_html_description($final_description3);
          $obj->set_publish_date($pub_newdate);
         
         
          
          $insert_data = $obj->insertAutomationIsaContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
  }
  
  public function LinearMotionTips()
  {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://feeds.feedburner.com/LinearMotionTips?format=xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title        = $item['title']; 
          $author       = $item['creator'];
          $link         = $item['link']; 
          $publish_date = $item['pubDate']; 
          $description  = $item['content_encoded'];
          $pub_newdate  = $this->change_date($publish_date);
          $website_url  = "http://www.linearmotiontips.com";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
//          $description_html = file_get_html($link);
//          $description      =  $description_html->find('div.detail_body');
//          $full_description  = implode($description); echo $full_description;die;
          $full_description  = explode('About the Author',$description);
          $description2      = preg_replace("/&nbsp;/",' ',$full_description[0]); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description[0]);
          $obj->set_without_html_description($final_description3);
          $obj->set_publish_date($pub_newdate);
          $obj->set_author($author);
         
         
          
          $insert_data = $obj->insertLinearMotionTipsContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
  }
  
  public function MotionControlTips()
  {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://feeds.feedburner.com/MotionControlTips?format=xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title        = $item['title']; 
          $author       = $item['creator'];
          $link         = $item['link']; 
          $publish_date = $item['pubDate']; 
          $description  = $item['content_encoded'];
          $pub_newdate  = $this->change_date($publish_date);
          $website_url  = "http://www.motioncontroltips.com";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
//          $description_html = file_get_html($link);
//          $description      =  $description_html->find('div.detail_body');
//          $full_description  = implode($description); echo $full_description;die;
          $full_description  = explode('About the Author',$description);
          $description2      = preg_replace("/&nbsp;/",' ',$full_description[0]); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description[0]);
          $obj->set_without_html_description($final_description3);
          $obj->set_publish_date($pub_newdate);
          $obj->set_author($author);
         
         
          
          $insert_data = $obj->insertMotionControlTipsContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else 
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
  }
  
  public function Packword()
  {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://feeds.feedburner.com/packworld/QLwk")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title        = $item['title']; 
          $author       = $item['creator'];
          $link         = $item['link']; 
          $publish_date = $item['pubDate']; 
          $pub_newdate  = $this->change_date($publish_date);
          $website_url  = "http://www.packworld.com";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      =  $description_html->find('div.field-item');
          $full_description  = implode($description);
          $full_description1 = explode('<img typeof',$full_description);
          $full_description2 = $full_description1[0];
          $description2      = preg_replace("/&nbsp;/",' ',$full_description2); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description2);
          $obj->set_without_html_description($final_description3);
          $obj->set_publish_date($pub_newdate);
          $obj->set_author($author);
         
         
          
          $insert_data = $obj->insertPackwordContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else 
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
  }
  
  
  public function ScienceDaily()
  {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.sciencedaily.com/newsfeeds.htm");
         
          
             foreach($html->find('a') as $link)
             {
                
                 $url = $link->href;//echo $url."<br>";
                 
                 if (strpos($url, 'rss') )
                {  
                     if (strpos($url, "www.sciencedaily.com") == false ) { 
                        $url_new = str_replace($url, "http://www.sciencedaily.com$url", $url); //echo $url_new."<br>";//die;
                    } else { //echo $url;die;
                        
                        $url_new = $url;//echo $url_new."<br>";
                        
                    }
               
                
              //echo $url_new;die;
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "www.sciencedaily.com";
          $crawling_date = date("Y-m-d"); 
          
          
          
          $html = file_get_html($link);
          $description = $html->find('div#text');
          $full_description  = trim(implode($description)); 
          $description2 =preg_replace("/&nbsp;/",'',$full_description);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description2);
          
          
          $insert_data = $obj->insertScienceDailyContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
    }
  }
  
}

    public function SolarNovas() {
    
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://feeds.novustoday.com/SolarNovus?format=xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title        = $item['title']; 
          $author       = $item['creator'];
          $link         = $item['link']; 
          $publish_date = $item['pubDate']; 
          $pub_newdate  = $this->change_date($publish_date);
          $website_url  = "http://www.solarnovus.com";
          $crawling_date = date("Y-m-d"); 
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      =  $description_html->find('article#content');
          $full_description  = implode($description); 
          $description2      = preg_replace("/&nbsp;/",' ',$full_description); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description3);
          $obj->set_publish_date($pub_newdate);
          $obj->set_author($author);
         
         
          
          $insert_data = $obj->insertSolarNovasContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else 
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
        
    }
    
    public function SupplyChain247()
    {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.supplychain247.com/site/rss");
         
          foreach($html->find('div.quicklookbox') as $rss)
          {
             foreach($rss->find('a') as $link)
             {
                
                 $url = $link->href;//echo $url."<br>";
                 
                 if (strpos($url, 'rss') )
                {  
                     if (strpos($url, "sc247") == false ) { 
                        $url_new = str_replace($url, "http://www.supplychain247.com$url", $url); //echo $url_new."<br>";//die;
                    } else { //echo $url;die;
                        
                        $url_new = $url;//echo $url_new."<br>";
                        
                    }
               
                
              //echo $url_new;die;
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link']; 
          $publish_date = $item['date'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.supplychain247.com";
          $crawling_date = date("Y-m-d"); 
          
          
          
          $html = file_get_html($link);
          $description = $html->find('div#storybody');
          $full_description  = implode($description); 
          $full_description1 = explode('<div class="pad10"></div>',$full_description);
          $description2 =preg_replace("/&nbsp;/",'',$full_description1[0]);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description1[0]);
          $obj->set_without_html_description($final_description2);
          
          
          
          $insert_data = $obj->insertSupplyChain247Content();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
     }
    }
  }
    }
    
    public function TheEngineer()
    {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.theengineer.co.uk");
         
          
             foreach($html->find('a.rssfeed') as $link)
             { 
                
                 $url = $link->href;//echo $url."<br>";
                 
                
               
                
              //echo $url_new;die;
         $rss = $this->rssparser->set_feed_url($url)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.theengineer.co.uk";
          $crawling_date = date("Y-m-d"); 
          
          
          
          $html = file_get_html($link);
          $description = $html->find('p');
          $full_description  = trim(implode($description)); 
          $full_description1  = str_replace('src="','src="http://www.theengineer.co.uk/',$full_description); //echo $full_description1;//die;
          
          $description2 =preg_replace("/&nbsp;/",'',$full_description1);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); //echo $final_description2;die;
          $full_description3  = str_replace('You have no saved stories Save this article Site powered by Webvision','',$final_description2);

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description1);
          $obj->set_without_html_description($full_description3);
          
          
          $insert_data = $obj->insertTheEngineerContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
       
        }
    
  }
 }
 
 public function ControlGlobal()
 {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.controlglobal.com");
         
          
             foreach($html->find('a.dropdown-link') as $link)
             {
                
                 $url = $link->href;//echo $url."<br>";
                 
                 if (strpos($url, 'rss') )
                {  
                     if (strpos($url, "www.controlglobal.com") == false ) { 
                        $url_new = str_replace($url, "http://www.controlglobal.com$url", $url); //echo $url_new."<br>";//die;
                    } else { //echo $url;die;
                        
                        $url_new = $url;//echo $url_new."<br>";
                        
                    }
               
                
              //echo $url_new;die;
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link']; 
          $publish_date = $item['pubDate'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.controlglobal.com";
          $crawling_date = date("Y-m-d"); 
          
          
          
          $html = file_get_html($link);
          $description = $html->find('div.Content');
          $full_description  = implode($description); 
          $description2 =preg_replace("/&nbsp;/",'',$full_description);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description2);
          
          
          $insert_data = $obj->insertControlGlobalContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
     //}
    }
  }
 }
 
 public function SpectrumIEEE()
 {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://feeds2.feedburner.com/IeeeSpectrum")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title        = $item['title']; 
          $author       = $item['creator'];
          $link         = $item['link']; 
          $publish_date = $item['pubDate']; 
          $pub_newdate  = $this->change_date($publish_date);
          $website_url  = "http://spectrum.ieee.org";
          $crawling_date = date("Y-m-d"); 
          $media_url     = $item['content_url']['url'];
          $media_type    = "image";
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      =  $description_html->find('div.entry-content');
          $full_description  = implode($description); 
          $full_description1 =  str_replace('src="','src="http://spectrum.ieee.org/',$full_description);
          $description2      = preg_replace("/&nbsp;/",' ',$full_description); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2);
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description1);
          $obj->set_without_html_description($final_description3);
          $obj->set_publish_date($pub_newdate);
          $obj->set_author($author);
          $obj->set_content_url($media_url);
          $obj->set_media_type($media_type);
         
         
          
          $insert_data = $obj->insertSpectrumIEEEContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else 
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
 }
 
 public function ReliablePlant()
 {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.reliableplant.com/Meta/Topics");
         
          
             foreach($html->find('a.topic_rss') as $link)
             {
                
                 $url = $link->href;//echo $url."<br>";
                 
                
                        
                    
               
                
              //echo $url_new;die;
         $rss = $this->rssparser->set_feed_url($url)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title'];
          $link = $item['link']; 
          $publish_date = $item['publish'];
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.reliableplant.com";
          $crawling_date = date("Y-m-d"); 
          $author = $item['author1'];
          
          
          
          $description_html = file_get_html($link);
          $description      =  $description_html->find('div.ArticleBody');
          $full_description  = implode($description); 
          $description2      =preg_replace("/&nbsp;/",'',$full_description); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description);
          $obj->set_without_html_description($final_description2);
          $obj->set_author($author);
          
          
          $insert_data = $obj->insertReliablePlantContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
     
    }
  }
 }
 
 
 
 public function GreenTechMedia(){
     
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.greentechmedia.com/about/subscribe/rss");
         
          
             foreach($html->find('a') as $link)
             {
                
                 $url = $link->href;//echo $url."<br>";
                 
                 if (strpos($url, 'feeds') )
                {  
                     
                        $url_new = $url;//echo $url_new."<br>";
                        
                   
               
                
              //echo $url_new;die;
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title']; 
          $link = $item['link']; 
          $author = $item['creator']; 
          $publish_date = $item['date']; 
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.greentechmedia.com";
          $crawling_date = date("Y-m-d"); 
          $description = $item['description'];
          
          
          $description2 =preg_replace("/&nbsp;/",'',$description);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($description);
          $obj->set_without_html_description($final_description2);
          
          
          $insert_data = $obj->insertGreenTechMediaContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
     }
    //}
  }
 }
 
 
 
 public function ElectronicProducts()
 {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
          
     
         $html = file_get_html("http://www.electronicproducts.com/RSS.aspx");
         
          
             foreach($html->find('a') as $link)
             {
                
                 $url = $link->href;//echo $url."<br>";
                 
                 if (strpos($url, 'www.electronicproducts.com/rssFeed') )
                {  
                     
                        $url_new = $url;//echo $url_new."<br>";
                        
                   
               
                
              //echo $url_new;die;
         $rss = $this->rssparser->set_feed_url($url_new)->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title = $item['title']; 
          $link = $item['link']; //echo $link;//die;
          $publish_date = $item['pubDate']; 
          $pub_newdate = $this->change_date($publish_date);
          $website_url = "http://www.electronicproducts.com";
          $crawling_date = date("Y-m-d"); 
          
          
          $description_html = file_get_html($link);
          $description      =  $description_html->find('div.ep_Body');
          $full_description  = implode($description); 
          $full_description1 = str_replace('src="','src="http://www.electronicproducts.com',$full_description);
          $description2 =preg_replace("/&nbsp;/",'',$full_description1);
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_publish_date($pub_newdate);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description1);
          $obj->set_without_html_description($final_description2);
          
          
          $insert_data = $obj->insertGreenTechMediaContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else
          {
              echo "data not inserted successfully".'<br>';
          }
        
        }
     }
    //}
  }
 }
 
 
 public function AutomationWorld()
 {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://feeds.feedburner.com/AllAutomationWorldContent")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        {  
           
          $title        = $item['title']; 
          $author       = $item['creator'];
          $link         = $item['link']; 
          $publish_date = $item['pubDate']; 
          $pub_newdate  = $this->change_date($publish_date);
          $website_url  = "http://www.automationworld.com";
          $crawling_date = date("Y-m-d"); 
          
          
          
         
          //***********getting the full description from the article link*******//
          $description_html = file_get_html($link);
          $description      =  $description_html->find('div.field-items');
          $full_description  = implode($description); 
          $full_description1 = str_replace('src="','src="http://www.automationworld.com',$full_description); 
          $full_description2 = explode('<img typeof="foaf:Image"',$full_description1);
          $description2      = preg_replace("/&nbsp;/",' ',$full_description2[0]); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($full_description2[0]);
          $obj->set_without_html_description($final_description3);
          $obj->set_publish_date($pub_newdate);
          $obj->set_author($author);
          
         
         
          
          $insert_data = $obj->insertAutomationWorldContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else 
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
 }
 
 public function FasterAndFixing()
 {
            $obj = $this->load->model('website_parse_model');
            $obj = new website_parse_model();
         
         
         $rss = $this->rssparser->set_feed_url("http://www.fastenerandfixing.com/fastenerandfixing/News/rss.xml")->set_cache_life(30)->getFeed();

        foreach ($rss as $item)
        { 
           
          $title        = $item['title']; 
          $link         = $item['link']; 
          $publish_date = $item['pubDate']; 
          $description  = $item['description'];
          $pub_newdate  = $this->change_date($publish_date);
          $website_url  = "http://www.fastenerandfixing.com";
          $crawling_date = date("Y-m-d"); 
          
          
          
         
          //***********getting the full description from the article link*******//
          
          $description2      = preg_replace("/&nbsp;/",' ',$description); 
          $description_without_html = trim(strip_tags(strip_tags(html_entity_decode(preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($description2, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'))));//****************description without html******************//
          $final_description1 = preg_replace('/\s+/', ' ', $description_without_html);
          $final_description2 = $this->convert_ascii($final_description1); 
          $final_description3 =  str_replace("&nbsp;"," ",$final_description2); 
          
          

          $obj->set_title($title);
          $obj->set_article_link($link);
          $obj->set_website_url($website_url);
          $obj->set_crawling_date($crawling_date);
          $obj->set_description($description);
          $obj->set_without_html_description($final_description3);
          $obj->set_publish_date($pub_newdate);
          
         
         
          
          $insert_data = $obj->insertFasterAndFixingContent();
          if($insert_data)
          {
              echo "inserted successfully".'<br>';
          }
          else 
          {
              echo "data not inserted successfully".'<br>';
          }
          
        }
 }
 
 
 public function bigbasket()
 {
     $obj = $this->load->model('basket_model');
     $obj = new basket_model();
     
     $data = getBasketData();
     
     foreach($data as $val)
     {
         $category_url = $val['category_url'];
         $category_name = $val['category_name'];
         $subcategory_url = $val['subcategory_url'];
         $sub_category    = $val['sub_category'];
         $image_url       = $val['image_url'];
         $title_link      = $val['title_link'];
         $title           = $val['title'];
         $about_item      = $val['about_item'];
         $item_benefits   = $val['item_benefits'];
         $how_to_use      = $val['how-to-use'];
         //$rate            = $val['rate'];
         $brand           = $val['brand'];
         
         $obj->set_item_about_us($about_item);
         $obj->set_item_benefits($item_benefits);
         $obj->set_item_brand($brand);
         $obj->set_item_category($category_name);
         $obj->set_item_category_url($category_url);
         $obj->set_item_how_to_use($how_to_use);
         $obj->set_item_image_url($image_url);
         //$obj->set_item_rate($rate);
         $obj->set_item_subcategory($sub_category);
         $obj->set_item_subcategory_url($subcategory_url);
         $obj->set_item_title($title);
         $obj->set_item_title_link($title_link);
         
         $insert = $obj->insertBigBasketContent();
         
     }
     
 }

 
















 //****************function to change the article date to gmt date****************//
    function change_date($date) {
        
        $gmt_date = gmdate('Y-m-d H:i:s', strtotime($date));
        return $gmt_date;
    }

    
    //***********function for removing special symbols  from the description**********//
    function convert_ascii($string) { 
        
        $replace = array(
    '&lt;' => '', '&gt;' => '', '&#039;' => '', '&amp;' => '','&mdash;' => '','&bull;' => '','&rdquo;' => '','&rsquo;' => '','&raquo;' => '','&ldquo;'=> '','&rsquo;' => '','&ldquo;'=>'','&nbsp;' => '',
    '&quot;' => '', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ae',
    '&Auml;' => 'A', 'Å' => 'A', 'Ā' => 'A', 'Ą' => 'A', 'Ă' => 'A', 'Æ' => 'Ae',
    'Ç' => 'C', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C', 'Ď' => 'D', 'Đ' => 'D',
    'Ð' => 'D', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E',
    'Ę' => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ĝ' => 'G', 'Ğ' => 'G',
    'Ġ' => 'G', 'Ģ' => 'G', 'Ĥ' => 'H', 'Ħ' => 'H', 'Ì' => 'I', 'Í' => 'I',
    'Î' => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Į' => 'I',
    'İ' => 'I', 'Ĳ' => 'IJ', 'Ĵ' => 'J', 'Ķ' => 'K', 'Ł' => 'K', 'Ľ' => 'K',
    'Ĺ' => 'K', 'Ļ' => 'K', 'Ŀ' => 'K', 'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N',
    'Ņ' => 'N', 'Ŋ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O',
    'Ö' => 'Oe', '&Ouml;' => 'Oe', 'Ø' => 'O', 'Ō' => 'O', 'Ő' => 'O', 'Ŏ' => 'O',
    'Œ' => 'OE', 'Ŕ' => 'R', 'Ř' => 'R', 'Ŗ' => 'R', 'Ś' => 'S', 'Š' => 'S',
    'Ş' => 'S', 'Ŝ' => 'S', 'Ș' => 'S', 'Ť' => 'T', 'Ţ' => 'T', 'Ŧ' => 'T',
    'Ț' => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue', 'Ū' => 'U',
    '&Uuml;' => 'Ue', 'Ů' => 'U', 'Ű' => 'U', 'Ŭ' => 'U', 'Ũ' => 'U', 'Ų' => 'U',
    'Ŵ' => 'W', 'Ý' => 'Y', 'Ŷ' => 'Y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'Ž' => 'Z',
    'Ż' => 'Z', 'Þ' => 'T', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
    'ä' => 'ae', '&auml;' => 'ae', 'å' => 'a', 'ā' => 'a', 'ą' => 'a', 'ă' => 'a',
    'æ' => 'ae', 'ç' => 'c', 'ć' => 'c', 'č' => 'c', 'ĉ' => 'c', 'ċ' => 'c',
    'ď' => 'd', 'đ' => 'd', 'ð' => 'd', 'è' => 'e', 'é' => 'e', 'ê' => 'e',
    'ë' => 'e', 'ē' => 'e', 'ę' => 'e', 'ě' => 'e', 'ĕ' => 'e', 'ė' => 'e',
    'ƒ' => 'f', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g', 'ĥ' => 'h',
    'ħ' => 'h', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i',
    'ĩ' => 'i', 'ĭ' => 'i', 'į' => 'i', 'ı' => 'i', 'ĳ' => 'ij', 'ĵ' => 'j',
    'ķ' => 'k', 'ĸ' => 'k', 'ł' => 'l', 'ľ' => 'l', 'ĺ' => 'l', 'ļ' => 'l',
    'ŀ' => 'l', 'ñ' => 'n', 'ń' => 'n', 'ň' => 'n', 'ņ' => 'n', 'ŉ' => 'n',
    'ŋ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'oe',
    '&ouml;' => 'oe', 'ø' => 'o', 'ō' => 'o', 'ő' => 'o', 'ŏ' => 'o', 'œ' => 'oe',
    'ŕ' => 'r', 'ř' => 'r', 'ŗ' => 'r', 'š' => 's', 'ù' => 'u', 'ú' => 'u',
    'û' => 'u', 'ü' => 'ue', 'ū' => 'u', '&uuml;' => 'ue', 'ů' => 'u', 'ű' => 'u',
    'ŭ' => 'u', 'ũ' => 'u', 'ų' => 'u', 'ŵ' => 'w', 'ý' => 'y', 'ÿ' => 'y',
    'ŷ' => 'y', 'ž' => 'z', 'ż' => 'z', 'ź' => 'z', 'þ' => 't', 'ß' => 'ss',
    'ſ' => 'ss', 'ый' => 'iy', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
    'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
    'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
    'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
    'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '',
    'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a',
    'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
    'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l',
    'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
    'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
    'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e',
    'ю' => 'yu', 'я' => 'ya');
                

   $string1= str_replace(array_keys($replace), $replace, $string);  
        return $string1;
    }

}
