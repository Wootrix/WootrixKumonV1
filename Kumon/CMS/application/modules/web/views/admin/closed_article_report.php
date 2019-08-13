<script>
    $(document).ready(function () {
        $(".view-bttn").click(function () {
            $(".layer-bg").fadeIn();
        });

        $(".close-bttn").click(function () {
            $(".layer-bg").fadeOut();
        });

        getArticles();

    });

    // Load the Visualization API and the corechart package.
    google.charts.load('current', {'packages': ['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(getReport);

    var getArticles = function (magazineId) {

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/magazine_articles",
            data: {magazine_id: magazineId},
            success: function (data) {
                $("#filter_3").html(data);
            }
        });

    }

    // Callback that creates and populates a data table,
    // instantiates the pie chart, passes in the data and
    // draws it.
    function getReport() {

        var magazines = [];
        var articles = [];
        var groups = [];
        var locations = [];
        var disciplines = [];
        var branches = [];

        // $("input[name='magazine[]']:checked").each(function (){
        //     magazines.push(parseInt($(this).val()));
        // });
        //
        // $("input[name='article[]']:checked").each(function (){
        //     articles.push(parseInt($(this).val()));
        // });

        magazines.push($("#filter_1").find('option:selected').val());
        articles.push($("#filter_3").find('option:selected').val());

        $("input[name='group[]']:checked").each(function (){
            groups.push(parseInt($(this).val()));
        });

        $("input[name='location[]']:checked").each(function (){
            locations.push(parseInt($(this).val()));
        });

        $("input[name='discipline[]']:checked").each(function (){
            disciplines.push(parseInt($(this).val()));
        });

        $("input[name='branch[]']:checked").each(function (){
            branches.push($(this).val());
        });

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/admin_report_data_1",
            data: {magazines: magazines, articles: articles, groups: groups, locations: locations, disciplines: disciplines, branches: branches},
            success: function (data) {

                var objData = jQuery.parseJSON(data);

                var dataTable = new google.visualization.DataTable();
                dataTable.addColumn('string', 'Sistema Operacional');
                dataTable.addColumn('number', 'Acesso');

                if (objData.length < 1) {
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

            }
        });

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/admin_report_data_2",
            data: {magazines: magazines, articles: articles, groups: groups, locations: locations, disciplines: disciplines, branches: branches},
            success: function (data) {

                var objData = jQuery.parseJSON(data);

                var dataTable = new google.visualization.DataTable();
                dataTable.addColumn('string', 'Tipo');
                dataTable.addColumn('number', 'Acesso');

                if (objData.length < 1) {
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
            url: "<?php echo $this->config->base_url(); ?>index.php/admin_report_data_3",
            data: {magazines: magazines, articles: articles, groups: groups, locations: locations, disciplines: disciplines, branches: branches},
            success: function (data) {

                var objData = jQuery.parseJSON(data);

                var dataTable = new google.visualization.DataTable();
                dataTable.addColumn('string', 'Cidade');
                dataTable.addColumn('number', 'Acesso');

                for (var i in objData) {
                    dataTable.addRow([objData[i].city, Number(objData[i].total)]);
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
            data: {magazines: magazines, articles: articles, groups: groups, locations: locations, disciplines: disciplines, branches: branches},
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
            data: {magazines: magazines, articles: articles, groups: groups, locations: locations, disciplines: disciplines, branches: branches},
            success: function (data) {
                $("#list_user").html(data);
            }
        });

    }

    function toggleMagazine(source) {
        checkboxes = document.getElementsByName('magazine[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    function toggleArticle(source) {
        checkboxes = document.getElementsByName('article[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    function toggleGroup(source) {
        checkboxes = document.getElementsByName('group[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    function toggleLocation(source) {
        checkboxes = document.getElementsByName('location[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    function toggleDiscipline(source) {
        checkboxes = document.getElementsByName('discipline[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
    }

    function toggleBranch(source) {
        checkboxes = document.getElementsByName('branch[]');
        for (var i = 0, n = checkboxes.length; i < n; i++) {
            checkboxes[i].checked = source.checked;
        }
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

        <div class="select-container" style="float: left;">

            <div class="select-arrow">
                <div class="arrow-down"></div>
            </div>

            <select id="filter_1" class="choose-lang" onChange="getArticles(this.value);">
                <option value="">Todos</option>
                <?php
                if (!empty($magazines)) {
                    foreach ($magazines as $value) {
                        $id = $value['id'];
                        $magazine_name = $value['title'];
                        echo '<option value="' . $id . '">' . $magazine_name . '</div>';
                    }
                }
                ?>
            </select>

        </div>

        <div class="select-container clearfix" style="width: 70%; float: left; margin-left: 10px;">

            <div class="select-arrow">
                <div class="arrow-down"></div>
            </div>

            <select id="filter_3" class="choose-lang" onChange="" style="width: 100%; overflow: hidden;"></select>

        </div>

<!--        <div style="float: left; margin-right: 10px;" >-->
<!---->
<!--            <label class="full-width-label" style="float: left;"><b>Revistas</b></label>-->
<!---->
<!--            <br clear="all" />-->
<!---->
<!--            <div class="selectAll" style="text-align: left;">-->
<!--                <input type="checkbox" name='selectall' id='selectall' onClick="toggleMagazine(this)"/> Select All-->
<!--            </div>-->
<!---->
<!--            <div class="clearfix">-->
<!---->
<!--                <div class="multiselect-box clearfix fullbox selectCategory" style="text-align: left;">-->
<!--                    --><?php
//                    if (!empty($magazines)) {
//                        foreach ($magazines as $value) {
//                            $id = $value['id'];
//                            $magazine_name = $value['title'];
//                            echo '<div class="cbox"><input type="checkbox" class="catagory_class" id="magazine[]" name="magazine[]" value="' . $id . '" />' . $magazine_name . '</div>';
//                        }
//                    }
//                    ?>
<!--                </div>-->
<!---->
<!--            </div>-->
<!---->
<!--        </div>-->

<!--        <div style="margin-left: 10px; overflow:hidden">-->
<!---->
<!--            <label class="full-width-label" style="float: left;"><b>Artigos</b></label>-->
<!---->
<!--            <br clear="all" />-->
<!---->
<!--            <div class="selectAll" style="text-align: left;">-->
<!--                <input type="checkbox" name='selectall' id='selectall' onClick="toggleArticle(this)"/> Select All-->
<!--            </div>-->
<!---->
<!--            <div class="clearfix">-->
<!---->
<!--                <div class="multiselect-box clearfix fullbox selectCategory" style="text-align: left; width: 100%;">-->
<!--                    --><?php
//                    if (!empty($articles)) {
//                        foreach ($articles as $article) {
//                            $id = $article['id'];
//                            $magazine_name = $article['title'];
//                            echo '<div class="cbox"><input type="checkbox"  class="catagory_class" id="article[]" name="article[]" value="' . $id . '" />' . $magazine_name . '</div>';
//                        }
//                    }
//                    ?>
<!--                </div>-->
<!---->
<!--                <label for="catagory" style="display:none;" generated="true"-->
<!--                       class="error category_class_error">--><?php //echo $this->lang->line("Please_select_magazine") ?>
<!--                    .</label>-->
<!---->
<!--            </div>-->
<!---->
<!--        </div>-->

        <br clear="all" /><br clear="all" />

        <div style="float: left;">

            <label class="full-width-label" style="float: left;"><b>Grupos</b></label>

            <br clear="all" />

            <div class="selectAll" style="text-align: left;">
                <input type="checkbox" name='selectall' id='selectall' onClick="toggleGroup(this)"/> Select All
            </div>

            <div class="select-container clearfix">

                <div class="multiselect-box clearfix fullbox selectCategory" style="text-align: left;">
                    <?php
                    if (!empty($groups)) {
                        foreach ($groups as $value) {
                            $id = $value['id'];
                            $magazine_name = $value['name'];
                            echo '<div class="cbox"><input type="checkbox"  class="catagory_class" id="group[]" name="group[]" value="' . $id . '" />' . $magazine_name . '</div>';
                        }
                    }
                    ?>
                </div>

                <label for="catagory" style="display:none;" generated="true"
                       class="error category_class_error"><?php echo $this->lang->line("Please_select_magazine") ?>
                    .</label>

            </div>

        </div>

        <div style="margin-left: 10px; float: left;">

            <label class="full-width-label" style="float: left;"><b>Localizações</b></label>

            <br clear="all" />

            <div class="selectAll" style="text-align: left;">
                <input type="checkbox" name='selectall' id='selectall' onClick="toggleLocation(this)"/> Select All
            </div>

            <div class="multiselect-box clearfix fullbox selectCategory" style="text-align: left;">
                <?php
                if (!empty($locations)) {
                    foreach ($locations as $value) {
                        $id = $value['id'];
                        $magazine_name = $value['city'];
                        echo '<div class="cbox"><input type="checkbox"  class="catagory_class" id="location[]" name="location[]" value="' . $id . '" />' . $magazine_name . '</div>';
                    }
                }
                ?>
            </div>

            <label for="location" style="display:none;" generated="true" class="error category_class_error">Por favor,
                selecione uma localidade.</label>

        </div>

        <div style="margin-left: 10px; float: left;">

            <label class="full-width-label" style="float: left;"><b>Disciplinas</b></label>

            <br clear="all" />

            <div class="selectAll" style="text-align: left;">
                <input type="checkbox" name='selectall' id='selectall' onClick="toggleDiscipline(this)"/> Select All
            </div>

            <div class="multiselect-box clearfix fullbox selectCategory" style="text-align: left;">
                <?php
                if (!empty($disciplines)) {
                    foreach ($disciplines as $value) {
                        $id = $value['id'];
                        $magazine_name = $value['name'];
                        echo '<div class="cbox"><input type="checkbox"  class="catagory_class" id="discipline[]" name="discipline[]" value="' . $id . '" />' . $magazine_name . '</div>';
                    }
                }
                ?>
            </div>

            <label for="catagory" style="display:none;" generated="true" class="error category_class_error">Por favor,
                selecione uma matéria.</label>

        </div>

        <br clear="all" /><br clear="all" />

        <div style="float: left;">

            <label class="full-width-label" style="float: left;"><b>Filiais</b></label>

            <br clear="all" />

            <div class="selectAll" style="text-align: left;">
                <input type="checkbox" name='selectall' id='selectall' onClick="toggleBranch(this)"/> Select All
            </div>

            <div class="select-container clearfix">

                <div class="multiselect-box clearfix fullbox selectCategory" style="text-align: left;">
                    <?php
                    if (!empty($branches)) {
                        foreach ($branches as $value) {
                            $id = $value['branch'];
                            $magazine_name = $value['branch'];
                            echo '<div class="cbox"><input type="checkbox"  class="catagory_class" id="branch[]" name="branch[]" value="' . $id . '" />' . $magazine_name . '</div>';
                        }
                    }
                    ?>
                </div>

            </div>

        </div>

        <br clear="all"/><br clear="all"/>

        <div class="search-bar left-align-box">
            <a class="add-new" href="javascript:;" onclick="getReport();">Visualizar</a>
        </div>

    </div>

    <div class="category-list">

        <div class="table-container">

            <div id="chart_div" style="float: left;"></div>

            <div id="chart_div_2" style="float: left;"></div>

            <br clear="all"/>

            <div id="chart_div_3" style="float: left;"></div>

            <div id="chart_div_4" style="float: left;"></div>

            <br clear="all"/><br/>

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