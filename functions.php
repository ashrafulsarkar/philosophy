<?php
require_once(get_theme_file_path("/inc/tgm.php"));
require_once(get_theme_file_path("/inc/attachments.php"));
require_once(get_theme_file_path("/widgets/social-icons-widget.php"));

if (!isset($content_width)) $content_width = 960;

if (site_url() == "http://localhost/one") {
	define("VERSION", time());
} else {
	define("VERSION", wp_get_theme()->get("Version"));
}

function philosophy_setup_theme()
{
	load_theme_textdomain("philosophy",get_theme_file_path("/languages"));
	add_theme_support("post-thumbnails");
	add_theme_support("custom-logo");
	add_theme_support("title-tag");
	add_theme_support( 'automatic-feed-links' );
	add_theme_support("html5", array("search-form", "comment-list"));
	add_theme_support("post-formats", array("image", "video", "audio", "gallery", "link", "quote"));
	add_editor_style("/assets/css/editor-style.css");

	register_nav_menu("topmenu", __("Top Menu", "philosophy"));
	register_nav_menus(array(
		"footer-left" => __("Footer Left Menu", "philosophy"),
		"footer-middle" => __("Footer Middle Menu", "philosophy"),
		"footer-right" => __("Footer Right Menu", "philosophy"),
	));
	add_image_size("philosophy-square", "400", "400", true);
}
add_action("after_setup_theme", "philosophy_setup_theme");

function philosophy_assets(){
	wp_enqueue_style("fontawasome-css", get_theme_file_uri("/assets/css/font-awesome/css/font-awesome.min.css"), null, "1.0");
	wp_enqueue_style("fonts-css", get_theme_file_uri("/assets/css/fonts.css"), null, "1.0");
	wp_enqueue_style("base-css", get_theme_file_uri("/assets/css/base.css"), null, "1.0");
	wp_enqueue_style("vendor-css", get_theme_file_uri("/assets/css/vendor.css"), null, "1.0");
	wp_enqueue_style("main-css", get_theme_file_uri("/assets/css/main.css"), null, "1.0");
	wp_enqueue_style("philosophy-css", get_stylesheet_uri(), null, VERSION);

	wp_enqueue_script("modernizr-js", get_theme_file_uri("assets/js/modernizr.js"), null, "1.0");
	wp_enqueue_script("pace-js", get_theme_file_uri("assets/js/pace.min.js"), null, "1.0");
	wp_enqueue_script("plugins-js", get_theme_file_uri("assets/js/plugins.js"), array("jquery"), "1.0", true);
	if (is_singular()) wp_enqueue_script("comment-reply");
	wp_enqueue_script("main-js", get_theme_file_uri("assets/js/main.js"), array("jquery"), "1.0", true);
}
add_action("wp_enqueue_scripts", "philosophy_assets");

function philosophy_pagination()
{
	global $wp_query;
	$links = paginate_links(array(
		"current" => max(1, get_query_var("paged")),
		"total" => $wp_query->max_num_pages,
		"type" => "list",
		"mid_size" => "3",
	));
	$links = str_replace("page-numbers", "pgn__num", $links);
	$links = str_replace("<ul class='pgn__num'>", "<ul>", $links);
	$links = str_replace("next pgn__num", "pgn__next", $links);
	$links = str_replace("prev pgn__num", "pgn__prev", $links);
	echo wp_kses_post($links);
}

remove_action("term_description", "wpautop");

function philosophy_about_widgets()
{
	register_sidebar(array(
		'name'          => __('About Us Page', 'philosophy'),
		'id'            => 'about-us',
		'description'   => __('Widgets in this area will be shown on About Us page.', 'philosophy'),
		'before_widget' => '<div id="%1$s" class="col-block %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="quarter-top-margin">',
		'after_title'   => '</h3>',
	));
	register_sidebar(array(
		'name'          => __('Contact Page Maps', 'philosophy'),
		'id'            => 'contact-maps',
		'description'   => __('Widgets in this area will be shown on Contact page.', 'philosophy'),
		'before_widget' => '<div id="%1$s" class="%2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '',
		'after_title'   => '',
	));
	register_sidebar(array(
		'name'          => __('Contact Page Information', 'philosophy'),
		'id'            => 'contact-info',
		'description'   => __('Widgets in this area will be shown on Contact page.', 'philosophy'),
		'before_widget' => '<div id="%1$s" class="col-six tab-full %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	));
	register_sidebar(array(
		'name'          => __('Before Footer Section', 'philosophy'),
		'id'            => 'before-footer-right',
		'description'   => __('Widgets in this area will be shown on Before Footer Area for all pages.', 'philosophy'),
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	));
	register_sidebar(array(
		'name'          => __('Footer Right', 'philosophy'),
		'id'            => 'footer-right',
		'description'   => __('Widgets in this area will be shown on Footer right area.', 'philosophy'),
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	));
	register_sidebar(array(
		'name'          => __('Footer Bottom', 'philosophy'),
		'id'            => 'footer-bottom',
		'description'   => __('Widgets in this area will be shown on Footer bottom area.', 'philosophy'),
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h4>',
		'after_title'   => '</h4>',
	));

	register_sidebar(array(
		'name'          => __('Header Section', 'philosophy'),
		'id'            => 'header-social',
		'description'   => __('Widgets in this area will be shown on Header.', 'philosophy'),
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	));
}
add_action("widgets_init", "philosophy_about_widgets");

function philosophy_search_form($form)
{
	$homedir = home_url("/");
	$lable = __("Search for:", "philosophy");
	$input_placeholder = __("Type Keywords", "philosophy");
	$button_label = __("Search", "philosophy");
	$newform = <<<FORM
	<form role="search" method="get" class="header__search-form" action="{$homedir}">
		<label>
			<span class="hide-content">{$lable}</span>
			<input type="search" class="search-field" placeholder="{$input_placeholder}" value="" name="s" title="{$lable}" autocomplete="off">
		</label>
		<input type="submit" class="search-submit" value="{$button_label}">
	</form>
FORM;
	return $newform;
}
add_filter("get_search_form", "philosophy_search_form");
