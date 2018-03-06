
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand" tabindex="1" href="<?= \ElggMultisite\Site::site()->getWWWRoot(); ?>">Elgg Multisite</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-collapse">

            <ul class="nav navbar-nav navbar-right">
                <?php

                    if (\ElggMultisite\User::isLoggedIn()) {

                        echo $this->draw('shell/toolbar/logged-in');

                    } else {

                        echo $this->draw('shell/toolbar/logged-out');

                    }

                ?>
            </ul>

        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>