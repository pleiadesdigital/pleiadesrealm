<?php /* Pleiades Realm functions and definitions */

// SETUP FUNCTIONS
function pleiadesrealm_setup() {
	// Translation
	load_theme_textdomain('pleiadesrealm');
	// Add default posts and comments RSS feed links to head
	add_theme_support('automatic-feed-links');
	// Title tag
	add_theme_support('title-tag');
  // Post Thumbnails
	add_theme_support('post-thumbnails');
	// Image Sizes
	add_image_size('pleiadesrealm-featured-image', 2000, 1200, true);
	add_image_size('pleiadesrealm-thumbnail-avatar', 100, 100, true);
	// MENUS (nav_menus())
	register_nav_menus( array(
		'top'    => __('Top Menu', 'pleiadesrealm'),
		'social' => __('Social Links Menu', 'pleiadesrealm'),
	));
	// HTML5 support
	add_theme_support('html5', array(
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	));
	// Post Formats
	add_theme_support('post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'audio',
	));
	// Custom LOGO
	add_theme_support('custom-logo', array(
		'width'       => 250,
		'height'      => 250,
		'flex-width'  => true,
	));
}
add_action('after_setup_theme', 'pleiadesrealm_setup');

// SIDEBAR WIDGET AREAS
function pleiadesrealm_widgets_init() {
	register_sidebar(array(
		'name'          => __('Blog Sidebar', 'pleiadesrealm'),
		'id'            => 'sidebar-1',
		'description'   => __('Add widgets here to appear in your sidebar on blog posts and archive pages.', 'pleiadesrealm'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));
	register_sidebar(array(
		'name'          => __('Footer 1', 'pleiadesrealm'),
		'id'            => 'sidebar-2',
		'description'   => __('Add widgets here to appear in your footer.', 'pleiadesrealm'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));
	register_sidebar(array(
		'name'          => __('Footer 2', 'pleiadesrealm'),
		'id'            => 'sidebar-3',
		'description'   => __('Add widgets here to appear in your footer.', 'pleiadesrealm'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	));
}
add_action('widgets_init', 'pleiadesrealm_widgets_init');

/* Replaces "[...]" with ... and a 'Continue reading' link */
function pleiadesrealm_excerpt_more($link) {
	if (is_admin()) {
		return $link;
	}
	$link = sprintf('<p class="link-more"><a href="%1$s" class="more-link">%2$s</a></p>',
		esc_url(get_permalink(get_the_ID())),
		/* translators: %s: Name of current post */
		sprintf( __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'pleiadesrealm'), get_the_title(get_the_ID()))
	);
	return ' &hellip; ' . $link;
}
add_filter('excerpt_more', 'pleiadesrealm_excerpt_more');

/* Adds a `js` class to the root `<html>` element when JavaScript is detected */
function pleiadesrealm_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action('wp_head', 'pleiadesrealm_javascript_detection', 0);

/*****************************************************
 *********** ENQUEUE SCRIPTS AND STYLES **************
 *****************************************************/
function pleiadesrealm_scripts() {
	// Theme stylesheet
	wp_enqueue_style('pleiadesrealm-style', get_stylesheet_uri());
	wp_enqueue_script('pleiadesrealm-skip-link-focus-fix', get_theme_file_uri('/assets/js/skip-link-focus-fix.js'), array(), '1.0', true);
	$pleiadesrealm_l10n = array(
		'quote'          => pleiadesrealm_get_svg(array('icon' => 'quote-right')),
	);
	// MENU JS FUNCTIONALITY
	if (has_nav_menu('top')) {
		wp_enqueue_script('pleiadesrealm-navigation', get_theme_file_uri('/assets/js/navigation.js'), array('jquery'), '1.0', true);
		$pleiadesrealm_l10n['expand']         = __('Expand child menu', 'pleiadesrealm');
		$pleiadesrealm_l10n['collapse']       = __('Collapse child menu', 'pleiadesrealm');
		$pleiadesrealm_l10n['icon']           = pleiadesrealm_get_svg(array('icon' => 'angle-down', 'fallback' => true));
	}
	// GLOBAL VARIABLES AND SCRIPTS
	wp_enqueue_script('pleiadesrealm-global', get_theme_file_uri( '/assets/js/global.js' ), array('jquery'), '1.0', true);
	// SCROLL TO
	wp_enqueue_script('jquery-scrollto', get_theme_file_uri('/assets/js/jquery.scrollTo.js'), array('jquery'), '2.1.2', true );

	wp_localize_script('pleiadesrealm-skip-link-focus-fix', 'pleiadesrealmScreenReaderText', $pleiadesrealm_l10n);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action( 'wp_enqueue_scripts', 'pleiadesrealm_scripts' );

/* Modifies tag cloud widget arguments to display all tags in the same font sizeand use list format for better accessibility */
function pleiadesrealm_widget_tag_cloud_args( $args ) {
	$args['largest']  = 1;
	$args['smallest'] = 1;
	$args['unit']     = 'em';
	$args['format']   = 'list';
	return $args;
}
add_filter('widget_tag_cloud_args', 'pleiadesrealm_widget_tag_cloud_args');

/* Custom Header feature */
require get_parent_theme_file_path('/inc/custom-header.php');
/* Custom template tags for this theme */
require get_parent_theme_file_path('/inc/template-tags.php');
/* Additional features to allow styling of the templates */
require get_parent_theme_file_path('/inc/template-functions.php');
/* SVG icons functions and filters */
require get_parent_theme_file_path('/inc/icon-functions.php');
