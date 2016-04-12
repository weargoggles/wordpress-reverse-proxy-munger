defined( 'ABSPATH' ) or die( 'this is a plugin' );

function rp_is_login_page() {
    return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
}

if ( $_SERVER["REMOTE_ADDR"] != "127.0.0.1" && !is_admin() && !rp_is_login_page() && $_GET["preview"] != "true" ) {

    // From http://stackoverflow.com/questions/772510/wordpress-filter-to-modify-final-html-output
    ob_start();
    add_action('shutdown', function() {
        $final = '';
        $levels = count(ob_get_level());
        for ( $i = 0; $i < $levels; $i++ ) {
            $final .= ob_get_clean();
        }
    	if ($_SERVER["HTTP_X_ORIGINAL_HOST"]) {
            // Apply filters to the final output
            $final = str_replace("//" . $_SERVER["HTTP_HOST"], "//" . $_SERVER["HTTP_X_ORIGINAL_HOST"] . "/" . $_SERVER["HTTP_X_ORIGINAL_PATH"], $final);
          	$final = str_replace("\/\/" . $_SERVER["HTTP_HOST"], "\/\/" . $_SERVER["HTTP_X_ORIGINAL_HOST"] . "\/" . $_SERVER["HTTP_X_ORIGINAL_PATH"], $final);
          	$final = str_replace("='/wp-", "='/" . $_SERVER["HTTP_X_ORIGINAL_PATH"] . "/wp-", $final);
          	$final = str_replace('="/wp-', '="/' . $_SERVER["HTTP_X_ORIGINAL_PATH"] . "/wp-", $final);
    	}
        echo $final;
    }, 0);
}
