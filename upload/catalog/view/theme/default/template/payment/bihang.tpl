
<?php if ($error_warning) { ?>
     <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
         <button type="button" class="close" data-dismiss="alert">&times;</button>
     </div>
<?php }else{ ?>
	<div class="buttons">
	    <div class="pull-right">
	    	<a href="<?php echo $bihang_payment_url; ?>" class="btn btn-primary"><?php echo $button_confirm; ?></a>
	    </div>
	</div>
<?php } ?>
