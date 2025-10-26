<?php
/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */

// This theme requires WordPress 5.3 or later.
if ( version_compare( $GLOBALS['wp_version'], '5.3', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'twenty_twenty_one_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @since Twenty Twenty-One 1.0
	 *
	 * @return void
	 */
	function twenty_twenty_one_setup() {

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * This theme does not use a hard-coded <title> tag in the document head,
		 * WordPress will provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/**
		 * Add post-formats support.
		 */
		add_theme_support(
			'post-formats',
			array(
				'link',
				'aside',
				'gallery',
				'image',
				'quote',
				'status',
				'video',
				'audio',
				'chat',
			)
		);

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 1568, 9999 );

		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary menu', 'twentytwentyone' ),
				'footer'  => esc_html__( 'Secondary menu', 'twentytwentyone' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
				'navigation-widgets',
			)
		);

		/*
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		$logo_width  = 300;
		$logo_height = 100;

		add_theme_support(
			'custom-logo',
			array(
				'height'               => $logo_height,
				'width'                => $logo_width,
				'flex-width'           => true,
				'flex-height'          => true,
				'unlink-homepage-logo' => true,
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );
		$background_color = get_theme_mod( 'background_color', 'D1E4DD' );
		if ( 127 > Twenty_Twenty_One_Custom_Colors::get_relative_luminance_from_hex( $background_color ) ) {
			add_theme_support( 'dark-editor-style' );
		}

		$editor_stylesheet_path = './assets/css/style-editor.css';

		// Note, the is_IE global variable is defined by WordPress and is used
		// to detect if the current browser is internet explorer.
		global $is_IE;
		if ( $is_IE ) {
			$editor_stylesheet_path = './assets/css/ie-editor.css';
		}

		// Enqueue editor styles.
		add_editor_style( $editor_stylesheet_path );

		// Add custom editor font sizes.
		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name'      => esc_html__( 'Extra small', 'twentytwentyone' ),
					'shortName' => esc_html_x( 'XS', 'Font size', 'twentytwentyone' ),
					'size'      => 16,
					'slug'      => 'extra-small',
				),
				array(
					'name'      => esc_html__( 'Small', 'twentytwentyone' ),
					'shortName' => esc_html_x( 'S', 'Font size', 'twentytwentyone' ),
					'size'      => 18,
					'slug'      => 'small',
				),
				array(
					'name'      => esc_html__( 'Normal', 'twentytwentyone' ),
					'shortName' => esc_html_x( 'M', 'Font size', 'twentytwentyone' ),
					'size'      => 20,
					'slug'      => 'normal',
				),
				array(
					'name'      => esc_html__( 'Large', 'twentytwentyone' ),
					'shortName' => esc_html_x( 'L', 'Font size', 'twentytwentyone' ),
					'size'      => 24,
					'slug'      => 'large',
				),
				array(
					'name'      => esc_html__( 'Extra large', 'twentytwentyone' ),
					'shortName' => esc_html_x( 'XL', 'Font size', 'twentytwentyone' ),
					'size'      => 40,
					'slug'      => 'extra-large',
				),
				array(
					'name'      => esc_html__( 'Huge', 'twentytwentyone' ),
					'shortName' => esc_html_x( 'XXL', 'Font size', 'twentytwentyone' ),
					'size'      => 96,
					'slug'      => 'huge',
				),
				array(
					'name'      => esc_html__( 'Gigantic', 'twentytwentyone' ),
					'shortName' => esc_html_x( 'XXXL', 'Font size', 'twentytwentyone' ),
					'size'      => 144,
					'slug'      => 'gigantic',
				),
			)
		);

		// Custom background color.
		add_theme_support(
			'custom-background',
			array(
				'default-color' => 'd1e4dd',
			)
		);

		// Editor color palette.
		$black     = '#000000';
		$dark_gray = '#28303D';
		$gray      = '#39414D';
		$green     = '#D1E4DD';
		$blue      = '#D1DFE4';
		$purple    = '#D1D1E4';
		$red       = '#E4D1D1';
		$orange    = '#E4DAD1';
		$yellow    = '#EEEADD';
		$white     = '#FFFFFF';

		add_theme_support(
			'editor-color-palette',
			array(
				array(
					'name'  => esc_html__( 'Black', 'twentytwentyone' ),
					'slug'  => 'black',
					'color' => $black,
				),
				array(
					'name'  => esc_html__( 'Dark gray', 'twentytwentyone' ),
					'slug'  => 'dark-gray',
					'color' => $dark_gray,
				),
				array(
					'name'  => esc_html__( 'Gray', 'twentytwentyone' ),
					'slug'  => 'gray',
					'color' => $gray,
				),
				array(
					'name'  => esc_html__( 'Green', 'twentytwentyone' ),
					'slug'  => 'green',
					'color' => $green,
				),
				array(
					'name'  => esc_html__( 'Blue', 'twentytwentyone' ),
					'slug'  => 'blue',
					'color' => $blue,
				),
				array(
					'name'  => esc_html__( 'Purple', 'twentytwentyone' ),
					'slug'  => 'purple',
					'color' => $purple,
				),
				array(
					'name'  => esc_html__( 'Red', 'twentytwentyone' ),
					'slug'  => 'red',
					'color' => $red,
				),
				array(
					'name'  => esc_html__( 'Orange', 'twentytwentyone' ),
					'slug'  => 'orange',
					'color' => $orange,
				),
				array(
					'name'  => esc_html__( 'Yellow', 'twentytwentyone' ),
					'slug'  => 'yellow',
					'color' => $yellow,
				),
				array(
					'name'  => esc_html__( 'White', 'twentytwentyone' ),
					'slug'  => 'white',
					'color' => $white,
				),
			)
		);

		add_theme_support(
			'editor-gradient-presets',
			array(
				array(
					'name'     => esc_html__( 'Purple to yellow', 'twentytwentyone' ),
					'gradient' => 'linear-gradient(160deg, ' . $purple . ' 0%, ' . $yellow . ' 100%)',
					'slug'     => 'purple-to-yellow',
				),
				array(
					'name'     => esc_html__( 'Yellow to purple', 'twentytwentyone' ),
					'gradient' => 'linear-gradient(160deg, ' . $yellow . ' 0%, ' . $purple . ' 100%)',
					'slug'     => 'yellow-to-purple',
				),
				array(
					'name'     => esc_html__( 'Green to yellow', 'twentytwentyone' ),
					'gradient' => 'linear-gradient(160deg, ' . $green . ' 0%, ' . $yellow . ' 100%)',
					'slug'     => 'green-to-yellow',
				),
				array(
					'name'     => esc_html__( 'Yellow to green', 'twentytwentyone' ),
					'gradient' => 'linear-gradient(160deg, ' . $yellow . ' 0%, ' . $green . ' 100%)',
					'slug'     => 'yellow-to-green',
				),
				array(
					'name'     => esc_html__( 'Red to yellow', 'twentytwentyone' ),
					'gradient' => 'linear-gradient(160deg, ' . $red . ' 0%, ' . $yellow . ' 100%)',
					'slug'     => 'red-to-yellow',
				),
				array(
					'name'     => esc_html__( 'Yellow to red', 'twentytwentyone' ),
					'gradient' => 'linear-gradient(160deg, ' . $yellow . ' 0%, ' . $red . ' 100%)',
					'slug'     => 'yellow-to-red',
				),
				array(
					'name'     => esc_html__( 'Purple to red', 'twentytwentyone' ),
					'gradient' => 'linear-gradient(160deg, ' . $purple . ' 0%, ' . $red . ' 100%)',
					'slug'     => 'purple-to-red',
				),
				array(
					'name'     => esc_html__( 'Red to purple', 'twentytwentyone' ),
					'gradient' => 'linear-gradient(160deg, ' . $red . ' 0%, ' . $purple . ' 100%)',
					'slug'     => 'red-to-purple',
				),
			)
		);

		/*
		* Adds starter content to highlight the theme on fresh sites.
		* This is done conditionally to avoid loading the starter content on every
		* page load, as it is a one-off operation only needed once in the customizer.
		*/
		if ( is_customize_preview() ) {
			require get_template_directory() . '/inc/starter-content.php';
			add_theme_support( 'starter-content', twenty_twenty_one_get_starter_content() );
		}

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );

		// Add support for custom line height controls.
		add_theme_support( 'custom-line-height' );

		// Add support for link color control.
		add_theme_support( 'link-color' );

		// Add support for experimental cover block spacing.
		add_theme_support( 'custom-spacing' );

		// Add support for custom units.
		// This was removed in WordPress 5.6 but is still required to properly support WP 5.5.
		add_theme_support( 'custom-units' );

		// Remove feed icon link from legacy RSS widget.
		add_filter( 'rss_widget_feed_link', '__return_empty_string' );
	}
}
add_action( 'after_setup_theme', 'twenty_twenty_one_setup' );

/**
 * Registers widget area.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 *
 * @return void
 */
function twenty_twenty_one_widgets_init() {

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer', 'twentytwentyone' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here to appear in your footer.', 'twentytwentyone' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'twenty_twenty_one_widgets_init' );

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @global int $content_width Content width.
 *
 * @return void
 */
function twenty_twenty_one_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'twenty_twenty_one_content_width', 750 );
}
add_action( 'after_setup_theme', 'twenty_twenty_one_content_width', 0 );

/**
 * Enqueues scripts and styles.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @global bool       $is_IE
 * @global WP_Scripts $wp_scripts
 *
 * @return void
 */
function twenty_twenty_one_scripts() {
	// Note, the is_IE global variable is defined by WordPress and is used
	// to detect if the current browser is internet explorer.
	global $is_IE, $wp_scripts;
	if ( $is_IE ) {
		// If IE 11 or below, use a flattened stylesheet with static values replacing CSS Variables.
		wp_enqueue_style( 'twenty-twenty-one-style', get_template_directory_uri() . '/assets/css/ie.css', array(), wp_get_theme()->get( 'Version' ) );
	} else {
		// If not IE, use the standard stylesheet.
		wp_enqueue_style( 'twenty-twenty-one-style', get_template_directory_uri() . '/style.css', array(), wp_get_theme()->get( 'Version' ) );
	}

	// RTL styles.
	wp_style_add_data( 'twenty-twenty-one-style', 'rtl', 'replace' );

	// Print styles.
	wp_enqueue_style( 'twenty-twenty-one-print-style', get_template_directory_uri() . '/assets/css/print.css', array(), wp_get_theme()->get( 'Version' ), 'print' );

	// Threaded comment reply styles.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Register the IE11 polyfill file.
	wp_register_script(
		'twenty-twenty-one-ie11-polyfills-asset',
		get_template_directory_uri() . '/assets/js/polyfills.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		array( 'in_footer' => true )
	);

	// Register the IE11 polyfill loader.
	wp_register_script(
		'twenty-twenty-one-ie11-polyfills',
		null,
		array(),
		wp_get_theme()->get( 'Version' ),
		array( 'in_footer' => true )
	);
	wp_add_inline_script(
		'twenty-twenty-one-ie11-polyfills',
		wp_get_script_polyfill(
			$wp_scripts,
			array(
				'Element.prototype.matches && Element.prototype.closest && window.NodeList && NodeList.prototype.forEach' => 'twenty-twenty-one-ie11-polyfills-asset',
			)
		)
	);

	// Main navigation scripts.
	if ( has_nav_menu( 'primary' ) ) {
		wp_enqueue_script(
			'twenty-twenty-one-primary-navigation-script',
			get_template_directory_uri() . '/assets/js/primary-navigation.js',
			array( 'twenty-twenty-one-ie11-polyfills' ),
			wp_get_theme()->get( 'Version' ),
			array(
				'in_footer' => false, // Because involves header.
				'strategy'  => 'defer',
			)
		);
	}

	// Responsive embeds script.
	wp_enqueue_script(
		'twenty-twenty-one-responsive-embeds-script',
		get_template_directory_uri() . '/assets/js/responsive-embeds.js',
		array( 'twenty-twenty-one-ie11-polyfills' ),
		wp_get_theme()->get( 'Version' ),
		array( 'in_footer' => true )
	);
}
add_action( 'wp_enqueue_scripts', 'twenty_twenty_one_scripts' );

/**
 * Enqueues block editor script.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @return void
 */
function twentytwentyone_block_editor_script() {

	wp_enqueue_script( 'twentytwentyone-editor', get_theme_file_uri( '/assets/js/editor.js' ), array( 'wp-blocks', 'wp-dom' ), wp_get_theme()->get( 'Version' ), array( 'in_footer' => true ) );
}

add_action( 'enqueue_block_editor_assets', 'twentytwentyone_block_editor_script' );

/**
 * Fixes skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @since Twenty Twenty-One 1.0
 * @deprecated Twenty Twenty-One 1.9 Removed from wp_print_footer_scripts action.
 *
 * @link https://git.io/vWdr2
 */
function twenty_twenty_one_skip_link_focus_fix() {

	// If SCRIPT_DEBUG is defined and true, print the unminified file.
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
		echo '<script>';
		include get_template_directory() . '/assets/js/skip-link-focus-fix.js';
		echo '</script>';
	} else {
		// The following is minified via `npx terser --compress --mangle -- assets/js/skip-link-focus-fix.js`.
		?>
		<script>
		/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",(function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())}),!1);
		</script>
		<?php
	}
}

/**
 * Enqueues non-latin language styles.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @return void
 */
function twenty_twenty_one_non_latin_languages() {
	$custom_css = twenty_twenty_one_get_non_latin_css( 'front-end' );

	if ( $custom_css ) {
		wp_add_inline_style( 'twenty-twenty-one-style', $custom_css );
	}
}
add_action( 'wp_enqueue_scripts', 'twenty_twenty_one_non_latin_languages' );

// SVG Icons class.
require get_template_directory() . '/classes/class-twenty-twenty-one-svg-icons.php';

// Custom color classes.
require get_template_directory() . '/classes/class-twenty-twenty-one-custom-colors.php';
new Twenty_Twenty_One_Custom_Colors();

// Enhance the theme by hooking into WordPress.
require get_template_directory() . '/inc/template-functions.php';

// Menu functions and filters.
require get_template_directory() . '/inc/menu-functions.php';

// Custom template tags for the theme.
require get_template_directory() . '/inc/template-tags.php';

// Customizer additions.
require get_template_directory() . '/classes/class-twenty-twenty-one-customize.php';
new Twenty_Twenty_One_Customize();

// Block Patterns.
require get_template_directory() . '/inc/block-patterns.php';

// Block Styles.
require get_template_directory() . '/inc/block-styles.php';

// Dark Mode.
require_once get_template_directory() . '/classes/class-twenty-twenty-one-dark-mode.php';
new Twenty_Twenty_One_Dark_Mode();

/**
 * Enqueues scripts for the customizer preview.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @return void
 */
function twentytwentyone_customize_preview_init() {
	wp_enqueue_script(
		'twentytwentyone-customize-helpers',
		get_theme_file_uri( '/assets/js/customize-helpers.js' ),
		array(),
		wp_get_theme()->get( 'Version' ),
		array( 'in_footer' => true )
	);

	wp_enqueue_script(
		'twentytwentyone-customize-preview',
		get_theme_file_uri( '/assets/js/customize-preview.js' ),
		array( 'customize-preview', 'customize-selective-refresh', 'jquery', 'twentytwentyone-customize-helpers' ),
		wp_get_theme()->get( 'Version' ),
		array( 'in_footer' => true )
	);
}
add_action( 'customize_preview_init', 'twentytwentyone_customize_preview_init' );

/**
 * Enqueues scripts for the customizer.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @return void
 */
function twentytwentyone_customize_controls_enqueue_scripts() {

	wp_enqueue_script(
		'twentytwentyone-customize-helpers',
		get_theme_file_uri( '/assets/js/customize-helpers.js' ),
		array(),
		wp_get_theme()->get( 'Version' ),
		array( 'in_footer' => true )
	);
}
add_action( 'customize_controls_enqueue_scripts', 'twentytwentyone_customize_controls_enqueue_scripts' );

/**
 * Calculates classes for the main <html> element.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @return void
 */
function twentytwentyone_the_html_classes() {
	/**
	 * Filters the classes for the main <html> element.
	 *
	 * @since Twenty Twenty-One 1.0
	 *
	 * @param string The list of classes. Default empty string.
	 */
	$classes = apply_filters( 'twentytwentyone_html_classes', '' );
	if ( ! $classes ) {
		return;
	}
	echo 'class="' . esc_attr( $classes ) . '"';
}

/**
 * Adds "is-IE" class to body if the user is on Internet Explorer.
 *
 * @since Twenty Twenty-One 1.0
 *
 * @return void
 */
function twentytwentyone_add_ie_class() {
	?>
	<script>
	if ( -1 !== navigator.userAgent.indexOf( 'MSIE' ) || -1 !== navigator.appVersion.indexOf( 'Trident/' ) ) {
		document.body.classList.add( 'is-IE' );
	}
	</script>
	<?php
}
add_action( 'wp_footer', 'twentytwentyone_add_ie_class' );

function enqueue_media_uploader_assets() {
    if (!is_admin()) {
        wp_enqueue_media(); // メディアアップローダーのスクリプトを読み込む
    }
}
add_action('wp_enqueue_scripts', 'enqueue_media_uploader_assets');

/*
function custom_media_uploader_script() {
    // メディアアップローダーのスクリプトをフロントエンドで使うために読み込む
    if (is_admin()) return; // 管理画面ではなくフロントエンド用の処理

    wp_enqueue_media(); // メディアアップローダーを読み込む

    // JavaScriptのインラインコード
    ?>
    <script>
    jQuery(document).ready(function($) {
        var mediaUploader;

        $('#upload_image_button').on('click', function(e) {

				// クリックされた要素を取得
				var clickedElement = $(this);
				var className = clickedElement.attr('class');
				
				e.preventDefault();

				if (mediaUploader) {
					mediaUploader.open();
					return;
				}

				mediaUploader = wp.media.frames.file_frame = wp.media({
					title: '画像を選択',
					button: {
						text: 'この画像を使用'
					},
					multiple: false
				});

				mediaUploader.on('select', function() {
					var attachment = mediaUploader.state().get('selection').first().toJSON();


					if(className == "e-mark form-btn"){
						// 施術結果

						$('#acf_e-mark_image_field').val(attachment.id);
						$('#uploaded_e-mark_image_preview').attr('src', attachment.url).show();

						// 即時保存
    					const treatment_result_form = document.getElementById('treatment_result_form');
						treatment_result_form.submit();
					}else{
					// Eマーク送付画像
						$('#acf_image_field').val(attachment.id);
						$('#uploaded_image_preview').attr('src', attachment.url).show();
					}
				});

				mediaUploader.open();
        });


		

        $('#treatment_result_image').on('click', function(e) {

				// クリックされた要素を取得
				var clickedElement = $(this);
				var className = clickedElement.attr('class');
				
				e.preventDefault();

				if (mediaUploader) {
					mediaUploader.open();
					return;
				}

				mediaUploader = wp.media.frames.file_frame = wp.media({
					title: '画像を選択',
					button: {
						text: 'この画像を使用'
					},
					multiple: false
				});

				mediaUploader.on('select', function() {
					var attachment = mediaUploader.state().get('selection').first().toJSON();


					if(className == "treatment_result_image form-btn"){
						// 施術提出

						$('#acf_e-acf_treatment_result_image_field').val(attachment.id);
						$('#uploaded_treatment_result_image_preview').attr('src', attachment.url).show();

						// 即時保存
    					const treatment_result_form = document.getElementById('acf_treatment_result_image_field');
						treatment_result_form.submit();
					}
				});

				mediaUploader.open();
        });


		for($i=1;$i<=10;$i++){

			 $('#upload_remote_image_button' + $i).on('click', function(e) {

					// クリックされた要素を取得
					var clickedElement = $(this);
					var className = clickedElement.attr('class');
				
					e.preventDefault();

					if (mediaUploader) {
						mediaUploader.open();
						return;
					}

					mediaUploader = wp.media.frames.file_frame = wp.media({
						title: '画像を選択',
						button: {
							text: 'この画像を使用'
						},
						multiple: false
					});

					mediaUploader.on('select', function() {
						var attachment = mediaUploader.state().get('selection').first().toJSON();

						alert("aaaaaa");

						//if(className == "e-mark form-btn"){
							// 施術結果

						//	$('#remote_image_field' + $i).val(attachment.id);
						//	$('#uploaded_remote_image_preview' + $i).attr('src', attachment.url).show();

							// 即時保存
    						//const treatment_result_form = document.getElementById('remote_image_field'+ $i);
							//treatment_result_form.submit();

						//}
					});

					mediaUploader.open();
			});

		}

    });

    </script>
    <?php
}
*/

function custom_media_uploader_script() {
    // メディアアップローダーのスクリプトをフロントエンドで使うために読み込む
    if (is_admin()) return; // 管理画面ではなくフロントエンド用の処理
?>
    <script>
	jQuery(document).ready(function($) {
		// イベントごとにローカルスコープでメディアアップローダーを作成
		// $('#upload_image_button, #treatment_result_image').on('click', function(e) {
		$('#upload_image_button').on('click', function(e) {
			e.preventDefault();

			const clickedElement = $(this);
			const className = clickedElement.attr('class');

			let mediaUploader = wp.media({
				title: '画像を選択',
				button: {
					text: 'この画像を使用'
				},
				multiple: false
			});

			mediaUploader.on('select', function() {
				const attachment = mediaUploader.state().get('selection').first().toJSON();

				if(className === "e-mark form-btn") {
					$('#acf_e-mark_image_field').val(attachment.id);
					$('#uploaded_e-mark_image_preview').attr('src', attachment.url).show();
					$('#treatment_result_form').submit();
				// } else if (className === "treatment_result_image form-btn") {
				// 	// 提出画像
				// 	$('#acf_e-acf_treatment_result_image_field').val(attachment.id);
				// 	$('#uploaded_treatment_result_image_preview').attr('src', attachment.url).show();
				// 	$('#treatment_result_form').submit();
				// } else {
				// 	$('#acf_image_field').val(attachment.id);
				// 	$('#uploaded_image_preview').attr('src', attachment.url).show();
				}
			});

			mediaUploader.open();
		});
		$('#treatment_result_image').on('click', function(e) {
			e.preventDefault();

			const clickedElement = $(this);
			const className = clickedElement.attr('class');

			let mediaUploader = wp.media({
				title: '画像を選択',
				button: {
					text: 'この画像を使用'
				},
				multiple: false
			});

			mediaUploader.on('select', function() {
				const attachment = mediaUploader.state().get('selection').first().toJSON();

				// if (className === "treatment_result_image form-btn") {
					// 提出画像
					$('#acf_e-acf_treatment_result_image_field').val(attachment.id);
					$('#uploaded_treatment_result_image_preview').attr('src', attachment.url).show();
					$('#acf_treatment_result_image_field').submit();
				// }
			});

			mediaUploader.open();
		});

    for(let i=1; i<=25; i++) {
        $(`#upload_remote_image_button${i}`).on('click', function(e) {
            e.preventDefault();

            const clickedElement = $(this);
            const className = clickedElement.attr('class');

            let mediaUploader = wp.media({
                title: '画像を選択',
                button: {
                    text: 'この画像を使用'
                },
                multiple: false
            });

            mediaUploader.on('select', function() {
                const attachment = mediaUploader.state().get('selection').first().toJSON();
                $(`#remote_image_field${i}`).val(attachment.url);
                $(`#uploaded_remote_image_preview${i}`).attr('src', attachment.url).show();
            });

            mediaUploader.open();
        });
    }
});

</script>
    <?php
}
add_action('wp_footer', 'custom_media_uploader_script');


add_action('wp_login', function($user_login, $user) {
    // リダイレクトURLを指定
    $redirect_url = home_url('/admin-menu'); // 指定のURLに変更

    // リダイレクトを実行
    wp_redirect($redirect_url);
    exit;
}, 10, 2);


if ( ! function_exists( 'wp_get_list_item_separator' ) ) :
	/**
	 * Retrieves the list item separator based on the locale.
	 *
	 * Added for backward compatibility to support pre-6.0.0 WordPress versions.
	 *
	 * @since 6.0.0
	 */
	function wp_get_list_item_separator() {
		/* translators: Used between list items, there is a space after the comma. */
		return __( ', ', 'twentytwentyone' );
	}
endif;

add_action('admin_post_upload_files', 'handle_file_upload');
add_action('admin_post_nopriv_upload_files', 'handle_file_upload');

function handle_file_upload() {
    if (!isset($_FILES['uploaded_files']) || empty($_FILES['uploaded_files']['name'][0])) {
        error_log("アップロードエラー: ファイルが選択されていません。");
        wp_redirect(home_url('/upload_error'));
        exit;
    }

    $uploaded_files = $_FILES['uploaded_files'];
    $upload_dir = wp_upload_dir();
    $uploaded_urls = [];
    $uploaded_file_names = []; // メール送信用のファイル名リスト

	$custom_text = sanitize_text_field($_POST['img_register_code']);
    $custom_name = sanitize_text_field($_POST['img_register_name']);

    foreach ($uploaded_files['name'] as $index => $filename) {
        if ($uploaded_files['error'][$index] !== UPLOAD_ERR_OK) {
            error_log("アップロードエラー ($filename): " . $uploaded_files['error'][$index]);
            continue;
        }

        $tmp_name = $uploaded_files['tmp_name'][$index];
        $original_name = pathinfo($filename, PATHINFO_FILENAME);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
       

        $new_name = remove_accents(
            sanitize_file_name($custom_text . "-" . $custom_name . "-" . $original_name . '-' . date('Ymd-His') . '.' . $extension)
        );
        $new_path = $upload_dir['path'] . '/' . $new_name;

        // ファイルの移動
        if (!move_uploaded_file($tmp_name, $new_path)) {
            error_log("アップロードエラー: ファイルの移動に失敗しました ($tmp_name -> $new_path)");
            continue;
        }

        // アップロード成功
        $uploaded_urls[] = $upload_dir['url'] . '/' . $new_name;
        $uploaded_file_names[] = $new_name; // メール送信用に保存

        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attachment = [
            'guid'           => $upload_dir['url'] . '/' . $new_name,
            'post_mime_type' => $uploaded_files['type'][$index],
            'post_title'     => $original_name,
            'post_content'   => '',
            'post_status'    => 'inherit',
        ];

        $attach_id = wp_insert_attachment($attachment, $new_path);
        if (!$attach_id) {
            error_log("アップロードエラー: メディア登録に失敗しました ($new_path)");
            continue;
        }

        // メタデータ生成と更新
        $attach_data = wp_generate_attachment_metadata($attach_id, $new_path);
        if (!$attach_data) {
            error_log("アップロードエラー: メタデータ生成に失敗しました ($new_path)");
        } elseif (!wp_update_attachment_metadata($attach_id, $attach_data)) {
            error_log("アップロードエラー: メタデータの更新に失敗しました ($new_path)");
        }
    }

    // メール送信
    if (!empty($uploaded_file_names)) {
        $to = 'info@a-gate-kanri.com'; // メール送信先
        $subject = $custom_name . '様のアップロードされた画像のファイル名一覧';
        $body = "以下の画像がアップロードされました:\n\n" . implode("\n", $uploaded_file_names);
		$body .= "承認code \n\n" .$custom_text;
		
        $headers = ['Content-Type: text/plain; charset=UTF-8'];

        if (!wp_mail($to, $subject, $body, $headers)) {
            error_log("メール送信に失敗しました。");
        }
    }

    // アップロードの成否に応じてリダイレクト
    if (!empty($uploaded_urls)) {
        wp_redirect(home_url('/img_thanks'));
        exit;
    } else {
        error_log("アップロードエラー: すべてのファイルのアップロードに失敗しました。");
        wp_redirect(home_url('/upload_error'));
        exit;
    }
}

add_action('wp_ajax_handle_password_change', 'handle_password_change');
add_action('wp_ajax_nopriv_handle_password_change', 'handle_password_change');

function handle_password_change() {
    // POST データを取得
    $target_user_id = isset($_POST['target_user_id']) ? intval($_POST['target_user_id']) : 0;
    $current_password = isset($_POST['current_password']) ? trim($_POST['current_password']) : '';
    $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

    // 未入力チェック（管理者の場合は既存パスワード不要）
    if (!current_user_can('administrator') && empty($current_password)) {
        wp_send_json(array('status' => 'error', 'message' => '現在のパスワードを入力してください。'));
    }
    if (empty($new_password) || empty($confirm_password)) {
        wp_send_json(array('status' => 'error', 'message' => '新しいパスワードを入力してください。'));
    }

    // ユーザーが指定されていない場合は自分のアカウントを対象にする
    $user_id = $target_user_id ?: get_current_user_id();

    if (!$user_id) {
        wp_send_json(array('status' => 'error', 'message' => 'ログインしている必要があります。'));
    }

    // 対象のユーザー情報を取得
    $user = get_user_by('ID', $user_id);
    if (!$user) {
        wp_send_json(array('status' => 'error', 'message' => '指定されたユーザーが見つかりません。'));
    }

    // パスワード一致の確認
    if ($new_password !== $confirm_password) {
        wp_send_json(array('status' => 'error', 'message' => '新しいパスワードが一致していません。'));
    }

    // **管理者の場合は既存パスワードの検証をスキップ**
    if (!current_user_can('administrator') && !wp_check_password($current_password, $user->user_pass, $user->ID)) {
        wp_send_json(array('status' => 'error', 'message' => '指定したユーザーの現在のパスワードが正しくありません。'));
    }

    // パスワードを更新
    wp_set_password($new_password, $user_id);

    // パスワードを変更されたユーザーが現在ログイン中ならログアウト
    if ($target_user_id == 0 || $target_user_id == get_current_user_id()) {
       // wp_logout(); // 自分自身のパスワードを変更した場合のみログアウト
        wp_send_json(array('status' => 'success', 'message' => 'パスワードが正常に変更されました。'));
    } else {
        wp_send_json(array('status' => 'success', 'message' => '指定したユーザーのパスワードが変更されました。'));
    }
}

add_action('wp_ajax_upload_user_image', 'upload_user_image');

function upload_user_image() {
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'ログインしてください。']);
    }

    $target_user_id = isset($_POST['target_user_id']) ? intval($_POST['target_user_id']) : get_current_user_id();

    // ユーザーごとにディレクトリ作成
    $upload_dir = wp_upload_dir();
    $user_folder = $upload_dir['basedir'] . '/user_images/' . $target_user_id;

    if (!file_exists($user_folder)) {
        mkdir($user_folder, 0755, true);
    }

    if (!empty($_FILES['user_image']['name'])) {
        $file = $_FILES['user_image'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($file['type'], $allowed_types)) {
            wp_send_json_error(['message' => 'jpg、png、gif形式のみ許可されています。']);
        }

        $file_name = $target_user_id . '-' . time() . '-' . basename($file['name']);
        $file_path = $user_folder . '/' . $file_name;

        if (move_uploaded_file($file['tmp_name'], $file_path)) {
            $file_url = content_url('uploads/user_images/' . $target_user_id . '/' . $file_name);
            update_user_meta($target_user_id, 'uploaded_image', $file_url);

            wp_send_json_success(['url' => $file_url, 'message' => '画像が保存されました。']);
        } else {
            wp_send_json_error(['message' => 'ファイルの保存に失敗しました。']);
        }
    } else {
        wp_send_json_error(['message' => '画像が選択されていません。']);
    }
}


add_action('wp_ajax_delete_user_image', 'delete_user_image');

function delete_user_image() {
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'ログインしてください。']);
    }

    $target_user_id = isset($_POST['target_user_id']) ? intval($_POST['target_user_id']) : get_current_user_id();

    if (!current_user_can('manage_options') && get_current_user_id() !== $target_user_id) {
        wp_send_json_error(['message' => '指定されたユーザーの画像を削除する権限がありません。']);
    }

    $image_url = get_user_meta($target_user_id, 'uploaded_image', true);

    if ($image_url) {
        $file_path = str_replace(content_url(), ABSPATH, $image_url);
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        delete_user_meta($target_user_id, 'uploaded_image');
        wp_send_json_success(['message' => '画像が削除されました。']);
    } else {
        wp_send_json_error(['message' => '削除する画像が見つかりません。']);
    }
}

function restrict_image_access($user_id) {
    if (current_user_can('manage_options')) {
        return true;  // 管理者は常にアクセス可能
    }

    if (get_current_user_id() !== $user_id) {
        wp_die('アクセス権がありません。');
    }
}
add_action('wp_ajax_get_user_image', 'get_user_image');

function get_user_image() {
    if (!is_user_logged_in()) {
        wp_send_json_error(['message' => 'ログインしてください。']);
    }

    $target_user_id = isset($_GET['target_user_id']) ? intval($_GET['target_user_id']) : get_current_user_id();

    $image_url = get_user_meta($target_user_id, 'uploaded_image', true);

    if ($image_url) {
        wp_send_json_success(['url' => $image_url]);
    } else {
        wp_send_json_error(['message' => '画像が見つかりません。']);
    }
}

function redirect_non_admin_users() {
    if (is_admin() && !defined('DOING_AJAX') && !current_user_can('administrator')) {
        wp_redirect(home_url());
        exit;
    }
}
add_action('admin_init', 'redirect_non_admin_users');

function hide_admin_bar_for_non_admin() {
    if (!current_user_can('administrator')) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'hide_admin_bar_for_non_admin');
add_action('template_redirect', function() {
    // ログイン済みならスキップ
    if (is_user_logged_in()) {
        return;
    }
    
    // 除外するページ・パス
    $excluded_paths = [
        'wp-login.php',
        'wp-admin',
        '/login-members/',
        'wp-json', // REST API用
        '/privacy-policy',
		'/terms-of-service',
		'/company-profile'
    ];
    
    $current_uri = $_SERVER['REQUEST_URI'] ?? '';
    
    // 除外パスをチェック
    foreach ($excluded_paths as $path) {
        if (strpos($current_uri, $path) !== false) {
            return;
        }
    }
    
    // ログインページかどうかもチェック
    if (is_page('login-members')) {
        return;
    }
    
    // 上記に該当しない場合はリダイレクト
    wp_redirect(home_url('/login-members/'));
    exit;
});
// ログイン画面のプレースホルダーを追加
function custom_login_placeholders() {
	?>
	<script type="text/javascript">
	  document.addEventListener('DOMContentLoaded', function() {
		var user = document.getElementById('user_login');
		var pass = document.getElementById('user_pass');
		if (user) user.placeholder = 'メールアドレスまたはユーザー名';
		if (pass) pass.placeholder = 'パスワード';
	  });
	</script>
	<?php
  }
  add_action('login_footer', 'custom_login_placeholders');
  // カート関数をインクルード
require_once get_template_directory() . '/inc/shopping-cart-functions.php';

// JavaScript/CSSを読み込み
function enqueue_cart_scripts() {
    // jQueryを確実に読み込む
    wp_enqueue_script('jquery');
    
    // cart.jsを読み込む（依存関係を明示的に指定）
    wp_enqueue_script(
        'cart-script', 
        get_template_directory_uri() . '/assets/js/cart.js', 
        array('jquery'), 
        '1.0', 
        false // フッターではなくヘッダーに読み込み
    );
    
    // CSSを読み込む
    wp_enqueue_style('cart-style', get_template_directory_uri() . '/assets/css/cart.css', array(), '1.0');
    
    // デバッグ用：スクリプトの読み込み状況をログに出力
    error_log('Cart scripts enqueued - cart.js path: ' . get_template_directory_uri() . '/assets/js/cart.js');
}
add_action('wp_enqueue_scripts', 'enqueue_cart_scripts', 1); // 優先度を1に設定して最優先で読み込み
// functions.phpに追加するコード

/**
 * 購読者のアクセスを/users/以下に制限する
 * /users/以外にアクセスした場合は/users/user_top/にリダイレクト
 */
function restrict_subscriber_access() {
    // ログインしていない場合はスキップ
    if (!is_user_logged_in()) {
        return;
    }
    
    // 現在のユーザー情報を取得
    $current_user = wp_get_current_user();
    
    // 購読者（subscriber）以外はスキップ
    if (!in_array('subscriber', $current_user->roles)) {
        return;
    }
    
    // 現在のURLパスを取得
    $current_uri = $_SERVER['REQUEST_URI'] ?? '';
    $parsed_url = parse_url($current_uri);
    $current_path = $parsed_url['path'] ?? '';
    
    // 除外するパス（WordPress管理系、AJAX、REST API等）
    $excluded_paths = [
        '/wp-admin/',
        '/wp-json/',
        '/wp-login.php',
        '/wp-cron.php',
		'/privacy-policy',
		'/terms-of-service',
		'/company-profile'
    ];
    
    // 除外パスをチェック
    foreach ($excluded_paths as $excluded_path) {
        if (strpos($current_path, $excluded_path) !== false) {
            return;
        }
    }
    
    // AJAXリクエストの場合はスキップ
    if (defined('DOING_AJAX') && DOING_AJAX) {
        return;
    }
    
    // /users/以下にいるかチェック
    if (strpos($current_path, '/users/') === 0) {
        return; // /users/以下なのでOK
    }
    
    // /users/以外にアクセスしている場合はリダイレクト
    wp_redirect(home_url('/users/user_top/'));
    exit;
}
add_action('template_redirect', 'restrict_subscriber_access', 5);

/**
 * 購読者のログイン後リダイレクト先を設定
 */
function redirect_subscriber_after_login($redirect_to, $request, $user) {
    // ユーザーオブジェクトが有効でない場合はデフォルトのリダイレクトを使用
    if (!isset($user->roles) || is_wp_error($user)) {
        return $redirect_to;
    }
    
    // 購読者の場合のみリダイレクト先を変更
    if (in_array('subscriber', $user->roles)) {
        return home_url('/users/user_top/');
    }
    
    return $redirect_to;
}
add_filter('login_redirect', 'redirect_subscriber_after_login', 10, 3);

/**
 * 購読者がWordPress管理画面にアクセスしようとした場合のリダイレクト
 */
function redirect_subscriber_from_admin() {
    if (is_admin() && !defined('DOING_AJAX') && current_user_can('subscriber') && !current_user_can('manage_options')) {
        wp_redirect(home_url('/users/user_top/'));
        exit;
    }
}
add_action('admin_init', 'redirect_subscriber_from_admin', 1);

/**
 * 購読者の管理バーを非表示にする
 */
function hide_admin_bar_for_subscribers() {
    if (current_user_can('subscriber') && !current_user_can('manage_options')) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'hide_admin_bar_for_subscribers');

/**
 * デバッグ用：現在のパスとユーザー権限をログに記録（開発時のみ使用）
 */
function debug_user_access() {
    if (!is_user_logged_in()) {
        return;
    }
    
    // デバッグモードが有効な場合のみログ出力
    if (defined('WP_DEBUG') && WP_DEBUG) {
        $current_user = wp_get_current_user();
        $current_uri = $_SERVER['REQUEST_URI'] ?? '';
        
        error_log('User Access Debug - User: ' . $current_user->user_login . 
                 ', Roles: ' . implode(', ', $current_user->roles) . 
                 ', Path: ' . $current_uri);
    }
}
add_action('template_redirect', 'debug_user_access', 1);

// WP-Membersで配列型のユーザーメタフィールドを除外（方法1: フィールドリストから除外）
function exclude_array_meta_from_wpmembers( $fields ) {
    $exclude_fields = array(
        'spirit_data',          // 施術ID配列（JSON形式）
        'connect_group',        // 関連グループ番号（JSON形式）
        'connect_group_disp',   // 関連グループ名（JSON形式）
        'group_data',           // グループデータ（JSON形式）
    );
    
    foreach ( $exclude_fields as $field ) {
        if ( isset( $fields[ $field ] ) ) {
            unset( $fields[ $field ] );
        }
    }
    
    return $fields;
}
add_filter( 'wpmem_fields', 'exclude_array_meta_from_wpmembers', 100 );

// WP-Membersで配列型のユーザーメタフィールドを除外（方法2: 管理画面でのみ配列を空文字に変換）
function sanitize_array_user_meta_for_admin( $check, $object_id, $meta_key, $single ) {
    // 管理画面かつuser-edit.phpの場合のみ処理
    if ( ! is_admin() || ! isset( $_SERVER['SCRIPT_NAME'] ) || strpos( $_SERVER['SCRIPT_NAME'], 'user-edit.php' ) === false ) {
        return $check;
    }
    
    // 配列型のメタキーリスト
    $array_meta_keys = array(
        'spirit_data',
        'connect_group',
        'connect_group_disp',
        'group_data',
    );
    
    // 該当するメタキーの場合、空文字列を返す
    if ( in_array( $meta_key, $array_meta_keys ) ) {
        return ''; // 空文字列を返すことでhtmlspecialchars()のエラーを防ぐ
    }
    
    return $check;
}
add_filter( 'get_user_metadata', 'sanitize_array_user_meta_for_admin', 10, 4 );