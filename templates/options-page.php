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
</style>

<div class="wrap feedcomet">
    <h1><?php _e('Feedcomet options', 'feedcomet'); ?></h1>
    <p>
        <?php _e('Please provide token from FeedComet platform.', 'feedcomet'); ?>
        <?php _e('Don\'t have token? <a href="http://feedcomet.com/plugin/token/">Get one here!</a>', 'feedcomet'); ?>

    </p>

    <form method="POST" action="">
        <input type="text" class="token" name="token" value="<?php echo $token; ?>" placeholder="Token" />
        <input type="submit" class="button button-primary save" value="Save" />
    </form>
</div>
