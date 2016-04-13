<?php
defined('AE') or die('Access is denied'); ?>

<html ng-app="angularExchangeApp">
<base href="<?php echo home_url(); ?>/">
<link rel="stylesheet" href="<?php echo get_css_directory_uri(); ?>/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="<?php echo get_css_directory_uri(); ?>/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" />
<link rel="stylesheet" href="<?php echo get_css_directory_uri(); ?>/textAngular.css" />
<link rel="stylesheet" href="<?php echo get_css_directory_uri(); ?>/angular-material.min.css" />
<link rel="stylesheet" href="<?php echo get_css_directory_uri(); ?>/style.css" />
<?php do_action( 'ae_head' ); ?>
<body>
<header>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo home_url(); ?>">Angular Exchange Application</a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php echo home_url(); ?>"><i class="fa fa-home"></i> Login</a></li>
                <li><a href="<?php echo home_url(); ?>"><i class="fa fa-home"></i> Register</a></li>
            </ul>
        </div>
    </nav>
</header>
