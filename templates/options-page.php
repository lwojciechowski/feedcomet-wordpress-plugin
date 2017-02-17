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
    <h1><?php _e('Feedcomet Options', 'feedcomet'); ?></h1>
    <hr />
    <?php if (!$token): ?>
        <p style="font-size: 150%;">
            <?php _e('Please provide token from FeedComet platform.', 'feedcomet'); ?>
            <?php _e('Don\'t have token? <a href="http://feedcomet.com/plugin/token/">Get one here!</a>', 'feedcomet'); ?>
        </p>

        <?php if ($token_error): ?><p style="color: red;"><?php _e('Provided token is invalid.', 'feedcomet'); ?></p><?php endif; ?>

        <form method="POST" action="">
            <input type="text" class="token" name="token" value="<?php echo $token; ?>" placeholder="Token" />
            <input type="submit" class="button button-primary save" value="<?php _e('Save', 'feedcomet'); ?>" />
        </form>

    <?php else: ?>
        <form method="POST" action="">
            <p style="font-size: 150%;">Your are successfully connected to the feedcomet platform.</p>
            <input type="hidden" name="disconnect" value="1" />
            <input type="submit" class="button button-primary save" value="<?php _e('Disconnect', 'feedcomet'); ?>" />
        </form>
    <?php endif; ?>

    <div class="syncing_products"><img src="/wp-admin/images/wpspin_light.gif" /> <?php _e('Synchronizing your products', 'feedcomet'); ?></span></div>
</div>
