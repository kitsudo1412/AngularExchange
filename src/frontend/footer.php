<?php
defined('AE') or die('Access is denied'); ?>

<script src="<?php echo get_js_directory_uri(); ?>/jquery.min.js"></script>
<script src="<?php echo get_js_directory_uri(); ?>/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
        crossorigin="anonymous"></script>
<script src="<?php echo get_js_directory_uri(); ?>/angular.min.js"></script>
<script src="<?php echo get_js_directory_uri(); ?>/angular-route.min.js"></script>
<script src="<?php echo get_js_directory_uri(); ?>/angular-resource.min.js"></script>
<script src="<?php echo get_js_directory_uri(); ?>/ng-infinite-scroll.min.js"></script>
<script type="text/javascript" src="<?php echo get_js_directory_uri(); ?>/app.js"></script>
<?php do_action('ae_footer'); ?>
</body>
</html>