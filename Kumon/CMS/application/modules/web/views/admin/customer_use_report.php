<script src="js/jquery.js"></script>

<script>
    $(document).ready(function () {
        $(".view-bttn").click(function () {
            $(".layer-bg").fadeIn();
        });

        $(".close-bttn").click(function () {
            $(".layer-bg").fadeOut();
        });
    });

    /* GET ADMIN DETAILS AJAX*/
    function getAllSupportDetails(val1) {

        var tid1 = val1;
        //alert(tid1);
        $.ajax({
            type: "POST",
            data: {Id: tid1},
            url: "<?php echo $this->config->base_url(); ?>index.php/customerdetails",
            success: function (data) {
                //console.log(data);
                $(".popUpDiv").html(data);

            }

        });

    }

    var getCustomerMagazine = function(){

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/customer_magazine_filter",
            success: function (data) {
                $("#magazine_filter").html(data);
                getMagazineContent();
            }
        });

    }

    getCustomerMagazine();

    var getMagazineContent = function(){

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/magazine_content_filter",
            success: function (data) {
                $("#article_filter").html(data);
                getMagazineUsers();
            }
        });

    }

    var getMagazineUsers = function(){

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/magazine_users_filter",
            success: function (data) {
                $("#user_filter").html(data);
            }
        });

    }

    // Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(getReport);

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function getReport() {

        //BARRA visualizações por estado
        //BARRA HORIZONTAL por article
        //RELATORIO NORMAL de usuarios (nome, SO, tipo, país, estado, cidade)

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/use_report_data_1",
            success: function (data) {

                var objData = jQuery.parseJSON(data);

                var dataTable = new google.visualization.DataTable();
                dataTable.addColumn('string', 'Sistema Operacional');
                dataTable.addColumn('number', 'Acesso');

                for( var i in objData ){
                    dataTable.addRow([objData[i].so_access, Number(objData[i].total)]);
                }

                var options = {'title':'Visualizações por sistema operacional',
                    'width':500,
                    'height':300};

                var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                chart.draw(dataTable, options);

            }
        });

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/use_report_data_2",
            success: function (data) {

                var objData = jQuery.parseJSON(data);

                var dataTable = new google.visualization.DataTable();
                dataTable.addColumn('string', 'Tipo');
                dataTable.addColumn('number', 'Acesso');

                for( var i in objData ){
                    dataTable.addRow([objData[i].type_device_access, Number(objData[i].total)]);
                }

                var options = {'title':'Visualizações por tipo de dispositivo',
                    'width':500,
                    'height':300};

                var chart = new google.visualization.PieChart(document.getElementById('chart_div_2'));
                chart.draw(dataTable, options);

            }
        });

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/use_report_data_3",
            success: function (data) {

                var objData = jQuery.parseJSON(data);

                var dataTable = new google.visualization.DataTable();
                dataTable.addColumn('string', 'Estado');
                dataTable.addColumn('number', 'Acesso');

                for( var i in objData ){
                    dataTable.addRow([objData[i].state, Number(objData[i].total)]);
                }

                var options = {'title':'Visualizações por localização',
                    'width':500,
                    'height':300};

                var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_3'));
                chart.draw(dataTable, options);

            }
        });

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/use_report_data_4",
            success: function (data) {

                var objData = jQuery.parseJSON(data);

                var dataTable = new google.visualization.DataTable();
                dataTable.addColumn('string', 'Artigo');
                dataTable.addColumn('number', 'Acesso');

                for( var i in objData ){
                    dataTable.addRow([objData[i].title, Number(objData[i].total)]);
                }

                var options = {'title':'Visualizações por artigo/publicidade',
                    'width':500,
                    'height':300};

                var chart = new google.visualization.BarChart(document.getElementById('chart_div_4'));
                chart.draw(dataTable, options);

            }
        });

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/use_report_data_5",
            success: function (data) {
                $("#list_user").html(data);
            }
        });

    }

</script>

</head>

<?php
/*SESSION VARIABLE*/
$selcted_permission = $_GET['perId'];
$searchString = '';
$searchString = $this->session->userdata('searchUser');
?>
<div class="subheader">
    <div class="fix-container clearfix">
        <h4><?php echo $this->lang->line('access_use_report_title') ?></h4>
    </div>
</div>

<div class="fix-container clearfix">

    <div class="upper_bar clearfix filter-bar">

        <div class="search-bar left-align-box">

            <div class="language-filter clearfix">

                <div class="select-container">

                    <div class="select-arrow">
                        <div class="arrow-down"></div>
                    </div>

                    <select id="magazine_filter" class="choose-lang" onChange="getMagazineContent();"></select>

                </div>

                <div class="select-container">

                    <div class="select-arrow">
                        <div class="arrow-down"></div>
                    </div>

                    <select id="article_filter" class="choose-lang" onChange="getMagazineUsers();"></select>

                </div>

                <div class="select-container">

                    <div class="select-arrow">
                        <div class="arrow-down"></div>
                    </div>

                    <select id="user_filter" class="choose-lang" onChange=""></select>

                </div>

                <a class="add-new" href="javascript:;" onclick="getReport();">Visualizar</a>

            </div>

        </div>

    </div>

    <div class="category-list">

        <div class="table-container">

            <div id="chart_div" style="float: left;"></div>

            <div id="chart_div_2" style="float: left;"></div>

            <br clear="all" />

            <div id="chart_div_3" style="float: left;"></div>

            <div id="chart_div_4" style="float: left;"></div>

            <br clear="all" /><br />

            <div id="list_user"></div>

        </div>

    </div>

<!--    <div class="pagination clearfix">-->
<!--        <ul>--><?php //echo $this->pagination->create_links(); ?><!--</ul>-->
<!--    </div>-->

</div>

<!--Pop up-->
<div class="layer-bg">
    <div class="popup-container info-popup clearfix">
        <div class="close-bttn"></div>
        <div class="displayData popUpDiv">

        </div>
    </div>
</div>

</body>
</html>