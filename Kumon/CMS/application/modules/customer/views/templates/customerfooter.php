<script>
    $( document ).ready(function() {
    $("<div class='clearfix'></div>").insertAfter(".dataTables_filter");
    $('iframe', window.parent.document).width('150px');
    $('iframe', window.parent.document).height('150px');
});
    
</script>
<footer>
        <div class="fix-container clearfix">
            <div class="bttm-logo"><a href="#"><img src="<?php echo $this->config->base_url();?>images/logo.png" alt=""/></a></div>
            <div class="bttm-links"><span>© 2014 wootrix, Inc.</span> | <a href="#">Terms of Service</a> | <a href="#">Privacy Policy</a></div>
        </div>
    
    </footer>
    
</body>
</html>