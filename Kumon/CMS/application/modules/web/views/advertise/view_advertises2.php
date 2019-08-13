<script src="js/jquery.js"></script>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<!--<script src="http://code.jquery.com/jquery-1.9.1.js"></script>-->
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

<!-- Load jQuery and the validate plugin -->  
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>

<!-- Define some CSS -->
<style type="text/css">
    .label {width:100px;text-align:right;float:left;padding-right:10px;font-weight:bold;}
    add_subadmin.error, .output {color:#FFFFFF;font-weight:bold;}
   
</style>


<script>
    $(document).ready(function() {
        $(".review").click(function() {
            $(".layer-bg").fadeIn();
        });

        $(".close-bttn, .layer-bg-popupOverlay").click(function() {
            $(".layer-bg").fadeOut();
        });
    });
</script>

<script>
    /*Sort by status*/
    function StatusData(val) {
        var rowId = val;
        $('#sort_status').submit();
    }

    /* GET LANGUAGE ID AND FETCH LANGUAGE DATA*/

    function LanguageData(val) {
        var rowId = val;//       
        $('#languge_form').submit();
    }



    /*SERCH USER NAME BY SERCH BAR*/

    $('#search').blur(function() {
        $.ajax({
            type: "POST",
            url: "search.php",
            data: {text: $(this).val()}
        });
    });

    /*PERFORMING DELETE < EDIT AND OTHER OPERATION*/

    function DELETE(val) {

        var tid1 = val;
        //alert(tid1);
        $.ajax({
            type: "POST",
            data: {Id: tid1},
            url: "<?php echo $this->config->base_url(); ?>index.php/advertisedelete",
            success: function(data)
            {
                //console.log(data);
                location.reload();

            }

        });

    }

</script>



</head>

<div class="subheader">
    <div class="fix-container clearfix">
        <h4><?php echo $this->lang->line('Advertisements') ?></h4>
        <a class="add-new" href="<?php echo $this->config->base_url() . 'index.php/addadvertise' ?>">Add New</a>
    </div>
</div>


<div class="fix-container clearfix">
    <div class="addvertisement-wrapper clearfix">        
        <div class="advertisment-image">
            <img src =" <?php echo $this->config->base_url() . 'assets/Advertise/' . $ad_result['cover_image']; ?>">
        </div>
        <div class="addvertisment-details">
            <div class="fields-cont clearfix"><label><?php echo $this->lang->line('Link') ?></label> 
                <span> <?php echo $ad_result['link']; ?></span> </div>
            <div class="fields-cont clearfix"><label><?php echo $this->lang->line('Categories') ?></label> 
                <span>  <?php
                    $name = array();
                    foreach ($ad_result['categoy_name'] as $cn) {
                        $name[] = $cn['category_name'];
                    }
                    echo implode(',', $name);
                    ?></span>
            </div>
            <div class="fields-cont clearfix"><label><?php echo $this->lang->line('Status') ?></label> 
                <span> <?php
                    if ($ad_result['status'] == '0') {
                        echo"New";
                    } elseif ($ad_result['status'] == '1') {
                        echo"Publish";
                    }else{
                        echo  "Rejected";
                    }
                    ?></span>
            </div>
            <div class="fields-cont clearfix"><label><?php echo $this->lang->line('Date') ?></label>
                <span><?php //
                if ($ad_result['publish_date_from'] != "") { 
                    echo date('d-m-y', strtotime($ad_result['publish_date_from'])) . " To " . date('d-m-y', strtotime($ad_result['publish_date_to']));
                }
                ?>
            <span></div> <?php //echo"<pre>"; print_r($ads_report); ?>
            <div class="fields-cont clearfix"><label><?php echo $this->lang->line('Hits') ?></label> 
             <?php echo $ads_report['total_count'] ?> 
            </div>
            <div class="fields-cont clearfix">
                
            </div>
        </div>

        <script  type="text/javascript">
            var locations;
<?php
foreach ($ads_report as $value) {//echo 'yes';
    $lat = $value['latitude'];
    $long = $value['longitude'];
    $id = $value['id'];
    ?>
                locations = [["<?php echo $id; ?>", "<?php echo $lat; ?>", "<?php echo $long; ?>", '<?php echo $id; ?>'], ];
                console.log(locations);
<?php } ?>
        </script>


        <script src="http://maps.google.com/maps/api/js?sensor=false" 
        type="text/javascript"></script>

        <div id="map" class="googlemapimage"></div>
        <div class="map-details">
            <div class="row-strip clearfix">
                <div class="map-color-blue"></div>
                <span> <?php echo $this->lang->line('Android') ?></span>
                
            </div>
            
            <div class="row-strip clearfix">
                <div class="map-color-red"></div>
                <span><?php echo $this->lang->line('Ios') ?> </span>
                
            </div>
            
            <div class="row-strip clearfix">
                <div class="map-color-yellow"></div>
                <span> <?php echo $this->lang->line('Website') ?> </span>
                
            </div>
            
        </div>
        <script type="text/javascript">
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 4,
                center: new google.maps.LatLng(40.712216, 151.2),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                panControl: true,
                zoomControl: true,
                mapTypeControl: true,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                    position: google.maps.ControlPosition.TOP_CENTER
                },
                scaleControl: true,
                streetViewControl: true,
                overviewMapControl: true,
                rotateControl: true,
            });

            var infowindow = new google.maps.InfoWindow();
            
<?php
foreach ($ads_report as $value) {
    $id = $value['id'];
    $lat = $value['latitude'];
    $long = $value['longitude'];
    $device = $value['device_type'];
    if ($device == '1') {
        $image = 'https://maps.gstatic.com/mapfiles/ms2/micons/blue-pushpin.png';
    } elseif ($device == '2') {
        $image = 'https://maps.gstatic.com/mapfiles/ms2/micons/pink-pushpin.png';
    } else {
        $image = 'https://maps.gstatic.com/mapfiles/ms2/micons/ylw-pushpin.png';
    }
    ?>
    
 
    marker = new google.maps.Marker({
              position: new google.maps.LatLng(<?php echo $lat;?>,<?php echo $long;?>),
              map: map,
              icon: "<?php echo $image;?>"
            });
            
    // echo "var latlng = new google.maps.LatLng(".$lat.",".$long.")\n";
    //echo "WalkingPathCoordinates.push(latlng);\n";
<?php }
?>

        </script>

    </div>

</div>

<!--Pop up-->

</body>
</html>