<style>
    .feedcomet input.token {
        padding: 10px;
        border-radius: 5px;
        min-width: 500px;
    }

    .feedcomet input.save {
        padding: 9px 30px;
        height: auto;
        line-height: normal;
        margin-top: 1px;
        font-size:15px;
    }

    .feedcomet .syncing_products {
        line-height: 16px;
        margin-top: 2em;
    }
</style>

<div class="wrap feedcomet">
    <h1><?php _e('feedcomet options', 'feedcomet'); ?></h1>
    <p>
        <?php _e('Please provide token from FeedComet platform.', 'feedcomet'); ?>
        <?php _e('Don\'t have token? <a href="http://feedcomet.com/plugin/token/">Get one here!</a>', 'feedcomet'); ?>

    </p>

    <form method="POST" action="">
        <input type="text" class="token" name="token" value="<?php echo $token; ?>" placeholder="Token" />
        <input type="submit" class="button button-primary save" value="<?php _e('Save', 'feedcomet'); ?>" />
    </form>
    <div class="syncing_products"><img src="/wp-admin/images/wpspin_light.gif" /> <?php _e('Synchronizing your products', 'feedcomet'); ?></span></div>
</div>
