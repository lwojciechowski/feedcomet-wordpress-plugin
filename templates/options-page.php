<style>
	input[type=text], input[type=password] {
		display: block;
		width: 50%;
		padding: 10px;
		margin-bottom: 10px;
	}
	input[type=submit] {
		display: block;
		clear: both;
	}
</style>

<div class="wrap">
	<h1><?php _e('VueFeed Options', 'vuefeed'); ?></h1>	
	<p><?php _e('Please provide client ID and secret generated in VueFeed system.', 'vuefeed'); ?></p>
	<form method="POST" action="">
		<input type="text" name="client" placeholder="Client ID" value="<?php echo $client; ?>" />
		<input type="password" name="secret" placeholder="Secret" />
		<input type="submit" class="button button-primary" value="Save" />
	</form>
</div>
