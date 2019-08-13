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
    .googlemapimage{float: left; width: 100%; overflow: hidden; height: 500px; margin-top: 10px;}
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
                $(".popUpDiv").html(data);

            }

        });

    }

</script>



</head>

<div class="subheader">
    <div class="fix-container clearfix">
        <h4><?php echo $this->lang->line('Add_Category') ?></h4>
        <a class="add-new" href="<?php echo $this->config->base_url() . 'index.php/addadvertise' ?>">Add New</a>

    </div>
</div>


<div class="fix-container clearfix">
    <div class="addvertisement-wrapper clearfix">
<?php //echo "<pre>"; print_r($ad_result); ?>  
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
                echo"new";
            } elseif ($ad_result['status'] == '0') {
                echo"Publish";
            }
        ?></span>
        </div>
        <div class="fields-cont clearfix"><?php
            if ($ad_result['publish_date_from'] != "") {
                echo date('dd:yy:mm', strtotime($ad_result['publish_date_from'])) . " To " . date('dd:yy:mm', strtotime($ad_result['publish_date_to']));
            }
            ?></div>
     <div class="fields-cont clearfix">
        <?php
        foreach ($ads_report as $value) {
            $id = $value['id'];
            $lat = $value['latitude'];
            $long = $value['longitude'];
        }
        ?>
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
            locations = [["<?php echo $id; ?>", "<?php echo $lat; ?>", "<?php echo $long; ?>", '1'], ];
            console.log(locations);
<?php } ?>
    </script>


    <script src="http://maps.google.com/maps/api/js?sensor=false" 
    type="text/javascript"></script>

    <div id="map" class="googlemapimage"></div>

    <script type="text/javascript">
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 8,
            center: new google.maps.LatLng(51.508742, -0.120850),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            panControl: true,
            zoomControl: true,
            mapTypeControl: true,
            mapTypeControlOptions: {
            style:google.maps.MapTypeControlStyle.HORIZONTAL_BAR ,
            position:google.maps.ControlPosition.TOP_CENTER
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

    echo "  marker = new google.maps.Marker({
          position: new google.maps.LatLng(" . $lat . ", " . $long . "),
          map: map
        });";

    // echo "var latlng = new google.maps.LatLng(".$lat.",".$long.")\n";
    //echo "WalkingPathCoordinates.push(latlng);\n";
}
?>

        //    var marker, i;
        //    for (i = 0; i < locations.length; i++) {  
        //      marker = new google.maps.Marker({
        //        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        //        map: map
        //      });
        //
        //      google.maps.event.addListener(marker, 'mouseover', (function(marker, i) {
        //        return function() {
        //          infowindow.setContent(locations[i][0]);
        //          infowindow.open(map, marker);
        //        }
        //
        //      })(marker, i));
        //      google.maps.event.addListener(marker, 'mouseout', (function(marker, i) {
        //        return function() {
        //
        //          infowindow.close();
        //        }
        //
        //      })(marker, i));
        //       google.maps.event.addListener(marker, 'click', (function(marker, i) {
        //        return function() {
        //        infowindow.setContent(locations[i][0]);
        //           infowindow.open(map, marker);
        //        }
        //
        //      })(marker, i));
        //    }
    </script>

</div>

</div>

<!--Pop up-->


<div class="layer-bg">
    <div class="layer-bg-popupOverlay"></div>
    <div class="popup-container clearfix">
        <div class="close-bttn"></div>
        <h4>Advertisement Publish</h4>
        <?php
        $attributes = array('name' => 'publish_form', 'id' => 'publish_form', 'class' => 'form-horizontal');
        echo form_open($this->config->base_url() . 'index.php/publishadvertise', $attributes);
        ?>  
<?php echo validation_errors(); ?>
        <div class="popup-inner clearfix">
            <div class="select-container">
                <div class="select-arrow">
                    <div class="arrow-down"></div>
                </div>             
            </div> 
        </div>
        <table>

            <script>
                $(function() {
                    $("#datefrom").datepicker({yearRange: "1900:3000", dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, minDate: 'today', inline: true});
                });
            </script>
            <tr><td>Publish Date From</td><td><input type="text" id ="datefrom" placeholder="enter date" name="datefrom"></td></tr>
            <script>
                $(function() {
                    $("#dateto").datepicker({yearRange: "1900:3000", dateFormat: 'yy-mm-dd', changeMonth: true, changeYear: true, minDate: 'today', inline: true});
                });
            </script>
            <tr><td>Publish Date To</td><td><input type="text" id ="dateto" placeholder="enter date" name="dateto"></td></tr>

            <tr><td>Display time</td><td><input type="text" id ="time" placeholder="enter time" name="time"></td></tr>
            <input type="hidden" name="advertise_id" id="advertise_id" value="">            
        </table>

        <div class="popup-inner clearfix">
            <input type="submit" value="Save" />
        </div>
    </div>        
</div>
<?php echo form_close(); ?>

<script>
    // validation
    jQuery.validator.setDefaults({
        debug: false,
        success: "valid"
    });

    $("#publish_form").validate({
        rules: {
            datefrom: {
                required: true
            },
            dateto: {
                required: true
            },
            time: {
                required: true
            }
        }

    });
</script>
</body>
</html>