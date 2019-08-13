<?php

function getBigBasketData()
{ echo "hii";die;
    //$html = file_get_html("http://bigbasket.com/");
    
//    foreach($html->find('a.top-category') as $category)
//    {
//        $category_url = $category->href;
//        $res['category_url'] = "http://bigbasket.com".$category_url;
//        $category_name = $category;
//        $res['category_name'] = strip_tags($category_name); 
        
       
//        $categorypage = file_get_html("http://bigbasket.com/cl/fruits-vegetables/?nc=nb");
////        if (false !== ( $categorypage = file_get_html("http://bigbasket.com/cl/fruits-vegetables/?nc=nb")))
////        {
//            foreach ($categorypage->find('a.category-arrow') as $subcategory)
//            {
//                $subcategory_url = $subcategory->href;
//                $res['subcategory_url'] = "http://bigbasket.com".$subcategory_url; 
//   
//            }
           
            $subcategorypage = file_get_html("http://bigbasket.com/pc/fruits-vegetables/cut-fruits-vegetables/?nc=fa"); 
            
            foreach ($subcategorypage->find('div.uiv2-list-box-img-block') as $content)
            {
                foreach ($content->find('img') as $image)
                {
                    $image_url = $image->src;
                    $res['image_url'] = str_replace("//", "", $image_url); //echo $res['image_url'];die;
                }
            
            
            
            foreach ($subcategorypage->find('div.uiv2-list-box-img-title') as $title)
            {

                $title1 = explode('</span>', $title);
                $res['brand']  = strip_tags($title1[0]);
                $title2 = explode('</a>', $title1[1]);
                $title_url = explode('<a href="', $title);
                $title_url1 = explode('"', $title_url[1]);
                $res['title_link'] = "http://bigbasket.com" . $title_url1[0];
                $res['title'] = $title2[1];//echo $res['title'];

                $item = file_get_html($res['title_link']);
                $item_details = $item->find('div#uiv2-tab1');
                $item_about = implode($item_details);
                $res['about_item'] = $item_about;
                $benefits = $item->find('div#uiv2-tab2');
                $item_benefits = trim(implode($benefits));
                $res['item_benefits'] = trim($item_benefits);
                $how_to_use = $item->find('div#uiv2-tab3');
                $res['how_to_use'] = trim(implode($how_to_use));
                
                $subcategory_new = $item->find('div.uiv2-shopping-list-bredcom');
                $subcategory_name = implode($subcategory_new);
                $subcategory_name2 = explode('<div class="breadcrumb-item"',$subcategory_name);
                $subcategory_name3 = explode('>',$subcategory_name2[3]);//echo '<pre>';print_r($subcategory_name3);die;
                $res['subcategory'] = str_replace('>','', $subcategory_name3[3]);
                
//                $rate = $item->find('div.uiv2-product-value');
//                $rate_new = implode($rate);
//                $rate1 = explode('<div class="uiv2-price" itemprop="price">',$rate_new);
//                $rate2 = explode('<div class="uiv2-product-notify">',$rate1[1]);
//                $res['rate'] = $rate2[0];
            }
           //echo '<pre>';print_r($res);die;
            $final[] = $res;
        }
         
      
       
//      }
      
         
         return $final;
       
    }
//}

