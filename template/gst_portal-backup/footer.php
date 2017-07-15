  <div class="footer">Copyright @ by GST Keeper</div> 

<script>
    if (screen.width < 992) {
   $('[data-toggle=offcanvas]').click(function() {
    $('.row-offcanvas').toggleClass('active');
    $('.collapse').toggleClass('in').toggleClass('visible-xs').toggleClass('visible-xs');
	
});
}
else {

    $('[data-toggle=offcanvas]').click(function() {
    $('.row-offcanvas').toggleClass('active');
    $('.collapse').toggleClass('in').toggleClass('hidden-xs').toggleClass('visible-xs');
});
}
    
    </script>
<script src="<?php echo PROJECT_URL; ?>/script/validation.js"></script>
<script src="<?php echo PROJECT_URL; ?>/script/jquery_ui/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo PROJECT_URL; ?>/script/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo PROJECT_URL; ?>/script/jquery-ui-timepicker/jquery-ui-timepicker-addon.js"></script>
<script src="<?php echo PROJECT_URL; ?>/script/select2/select2.full.js"></script>
</body>
</html>