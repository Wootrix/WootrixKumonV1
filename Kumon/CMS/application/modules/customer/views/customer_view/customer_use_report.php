<script>

    $(document).ready(function () {
        getCustomerMagazine();
    });

    var totalLoaded = 0;

    var getCustomerMagazine = function(){

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/customer_magazine_filter",
            success: function (data) {
                $("#magazine_filter").html(data);
                var magazineId = $("#magazine_filter").val();
                getMagazineContent(magazineId);
                getMagazineUsers(magazineId);
                totalLoaded++;
                checkAllLoaded();
            }
        });

    }

    var getMagazineContent = function(magazineId){

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/magazine_content_filter",
            data: {magazineId: magazineId},
            success: function (data) {
                $("#article_filter").html(data);
                totalLoaded++;
                checkAllLoaded();
            }
        });

    }

    var getMagazineUsers = function(magazineId){

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/magazine_users_filter",
            data: {magazineId: magazineId},
            success: function (data) {
                $("#user_filter").html(data);
                totalLoaded++;
                checkAllLoaded();
            }
        });

    }

    var checkAllLoaded = function(){

        if( totalLoaded == 3 ){

            // Load the Visualization API and the corechart package.
            google.charts.load('current', {'packages': ['corechart']});

            // Set a callback to run when the Google Visualization API is loaded.
            google.charts.setOnLoadCallback(getReport);

        }

    }

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function getReport() {

        var magazineId = $("#magazine_filter").val() == null ? "" : $("#magazine_filter").val();
        var articleId = $("#article_filter").val() == null ? "" : $("#article_filter").val();
        var userId = $("#user_filter").val() == null ? "" : $("#user_filter").val();

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/use_report_data_1",
            data: {magazineId: magazineId, articleId: articleId, userId: userId},
            success: function (data) {

                var objData = jQuery.parseJSON(data);

                var dataTable = new google.visualization.DataTable();
                dataTable.addColumn('string', 'Sistema Operacional');
                dataTable.addColumn('number', 'Acesso');

                if( objData.length < 1 ){
                    dataTable.addRow(["", 0]);
                } else {

                    for( var i in objData ){
                        dataTable.addRow([objData[i].so_access, Number(objData[i].total)]);
                    }

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
            data: {magazineId: magazineId, articleId: articleId, userId: userId},
            success: function (data) {

                var objData = jQuery.parseJSON(data);

                var dataTable = new google.visualization.DataTable();
                dataTable.addColumn('string', 'Tipo');
                dataTable.addColumn('number', 'Acesso');

                if( objData.length < 1 ){
                    dataTable.addRow(["", 0]);
                } else {

                    for( var i in objData ){
                        dataTable.addRow([objData[i].type_device_access, Number(objData[i].total)]);
                    }

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
            data: {magazineId: magazineId, articleId: articleId, userId: userId},
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
            data: {magazineId: magazineId, articleId: articleId, userId: userId},
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
            data: {magazineId: magazineId, articleId: articleId, userId: userId},
            success: function (data) {
                $("#list_user").html(data);
            }
        });

    }

</script>

</head>

<?php

$userMetric = false;
$soMetric = false;
$typeMetric = false;
$locationMetric = false;
$companyMetric = false;
$articleMetric = false;

foreach( $metrics as $metric ){

    if( $metric["metric"] == 1 ){
        $userMetric = true;
    }

    if( $metric["metric"] == 2 ){
        $soMetric = true;
    }

    if( $metric["metric"] == 3 ){
        $typeMetric = true;
    }

    if( $metric["metric"] == 4 ){
        $locationMetric = true;
    }

    if( $metric["metric"] == 5 ){
        $companyMetric = true;
    }

    if( $metric["metric"] == 6 ){
        $articleMetric = true;
    }

}

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

                    <select id="magazine_filter" class="choose-lang" onChange="getMagazineContent(this.value);"></select>

                </div>

                <div class="select-container">

                    <div class="select-arrow">
                        <div class="arrow-down"></div>
                    </div>

                    <select id="article_filter" class="choose-lang"></select>

                </div>

                <div class="select-container">

                    <div class="select-arrow">
                        <div class="arrow-down"></div>
                    </div>

                    <select id="user_filter" class="choose-lang"></select>

                </div>

            </div>

        </div>

        <br clear="all"/>

        <div class="search-bar left-align-box">
            <a class="add-new" href="javascript:;" onclick="getReport();">Visualizar</a>
        </div>

    </div>

    <div class="category-list">

        <div class="table-container">

            <?php if( $soMetric ): ?>
                <div id="chart_div" style="float: left;"></div>
            <?php endif; ?>

            <?php if( $typeMetric ): ?>
                <div id="chart_div_2" style="float: left;"></div>
            <?php endif; ?>

            <br clear="all" />

            <?php if( $locationMetric ): ?>
                <div id="chart_div_3" style="float: left;"></div>
            <?php endif; ?>

            <?php if( $articleMetric ): ?>
                <div id="chart_div_4" style="float: left;"></div>
            <?php endif; ?>

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