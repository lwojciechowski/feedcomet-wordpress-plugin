<style>
    .feedcomet {
        position: relative;
    }

    .feedcomet input.token {
        padding: 10px;
        border-radius: 5px;
        min-width: 500px;
    }

    .feedcomet .button-big {
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

    .feedcomet .help-banner {
        position: absolute;
        top: 60px;
        left: 750px;
    }
</style>

<div class="wrap feedcomet">
    <h1><?php _e('Feedcomet Options', 'feedcomet'); ?></h1>
    <hr />
    <a class="help-banner" href="https://feedcomet.com/help/" target="_blank">
        <img src="<?php echo plugins_url('templates/need_help.png', FEEDCOMET_BASEFILE); ?>" alt="Help" />
    </a>

    <?php if (!$token): ?>
        <p style="font-size: 150%;">
            <?php _e('Please provide token from FeedComet platform.', 'feedcomet'); ?>
            <?php _e('Don\'t have token? <a href="https://app.feedcomet.com/plugin-token">Get one here!</a>', 'feedcomet'); ?>
        </p>

        <?php if ($token_error): ?><p style="color: red;"><?php _e('Provided token is invalid.', 'feedcomet'); ?></p><?php endif; ?>

        <form method="POST" action="">
            <input type="text" class="token" name="token" value="<?php echo $token; ?>" placeholder="Token" />
            <input type="submit" class="button button-primary button-big" value="<?php _e('Connect', 'feedcomet'); ?>" />
        </form>

    <?php else: ?>
        <form method="POST" action="">
            <p style="font-size: 150%;"><?php _e('You are successfully connected to the feedcomet platform.', 'feedcomet') ?></p>
            <input type="hidden" name="disconnect" value="1" />
            <a href="https://app.feedcomet.com/" target="_blank" class="button button-primary button-big"><?php _e('Manage your products', 'feedcomet') ?></a>
            <input type="submit" class="button button-primary button-big" value="<?php _e('Disconnect', 'feedcomet'); ?>" />
        </form>
    <?php endif; ?>

    <div class="syncing_products"><img src="/wp-admin/images/wpspin_light.gif" /> <?php _e('Synchronizing your products', 'feedcomet'); ?></span></div>
</div>
