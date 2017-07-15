		</div>
	</div>
</div>
<script src="<?php echo PROJECT_URL; ?>/script/validation.js"></script>
<script src="<?php echo PROJECT_URL; ?>/script/jquery_ui/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo PROJECT_URL; ?>/script/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="<?php echo PROJECT_URL; ?>/script/jquery-ui-timepicker/jquery-ui-timepicker-addon.js"></script>
<script src="<?php echo PROJECT_URL; ?>/script/select2/select2.full.js"></script>

<script>
	if (screen.width < 992) {
		$('[data-toggle=offcanvas]').click(function() {
			$('.row-offcanvas').toggleClass('active');
			$('.collapse').toggleClass('in').toggleClass('visible-xs').toggleClass('visible-xs');
		});
	} else {
		$('[data-toggle=offcanvas]').click(function() {
			$('.row-offcanvas').toggleClass('active');
			$('.collapse').toggleClass('in').toggleClass('hidden-xs').toggleClass('visible-xs');
		});
	}
</script>
<script>
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
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
$('.nav li a:first-child').click(function(e) {
	console.log($(this).children('.navrgtarrow').children('fa-chevron-right'));
  //$(this).children('i').removeClass('fa-chevron-right').addClass('fa-chevron-down');
});
//$('.collapsed .navrgtarrow').children('i').removeClass('fa-chevron-right').addClass('fa-chevron-down');  
 $(document).ready(function(e) {
    $('.nav li a:first-child').addClass('collapsed');
	
});   

    </script>
	<script>
    if (screen.width < 992) {
        $('[data-toggle=offcanvas]').click(function () {
            $('.row-offcanvas').toggleClass('active');
            $('.collapse').toggleClass('in').toggleClass('visible-xs').toggleClass('visible-xs');

        });
    } else {

        $('[data-toggle=offcanvas]').click(function () {
            $('.row-offcanvas').toggleClass('active');
            $('.collapse').toggleClass('in').toggleClass('hidden-xs').toggleClass('visible-xs');
        });
    }

</script>

</body>
</html>