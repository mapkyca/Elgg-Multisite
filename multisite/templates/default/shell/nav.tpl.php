
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand" tabindex="1" href="<?= \ElggMultisite\Site::site()->getWWWRoot(); ?>">Elgg Multisite</a>
        </div>


            <ul class="nav justify-content-end">
                <?php

                    if (\ElggMultisite\User::isLoggedIn()) {

                        echo $this->draw('shell/toolbar/logged-in');

                    } else {

                        echo $this->draw('shell/toolbar/logged-out');

                    }

                ?>
            </ul>

    </div><!-- /.container-fluid -->
</nav>