<script>

    $(document).ready(function () {
        $(".view-bttn").click(function () {
            $(".layer-bg").fadeIn();
        });

        $(".close-bttn").click(function () {
            $(".layer-bg").fadeOut();
        });

//        getOpenMagazines();
//        getLanguage();
//        getCustomer();

    });

//    var totalLoaded = 0;
//
//    var getOpenMagazines = function () {
//
//        $.ajax({
//            type: "POST",
//            url: "<?php //echo $this->config->base_url(); ?>//index.php/open_magazine_filter",
//            success: function (data) {
//                $("#filter_1").html(data);
//                var categoryId = $("#filter_1").val();
//                getCategoryArticles(categoryId);
//                totalLoaded++;
//                checkAllLoaded();
//            }
//        });
//
//    }
//
//    var getLanguage = function () {
//
//        $.ajax({
//            type: "POST",
//            url: "<?php //echo $this->config->base_url(); ?>//index.php/language_filter",
//            success: function (data) {
//                $("#filter_2").html(data);
//                var customerId = $("#filter_1").val();
//                var languageId = $("#filter_2").val();
//                getCategoryArticles(customerId, languageId);
//                totalLoaded++;
//                checkAllLoaded();
//            }
//        });
//
//    }
//
//    var getCategoryArticles = function (categoryId, languageId) {
//
//        $.ajax({
//            type: "POST",
//            url: "<?php //echo $this->config->base_url(); ?>//index.php/category_article_filter",
//            data: {categoryId: categoryId, languageId: languageId},
//            success: function (data) {
//                $("#filter_3").html(data);
//                totalLoaded++;
//                checkAllLoaded();
//            }
//        });
//
//    }
//
//    var getCustomer = function () {
//
//        $.ajax({
//            type: "POST",
//            url: "<?php //echo $this->config->base_url(); ?>//index.php/get_customer_filter",
//            success: function (data) {
//                $("#filter_4").html(data);
//                var customerId = $("#filter_4").val();
//                getCustomerUsers(customerId);
//                totalLoaded++;
//                checkAllLoaded();
//            }
//        });
//
//    }
//
//    var getCustomerUsers = function (customerId) {
//
//        $.ajax({
//            type: "POST",
//            url: "<?php //echo $this->config->base_url(); ?>//index.php/customer_users_filter",
//            data: {customerId: customerId},
//            success: function (data) {
//                $("#filter_5").html(data);
//                totalLoaded++;
//                checkAllLoaded();
//            }
//        });
//
//    }
//
//    var checkAllLoaded = function(){
//
//        if( totalLoaded == 5 ){
//
//            // Load the Visualization API and the corechart package.
//            google.charts.load('current', {'packages': ['corechart']});
//
//            // Set a callback to run when the Google Visualization API is loaded.
//            google.charts.setOnLoadCallback(getReport);
//
//        }
//
//    }

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function getReport() {

        var categoryId = $("#filter_1").val() == null ? "" : $("#filter_1").val();
        var languageId = $("#filter_2").val() == null ? "" : $("#filter_2").val();
        var articleId = $("#filter_3").val() == null ? "" : $("#filter_3").val();
        var customerId = $("#filter_4").val() == null ? "" : $("#filter_4").val();
        var userId = $("#filter_5").val() == null ? "" : $("#filter_5").val();

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/admin_report_data_1",
            data: {categoryId: categoryId, languageId: languageId, articleId: articleId, customerId: customerId, userId: userId},
            success: function (data) {

                var objData = jQuery.parseJSON(data);

                var dataTable = new google.visualization.DataTable();
                dataTable.addColumn('string', 'Sistema Operacional');
                dataTable.addColumn('number', 'Acesso');

                if( objData.length < 1 ){
                    dataTable.addRow(["", 0]);
                } else {

                    for (var i in objData) {
                        dataTable.addRow([objData[i].so_access, Number(objData[i].total)]);
                    }

                }

                var options = {
                    'title': 'Visualizações por sistema operacional',
                    'width': 500,
                    'height': 300
                };

                var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                chart.draw(dataTable, options);

//                if(dataTable.getNumberOfRows() == 0){
//                    $("#chart_div").html("Sorry, not info available")
//                }

            }
        });

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/admin_report_data_2",
            data: {categoryId: categoryId, languageId: languageId, articleId: articleId, customerId: customerId, userId: userId},
            success: function (data) {

                var objData = jQuery.parseJSON(data);

                var dataTable = new google.visualization.DataTable();
                dataTable.addColumn('string', 'Tipo');
                dataTable.addColumn('number', 'Acesso');

                if( objData.length < 1 ){
                    dataTable.addRow(["", 0]);
                } else {

                    for (var i in objData) {
                        dataTable.addRow([objData[i].type_device_access, Number(objData[i].total)]);
                    }

                }

                var options = {
                    'title': 'Visualizações por tipo de dispositivo',
                    'width': 500,
                    'height': 300
                };

                var chart = new google.visualization.PieChart(document.getElementById('chart_div_2'));
                chart.draw(dataTable, options);

            }
        });

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/admin_report_data_6",
            data: {categoryId: categoryId, languageId: languageId, articleId: articleId, customerId: customerId, userId: userId},
            success: function (data) {

                var objData = jQuery.parseJSON(data);

                var dataTable = new google.visualization.DataTable();
                dataTable.addColumn('string', 'Cliente');
                dataTable.addColumn('number', 'Acesso');

                for (var i in objData) {
                    dataTable.addRow([objData[i].name, Number(objData[i].total)]);
                }

                var options = {
                    'title': 'Visualizações por cliente',
                    'width': 500,
                    'height': 300
                };

                var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_5'));
                chart.draw(dataTable, options);

            }
        });

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/admin_report_data_3",
            data: {categoryId: categoryId, languageId: languageId, articleId: articleId, customerId: customerId, userId: userId},
            success: function (data) {

                var objData = jQuery.parseJSON(data);

                var dataTable = new google.visualization.DataTable();
                dataTable.addColumn('string', 'Estado');
                dataTable.addColumn('number', 'Acesso');

                for (var i in objData) {
                    dataTable.addRow([objData[i].state, Number(objData[i].total)]);
                }

                var options = {
                    'title': 'Visualizações por localização',
                    'width': 500,
                    'height': 300
                };

                var chart = new google.visualization.ColumnChart(document.getElementById('chart_div_3'));
                chart.draw(dataTable, options);

            }
        });

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/admin_report_data_4",
            data: {categoryId: categoryId, languageId: languageId, articleId: articleId, customerId: customerId, userId: userId},
            success: function (data) {

                var objData = jQuery.parseJSON(data);

                var dataTable = new google.visualization.DataTable();
                dataTable.addColumn('string', 'Artigo');
                dataTable.addColumn('number', 'Acesso');

                for (var i in objData) {
                    dataTable.addRow([objData[i].title, Number(objData[i].total)]);
                }

                var options = {
                    'title': 'Visualizações por artigo/publicidade',
                    'width': 500,
                    'height': 300
                };

                var chart = new google.visualization.BarChart(document.getElementById('chart_div_4'));
                chart.draw(dataTable, options);

            }
        });

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/admin_report_data_5",
            data: {categoryId: categoryId, languageId: languageId, articleId: articleId, customerId: customerId, userId: userId},
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
        <h4>Deep Links</h4>
    </div>
</div>

<div class="fix-container clearfix">

    <div class="upper_bar clearfix filter-bar">

        <div class="search-bar left-align-box">

<!--            <div class="select-container">-->
<!---->
<!--                <div class="select-arrow">-->
<!--                    <div class="arrow-down"></div>-->
<!--                </div>-->
<!---->
<!--                <select id="filter_1" class="choose-lang" onChange="getCategoryArticles(this.value);"></select>-->
<!---->
<!--            </div>-->
<!---->
<!--            <div class="select-container">-->
<!---->
<!--                <div class="select-arrow">-->
<!--                    <div class="arrow-down"></div>-->
<!--                </div>-->
<!---->
<!--                <select id="filter_2" class="choose-lang" onChange="getCategoryArticles($('#filter_1').val(), this.value);"></select>-->
<!---->
<!--            </div>-->
<!---->
<!--            <div class="select-container clearfix">-->
<!---->
<!--                <div class="select-arrow">-->
<!--                    <div class="arrow-down"></div>-->
<!--                </div>-->
<!---->
<!--                <select id="filter_3" class="choose-lang" onChange=""></select>-->
<!---->
<!--            </div>-->

        </div>

        <br clear="all"/>

        <div class="search-bar left-align-box">

<!--            <div class="select-container clearfix">-->
<!---->
<!--                <div class="select-arrow">-->
<!--                    <div class="arrow-down"></div>-->
<!--                </div>-->
<!---->
<!--                <select id="filter_4" class="choose-lang" onChange="getCustomerUsers(this.value);"></select>-->
<!---->
<!--            </div>-->
<!---->
<!--            <div class="select-container">-->
<!---->
<!--                <div class="select-arrow">-->
<!--                    <div class="arrow-down"></div>-->
<!--                </div>-->
<!---->
<!--                <select id="filter_5" class="choose-lang" onChange=""></select>-->
<!---->
<!--            </div>-->

        </div>

        <br clear="all"/><br clear="all"/>

<!--        <div class="search-bar left-align-box">-->
<!--            <a class="add-new" href="javascript:;" onclick="getReport();">Visualizar</a>-->
<!--        </div>-->

    </div>

    <div class="category-list">

        <div class="table-container">

<!--            <div id="chart_div" style="float: left;"></div>-->
<!---->
<!--            <div id="chart_div_2" style="float: left;"></div>-->
<!---->
<!--            <br clear="all"/>-->
<!---->
<!--            <div id="chart_div_3" style="float: left;"></div>-->
<!---->
<!--            <div id="chart_div_4" style="float: left;"></div>-->
<!---->
<!--            <br clear="all"/><br/>-->
<!---->
<!--            <div id="chart_div_5" style="float: left;"></div>-->
<!---->
<!--            <br clear="all"/><br/>-->

            <div id="list_links"></div>

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