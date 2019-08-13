<?php

function getBasketData()
{
    $html  = file_get_html("http://bigbasket.com/pc/fruits-vegetables/cut-fruits-vegetables/?nc=nb");
    
    foreach($html->find('div.uiv2-list-box-img-block') as $content)
    {
        $res['category_name'] = "fruits-vegetables";
        $res['sub_category'] = "cut-fruits-vegetables";
        foreach($content->find('img') as $image)
        {
            $image_url = $image->src; //echo $res['image_url'];
            $res['image_url'] = str_replace('//','',$image_url); 
        }
        
        foreach($html->find('span.uiv2-title-tool-tip') as $brand)
        {
            foreach($brand->find('a') as $item_link)
            { 
            $res['title'] = strip_tags($item_link);//echo $item_link;
            $res['title_link'] = "http://bigbasket.com".$item_link->href; 
            $brand1 = explode('</span>',$item_link);//echo '<pre>';print_r($brand1);die;
            $res['brand'] = strip_tags($brand1[0]);
            
            $main_item_link = file_get_html($res['title_link']);//echo $main_item_link;die;
            $item_about_us = $main_item_link->find('div#uiv2-tab1');
            $res['about_item'] = trim(implode($item_about_us));//echo $res['about_item'];die;
            
            $item_benefits = $main_item_link->find('div#uiv2-tab2');
            $res['item_benefits'] = trim(implode($item_benefits));
            
            $item_how_to_use = $main_item_link->find('div#uiv2-tab3');
            $res['how-to-use'] = trim(implode($item_how_to_use));//echo $res['item_how_to_use'];die;
            }
            
            
            
        }
        echo '<pre>';print_r($res);die;
        
        $final[] = $res;
    }
    //echo '<pre>';print_r($final);die;
    return $final;
}
?>