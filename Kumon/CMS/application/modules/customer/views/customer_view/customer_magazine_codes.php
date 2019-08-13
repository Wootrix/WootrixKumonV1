<script>

    $(document).ready(function () {
        getCustomerMagazine();
    });

    var getCustomerMagazine = function () {

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/customer_magazine_filter",
            success: function (data) {
                $("#magazine_filter").html(data);
                var magazineId = $("#magazine_filter").val();
                getReport();
            }
        });

    }

    function getReport(page) {

        var magazineId = $("#magazine_filter").val() == null ? "" : $("#magazine_filter").val();
        var codeUsed = $("#code_used").val() == null ? "" : $("#code_used").val();

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->base_url(); ?>index.php/magazine_code_list",
            data: {magazineId: magazineId, codeUsed: codeUsed, page: page},
            success: function (data) {

                $("#list_user").html(data);

                var totalValues = $("#totalCodes").val();

                $( "#divMessages" ).delay(5000).fadeOut( "fast", function() {

                });

                $(function() {
                    $("#pagination-codes").pagination({
                        items: totalValues,
                        itemsOnPage: 10,
                        cssStyle: 'light-theme',
                        currentPage: page,
                        onPageClick: function(pageNumber, event){
                            getReport(pageNumber);
                        }
                    });
                });

            }
        });

    }

</script>

</head>

<div class="subheader">
    <div class="fix-container clearfix">
        <h4><?php echo $this->lang->line('access_use_report_title') ?></h4>
    </div>
</div>

<div class="fix-container clearfix">

    <div id="divMessages" style="color: red"><p><?php echo $msg;
            echo $this->session->flashdata("msg"); ?></p>
        <div style="color: green"><?php echo $this->session->flashdata("susmsg"); ?>   </div>
        <?php echo validation_errors(); ?>
    </div>

    <div class="upper_bar clearfix filter-bar">

        <div class="search-bar left-align-box">

            <div class="language-filter clearfix">

                <div class="select-container">

                    <div class="select-arrow">
                        <div class="arrow-down"></div>
                    </div>

                    <select id="magazine_filter" class="choose-lang"></select>

                </div>

                <div class="select-container">

                    <div class="select-arrow">
                        <div class="arrow-down"></div>
                    </div>

                    <select id="code_used" class="choose-lang">
                        <option value="">Já utilizado?</option>
                        <option value="0">Não</option>
                        <option value="1">Sim</option>
                    </select>

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

            <div id="list_user"></div>

        </div>

    </div>

    <div id="pagination-codes"></div>

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