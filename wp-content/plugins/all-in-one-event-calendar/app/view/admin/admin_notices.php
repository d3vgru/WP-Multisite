<div class="message updated fade">
	<p>
		<strong><?php echo $label ?></strong> <?php echo $msg ?>
		<?php if( isset( $button ) ) : ?>
			<br /><input type="button" class="button <?php echo $button->class ?>" value="<?php echo $button->value ?>" />
		<?php endif ?>
	</p>
</div>
