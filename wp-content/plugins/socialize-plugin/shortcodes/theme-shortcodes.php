<?php if ( ! class_exists( 'GhostPool_Shortcodes' ) ) {

	class GhostPool_Shortcodes {

		public function __construct() {
			add_action( 'init', array( &$this, 'ghostpool_shortcodes' ) );
			add_action( 'vc_before_init', array( &$this, 'ghostpool_vc_functions' ), 9 );
		}

		public function ghostpool_vc_functions() {
			if ( function_exists( 'vc_set_as_theme' ) ) {
				vc_set_as_theme(); // Disable design options
				vc_set_shortcodes_templates_dir( dirname( __FILE__ ) ); // Set templates directory
				vc_set_default_editor_post_types( array( 'page', 'gp_portfolio_item', 'epx_vcsb', 'vc-element' ) ); // Check VC post type checkboxes by default
			}
		}

		public function ghostpool_shortcodes() {
	
			if ( function_exists( 'vc_set_as_theme' ) ) {


				/*--------------------------------------------------------------
				If plugin is activated without theme
				--------------------------------------------------------------*/
			
				// $GLOBALS keys
				if ( ! function_exists( 'aq_resize' ) ) {
					$GLOBALS['ghostpool_tax'] = '';
					$GLOBALS['ghostpool_orderby_value'] = '';
					$GLOBALS['ghostpool_order'] = '';
					$GLOBALS['ghostpool_meta_key'] = '';
					$GLOBALS['ghostpool_meta_query'] = '';
					$GLOBALS['ghostpool_date_posted_value'] = '';
					$GLOBALS['ghostpool_date_modified_value'] = '';
					$GLOBALS['ghostpool_paged'] = '';	
				}			
				
				// Functions
				if ( ! function_exists( 'ghostpool_option' ) ) {
					function ghostpool_option() {}
				}
				if ( ! function_exists( 'aq_resize' ) ) {
					function aq_resize() {}
				}
				if ( ! function_exists( 'ghostpool_category_variables' ) ) {
					function ghostpool_category_variables() {}
				}
				if ( ! function_exists( 'ghostpool_get_previous_posts_page_link' ) ) {
					function ghostpool_get_previous_posts_page_link() {}
				}
				if ( ! function_exists( 'ghostpool_get_next_posts_page_link' ) ) {
					function ghostpool_get_next_posts_page_link() {}
				}
				if ( ! function_exists( 'ghostpool_exclude_cats' ) ) {
					function ghostpool_exclude_cats() {}
				}
				if ( ! function_exists( 'ghostpool_excerpt' ) ) {
					function ghostpool_excerpt() {}
				}
				if ( ! function_exists( 'ghostpool_pagination' ) ) {
					function ghostpool_pagination() {}
				}
				
							
				/*--------------------------------------------------------------
				Custom icon selection field
				--------------------------------------------------------------*/
		
				function ghostpool_icon_selection_vc_option( $settings, $value ) {
					$output = '<div class="gp-icon-container">';
					foreach ( $settings['value'] as $val ) {		   
						$output .= '<a href="' . esc_attr( $val ) . '" class="gp-icon-link"><i class="fa fa-lg ' . esc_attr( $val ) . '"></i></a>';		
					}
					$output .= '</div><a href="#" class="gp-all-icons-button">' . esc_html__( 'Show All Icons', 'socialize-plugin' ) . '</a><input name="' . esc_attr( $settings['param_name'] ) . '" id="gp-icon-selection-value" class="wpb_vc_param_value ' . esc_attr( $settings['param_name'] ) . ' ' . esc_attr( $settings['type'] ) . '_field" type="hidden" value="' . esc_attr( $value ) . '" />';    
					return $output;
				}
				vc_add_shortcode_param( 'icon_selection', 'ghostpool_icon_selection_vc_option', plugins_url( 'assets/icon-selection.js', dirname( __FILE__ ) ) );		
		
		
				/*--------------------------------------------------------------
				Shortcode Options
				--------------------------------------------------------------*/
									
				function ghostpool_shortcode_options( $atts ) {

					$GLOBALS['ghostpool_cats'] = isset( $atts['cats'] ) ? $atts['cats'] : '';
					$GLOBALS['ghostpool_page_ids'] = isset( $atts['page_ids'] ) ? $atts['page_ids'] : '';
					$GLOBALS['ghostpool_post_types'] = isset( $atts['post_types'] ) ? $atts['post_types'] : 'post';
					if ( isset( $GLOBALS['ghostpool_shortcode'] ) && $GLOBALS['ghostpool_shortcode'] == 'portfolio' ) {
						$GLOBALS['ghostpool_format'] = isset( $atts['format'] ) ? $atts['format'] : 'gp-portfolio-columns-2';
					} else {
						$GLOBALS['ghostpool_format'] = isset( $atts['format'] ) ? $atts['format'] : 'gp-blog-standard';
					}
					$GLOBALS['ghostpool_orderby'] =  isset( $atts['orderby'] ) ? $atts['orderby'] : 'newest';
					$GLOBALS['ghostpool_date_posted'] = isset( $atts['date_posted'] ) ? $atts['date_posted'] : 'all';
					$GLOBALS['ghostpool_date_modified'] = isset( $atts['date_modified'] ) ? $atts['date_modified'] : 'all';
					if ( isset( $GLOBALS['ghostpool_shortcode'] ) && $GLOBALS['ghostpool_shortcode'] == 'portfolio' ) {
						$GLOBALS['ghostpool_filter'] = isset( $atts['filter'] ) ? $atts['filter'] : 'enabled';
					} else {
						$GLOBALS['ghostpool_filter'] = isset( $atts['filter'] ) ? $atts['filter'] : 'disabled';
					}
					$GLOBALS['ghostpool_filter_cats'] = isset( $atts['filter_cats'] ) ? $atts['filter_cats'] : '';
					$GLOBALS['ghostpool_filter_date'] = isset( $atts['filter_date'] ) ? $atts['filter_date'] : '';
					$GLOBALS['ghostpool_filter_title'] = isset( $atts['filter_title'] ) ? $atts['filter_title'] : '';
					$GLOBALS['ghostpool_filter_comment_count'] = isset( $atts['filter_comment_count'] ) ? $atts['filter_comment_count'] : '';
					$GLOBALS['ghostpool_filter_views'] = isset( $atts['filter_views'] ) ? $atts['filter_views'] : '';
					$GLOBALS['ghostpool_filter_date_posted'] = isset( $atts['filter_date_posted'] ) ? $atts['filter_date_posted'] : '';
					$GLOBALS['ghostpool_filter_date_modified'] = isset( $atts['filter_date_modified'] ) ? $atts['filter_date_modified'] : '';
					$GLOBALS['ghostpool_filter_cats_id'] = isset( $atts['filter_cats_id'] ) ? $atts['filter_cats_id'] : '';
					if ( isset( $GLOBALS['ghostpool_shortcode'] ) && $GLOBALS['ghostpool_shortcode'] == 'showcase' ) {
						$GLOBALS['ghostpool_per_page'] = isset( $atts['per_page'] ) ? $atts['per_page'] : '5';
					} else {
						$GLOBALS['ghostpool_per_page'] = isset( $atts['per_page'] ) ? $atts['per_page'] : '12';
					}
					$GLOBALS['ghostpool_offset'] = isset( $atts['offset'] ) ? $atts['offset'] : '';
					$GLOBALS['ghostpool_featured_image'] = isset( $atts['featured_image'] ) ? $atts['featured_image'] : 'enabled';
					$GLOBALS['ghostpool_image_width'] = isset( $atts['image_width'] ) ? $atts['image_width'] : '200';
					$GLOBALS['ghostpool_image_height'] = isset( $atts['image_height'] ) ? $atts['image_height'] : '200';
					$GLOBALS['ghostpool_hard_crop'] = isset( $atts['hard_crop'] ) ? $atts['hard_crop'] : 'enabled';
					$GLOBALS['ghostpool_image_alignment'] = isset( $atts['image_alignment'] ) ? $atts['image_alignment'] : 'gp-image-align-left';
					$GLOBALS['ghostpool_content_display'] = isset( $atts['content_display'] ) ? $atts['content_display'] : 'excerpt';
					$GLOBALS['ghostpool_excerpt_length'] = isset( $atts['excerpt_length'] ) ? $atts['excerpt_length'] : '160';
					$GLOBALS['ghostpool_meta_author'] = isset( $atts['meta_author'] ) ? $atts['meta_author'] : '';
					$GLOBALS['ghostpool_meta_date'] = isset( $atts['meta_date'] ) ? $atts['meta_date'] : '';
					$GLOBALS['ghostpool_meta_comment_count'] = isset( $atts['meta_comment_count'] ) ? $atts['meta_comment_count'] : '';
					$GLOBALS['ghostpool_meta_views'] = isset( $atts['meta_views'] ) ? $atts['meta_views'] : '';
					$GLOBALS['ghostpool_meta_cats'] = isset( $atts['meta_cats'] ) ? $atts['meta_cats'] : '';
					$GLOBALS['ghostpool_meta_tags'] = isset( $atts['meta_tags'] ) ? $atts['meta_tags'] : '';
					$GLOBALS['ghostpool_read_more_link'] = isset( $atts['read_more_link'] ) ? $atts['read_more_link'] : 'disabled';
					$GLOBALS['ghostpool_page_arrows'] = isset( $atts['page_arrows'] ) ? $atts['page_arrows'] : 'disabled';
					$GLOBALS['ghostpool_page_numbers'] = isset( $atts['page_numbers'] ) ? $atts['page_numbers'] : 'disabled';
					$GLOBALS['ghostpool_caption_title'] = isset( $atts['caption_title'] ) ? $atts['caption_title'] : 'enabled';
					$GLOBALS['ghostpool_caption_text'] = isset( $atts['caption_text'] ) ? $atts['caption_text'] : 'enabled';
		
					// Convert hard_crop option to true or false
					if ( isset( $GLOBALS['ghostpool_hard_crop'] ) && $GLOBALS['ghostpool_hard_crop'] == 'enabled' ) {
						$GLOBALS['ghostpool_hard_crop'] = true;
					} elseif ( isset( $GLOBALS['ghostpool_hard_crop'] ) && $GLOBALS['ghostpool_hard_crop'] == 'disabled' ) {	
						$GLOBALS['ghostpool_hard_crop'] = false;
					}
		
					// Add slug support for filter categories option
					if ( isset( $GLOBALS['ghostpool_filter_cats_id'] ) ) {
						if ( ! preg_match( '/^[1-9][0-9]*$/', $GLOBALS['ghostpool_filter_cats_id'] ) ) {
							$taxonomies = get_taxonomies();
							foreach ( $taxonomies as $taxonomy ) {
								$term = term_exists( $GLOBALS['ghostpool_filter_cats_id'], $taxonomy );
								$tax_name = '';
								if ( $term !== 0 && $term !== null ) {
									$tax_name = $taxonomy;
									break;
								}
							}		
							$filter_cats_slug = get_term_by( 'slug', $GLOBALS['ghostpool_filter_cats_id'], $tax_name );
							if ( $filter_cats_slug ) {
								$GLOBALS['ghostpool_filter_cats_id'] = $filter_cats_slug->term_id;
							}
						}	
					}
			
				}
			

				/*--------------------------------------------------------------
				Custom Shortcodes
				--------------------------------------------------------------*/
	
				// Only load admin CSS if using theme
				if ( file_exists( get_template_directory_uri() . '/lib/framework/css/general-admin.css' ) && file_exists( get_template_directory_uri() . '/lib/fonts/font-awesome/css/font-awesome.min.css' ) ) {
					$admin_css = array( get_template_directory_uri() . '/lib/framework/css/general-admin.css', get_template_directory_uri() . '/lib/fonts/font-awesome/css/font-awesome.min.css' ); 
				} else {
					$admin_css = '';
				}
				
				// Only load JS files if using theme
				if ( file_exists( get_template_directory_uri() . '/lib/scripts/jquery.flexslider-min.js' ) ) {
					$flexslider_js = get_template_directory_uri() . '/lib/scripts/jquery.flexslider-min.js';
				} else {
					$flexslider_js = '';
				}
				if ( file_exists( get_template_directory_uri() . '/lib/scripts/isotope.pkgd.min.js' ) ) {
					$masonry_js = array( get_template_directory_uri() . '/lib/scripts/isotope.pkgd.min.js', get_template_directory_uri() . '/lib/scripts/imagesLoaded.min.js' );
				} else {
					$masonry_js = '';
				}

				// Icons
				$icons = array( '', 'fa-500px', 'fa-adjust', 'fa-adn', 'fa-align-center', 'fa-align-justify', 'fa-align-left', 'fa-align-right', 'fa-amazon', 'fa-ambulance', 'fa-american-sign-language-interpreting', 'fa-anchor', 'fa-android', 'fa-angellist', 'fa-angle-double-down', 'fa-angle-double-left', 'fa-angle-double-right', 'fa-angle-double-up', 'fa-angle-down', 'fa-angle-left', 'fa-angle-right', 'fa-angle-up', 'fa-apple', 'fa-archive', 'fa-area-chart', 'fa-arrow-circle-down', 'fa-arrow-circle-left', 'fa-arrow-circle-o-down', 'fa-arrow-circle-o-left', 'fa-arrow-circle-o-right', 'fa-arrow-circle-o-up', 'fa-arrow-circle-right', 'fa-arrow-circle-up', 'fa-arrow-down', 'fa-arrow-left', 'fa-arrow-right', 'fa-arrow-up', 'fa-arrows', 'fa-arrows-alt', 'fa-arrows-h', 'fa-arrows-v', 'fa-asl-interpreting', 'fa-assistive-listening-systems', 'fa-asterisk', 'fa-at', 'fa-audio-description', 'fa-automobile', 'fa-backward', 'fa-balance-scale', 'fa-ban', 'fa-bank', 'fa-bar-chart', 'fa-bar-chart-o', 'fa-barcode', 'fa-bars', 'fa-battery-0', 'fa-battery-1', 'fa-battery-2', 'fa-battery-3', 'fa-battery-4', 'fa-battery-empty', 'fa-battery-full', 'fa-battery-half', 'fa-battery-quarter', 'fa-battery-three-quarters', 'fa-bed', 'fa-beer', 'fa-behance', 'fa-behance-square', 'fa-bell', 'fa-bell-o', 'fa-bell-slash', 'fa-bell-slash-o', 'fa-bicycle', 'fa-binoculars', 'fa-birthday-cake', 'fa-bitbucket', 'fa-bitbucket-square', 'fa-bitcoin', 'fa-black-tie', 'fa-blind', 'fa-bluetooth', 'fa-bluetooth-b', 'fa-bold', 'fa-bolt', 'fa-bomb', 'fa-book', 'fa-bookmark', 'fa-bookmark-o', 'fa-braille', 'fa-briefcase', 'fa-btc', 'fa-bug', 'fa-building', 'fa-building-o', 'fa-bullhorn', 'fa-bullseye', 'fa-bus', 'fa-buysellads', 'fa-cab', 'fa-calculator', 'fa-calendar', 'fa-calendar-check-o', 'fa-calendar-minus-o', 'fa-calendar-o', 'fa-calendar-plus-o', 'fa-calendar-times-o', 'fa-camera', 'fa-camera-retro', 'fa-car', 'fa-caret-down', 'fa-caret-left', 'fa-caret-right', 'fa-caret-square-o-down', 'fa-caret-square-o-left', 'fa-caret-square-o-right', 'fa-caret-square-o-up', 'fa-caret-up', 'fa-cart-arrow-down', 'fa-cart-plus', 'fa-cc', 'fa-cc-amex', 'fa-cc-diners-club', 'fa-cc-discover', 'fa-cc-jcb', 'fa-cc-mastercard', 'fa-cc-paypal', 'fa-cc-stripe', 'fa-cc-visa', 'fa-certificate', 'fa-chain', 'fa-chain-broken', 'fa-check', 'fa-check-circle', 'fa-check-circle-o', 'fa-check-square', 'fa-check-square-o', 'fa-chevron-circle-down', 'fa-chevron-circle-left', 'fa-chevron-circle-right', 'fa-chevron-circle-up', 'fa-chevron-down', 'fa-chevron-left', 'fa-chevron-right', 'fa-chevron-up', 'fa-child', 'fa-chrome', 'fa-circle', 'fa-circle-o', 'fa-circle-o-notch', 'fa-circle-thin', 'fa-clipboard', 'fa-clock-o', 'fa-clone', 'fa-close', 'fa-cloud', 'fa-cloud-download', 'fa-cloud-upload', 'fa-cny', 'fa-code', 'fa-code-fork', 'fa-codepen', 'fa-codiepie', 'fa-coffee', 'fa-cog', 'fa-cogs', 'fa-columns', 'fa-comment', 'fa-comment-o', 'fa-commenting', 'fa-commenting-o', 'fa-comments', 'fa-comments-o', 'fa-compass', 'fa-compress', 'fa-connectdevelop', 'fa-contao', 'fa-copy', 'fa-copyright', 'fa-creative-commons', 'fa-credit-card', 'fa-credit-card-alt', 'fa-crop', 'fa-crosshairs', 'fa-css3', 'fa-cube', 'fa-cubes', 'fa-cut', 'fa-cutlery', 'fa-dashboard', 'fa-dashcube', 'fa-database', 'fa-deaf', 'fa-deafness', 'fa-dedent', 'fa-delicious', 'fa-desktop', 'fa-deviantart', 'fa-diamond', 'fa-digg', 'fa-dollar', 'fa-dot-circle-o', 'fa-download', 'fa-dribbble', 'fa-dropbox', 'fa-drupal', 'fa-edge', 'fa-edit', 'fa-eject', 'fa-ellipsis-h', 'fa-ellipsis-v', 'fa-empire', 'fa-envelope', 'fa-envelope-o', 'fa-envelope-square', 'fa-envira', 'fa-eraser', 'fa-eur', 'fa-euro', 'fa-exchange', 'fa-exclamation', 'fa-exclamation-circle', 'fa-exclamation-triangle', 'fa-expand', 'fa-expeditedssl', 'fa-external-link', 'fa-external-link-square', 'fa-eye', 'fa-eye-slash', 'fa-eyedropper', 'fa-fa', 'fa-facebook', 'fa-facebook-f', 'fa-facebook-official', 'fa-facebook-square', 'fa-fast-backward', 'fa-fast-forward', 'fa-fax', 'fa-feed', 'fa-female', 'fa-fighter-jet', 'fa-file', 'fa-file-archive-o', 'fa-file-audio-o', 'fa-file-code-o', 'fa-file-excel-o', 'fa-file-image-o', 'fa-file-movie-o', 'fa-file-o', 'fa-file-pdf-o', 'fa-file-photo-o', 'fa-file-picture-o', 'fa-file-powerpoint-o', 'fa-file-sound-o', 'fa-file-text', 'fa-file-text-o', 'fa-file-video-o', 'fa-file-word-o', 'fa-file-zip-o', 'fa-files-o', 'fa-film', 'fa-filter', 'fa-fire', 'fa-fire-extinguisher', 'fa-firefox', 'fa-first-order', 'fa-flag', 'fa-flag-checkered', 'fa-flag-o', 'fa-flash', 'fa-flask', 'fa-flickr', 'fa-floppy-o', 'fa-folder', 'fa-folder-o', 'fa-folder-open', 'fa-folder-open-o', 'fa-font', 'fa-font-awesome', 'fa-fonticons', 'fa-fort-awesome', 'fa-forumbee', 'fa-forward', 'fa-foursquare', 'fa-frown-o', 'fa-futbol-o', 'fa-gamepad', 'fa-gavel', 'fa-gbp', 'fa-ge', 'fa-gear', 'fa-gears', 'fa-genderless', 'fa-get-pocket', 'fa-gg', 'fa-gg-circle', 'fa-gift', 'fa-git', 'fa-git-square', 'fa-github', 'fa-github-alt', 'fa-github-square', 'fa-gitlab', 'fa-gittip', 'fa-glass', 'fa-glide', 'fa-glide-g', 'fa-globe', 'fa-google', 'fa-google-plus', 'fa-google-plus-circle', 'fa-google-plus-official', 'fa-google-plus-square', 'fa-google-wallet', 'fa-graduation-cap', 'fa-gratipay', 'fa-group', 'fa-h-square', 'fa-hacker-news', 'fa-hand-grab-o', 'fa-hand-lizard-o', 'fa-hand-o-down', 'fa-hand-o-left', 'fa-hand-o-right', 'fa-hand-o-up', 'fa-hand-paper-o', 'fa-hand-peace-o', 'fa-hand-pointer-o', 'fa-hand-rock-o', 'fa-hand-scissors-o', 'fa-hand-spock-o', 'fa-hand-stop-o', 'fa-hard-of-hearing', 'fa-hashtag', 'fa-hdd-o', 'fa-header', 'fa-headphones', 'fa-heart', 'fa-heart-o', 'fa-heartbeat', 'fa-history', 'fa-home', 'fa-hospital-o', 'fa-hotel', 'fa-hourglass', 'fa-hourglass-1', 'fa-hourglass-2', 'fa-hourglass-3', 'fa-hourglass-end', 'fa-hourglass-half', 'fa-hourglass-o', 'fa-hourglass-start', 'fa-houzz', 'fa-html5', 'fa-i-cursor', 'fa-ils', 'fa-image', 'fa-inbox', 'fa-indent', 'fa-industry', 'fa-info', 'fa-info-circle', 'fa-inr', 'fa-instagram', 'fa-institution', 'fa-internet-explorer', 'fa-intersex', 'fa-ioxhost', 'fa-italic', 'fa-joomla', 'fa-jpy', 'fa-jsfiddle', 'fa-key', 'fa-keyboard-o', 'fa-krw', 'fa-language', 'fa-laptop', 'fa-lastfm', 'fa-lastfm-square', 'fa-leaf', 'fa-leanpub', 'fa-legal', 'fa-lemon-o', 'fa-level-down', 'fa-level-up', 'fa-life-bouy', 'fa-life-buoy', 'fa-life-ring', 'fa-life-saver', 'fa-lightbulb-o', 'fa-line-chart', 'fa-link', 'fa-linkedin', 'fa-linkedin-square', 'fa-linux', 'fa-list', 'fa-list-alt', 'fa-list-ol', 'fa-list-ul', 'fa-location-arrow', 'fa-lock', 'fa-long-arrow-down', 'fa-long-arrow-left', 'fa-long-arrow-right', 'fa-long-arrow-up', 'fa-low-vision', 'fa-magic', 'fa-magnet', 'fa-mail-forward', 'fa-mail-reply', 'fa-mail-reply-all', 'fa-male', 'fa-map', 'fa-map-marker', 'fa-map-o', 'fa-map-pin', 'fa-map-signs', 'fa-mars', 'fa-mars-double', 'fa-mars-stroke', 'fa-mars-stroke-h', 'fa-mars-stroke-v', 'fa-maxcdn', 'fa-meanpath', 'fa-medium', 'fa-medkit', 'fa-meh-o', 'fa-mercury', 'fa-microphone', 'fa-microphone-slash', 'fa-minus', 'fa-minus-circle', 'fa-minus-square', 'fa-minus-square-o', 'fa-mixcloud', 'fa-mobile', 'fa-mobile-phone', 'fa-modx', 'fa-money', 'fa-moon-o', 'fa-mortar-board', 'fa-motorcycle', 'fa-mouse-pointer', 'fa-music', 'fa-navicon', 'fa-neuter', 'fa-newspaper-o', 'fa-object-group', 'fa-object-ungroup', 'fa-odnoklassniki', 'fa-odnoklassniki-square', 'fa-opencart', 'fa-openid', 'fa-opera', 'fa-optin-monster', 'fa-outdent', 'fa-pagelines', 'fa-paint-brush', 'fa-paper-plane', 'fa-paper-plane-o', 'fa-paperclip', 'fa-paragraph', 'fa-paste', 'fa-pause', 'fa-pause-circle', 'fa-pause-circle-o', 'fa-paw', 'fa-paypal', 'fa-pencil', 'fa-pencil-square', 'fa-pencil-square-o', 'fa-percent', 'fa-phone', 'fa-phone-square', 'fa-photo', 'fa-picture-o', 'fa-pie-chart', 'fa-pied-piper', 'fa-pied-piper-alt', 'fa-pied-piper-pp', 'fa-pinterest', 'fa-pinterest-p', 'fa-pinterest-square', 'fa-plane', 'fa-play', 'fa-play-circle', 'fa-play-circle-o', 'fa-plug', 'fa-plus', 'fa-plus-circle', 'fa-plus-square', 'fa-plus-square-o', 'fa-power-off', 'fa-print', 'fa-product-hunt', 'fa-puzzle-piece', 'fa-qq', 'fa-qrcode', 'fa-question', 'fa-question-circle', 'fa-question-circle-o', 'fa-quote-left', 'fa-quote-right', 'fa-ra', 'fa-random', 'fa-rebel', 'fa-recycle', 'fa-reddit', 'fa-reddit-alien', 'fa-reddit-square', 'fa-refresh', 'fa-registered', 'fa-remove', 'fa-renren', 'fa-reorder', 'fa-repeat', 'fa-reply', 'fa-reply-all', 'fa-resistance', 'fa-retweet', 'fa-rmb', 'fa-road', 'fa-rocket', 'fa-rotate-left', 'fa-rotate-right', 'fa-rouble', 'fa-rss', 'fa-rss-square', 'fa-rub', 'fa-ruble', 'fa-rupee', 'fa-safari', 'fa-save', 'fa-scissors', 'fa-scribd', 'fa-search', 'fa-search-minus', 'fa-search-plus', 'fa-sellsy', 'fa-send', 'fa-send-o', 'fa-server', 'fa-share', 'fa-share-alt', 'fa-share-alt-square', 'fa-share-square', 'fa-share-square-o', 'fa-shekel', 'fa-sheqel', 'fa-shield', 'fa-ship', 'fa-shirtsinbulk', 'fa-shopping-bag', 'fa-shopping-basket', 'fa-shopping-cart', 'fa-sign-in', 'fa-sign-language', 'fa-sign-out', 'fa-signal', 'fa-signing', 'fa-simplybuilt', 'fa-sitemap', 'fa-skyatlas', 'fa-skype', 'fa-slack', 'fa-sliders', 'fa-slideshare', 'fa-smile-o', 'fa-snapchat', 'fa-snapchat-ghost', 'fa-snapchat-square', 'fa-soccer-ball-o', 'fa-sort', 'fa-sort-alpha-asc', 'fa-sort-alpha-desc', 'fa-sort-amount-asc', 'fa-sort-amount-desc', 'fa-sort-asc', 'fa-sort-desc', 'fa-sort-down', 'fa-sort-numeric-asc', 'fa-sort-numeric-desc', 'fa-sort-up', 'fa-soundcloud', 'fa-space-shuttle', 'fa-spinner', 'fa-spoon', 'fa-spotify', 'fa-square', 'fa-square-o', 'fa-stack-exchange', 'fa-stack-overflow', 'fa-star', 'fa-star-half', 'fa-star-half-empty', 'fa-star-half-full', 'fa-star-half-o', 'fa-star-o', 'fa-steam', 'fa-steam-square', 'fa-step-backward', 'fa-step-forward', 'fa-stethoscope', 'fa-sticky-note', 'fa-sticky-note-o', 'fa-stop', 'fa-stop-circle', 'fa-stop-circle-o', 'fa-street-view', 'fa-strikethrough', 'fa-stumbleupon', 'fa-stumbleupon-circle', 'fa-subscript', 'fa-subway', 'fa-suitcase', 'fa-sun-o', 'fa-superscript', 'fa-support', 'fa-table', 'fa-tablet', 'fa-tachometer', 'fa-tag', 'fa-tags', 'fa-tasks', 'fa-taxi', 'fa-television', 'fa-tencent-weibo', 'fa-terminal', 'fa-text-height', 'fa-text-width', 'fa-th', 'fa-th-large', 'fa-th-list', 'fa-themeisle', 'fa-thumb-tack', 'fa-thumbs-down', 'fa-thumbs-o-down', 'fa-thumbs-o-up', 'fa-thumbs-up', 'fa-ticket', 'fa-times', 'fa-times-circle', 'fa-times-circle-o', 'fa-tint', 'fa-toggle-down', 'fa-toggle-left', 'fa-toggle-off', 'fa-toggle-on', 'fa-toggle-right', 'fa-toggle-up', 'fa-trademark', 'fa-train', 'fa-transgender', 'fa-transgender-alt', 'fa-trash', 'fa-trash-o', 'fa-tree', 'fa-trello', 'fa-tripadvisor', 'fa-trophy', 'fa-truck', 'fa-try', 'fa-tty', 'fa-tumblr', 'fa-tumblr-square', 'fa-turkish-lira', 'fa-tv', 'fa-twitch', 'fa-twitter', 'fa-twitter-square', 'fa-umbrella', 'fa-underline', 'fa-undo', 'fa-universal-access', 'fa-university', 'fa-unlink', 'fa-unlock', 'fa-unlock-alt', 'fa-unsorted', 'fa-upload', 'fa-usb', 'fa-usd', 'fa-user', 'fa-user-md', 'fa-user-plus', 'fa-user-secret', 'fa-user-times', 'fa-users', 'fa-venus', 'fa-venus-double', 'fa-venus-mars', 'fa-viacoin', 'fa-viadeo', 'fa-viadeo-square', 'fa-video-camera', 'fa-vimeo', 'fa-vimeo-square', 'fa-vine', 'fa-vk', 'fa-volume-control-phone', 'fa-volume-down', 'fa-volume-off', 'fa-volume-up', 'fa-warning', 'fa-wechat', 'fa-weibo', 'fa-weixin', 'fa-whatsapp', 'fa-wheelchair', 'fa-wheelchair-alt', 'fa-wifi', 'fa-wikipedia-w', 'fa-windows', 'fa-won', 'fa-wordpress', 'fa-wpbeginner', 'fa-wpforms', 'fa-wrench', 'fa-xing', 'fa-xing-square', 'fa-y-combinator', 'fa-y-combinator-square', 'fa-yahoo', 'fa-yc', 'fa-yc-square', 'fa-yelp', 'fa-yen', 'fa-yoast', 'fa-youtube', 'fa-youtube-play', 'fa-youtube-square' );


			/*--------------------------------------------------------------
			Activity Shortcode
			--------------------------------------------------------------*/

			if ( function_exists( 'bp_is_active' ) ) {
		
				require_once( sprintf( "%s/gp_vc_activity.php", dirname( __FILE__ ) ) );

				vc_map( array( 
					'name' => esc_html__( 'Activity', 'socialize-plugin' ),
					'base' => 'activity',
					'description' => esc_html__( 'Display a BuddyPress activity loop anywhere on your site.', 'socialize-plugin' ),
					'class' => 'wpb_vc_activity',
					'controls' => 'full',
					'icon' => 'gp-icon-activity',
					'category' => esc_html__( 'BuddyPress', 'socialize-plugin' ),
					'params' => array(		
						array( 
						'heading' => esc_html__( 'Title', 'socialize-plugin' ),
						'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
						'param_name' => 'widget_title',
						'type' => 'textfield',
						'admin_label' => true,
						'value' => '',
						),		
						array( 
						'heading' => esc_html__( 'Post Form', 'socialize-plugin' ),
						'description' => esc_html__( 'Choose whether to add the post form.', 'socialize-plugin' ),
						'param_name' => 'post_form',
						'value' => array( esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled', esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled' ),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Scope', 'socialize-plugin' ),
						'param_name' => 'scope',
						'type' => 'checkbox',
						'value' => array( esc_html__( 'Single User', 'socialize-plugin' ) => 'just-me', esc_html__( 'Friends', 'socialize-plugin' ) => 'friends', esc_html__( 'Groups', 'socialize-plugin' ) => 'groups', esc_html__( 'Favorites', 'socialize-plugin' ) => 'favorites', esc_html__( 'Mentions', 'socialize-plugin' ) => 'mentions' ),
						'description' => esc_html__( 'Pre-defined filtering of the activity stream. Show only activity for the scope you pass (based on the logged in user or a user_id you pass).', 'socialize-plugin' ),
						),									
						array( 
						'heading' => esc_html__( 'Display Comments', 'socialize-plugin' ),
						'description' => esc_html__( 'Whether or not to display comments along with activity items. Threaded will show comments threaded under the activity. Stream will show comments within the actual stream in chronological order along with activity items.', 'socialize-plugin' ),
						'param_name' => 'display_comments',
						'value' => array( esc_html__( 'Threaded', 'socialize-plugin' ) => 'threaded', esc_html__( 'Stream', 'socialize-plugin' ) => 'stream', esc_html__( 'Disable', 'socialize-plugin' ) => 'false' ),
						'type' => 'dropdown',
						),	
						array( 
						'heading' => esc_html__( 'Allow Commenting', 'socialize-plugin' ),
						'description' => esc_html__( 'Whether or not users can post comments in the activity loop.', 'socialize-plugin' ),
						'param_name' => 'allow_comments',
						'value' => array( esc_html__( 'Enabled', 'socialize-plugin' ) => 'gp-comments-enabled', esc_html__( 'Disabled', 'socialize-plugin' ) => 'gp-comments-disabled' ),
						'type' => 'dropdown',
						),					
						array( 
						'heading' => esc_html__( 'Include', 'socialize-plugin' ),
						'description' => esc_html__( 'Pass an activity_id or string of comma separated ids to show only these entries.', 'socialize-plugin' ),
						'param_name' => 'include',
						'type' => 'textfield',
						'value' => '',
						),	
						array( 
						'heading' => esc_html__( 'Order', 'socialize-plugin' ),
						'description' => esc_html__( 'The criteria which the items are ordered by.', 'socialize-plugin' ),
						'param_name' => 'order',
						'value' => array(
							esc_html__( 'Newest', 'socialize-plugin' ) => 'DESC',
							esc_html__( 'Oldest', 'socialize-plugin' ) => 'ASC',
						),
						'type' => 'dropdown',
						),				
						array( 
						'heading' => esc_html__( 'Items Per Page', 'socialize-plugin' ),
						'description' => esc_html__( 'The number of activity items on each page.', 'socialize-plugin' ),
						'param_name' => 'per_page',
						'value' => '5',
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'Maximum Items', 'socialize-plugin' ),
						'description' => esc_html__( 'The maximum number of activity items to show.', 'socialize-plugin' ),
						'param_name' => 'max',
						'value' => '',
						'type' => 'textfield',
						),						
						array( 
						'heading' => esc_html__( 'Show Hidden Items', 'socialize-plugin' ),
						'description' => esc_html__( 'Show items that have been hidden site wide such as private or hidden group posts.', 'socialize-plugin' ),
						'param_name' => 'show_hidden',
						'value' => array( esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled', esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled' ),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Search Terms', 'socialize-plugin' ),
						'description' => esc_html__( 'Return only activity items that match these search terms.', 'socialize-plugin' ),
						'param_name' => 'search_terms',
						'value' => '',
						'type' => 'textfield',
						),	
						array( 
						'heading' => esc_html__( 'User ID', 'socialize-plugin' ),
						'description' => esc_html__( 'Limit activity items to a specific user ID.', 'socialize-plugin' ),
						'param_name' => 'user_id',
						'value' => '',
						'type' => 'textfield',
						),		
						array( 
						'heading' => esc_html__( 'Object', 'socialize-plugin' ),
						'description' => esc_html__( 'The object type to filter by (can be any active component ID as well as custom component IDs) e.g. groups, friends, profile, status, blogs.', 'socialize-plugin' ),
						'param_name' => 'object',
						'value' => '',
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'Action', 'socialize-plugin' ),
						'description' => esc_html__( 'The action type to filter by (can be any active component action as well as custom component actions) e.g. bbp_reply_create, new_blog_comment new_blog_post, friendship_created, joined_group, created_group, activity_update.', 'socialize-plugin' ),
						'param_name' => 'action',
						'value' => '',
						'type' => 'textfield',
						),	
						array( 
						'heading' => esc_html__( 'Primary ID', 'socialize-plugin' ),
						'description' => esc_html__( 'The ID to filter by for a specific object. For example if you used groups as the object you could pass a group_id as the primary_id and restrict to that group.', 'socialize-plugin' ),
						'param_name' => 'primary_id',
						'value' => '',
						'type' => 'textfield',
						),	
						array( 
						'heading' => esc_html__( 'Secondary ID', 'socialize-plugin' ),
						'description' => esc_html__( 'The secondary ID to filter by for a specific object. For example if you used blogs as the object you could pass a blog_id as the primary_id and a post_id as the secondary_id then list all comments for that post using new_blog_comment as the action.', 'socialize-plugin' ),
						'param_name' => 'secondary_id',
						'value' => '',
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'See All', 'socialize-plugin' ),
						'description' => esc_html__( 'Add a "See All" link.', 'socialize-plugin' ),
						'param_name' => 'see_all',
						'value' => array( esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled', esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled' ),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'See All Link', 'socialize-plugin' ),
						'description' => esc_html__( 'URL for the "See All" link.', 'socialize-plugin' ),
						'param_name' => 'see_all_link',
						'type' => 'textfield',
						'dependency' => array( 'element' => 'see_all', 'value' => 'enabled' ),
						),				 			 
						array( 
						'heading' => esc_html__( 'See All Text', 'socialize-plugin' ),
						'description' => esc_html__( 'Custom text for the "See All" link.', 'socialize-plugin' ),
						'param_name' => 'see_all_text',
						'type' => 'textfield',
						'value' => esc_html__( 'See All Items', 'socialize-plugin' ),
						'dependency' => array( 'element' => 'see_all', 'value' => 'enabled' ),
						),						
						array( 
						'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
						'param_name' => 'classes',
						'value' => '',
						'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
						'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
						'param_name' => 'title_format',
						'value' => array( esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title', esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title' ),
						'type' => 'dropdown',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),
						array( 
						'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
						'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
						'param_name' => 'title_color',
						'value' => '',
						'type' => 'colorpicker',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),
						array( 
						'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
						'param_name' => 'icon',
						'value' => $icons,
						'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
						'type' => 'icon_selection',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),																																														
					 )
				) );
		
			}
		

				/*--------------------------------------------------------------
				Advertisement Shortcode
				--------------------------------------------------------------*/

				require_once( sprintf( "%s/gp_vc_advertisement.php", dirname( __FILE__ ) ) );

				vc_map( array( 
					'name' => esc_html__( 'Advertisement', 'socialize-plugin' ),
					'deprecated' => '1.15',
					'base' => 'advertisement',
					'description' => esc_html__( 'Insert an advertisement anywhere you can insert this element.', 'socialize-plugin' ),
					'class' => 'wpb_vc_advertisement',
					'controls' => 'full',
					'icon' => 'gp-icon-advertisement',
					'category' => esc_html__( 'Content', 'socialize-plugin' ),
					'params' => array(					
						array( 
						'heading' => esc_html__( 'Title', 'socialize-plugin' ),
						'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
						'param_name' => 'widget_title',
						'type' => 'textfield',
						'admin_label' => true,
						'value' => '',
						),
						array( 
						'heading' => esc_html__( 'Advertisement Code', 'socialize-plugin' ),
						'description' => esc_html__( 'The advertisement code e.g. Google Adsense JavaScript code.', 'socialize-plugin' ),
						'param_name' => 'content',
						'value' => '',
						'type' => 'textarea_html',
						),	
						array( 
						'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
						'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
						'param_name' => 'classes',
						'value' => '',
						'type' => 'textfield',
						),				
						array( 
						'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
						'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
						'param_name' => 'title_format',
						'value' => array( esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title', esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title' ),
						'type' => 'dropdown',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),
						array( 
						'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
						'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
						'param_name' => 'title_color',
						'value' => '',
						'type' => 'colorpicker',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),	
						array( 
						'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
						'param_name' => 'icon',
						'value' => $icons,
						'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
						'type' => 'icon_selection',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),
					 )
				) );
			
											
				/*--------------------------------------------------------------
				Blog Shortcode
				--------------------------------------------------------------*/

				require_once( sprintf( "%s/gp_vc_blog.php", dirname( __FILE__ ) ) );

				vc_map( array( 
					'name' => esc_html__( 'Blog', 'socialize-plugin' ),
					'base' => 'blog',
					'description' => esc_html__( 'Display posts, pages and custom post types in a variety of ways.', 'socialize-plugin' ),
					'class' => 'wpb_vc_blog',
					'controls' => 'full',
					'icon' => 'gp-icon-blog',
					'category' => esc_html__( 'Content', 'socialize-plugin' ),			
					'admin_enqueue_css' => $admin_css,
					'front_enqueue_css' => $admin_css,
					'front_enqueue_js' => $masonry_js,
					'params' => array(		
						array( 
						'heading' => esc_html__( 'Title', 'socialize-plugin' ),
						'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
						'param_name' => 'widget_title',
						'type' => 'textfield',
						'admin_label' => true,
						'value' => '',
						),								
						array( 
						'heading' => esc_html__( 'Categories', 'socialize-plugin' ),
						'description' => esc_html__( 'Enter the slugs or IDs separating each one with a comma e.g. xbox,ps3,pc.', 'socialize-plugin' ),
						'param_name' => 'cats',
						'type' => 'textfield',
						),					
						array( 
						'heading' => esc_html__( 'Page IDs', 'socialize-plugin' ),
						'description' => esc_html__( 'Enter the page IDs.', 'socialize-plugin' ),
						'param_name' => 'page_ids',
						'type' => 'textfield',
						),			
						array( 
						'heading' => esc_html__( 'Post Types', 'socialize-plugin' ),
						'description' => esc_html__( 'The post types to display.', 'socialize-plugin' ),
						'param_name' => 'post_types',
						'type' => 'posttypes',
						'value' => 'post',
						),	
						array( 
						'heading' => esc_html__( 'Format', 'socialize-plugin' ),
						'description' => esc_html__( 'The format to display the items in.', 'socialize-plugin' ),
						'param_name' => 'format',
						'value' => array( esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-blog-standard', esc_html__( '1 Column', 'socialize-plugin' ) => 'gp-blog-columns-1', esc_html__( '2 Columns', 'socialize-plugin' ) => 'gp-blog-columns-2', esc_html__( '3 Columns', 'socialize-plugin' ) => 'gp-blog-columns-3', esc_html__( '4 Columns', 'socialize-plugin' ) => 'gp-blog-columns-4', esc_html__( '5 Columns', 'socialize-plugin' ) => 'gp-blog-columns-5', esc_html__( '6 Columns', 'socialize-plugin' ) => 'gp-blog-columns-6', esc_html__( 'Masonry', 'socialize-plugin' ) => 'gp-blog-masonry' ),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Order By', 'socialize-plugin' ),
						'description' => esc_html__( 'The criteria which the items are ordered by.', 'socialize-plugin' ),
						'param_name' => 'orderby',
						'value' => array(
							esc_html__( 'Newest', 'socialize-plugin' ) => 'newest',
							esc_html__( 'Oldest', 'socialize-plugin' ) => 'oldest',
							esc_html__( 'Title (A-Z)', 'socialize-plugin' ) => 'title_az',
							esc_html__( 'Title (Z-A)', 'socialize-plugin' ) => 'title_za',
							esc_html__( 'Most Comments', 'socialize-plugin' ) => 'comment_count',
							esc_html__( 'Most Views', 'socialize-plugin' ) => 'views',
							esc_html__( 'Menu Order', 'socialize-plugin' ) => 'menu_order',
							esc_html__( 'Random', 'socialize-plugin' ) => 'rand',
						),
						'type' => 'dropdown',
						),	
						array( 
						'heading' => esc_html__( 'Date Posted', 'socialize-plugin' ),
						'description' => esc_html__( 'The date the items were posted.', 'socialize-plugin' ),
						'param_name' => 'date_posted',
						'value' => array(
							esc_html__( 'Any date', 'socialize-plugin' ) => 'all',
							esc_html__( 'In the last year', 'socialize-plugin' ) => 'year',
							esc_html__( 'In the last month', 'socialize-plugin' ) => 'month',
							esc_html__( 'In the last week', 'socialize-plugin' ) => 'week',
							esc_html__( 'In the last day', 'socialize-plugin' ) => 'day',
						),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Date Modified', 'socialize-plugin' ),
						'description' => esc_html__( 'The date the items were modified.', 'socialize-plugin' ),
						'param_name' => 'date_modified',
						'value' => array(
							esc_html__( 'Any date', 'socialize-plugin' ) => 'all',
							esc_html__( 'In the last year', 'socialize-plugin' ) => 'year',
							esc_html__( 'In the last month', 'socialize-plugin' ) => 'month',
							esc_html__( 'In the last week', 'socialize-plugin' ) => 'week',
							esc_html__( 'In the last day', 'socialize-plugin' ) => 'day',
						),
						'type' => 'dropdown',
						),	
						array( 
						'heading' => esc_html__( 'Filter', 'socialize-plugin' ),
						'description' => esc_html__( 'Add a dropdown filter menu to the page.', 'socialize-plugin' ),
						'param_name' => 'filter',
						'value' => array( esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled', esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled' ),
						'type' => 'dropdown',
						),	
						array(
						'heading' => esc_html__( 'Filter Options', 'socialize-plugin' ),
						'param_name' => 'filter_cats',
						'value' => array( esc_html__( 'Categories', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						'dependency' => array( 'element' => 'filter', 'value' => 'enabled' ),
						),	
						array(
						'param_name' => 'filter_date',
						'value' => array( esc_html__( 'Date', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						'dependency' => array( 'element' => 'filter', 'value' => 'enabled' ),
						),	
						array(
						'param_name' => 'filter_title',
						'value' => array( esc_html__( 'Title', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						'dependency' => array( 'element' => 'filter', 'value' => 'enabled' ),
						),								
						array(
						'param_name' => 'filter_comment_count',
						'value' => array( esc_html__( 'Comment Count', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						'dependency' => array( 'element' => 'filter', 'value' => 'enabled' ),
						),
						array(
						'param_name' => 'filter_views',
						'value' => array( esc_html__( 'Views', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						'dependency' => array( 'element' => 'filter', 'value' => 'enabled' ),
						),
						array( 
						'param_name' => 'filter_date_posted',
						'value' => array( esc_html__( 'Date Posted', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						'dependency' => array( 'element' => 'filter', 'value' => 'enabled' ),
						),				
						array( 
						'description' => esc_html__( 'Choose what options to display in the dropdown filter menu.', 'socialize-plugin' ),
						'param_name' => 'filter_date_modified',
						'value' => array( esc_html__( 'Date Modified', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						'dependency' => array( 'element' => 'filter', 'value' => 'enabled' ),
						),				
						array( 
						'heading' => esc_html__( 'Filter Category', 'socialize-plugin' ),
						'description' => esc_html__( 'Enter the slug or ID number of the category you want to filter by, leave blank to display all categories - the sub categories of this category will also be displayed.', 'socialize-plugin' ),
						'param_name' => 'filter_cats_id',
						'type' => 'textfield',
						'dependency' => array( 'element' => 'filter', 'value' => 'enabled' ),
						),												 
						array( 
						'heading' => esc_html__( 'Items Per Page', 'socialize-plugin' ),
						'description' => esc_html__( 'The number of items on each page.', 'socialize-plugin' ),
						'param_name' => 'per_page',
						'value' => '12',
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'Offset', 'socialize-plugin' ),
						'description' => esc_html__( 'The number of posts to offset by e.g. set to 3 to exclude the first 3 posts.', 'socialize-plugin' ),
						'param_name' => 'offset',
						'value' => '',
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'Featured Image', 'socialize-plugin' ),
						'description' => esc_html__( 'Display the featured images.', 'socialize-plugin' ),
						'param_name' => 'featured_image',
						'value' => array( esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled', esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled' ),
						'type' => 'dropdown',
						),	
						array( 
						'heading' => esc_html__( 'Image Width', 'socialize-plugin' ),
						'description' => esc_html__( 'The width of the featured images.', 'socialize-plugin' ),
						'param_name' => 'image_width',
						'value' => '200',
						'type' => 'textfield',
						'dependency' => array( 'element' => 'featured_image', 'value' => 'enabled' ),
						),		 
						array( 
						'heading' => esc_html__( 'Image Height', 'socialize-plugin' ),
						'description' => esc_html__( 'The height of the featured images.', 'socialize-plugin' ),
						'param_name' => 'image_height',
						'value' => '200',
						'type' => 'textfield',
						'dependency' => array( 'element' => 'featured_image', 'value' => 'enabled' ),
						),	
						array( 
						'heading' => esc_html__( 'Hard Crop', 'socialize-plugin' ),
						'description' => esc_html__( 'Images are cropped even if it is smaller than the dimensions you want to crop it to.', 'socialize-plugin' ),
						'param_name' => 'hard_crop',
						'value' => array( esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled', esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled' ),
						'type' => 'dropdown',
						'dependency' => array( 'element' => 'featured_image', 'value' => 'enabled' ),
						),	
						array( 
						'heading' => esc_html__( 'Image Alignment', 'socialize-plugin' ),
						'description' => esc_html__( 'Choose how the image aligns with the content.', 'socialize-plugin' ),
						'param_name' => 'image_alignment',
						'value' => array( esc_html__( 'Left Align', 'socialize-plugin' ) => 'gp-image-align-left', esc_html__( 'Right Align', 'socialize-plugin' ) => 'gp-image-align-right', esc_html__( 'Left Wrap', 'socialize-plugin' ) => 'gp-image-wrap-left', esc_html__( 'Right Wrap', 'socialize-plugin' ) => 'gp-image-wrap-right', esc_html__( 'Above Content', 'socialize-plugin' ) => 'gp-image-above' ),
						'type' => 'dropdown',
						'dependency' => array( 'element' => 'featured_image', 'value' => 'enabled' ),
						),
						array( 
						'heading' => esc_html__( 'Content Display', 'socialize-plugin' ),
						'description' => esc_html__( 'The amount of content displayed.', 'socialize-plugin' ),
						'param_name' => 'content_display',
						'value' => array( esc_html__( 'Excerpt', 'socialize-plugin' ) => 'excerpt', esc_html__( 'Full Content', 'socialize-plugin' ) => 'full_content' ),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Excerpt Length', 'socialize-plugin' ),
						'description' => esc_html__( 'The number of characters in excerpts.', 'socialize-plugin' ),
						'param_name' => 'excerpt_length',
						'value' => '160',
						'type' => 'textfield',
						'dependency' => array( 'element' => 'content_display', 'value' => 'excerpt' ),
						),	
						array(
						'heading' => esc_html__( 'Post Meta', 'socialize-plugin' ),
						'param_name' => 'meta_author',
						'value' => array( esc_html__( 'Author Name', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),	
						array(
						'param_name' => 'meta_date',
						'value' => array( esc_html__( 'Post Date', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),	
						array(
						'param_name' => 'meta_comment_count',
						'value' => array( esc_html__( 'Comment Count', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),
						array(
						'param_name' => 'meta_views',
						'value' => array( esc_html__( 'Views', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),	
						array( 
						'param_name' => 'meta_cats',
						'value' => array( esc_html__( 'Post Categories', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),						
						array(
						'description' => esc_html__( 'Select the meta data you want to display.', 'socialize-plugin' ),
						'param_name' => 'meta_tags',
						'value' => array( esc_html__( 'Post Tags', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),	
						array( 
						'heading' => esc_html__( 'Read More Link', 'socialize-plugin' ),
						'description' => esc_html__( 'Add a read more link below the content.', 'socialize-plugin' ),
						'param_name' => 'read_more_link',
						'value' => array( esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled', esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled' ),
						'type' => 'dropdown',
						),		 
						array( 
						'heading' => esc_html__( 'Pagination (Arrows)', 'socialize-plugin' ),
						'description' => esc_html__( 'Add pagination arrows.', 'socialize-plugin' ),
						'param_name' => 'page_arrows',
						'value' => array( esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled', esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled' ),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Pagination (Numbers)', 'socialize-plugin' ),
						'description' => esc_html__( 'Add pagination numbers.', 'socialize-plugin' ),
						'param_name' => 'page_numbers',
						'value' => array( esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled', esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled' ),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'See All', 'socialize-plugin' ),
						'description' => esc_html__( 'Add a "See All" link.', 'socialize-plugin' ),
						'param_name' => 'see_all',
						'value' => array( esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled', esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled' ),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'See All Link', 'socialize-plugin' ),
						'description' => esc_html__( 'URL for the "See All" link.', 'socialize-plugin' ),
						'param_name' => 'see_all_link',
						'type' => 'textfield',
						'dependency' => array( 'element' => 'see_all', 'value' => 'enabled' ),
						),				 			 
						array( 
						'heading' => esc_html__( 'See All Text', 'socialize-plugin' ),
						'description' => esc_html__( 'Custom text for the "See All" link.', 'socialize-plugin' ),
						'param_name' => 'see_all_text',
						'type' => 'textfield',
						'value' => esc_html__( 'See All Items', 'socialize-plugin' ),
						'dependency' => array( 'element' => 'see_all', 'value' => 'enabled' ),
						),		 				 		   			 			 
						array( 
						'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
						'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
						'param_name' => 'classes',
						'value' => '',
						'type' => 'textfield',
						),		
						array( 
						'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
						'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
						'param_name' => 'title_format',
						'value' => array( esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title', esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title' ),
						'type' => 'dropdown',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),
						array( 
						'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
						'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
						'param_name' => 'title_color',
						'value' => '',
						'type' => 'colorpicker',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),	
						array( 
						'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
						'param_name' => 'icon',
						'value' => $icons,
						'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
						'type' => 'icon_selection',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),																																											
					 )
				) );


				/*--------------------------------------------------------------
				BuddyPress Shortcodes
				--------------------------------------------------------------*/

				if ( function_exists( 'bp_is_active' ) ) {
			
					require_once( sprintf( "%s/gp_vc_buddypress.php", dirname( __FILE__ ) ) );

					// BuddyPress Groups
					vc_map( array( 
						'name' => esc_html__( 'Groups', 'socialize-plugin' ),
						'base' => 'bp_groups',
						'description' => esc_html__( 'A dynamic list of recently active, popular, and newest groups.', 'socialize-plugin' ),
						'class' => 'wpb_vc_bp_groups',
						'controls' => 'full',
						'icon' => 'gp-icon-bp-groups',
						'category' => esc_html__( 'BuddyPress', 'socialize-plugin' ),
						'params' => array(				

							array( 
							'heading' => esc_html__( 'Title', 'socialize-plugin' ),
							'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
							'param_name' => 'title',
							'type' => 'textfield',
							'admin_label' => true,
							'value' => esc_html__( 'Groups', 'socialize-plugin' ),
							),
							array( 
							'heading' => esc_html__( 'Link Widget Title', 'socialize-plugin' ),
							'param_name' => 'link_title',
							'type' => 'checkbox',
							'value' => array( esc_html__( 'Link widget title to Groups directory', 'socialize-plugin' ) => 'false' ),
							),	
							array( 
							'heading' => esc_html__( 'Maximum Groups', 'socialize-plugin' ),
							'description' => esc_html__( 'Maximum number of groups to show.', 'socialize-plugin' ),
							'param_name' => 'max_groups',
							'type' => 'textfield',
							'value' => 5,
							),					
							array( 
							'heading' => esc_html__( 'Default Display', 'socialize-plugin' ),
							'description' => esc_html__( 'The group display that is shown by default.', 'socialize-plugin' ),
							'param_name' => 'group_default',
							'value' => array(
								esc_html__( 'Popular', 'socialize-plugin' ) => 'popular',
								esc_html__( 'Active', 'socialize-plugin' ) => 'active',
								esc_html__( 'Newest', 'socialize-plugin' ) => 'newest',
							),
							'type' => 'dropdown',
							),						
							array( 
							'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
							'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
							'param_name' => 'classes',
							'value' => '',
							'type' => 'textfield',
							),		
							array( 
							'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
							'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
							'param_name' => 'title_format',
							'value' => array( esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title', esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title' ),
							'type' => 'dropdown',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
							array( 
							'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
							'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
							'param_name' => 'title_color',
							'value' => '',
							'type' => 'colorpicker',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),	
							array( 
							'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
							'param_name' => 'icon',
							'value' => $icons,
							'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
							'type' => 'icon_selection',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
						 )
					) );		

					// BuddyPress Members
					vc_map( array( 
						'name' => esc_html__( 'Members', 'socialize-plugin' ),
						'base' => 'bp_members',
						'description' => esc_html__( 'A dynamic list of recently active, popular, and newest members.', 'socialize-plugin' ),
						'class' => 'wpb_vc_bp_members',
						'controls' => 'full',
						'icon' => 'gp-icon-bp-members',
						'category' => esc_html__( 'BuddyPress', 'socialize-plugin' ),
						'params' => array(				

							array( 
							'heading' => esc_html__( 'Title', 'socialize-plugin' ),
							'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
							'param_name' => 'title',
							'type' => 'textfield',
							'admin_label' => true,
							'value' => esc_html__( 'Members', 'socialize-plugin' ),
							),
							array( 
							'heading' => esc_html__( 'Link Widget Title', 'socialize-plugin' ),
							'param_name' => 'link_title',
							'type' => 'checkbox',
							'value' => array( esc_html__( 'Link widget title to Groups directory', 'socialize-plugin' ) => 'false' ),
							),	
							array( 
							'heading' => esc_html__( 'Maximum Members', 'socialize-plugin' ),
							'description' => esc_html__( 'Maximum number of members to show.', 'socialize-plugin' ),
							'param_name' => 'max_members',
							'type' => 'textfield',
							'value' => 5,
							),					
							array( 
							'heading' => esc_html__( 'Default Display', 'socialize-plugin' ),
							'description' => esc_html__( 'The member display that is shown by default.', 'socialize-plugin' ),
							'param_name' => 'member_default',
							'value' => array(
								esc_html__( 'Active', 'socialize-plugin' ) => 'active',
								esc_html__( 'Newest', 'socialize-plugin' ) => 'newest',
								esc_html__( 'Popular', 'socialize-plugin' ) => 'popular',
							),
							'type' => 'dropdown',
							),						
							array( 
							'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
							'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
							'param_name' => 'classes',
							'value' => '',
							'type' => 'textfield',
							),								
							array( 
							'heading' => esc_html__( 'CSS box', 'socialize-plugin' ),
							'param_name' => 'css',
							'type' => 'css_editor',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),		
							array( 
							'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
							'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
							'param_name' => 'title_format',
							'value' => array( esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title', esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title' ),
							'type' => 'dropdown',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
							array( 
							'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
							'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
							'param_name' => 'title_color',
							'value' => '',
							'type' => 'colorpicker',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),	
							array( 
							'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
							'param_name' => 'icon',
							'value' => $icons,
							'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
							'type' => 'icon_selection',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
						 )
					) );							

					// BuddyPress Friends
					vc_map( array( 
						'name' => esc_html__( 'Friends', 'socialize-plugin' ),
						'base' => 'bp_friends',
						'description' => esc_html__( 'A dynamic list of recently active, popular, and newest friends.', 'socialize-plugin' ),
						'class' => 'wpb_vc_bp_friends',
						'controls' => 'full',
						'icon' => 'gp-icon-bp-friends',
						'category' => esc_html__( 'BuddyPress', 'socialize-plugin' ),
						'params' => array(				

							array( 
							'heading' => esc_html__( 'Title', 'socialize-plugin' ),
							'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
							'param_name' => 'title',
							'type' => 'textfield',
							'admin_label' => true,
							'value' => esc_html__( 'Friends', 'socialize-plugin' ),
							),
							array( 
							'heading' => esc_html__( 'Link Widget Title', 'socialize-plugin' ),
							'param_name' => 'link_title',
							'type' => 'checkbox',
							'value' => array( esc_html__( 'Link widget title to Groups directory', 'socialize-plugin' ) => 'false' ),
							),	
							array( 
							'heading' => esc_html__( 'Maximum Friends', 'socialize-plugin' ),
							'description' => esc_html__( 'Maximum number of friends to show.', 'socialize-plugin' ),
							'param_name' => 'max_friends',
							'type' => 'textfield',
							'value' => 5,
							),					
							array( 
							'heading' => esc_html__( 'Default Display', 'socialize-plugin' ),
							'description' => esc_html__( 'The friend display that is shown by default.', 'socialize-plugin' ),
							'param_name' => 'friend_default',
							'value' => array(
								esc_html__( 'Active', 'socialize-plugin' ) => 'active',
								esc_html__( 'Newest', 'socialize-plugin' ) => 'newest',
								esc_html__( 'Popular', 'socialize-plugin' ) => 'popular',
							),
							'type' => 'dropdown',
							),						
							array( 
							'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
							'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
							'param_name' => 'classes',
							'value' => '',
							'type' => 'textfield',
							),		
							array( 
							'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
							'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
							'param_name' => 'title_format',
							'value' => array( esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title', esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title' ),
							'type' => 'dropdown',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
							array( 
							'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
							'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
							'param_name' => 'title_color',
							'value' => '',
							'type' => 'colorpicker',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),	
							array( 
							'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
							'param_name' => 'icon',
							'value' => $icons,
							'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
							'type' => 'icon_selection',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
						 )
					) );							

					// BuddyPress Recently Active Members
					vc_map( array( 
						'name' => esc_html__( 'Recently Active Members', 'socialize-plugin' ),
						'base' => 'bp_recently_active_members',
						'description' => esc_html__( 'Profile photos of recently active members.', 'socialize-plugin' ),
						'class' => 'wpb_vc_bp_recently_active_members',
						'controls' => 'full',
						'icon' => 'gp-icon-bp-members',
						'category' => esc_html__( 'BuddyPress', 'socialize-plugin' ),
						'params' => array(				

							array( 
							'heading' => esc_html__( 'Title', 'socialize-plugin' ),
							'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
							'param_name' => 'title',
							'type' => 'textfield',
							'admin_label' => true,
							'value' => esc_html__( 'Recently Active Members', 'socialize-plugin' ),
							),
							array( 
							'heading' => esc_html__( 'Maximum Members', 'socialize-plugin' ),
							'description' => esc_html__( 'Maximum number of members to show.', 'socialize-plugin' ),
							'param_name' => 'max_members',
							'type' => 'textfield',
							'value' => 16,
							),	
							array( 
							'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
							'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
							'param_name' => 'classes',
							'value' => '',
							'type' => 'textfield',
							),		
							array( 
							'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
							'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
							'param_name' => 'title_format',
							'value' => array( esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title', esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title' ),
							'type' => 'dropdown',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
							array( 
							'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
							'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
							'param_name' => 'title_color',
							'value' => '',
							'type' => 'colorpicker',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),	
							array( 
							'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
							'param_name' => 'icon',
							'value' => $icons,
							'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
							'type' => 'icon_selection',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
						 )
					) );	
				
					// BuddyPress Who's Online
					vc_map( array( 
						'name' => esc_html__( 'Whos Online', 'socialize-plugin' ),
						'base' => 'bp_whos_online',
						'description' => esc_html__( 'Profile photos of online users.', 'socialize-plugin' ),
						'class' => 'wpb_vc_bp_whos_online',
						'controls' => 'full',
						'icon' => 'gp-icon-bp-members',
						'category' => esc_html__( 'BuddyPress', 'socialize-plugin' ),
						'params' => array(				

							array( 
							'heading' => esc_html__( 'Title', 'socialize-plugin' ),
							'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
							'param_name' => 'title',
							'type' => 'textfield',
							'admin_label' => true,
							'value' => esc_html__( 'Who\'s Online', 'socialize-plugin' ),
							),
							array( 
							'heading' => esc_html__( 'Maximum Members', 'socialize-plugin' ),
							'description' => esc_html__( 'Maximum number of members to show.', 'socialize-plugin' ),
							'param_name' => 'max_members',
							'type' => 'textfield',
							'value' => 16,
							),	
							array( 
							'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
							'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
							'param_name' => 'classes',
							'value' => '',
							'type' => 'textfield',
							),	
							array( 
							'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
							'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
							'param_name' => 'title_format',
							'value' => array( esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title', esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title' ),
							'type' => 'dropdown',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),	
							array( 
							'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
							'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
							'param_name' => 'title_color',
							'value' => '',
							'type' => 'colorpicker',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),	
							array( 
							'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
							'param_name' => 'icon',
							'value' => $icons,
							'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
							'type' => 'icon_selection',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
						 )
					) );
		
				}
				
		
				/*--------------------------------------------------------------
				bbPress Shortcodes
				--------------------------------------------------------------*/

				if ( class_exists( 'bbPress' ) ) {
		
					require_once( sprintf( "%s/gp_vc_bbpress.php", dirname( __FILE__ ) ) );
			
					// bbPress Forum Search Form
					vc_map( array( 
						'name' => esc_html__( 'Forums Search Form', 'socialize-plugin' ),
						'base' => 'bbp_search',
						'description' => esc_html__( 'The bbPress forum search form.', 'socialize-plugin' ),
						'class' => 'wpb_vc_bbp_search',
						'controls' => 'full',
						'icon' => 'gp-icon-bbp-search',
						'category' => esc_html__( 'BuddyPress', 'socialize-plugin' ),
						'params' => array(
							array( 
							'heading' => esc_html__( 'Title', 'socialize-plugin' ),
							'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
							'param_name' => 'title',
							'type' => 'textfield',
							'admin_label' => true,
							'value' => esc_html__( 'Search Forums', 'socialize-plugin' ),
							),
							array( 
							'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
							'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
							'param_name' => 'classes',
							'value' => '',
							'type' => 'textfield',
							),	
							array( 
							'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
							'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
							'param_name' => 'title_format',
							'value' => array( esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title', esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title' ),
							'type' => 'dropdown',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),	
							array( 
							'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
							'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
							'param_name' => 'title_color',
							'value' => '',
							'type' => 'colorpicker',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),	
							array( 
							'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
							'param_name' => 'icon',
							'value' => $icons,
							'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
							'type' => 'icon_selection',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
						 )
					) );

					// bbPress Forums List
					vc_map( array( 
						'name' => esc_html__( 'Forums List', 'socialize-plugin' ),
						'base' => 'bbp_forums_list',
						'description' => esc_html__( 'A list of forums with an option to set the parent.', 'socialize-plugin' ),
						'class' => 'wpb_vc_bbp_forums_list',
						'controls' => 'full',
						'icon' => 'gp-icon-bbp-forums-list',
						'category' => esc_html__( 'BuddyPress', 'socialize-plugin' ),
						'params' => array(			
							array( 
							'heading' => esc_html__( 'Title', 'socialize-plugin' ),
							'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
							'param_name' => 'title',
							'type' => 'textfield',
							'admin_label' => true,
							'value' => esc_html__( 'Forums List', 'socialize-plugin' ),
							),				
							array( 
							'heading' => esc_html__( 'Parent Forum ID', 'socialize-plugin' ),
							'description' => esc_html__( '"0" to show only root - "any" to show all.', 'socialize-plugin' ),
							'param_name' => 'parent_forum',
							'type' => 'textfield',
							'value' => '0',
							),
							array( 
							'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
							'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
							'param_name' => 'classes',
							'value' => '',
							'type' => 'textfield',
							),		
							array( 
							'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
							'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
							'param_name' => 'title_format',
							'value' => array( esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title', esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title' ),
							'type' => 'dropdown',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
							array( 
							'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
							'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
							'param_name' => 'title_color',
							'value' => '',
							'type' => 'colorpicker',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),	
							array( 
							'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
							'param_name' => 'icon',
							'value' => $icons,
							'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
							'type' => 'icon_selection',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
						 )
					) );
						
					// bbPress Recent Replies
					vc_map( array( 
						'name' => esc_html__( 'Recent Replies', 'socialize-plugin' ),
						'base' => 'bbp_recent_replies',
						'description' => esc_html__( 'A list of the most recent replies.', 'socialize-plugin' ),
						'class' => 'wpb_vc_bbp_recent_replies',
						'controls' => 'full',
						'icon' => 'gp-icon-bbp-recent-replies',
						'category' => esc_html__( 'BuddyPress', 'socialize-plugin' ),
						'params' => array(			
							array( 
							'heading' => esc_html__( 'Title', 'socialize-plugin' ),
							'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
							'param_name' => 'title',
							'type' => 'textfield',
							'admin_label' => true,
							'value' => esc_html__( 'Recent Replies', 'socialize-plugin' ),
							),				
							array( 
							'heading' => esc_html__( 'Maximum Replies', 'socialize-plugin' ),
							'description' => esc_html__( 'The maximum number of replies to show.', 'socialize-plugin' ),
							'param_name' => 'max_shown',
							'type' => 'textfield',
							'value' => 5,
							),
							array(
							'heading' => esc_html__( 'Post Date', 'socialize-plugin' ),
							'param_name' => 'show_date',
							'value' => array( esc_html__( 'Show post date.', 'socialize-plugin' ) => '1' ),
							'type' => 'checkbox',
							),	
							array(
							'heading' => esc_html__( 'Author', 'socialize-plugin' ),
							'param_name' => 'show_user',
							'value' => array( esc_html__( 'Show reply author.', 'socialize-plugin' ) => '1' ),
							'type' => 'checkbox',
							),	
							array( 
							'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
							'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
							'param_name' => 'classes',
							'value' => '',
							'type' => 'textfield',
							),		
							array( 
							'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
							'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
							'param_name' => 'title_format',
							'value' => array( esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title', esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title' ),
							'type' => 'dropdown',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
							array( 
							'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
							'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
							'param_name' => 'title_color',
							'value' => '',
							'type' => 'colorpicker',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),	
							array( 
							'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
							'param_name' => 'icon',
							'value' => $icons,
							'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
							'type' => 'icon_selection',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
						 )
					) );
						
					// bbPress Recent Topics
					vc_map( array( 
						'name' => esc_html__( 'Recent Topics', 'socialize-plugin' ),
						'base' => 'bbp_recent_topics',
						'description' => esc_html__( 'A list of recent topics, sorted by popularity or freshness.', 'socialize-plugin' ),
						'class' => 'wpb_vc_bbp_recent_topics',
						'controls' => 'full',
						'icon' => 'gp-icon-bbp-recent-topics',
						'category' => esc_html__( 'BuddyPress', 'socialize-plugin' ),
						'params' => array(			
							array( 
							'heading' => esc_html__( 'Title', 'socialize-plugin' ),
							'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
							'param_name' => 'title',
							'type' => 'textfield',
							'admin_label' => true,
							'value' => esc_html__( 'Recent Topics', 'socialize-plugin' ),
							),				
							array( 
							'heading' => esc_html__( 'Maximum Topics', 'socialize-plugin' ),
							'description' => esc_html__( 'The maximum number of topics to show.', 'socialize-plugin' ),
							'param_name' => 'max_shown',
							'type' => 'textfield',
							'value' => 5,
							),			
							array( 
							'heading' => esc_html__( 'Parent Forum ID', 'socialize-plugin' ),
							'description' => esc_html__( '"0" to show only root - "any" to show all.', 'socialize-plugin' ),
							'param_name' => 'parent_forum',
							'type' => 'textfield',
							'value' => 'any',
							),	
							array(
							'heading' => esc_html__( 'Post Date', 'socialize-plugin' ),
							'param_name' => 'show_date',
							'value' => array( esc_html__( 'Show post date.', 'socialize-plugin' ) => '1' ),
							'type' => 'checkbox',
							),	
							array(
							'heading' => esc_html__( 'Author', 'socialize-plugin' ),
							'param_name' => 'show_user',
							'value' => array( esc_html__( 'Show reply author.', 'socialize-plugin' ) => '1' ),
							'type' => 'checkbox',
							),				
							array( 
							'heading' => esc_html__( 'Order By', 'socialize-plugin' ),
							'description' => esc_html__( 'The criteria which the topics are ordered by.', 'socialize-plugin' ),
							'param_name' => 'order_by',
							'value' => array(
								esc_html__( 'Newest Topics', 'socialize-plugin' ) => 'newness',
								esc_html__( 'Popular Topics', 'socialize-plugin' ) => 'popular',
								esc_html__( 'Topics With Recent Replies', 'socialize-plugin' ) => 'freshness',
							),
							'type' => 'dropdown',
							),
							array( 
							'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
							'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
							'param_name' => 'classes',
							'value' => '',
							'type' => 'textfield',
							),		
							array( 
							'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
							'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
							'param_name' => 'title_format',
							'value' => array( esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title', esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title' ),
							'type' => 'dropdown',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
							array( 
							'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
							'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
							'param_name' => 'title_color',
							'value' => '',
							'type' => 'colorpicker',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),	
							array( 
							'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
							'param_name' => 'icon',
							'value' => $icons,
							'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
							'type' => 'icon_selection',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
						 )
					) );
						
					// bbPress Statistics
					vc_map( array( 
						'name' => esc_html__( 'bbPress Statistics', 'socialize-plugin' ),
						'base' => 'bbp_statistics',
						'description' => esc_html__( 'Some statistics from your forum.', 'socialize-plugin' ),
						'class' => 'wpb_vc_bbp_statistics',
						'controls' => 'full',
						'icon' => 'gp-icon-bbp-statistics',
						'category' => esc_html__( 'BuddyPress', 'socialize-plugin' ),
						'params' => array(			
							array( 
							'heading' => esc_html__( 'Title', 'socialize-plugin' ),
							'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
							'param_name' => 'title',
							'type' => 'textfield',
							'admin_label' => true,
							'value' => esc_html__( 'Forum Statistics', 'socialize-plugin' ),
							),
							array( 
							'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
							'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
							'param_name' => 'classes',
							'value' => '',
							'type' => 'textfield',
							),		
							array( 
							'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
							'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
							'param_name' => 'title_format',
							'value' => array( esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title', esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title' ),
							'type' => 'dropdown',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
							array( 
							'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
							'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
							'param_name' => 'title_color',
							'value' => '',
							'type' => 'colorpicker',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),	
							array( 
							'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
							'param_name' => 'icon',
							'value' => $icons,
							'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
							'type' => 'icon_selection',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
						 )
					) );
						
				}
		
										
				/*--------------------------------------------------------------
				Carousel Shortcode
				--------------------------------------------------------------*/

				require_once( sprintf( "%s/gp_vc_carousel.php", dirname( __FILE__ ) ) );
		
				vc_map( array( 
					'name' => esc_html__( 'Carousel', 'socialize-plugin' ),
					'base' => 'carousel',
					'description' => esc_html__( 'Display a carousel.', 'socialize-plugin' ),
					'class' => 'wpb_vc_carousel',
					'controls' => 'full',
					'icon' => 'gp-icon-carousel',
					'category' => esc_html__( 'Content', 'socialize-plugin' ),
					'front_enqueue_js' => $flexslider_js,
					'params' => array(				
						array( 
						'heading' => esc_html__( 'Title', 'socialize-plugin' ),
						'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
						'param_name' => 'widget_title',
						'type' => 'textfield',
						'admin_label' => true,
						'value' => '',
						),		
						array( 
						'heading' => esc_html__( 'Categories', 'socialize-plugin' ),
						'description' => esc_html__( 'Enter the slugs or IDs separating each one with a comma e.g. xbox,ps3,pc.', 'socialize-plugin' ),
						'param_name' => 'cats',
						'type' => 'textfield',
						),					
						array( 
						'heading' => esc_html__( 'Page IDs', 'socialize-plugin' ),
						'description' => esc_html__( 'Enter the page IDs.', 'socialize-plugin' ),
						'param_name' => 'page_ids',
						'type' => 'textfield',
						),		
						array( 
						'heading' => esc_html__( 'Post Types', 'socialize-plugin' ),
						'description' => esc_html__( 'The post types to display.', 'socialize-plugin' ),
						'param_name' => 'post_types',
						'type' => 'posttypes',
						'value' => 'post',
						),			
						array( 
						'heading' => esc_html__( 'Order By', 'socialize-plugin' ),
						'description' => esc_html__( 'The criteria which the items are ordered by.', 'socialize-plugin' ),
						'param_name' => 'orderby',
						'value' => array(
							esc_html__( 'Newest', 'socialize-plugin' ) => 'newest',
							esc_html__( 'Oldest', 'socialize-plugin' ) => 'oldest',
							esc_html__( 'Title (A-Z)', 'socialize-plugin' ) => 'title_az',
							esc_html__( 'Title (Z-A)', 'socialize-plugin' ) => 'title_za',
							esc_html__( 'Most Comments', 'socialize-plugin' ) => 'comment_count',
							esc_html__( 'Most Views', 'socialize-plugin' ) => 'views',
							esc_html__( 'Menu Order', 'socialize-plugin' ) => 'menu_order',
							esc_html__( 'Random', 'socialize-plugin' ) => 'rand',
						),
						'type' => 'dropdown',
						),	
						array( 
						'heading' => esc_html__( 'Date Posted', 'socialize-plugin' ),
						'description' => esc_html__( 'The date the items were posted.', 'socialize-plugin' ),
						'param_name' => 'date_posted',
						'value' => array(
							esc_html__( 'Any date', 'socialize-plugin' ) => 'all',
							esc_html__( 'In the last year', 'socialize-plugin' ) => 'year',
							esc_html__( 'In the last month', 'socialize-plugin' ) => 'month',
							esc_html__( 'In the last week', 'socialize-plugin' ) => 'week',
							esc_html__( 'In the last day', 'socialize-plugin' ) => 'day',
						),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Date Modified', 'socialize-plugin' ),
						'description' => esc_html__( 'The date the items were modified.', 'socialize-plugin' ),
						'param_name' => 'date_modified',
						'value' => array(
							esc_html__( 'Any date', 'socialize-plugin' ) => 'all',
							esc_html__( 'In the last year', 'socialize-plugin' ) => 'year',
							esc_html__( 'In the last month', 'socialize-plugin' ) => 'month',
							esc_html__( 'In the last week', 'socialize-plugin' ) => 'week',
							esc_html__( 'In the last day', 'socialize-plugin' ) => 'day',
						),
						'type' => 'dropdown',
						),	
						array( 
						'heading' => esc_html__( 'Items In View', 'socialize-plugin' ),
						'description' => esc_html__( 'The number of items in view at one time.', 'socialize-plugin' ),
						'param_name' => 'items_in_view',
						'value' => '3',
						'type' => 'textfield',
						),								 
						array( 
						'heading' => esc_html__( 'Total Items', 'socialize-plugin' ),
						'description' => esc_html__( 'The total number of items.', 'socialize-plugin' ),
						'param_name' => 'per_page',
						'value' => '12',
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'Offset', 'socialize-plugin' ),
						'description' => esc_html__( 'The number of posts to offset by e.g. set to 3 to exclude the first 3 posts.', 'socialize-plugin' ),
						'param_name' => 'offset',
						'value' => '',
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'Image Width', 'socialize-plugin' ),
						'description' => esc_html__( 'The width of the featured images.', 'socialize-plugin' ),
						'param_name' => 'image_width',
						'value' => '350',
						'type' => 'textfield',
						),		 
						array( 
						'heading' => esc_html__( 'Image Height', 'socialize-plugin' ),
						'description' => esc_html__( 'The height of the featured images.', 'socialize-plugin' ),
						'param_name' => 'image_height',
						'value' => '220',
						'type' => 'textfield',
						),	
						array( 
						'heading' => esc_html__( 'Hard Crop', 'socialize-plugin' ),
						'description' => esc_html__( 'Images are cropped even if it is smaller than the dimensions you want to crop it to.', 'socialize-plugin' ),
						'param_name' => 'hard_crop',
						'value' => array( esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled', esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled' ),
						'type' => 'dropdown',
						),					
						array( 
						'heading' => esc_html__( 'Carousel Speed', 'socialize-plugin' ),
						'description' => esc_html__( 'The number of seconds before the carousel goes to the next set of items.', 'socialize-plugin' ),
						'param_name' => 'slider_speed',
						'value' => '0',
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'Animation Speed', 'socialize-plugin' ),
						'description' => esc_html__( 'The speed of the carousel animation in seconds.', 'socialize-plugin' ),
						'param_name' => 'animation_speed',
						'value' => '0.6',
						'type' => 'textfield',		
						),	
						array( 
						'heading' => esc_html__( 'Navigation Buttons', 'socialize-plugin' ),
						'description' => esc_html__( 'Display the carousel navigation buttons.', 'socialize-plugin' ),
						'param_name' => 'buttons',
						'value' => array(
							esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled',
							esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled',
						),
						'type' => 'dropdown',
						),					
						array( 
						'heading' => esc_html__( 'Navigation Arrows', 'socialize-plugin' ),
						'description' => esc_html__( 'Display the carousel navigation arrows.', 'socialize-plugin' ),
						'param_name' => 'arrows',
						'value' => array(
							esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled',
							esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled',
						),
						'type' => 'dropdown',
						),				
						array( 
						'heading' => esc_html__( 'See All', 'socialize-plugin' ),
						'description' => esc_html__( 'Add a "See All" link.', 'socialize-plugin' ),
						'param_name' => 'see_all',
						'value' => array( esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled', esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled' ),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'See All Link', 'socialize-plugin' ),
						'description' => esc_html__( 'URL for the "See All" link.', 'socialize-plugin' ),
						'param_name' => 'see_all_link',
						'type' => 'textfield',
						'dependency' => array( 'element' => 'see_all', 'value' => 'enabled' ),
						),				 			 
						array( 
						'heading' => esc_html__( 'See All Text', 'socialize-plugin' ),
						'description' => esc_html__( 'Custom text for the "See All" link.', 'socialize-plugin' ),
						'param_name' => 'see_all_text',
						'type' => 'textfield',
						'value' => esc_html__( 'See All Items', 'socialize-plugin' ),
						'dependency' => array( 'element' => 'see_all', 'value' => 'enabled' ),
						),	 			 				 		   			 			 
						array( 
						'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
						'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
						'param_name' => 'classes',
						'value' => '',
						'type' => 'textfield',
						),		
						array( 
						'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
						'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
						'param_name' => 'title_format',
						'value' => array( esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title', esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title' ),
						'type' => 'dropdown',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),
						array( 
						'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
						'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
						'param_name' => 'title_color',
						'value' => '',
						'type' => 'colorpicker',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),		
						array( 
						'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
						'param_name' => 'icon',
						'value' => $icons,
						'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
						'type' => 'icon_selection',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),																																																						
					 )
				) );

					
				/*--------------------------------------------------------------
				Events List Shortcode
				--------------------------------------------------------------*/		

				if ( class_exists( 'Tribe__Events__Main' ) ) {

					require_once( sprintf( "%s/gp_vc_events.php", dirname( __FILE__ ) ) );

					vc_map( array( 
						'name' => esc_html__( 'Events List', 'socialize-plugin' ),
						'base' => 'events_list',
						'description' => esc_html__( 'A widget that displays upcoming events.', 'socialize-plugin' ),
						'class' => 'wpb_vc_events_list',
						'controls' => 'full',
						'icon' => 'gp-icon-events-list',
						'category' => esc_html__( 'Content', 'socialize-plugin' ),
						'params' => array(				

							array( 
							'heading' => esc_html__( 'Title', 'socialize-plugin' ),
							'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
							'param_name' => 'title',
							'type' => 'textfield',
							'admin_label' => true,
							'value' => '',
							),
							array( 
							'heading' => esc_html__( 'Number of Events', 'socialize-plugin' ),
							'description' => esc_html__( 'Number of events to show.', 'socialize-plugin' ),
							'param_name' => 'limit',
							'type' => 'textfield',
							'value' => '5',
							),
							array( 
							'heading' => esc_html__( 'Show', 'socialize-plugin' ),
							'param_name' => 'no_upcoming_events',
							'type' => 'checkbox',
							'value' => array( esc_html__( 'Show widget only if there are upcoming events', 'socialize-plugin' ) => '1' ),
							),
							array( 
							'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
							'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
							'param_name' => 'classes',
							'value' => '',
							'type' => 'textfield',
							),		
							array( 
							'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
							'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
							'param_name' => 'title_format',
							'value' => array( esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title', esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title' ),
							'type' => 'dropdown',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
							array( 
							'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
							'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
							'param_name' => 'title_color',
							'value' => '',
							'type' => 'colorpicker',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),	
							array( 
							'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
							'param_name' => 'icon',
							'value' => $icons,
							'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
							'type' => 'icon_selection',
							'group' => esc_html__( 'Design options', 'socialize-plugin' ),
							),
						 )
					) );	
		
				}
				
							
				/*--------------------------------------------------------------
				Login/Register Shortcode
				--------------------------------------------------------------*/

				require_once( sprintf( "%s/gp_vc_login.php", dirname( __FILE__ ) ) );
		
				vc_map( array( 
					'name' => esc_html__( 'Login/Register Form', 'socialize-plugin' ),
					'base' => 'login',
					'description' => esc_html__( 'Add a login and register form.', 'socialize-plugin' ),
					'class' => 'wpb_vc_login',
					'controls' => 'full',
					'icon' => 'gp-icon-login',
					'category' => esc_html__( 'Content', 'socialize-plugin' ),
					'params' => array(				
						array( 
						'heading' => esc_html__( 'Title', 'socialize-plugin' ),
						'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
						'param_name' => 'widget_title',
						'type' => 'textfield',
						'admin_label' => true,
						'value' => '',
						),				
						array( 
						'heading' => esc_html__( 'Default View', 'socialize-plugin' ),
						'description' => esc_html__( 'Choose whether the login or register form is shown by default.', 'socialize-plugin' ),
						'param_name' => 'default_view',
						'value' => array( esc_html__( 'Login Form', 'socialize-plugin' ) => 'gp-default-view-login', esc_html__( 'Registration Form', 'socialize-plugin' ) => 'gp-default-view-register' ),
						'type' => 'dropdown',
						),							
						array( 
						'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
						'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
						'param_name' => 'title_format',
						'value' => array( esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title', esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title' ),
						'type' => 'dropdown',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),
						array( 
						'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
						'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
						'param_name' => 'title_color',
						'value' => '',
						'type' => 'colorpicker',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),
						array( 
						'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
						'param_name' => 'icon',
						'value' => $icons,
						'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
						'type' => 'icon_selection',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),																																								
					 )
				) );
		
										
				/*--------------------------------------------------------------
				Portfolio Shortcode
				--------------------------------------------------------------*/

				require_once( sprintf( "%s/gp_vc_portfolio.php", dirname( __FILE__ ) ) );
		
				vc_map( array( 
					'name' => esc_html__( 'Portfolio', 'socialize-plugin' ),
					'base' => 'portfolio',
					'description' => esc_html__( 'Display your portfolio items in a variety of ways.', 'socialize-plugin' ),
					'class' => 'wpb_vc_portfolio',
					'controls' => 'full',
					'icon' => 'gp-icon-portfolio',
					'category' => esc_html__( 'Content', 'socialize-plugin' ),
					'front_enqueue_js' => $masonry_js,
					'params' => array( 						
						array( 
						'heading' => esc_html__( 'Title', 'socialize-plugin' ),
						'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
						'param_name' => 'widget_title',
						'type' => 'textfield',
						'admin_label' => true,
						'value' => '',
						),	
						array( 
						'heading' => esc_html__( 'Format', 'socialize-plugin' ),
						'description' => esc_html__( 'The format to display the items in.', 'socialize-plugin' ),
						'param_name' => 'format',
						'value' => array( esc_html__( '2 Columns', 'socialize-plugin' ) => 'gp-portfolio-columns-2', esc_html__( '3 Columns', 'socialize-plugin' ) => 'gp-portfolio-columns-3', esc_html__( '4 Columns', 'socialize-plugin' ) => 'gp-portfolio-columns-4', esc_html__( '5 Columns', 'socialize-plugin' ) => 'gp-portfolio-columns-5', esc_html__( '6 Columns', 'socialize-plugin' ) => 'gp-portfolio-columns-6', esc_html__( 'Masonry', 'socialize-plugin' ) => 'gp-portfolio-masonry' ),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Categories', 'socialize-plugin' ),
						'description' => esc_html__( 'Enter the slugs or IDs separating each one with a comma e.g. xbox,ps3,pc.', 'socialize-plugin' ),
						'param_name' => 'cats',
						'type' => 'textfield',
						),	 
						array( 
						'heading' => esc_html__( 'Order By', 'socialize-plugin' ),
						'description' => esc_html__( 'The criteria which the items are ordered by.', 'socialize-plugin' ),
						'param_name' => 'orderby',
						'value' => array(
							esc_html__( 'Newest', 'socialize-plugin' ) => 'newest',
							esc_html__( 'Oldest', 'socialize-plugin' ) => 'oldest',
							esc_html__( 'Title (A-Z)', 'socialize-plugin' ) => 'title_az',
							esc_html__( 'Title (Z-A)', 'socialize-plugin' ) => 'title_za',
							esc_html__( 'Most Comments', 'socialize-plugin' ) => 'comment_count',
							esc_html__( 'Most Views', 'socialize-plugin' ) => 'views',
							esc_html__( 'Menu Order', 'socialize-plugin' ) => 'menu_order',
							esc_html__( 'Random', 'socialize-plugin' ) => 'rand',
						),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Date Posted', 'socialize-plugin' ),
						'description' => esc_html__( 'The date the items were posted.', 'socialize-plugin' ),
						'param_name' => 'date_posted',
						'value' => array(
							esc_html__( 'Any date', 'socialize-plugin' ) => 'all',
							esc_html__( 'In the last year', 'socialize-plugin' ) => 'year',
							esc_html__( 'In the last month', 'socialize-plugin' ) => 'month',
							esc_html__( 'In the last week', 'socialize-plugin' ) => 'week',
							esc_html__( 'In the last day', 'socialize-plugin' ) => 'day',
						),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Date Modified', 'socialize-plugin' ),
						'description' => esc_html__( 'The date the items were modified.', 'socialize-plugin' ),
						'param_name' => 'date_modified',
						'value' => array(
							esc_html__( 'Any date', 'socialize-plugin' ) => 'all',
							esc_html__( 'In the last year', 'socialize-plugin' ) => 'year',
							esc_html__( 'In the last month', 'socialize-plugin' ) => 'month',
							esc_html__( 'In the last week', 'socialize-plugin' ) => 'week',
							esc_html__( 'In the last day', 'socialize-plugin' ) => 'day',
						),
						'type' => 'dropdown',
						),			
						array( 
						'heading' => esc_html__( 'Filter', 'socialize-plugin' ),
						'description' => esc_html__( 'Add category filter links to the page.', 'socialize-plugin' ),
						'param_name' => 'filter',
						'value' => array( esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled', esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled' ),
						'type' => 'dropdown',
						),			 
						array( 
						'heading' => esc_html__( 'Items Per Page', 'socialize-plugin' ),
						'description' => esc_html__( 'The number of items on each page.', 'socialize-plugin' ),
						'param_name' => 'per_page',
						'value' => '12',
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'Offset', 'socialize-plugin' ),
						'description' => esc_html__( 'The number of posts to offset by e.g. set to 3 to exclude the first 3 posts.', 'socialize-plugin' ),
						'param_name' => 'offset',
						'value' => '',
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'Pagination', 'socialize-plugin' ),
						'description' => esc_html__( 'Add pagination.', 'socialize-plugin' ),
						'param_name' => 'page_numbers',
						'value' => array( esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled', esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled' ),
						'type' => 'dropdown',
						),		 		 				 		   			 			 
						array( 
						'heading' => esc_html__( 'See All', 'socialize-plugin' ),
						'description' => esc_html__( 'Add a "See All" link.', 'socialize-plugin' ),
						'param_name' => 'see_all',
						'value' => array( esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled', esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled' ),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'See All Link', 'socialize-plugin' ),
						'description' => esc_html__( 'URL for the "See All" link.', 'socialize-plugin' ),
						'param_name' => 'see_all_link',
						'type' => 'textfield',
						'dependency' => array( 'element' => 'see_all', 'value' => 'enabled' ),
						),				 			 
						array( 
						'heading' => esc_html__( 'See All Text', 'socialize-plugin' ),
						'description' => esc_html__( 'Custom text for the "See All" link.', 'socialize-plugin' ),
						'param_name' => 'see_all_text',
						'type' => 'textfield',
						'value' => esc_html__( 'See All Items', 'socialize-plugin' ),
						'dependency' => array( 'element' => 'see_all', 'value' => 'enabled' ),
						),	 			 				 		   			 			 
						array( 
						'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
						'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
						'param_name' => 'classes',
						'value' => '',
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
						'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
						'param_name' => 'title_format',
						'value' => array( esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title', esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title' ),
						'type' => 'dropdown',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),
						array( 
						'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
						'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
						'param_name' => 'title_color',
						'value' => '',
						'type' => 'colorpicker',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),	
						array( 
						'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
						'param_name' => 'icon',
						'value' => $icons,
						'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
						'type' => 'icon_selection',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),																																																						
					 )
				) );


				/*--------------------------------------------------------------
				Pricing Table Shortcode
				--------------------------------------------------------------*/

				// Pricing Table
				vc_map( array( 
					'name' => esc_html__( 'Pricing Table', 'socialize-plugin' ),
					'base' => 'pricing_table',
					'description' => esc_html__( 'A table to compare the prices of different items.', 'socialize-plugin' ),
					'as_parent' => array( 'only' => 'pricing_column' ),
					'controls' => 'full',
					'icon' => 'gp-icon-pricing-table',
					'category' => esc_html__( 'Content', 'socialize-plugin' ),
					'js_view' => 'VcColumnView',
					'params' => array( 
						array( 
						'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
						'param_name' => 'classes',
						'value' => '',
						'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
						'type' => 'textfield',
						),	
					),
				 ) );


				// Pricing Column
				vc_map( array( 
					'name' => esc_html__( 'Pricing Column', 'socialize-plugin' ),
					'base' => 'pricing_column',
					'content_element' => true,
					'as_child' => array( 'only' => 'pricing_table' ),
					'icon' => 'gp-icon-pricing-table',
					'params' => array( 	
						array( 
						'heading' => esc_html__( 'Column Title', 'socialize-plugin' ),
						'description' => esc_html__( 'The title for the column.', 'socialize-plugin' ),
						'param_name' => 'title',
						'value' => '',
						'type' => 'textfield'
						),
						array( 
						'heading' => esc_html__( 'Price', 'socialize-plugin' ),
						'description' => esc_html__( 'The price for the column.', 'socialize-plugin' ),
						'param_name' => 'price',
						'value' => '',
						'type' => 'textfield'
						),
						array( 
						'heading' => esc_html__( 'Currency Symbol', 'socialize-plugin' ),
						'description' => esc_html__( 'The currency symbol.', 'socialize-plugin' ),
						'param_name' => 'currency_symbol',
						'value' => '',
						'type' => 'textfield',
						),		
						array( 
						'heading' => esc_html__( 'Interval', 'socialize-plugin' ),
						'description' => esc_html__( 'The interval for the column e.g. per week, per month.', 'socialize-plugin' ),
						'param_name' => 'interval',
						'value' => '',
						'type' => 'textfield',
						),		
						array( 
						'heading' => esc_html__( 'Highlight Column', 'socialize-plugin' ),
						'description' => esc_html__( 'Make this column stand out.', 'socialize-plugin' ),
						'param_name' => 'highlight',
						'value' => array( esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled', esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled' ),
						'type' => 'dropdown'
						),	
						array( 
						'heading' => esc_html__( 'Highlight Text', 'socialize-plugin' ),
						'description' => esc_html__( 'Add highlight text above the column title.', 'socialize-plugin' ),
						'param_name' => 'highlight_text',
						'value' => '',
						'dependency' => array( 'element' => 'highlight', 'value' => 'enabled' ),
						'type' => 'textfield',
						),	
						array( 
						'heading' => esc_html__( 'Content', 'socialize-plugin' ),
						'description' => esc_html__( 'Use the Unordered List button to create the points in your pricing column. You can also add shortcodes such as the [button link="#"] shortcode seen in the site demo.', 'socialize-plugin' ),
						'param_name' => 'content',
						'type' => 'textarea_html',
						),
						array( 
						'heading' => esc_html__( 'Highlight Color', 'socialize-plugin' ),
						'description' => esc_html__( 'The highlight color.', 'socialize-plugin' ),
						'param_name' => 'highlight_color',
						'value' => '#f84103',
						'dependency' => array( 'element' => 'highlight', 'value' => 'enabled' ),
						'type' => 'colorpicker',
						),		
						array( 
						'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
						'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
						'param_name' => 'title_color',
						'value' => '#f84103',
						'dependency' => array( 'element' => 'highlight', 'value' => 'disabled' ),
						'type' => 'colorpicker',
						),	
						array( 
						'heading' => esc_html__( 'Highlight Title Color', 'socialize-plugin' ),
						'description' => esc_html__( 'The highlight title color.', 'socialize-plugin' ),
						'param_name' => 'highlight_title_color',
						'value' => '#fff',
						'dependency' => array( 'element' => 'highlight', 'value' => 'enabled' ),
						'type' => 'colorpicker',
						),	
						array( 
						'heading' => esc_html__( 'Background Color', 'socialize-plugin' ),
						'description' => esc_html__( 'The background color.', 'socialize-plugin' ),
						'param_name' => 'background_color',
						'value' => '#f7f7f7',
						'dependency' => array( 'element' => 'highlight', 'value' => 'disabled' ),
						'type' => 'colorpicker',
						),		 
						array( 
						'heading' => esc_html__( 'Highlight Background Color', 'socialize-plugin' ),
						'description' => esc_html__( 'The highlight background color.', 'socialize-plugin' ),
						'param_name' => 'highlight_background_color',
						'value' => '#fff',
						'dependency' => array( 'element' => 'highlight', 'value' => 'enabled' ),
						'type' => 'colorpicker',
						),		 		 		 
						array( 
						'heading' => esc_html__( 'Text Color', 'socialize-plugin' ),
						'description' => esc_html__( 'The text color.', 'socialize-plugin' ),
						'param_name' => 'text_color',
						'value' => '#747474',
						'type' => 'colorpicker',
						),	
						array( 
						'heading' => esc_html__( 'Border', 'socialize-plugin' ),
						'description' => esc_html__( 'Add a border around the columns.', 'socialize-plugin' ),
						'param_name' => 'border',
						'value' => array( esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled', esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled' ),
						'type' => 'dropdown',
						),			 
						array( 
						'heading' => esc_html__( 'Border Color', 'socialize-plugin' ),
						'description' => esc_html__( 'The border color.', 'socialize-plugin' ),
						'param_name' => 'border_color',
						'value' => '#e7e7e7',
						'dependency' => array( 'element' => 'border', 'value' => 'enabled' ),
						'type' => 'colorpicker',
						),	 		 																																							
					 )
				 ) );


				/*--------------------------------------------------------------
				Showcase Shortcode
				--------------------------------------------------------------*/

				require_once( sprintf( "%s/gp_vc_showcase.php", dirname( __FILE__ ) ) );

				vc_map( array( 
					'name' => esc_html__( 'Showcase', 'socialize-plugin' ),
					'base' => 'showcase',
					'description' => esc_html__( 'Display your content in horizontal and vertical formats.', 'socialize-plugin' ),
					'class' => 'wpb_vc_showcase',
					'controls' => 'full',
					'icon' => 'gp-icon-showcase',
					'category' => esc_html__( 'Content', 'socialize-plugin' ),			
					'admin_enqueue_css' => $admin_css,
					'front_enqueue_css' => $admin_css,
					'params' => array(		
						array( 
						'heading' => esc_html__( 'Title', 'socialize-plugin' ),
						'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
						'param_name' => 'widget_title',
						'type' => 'textfield',
						'admin_label' => true,
						'value' => '',
						),		 									
						array( 
						'heading' => esc_html__( 'Categories', 'socialize-plugin' ),
						'description' => esc_html__( 'Enter the slugs or IDs separating each one with a comma e.g. xbox,ps3,pc.', 'socialize-plugin' ),
						'param_name' => 'cats',
						'type' => 'textfield',
						),					
						array( 
						'heading' => esc_html__( 'Page IDs', 'socialize-plugin' ),
						'description' => esc_html__( 'Enter the page IDs.', 'socialize-plugin' ),
						'param_name' => 'page_ids',
						'type' => 'textfield',
						),		
						array( 
						'heading' => esc_html__( 'Post Types', 'socialize-plugin' ),
						'description' => esc_html__( 'The post types to display.', 'socialize-plugin' ),
						'param_name' => 'post_types',
						'type' => 'posttypes',
						'value' => 'post',
						),			
						array( 
						'heading' => esc_html__( 'Format', 'socialize-plugin' ),
						'description' => esc_html__( 'The format to display the items in.', 'socialize-plugin' ),
						'param_name' => 'format',
						'value' => array( esc_html__( 'Horizontal Showcase', 'socialize-plugin' ) => 'gp-blog-horizontal', esc_html__( 'Vertical Showcase', 'socialize-plugin' ) => 'gp-blog-vertical' ),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Order By', 'socialize-plugin' ),
						'description' => esc_html__( 'The criteria which the items are ordered by.', 'socialize-plugin' ),
						'param_name' => 'orderby',
						'value' => array(
							esc_html__( 'Newest', 'socialize-plugin' ) => 'newest',
							esc_html__( 'Oldest', 'socialize-plugin' ) => 'oldest',
							esc_html__( 'Title (A-Z)', 'socialize-plugin' ) => 'title_az',
							esc_html__( 'Title (Z-A)', 'socialize-plugin' ) => 'title_za',
							esc_html__( 'Most Comments', 'socialize-plugin' ) => 'comment_count',
							esc_html__( 'Most Views', 'socialize-plugin' ) => 'views',
							esc_html__( 'Menu Order', 'socialize-plugin' ) => 'menu_order',
							esc_html__( 'Random', 'socialize-plugin' ) => 'rand',
						),
						'type' => 'dropdown',
						),	
						array( 
						'heading' => esc_html__( 'Date Posted', 'socialize-plugin' ),
						'description' => esc_html__( 'The date the items were posted.', 'socialize-plugin' ),
						'param_name' => 'date_posted',
						'value' => array(
							esc_html__( 'Any date', 'socialize-plugin' ) => 'all',
							esc_html__( 'In the last year', 'socialize-plugin' ) => 'year',
							esc_html__( 'In the last month', 'socialize-plugin' ) => 'month',
							esc_html__( 'In the last week', 'socialize-plugin' ) => 'week',
							esc_html__( 'In the last day', 'socialize-plugin' ) => 'day',
						),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Date Modified', 'socialize-plugin' ),
						'description' => esc_html__( 'The date the items were modified.', 'socialize-plugin' ),
						'param_name' => 'date_modified',
						'value' => array(
							esc_html__( 'Any date', 'socialize-plugin' ) => 'all',
							esc_html__( 'In the last year', 'socialize-plugin' ) => 'year',
							esc_html__( 'In the last month', 'socialize-plugin' ) => 'month',
							esc_html__( 'In the last week', 'socialize-plugin' ) => 'week',
							esc_html__( 'In the last day', 'socialize-plugin' ) => 'day',
						),
						'type' => 'dropdown',
						),	
						array( 
						'heading' => esc_html__( 'Filter', 'socialize-plugin' ),
						'description' => esc_html__( 'Add a dropdown filter menu to the page.', 'socialize-plugin' ),
						'param_name' => 'filter',
						'value' => array( esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled', esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled' ),
						'type' => 'dropdown',
						),	
						array(
						'heading' => esc_html__( 'Filter Options', 'socialize-plugin' ),
						'param_name' => 'filter_cats',
						'value' => array( esc_html__( 'Categories', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						'dependency' => array( 'element' => 'filter', 'value' => 'enabled' ),
						),	
						array(
						'param_name' => 'filter_date',
						'value' => array( esc_html__( 'Date', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						'dependency' => array( 'element' => 'filter', 'value' => 'enabled' ),
						),	
						array(
						'param_name' => 'filter_title',
						'value' => array( esc_html__( 'Title', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						'dependency' => array( 'element' => 'filter', 'value' => 'enabled' ),
						),								
						array(
						'param_name' => 'filter_comment_count',
						'value' => array( esc_html__( 'Comment Count', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						'dependency' => array( 'element' => 'filter', 'value' => 'enabled' ),
						),
						array(
						'param_name' => 'filter_views',
						'value' => array( esc_html__( 'Views', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						'dependency' => array( 'element' => 'filter', 'value' => 'enabled' ),
						),	
						array( 
						'param_name' => 'filter_date_posted',
						'value' => array( esc_html__( 'Date Posted', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						'dependency' => array( 'element' => 'filter', 'value' => 'enabled' ),
						),				
						array( 
						'description' => esc_html__( 'Choose what options to display in the dropdown filter menu.', 'socialize-plugin' ),
						'param_name' => 'filter_date_modified',
						'value' => array( esc_html__( 'Date Modified', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						'dependency' => array( 'element' => 'filter', 'value' => 'enabled' ),
						),				
						array( 
						'heading' => esc_html__( 'Filter Category', 'socialize-plugin' ),
						'description' => esc_html__( 'Enter the slug or ID number of the category you want to filter by, leave blank to display all categories - the sub categories of this category will also be displayed.', 'socialize-plugin' ),
						'param_name' => 'filter_cats_id',
						'type' => 'textfield',
						'dependency' => array( 'element' => 'filter', 'value' => 'enabled' ),
						),																	 
						array( 
						'heading' => esc_html__( 'Items Per Page', 'socialize-plugin' ),
						'description' => esc_html__( 'The number of items on each page.', 'socialize-plugin' ),
						'param_name' => 'per_page',
						'value' => '5',
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'Offset', 'socialize-plugin' ),
						'description' => esc_html__( 'The number of posts to offset by e.g. set to 3 to exclude the first 3 posts.', 'socialize-plugin' ),
						'param_name' => 'offset',
						'value' => '',
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'Large Featured Image', 'socialize-plugin' ),
						'description' => esc_html__( 'Display the large featured image.', 'socialize-plugin' ),
						'param_name' => 'large_featured_image',
						'value' => array( esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled', esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled' ),
						'type' => 'dropdown',
						),					
						array( 
						'heading' => esc_html__( 'Large Image Width', 'socialize-plugin' ),
						'description' => esc_html__( 'The width of the large featured image.', 'socialize-plugin' ),
						'param_name' => 'large_image_width',
						'value' => '350',
						'type' => 'textfield',
						'dependency' => array( 'element' => 'large_featured_image', 'value' => 'enabled' ),
						),		 
						array( 
						'heading' => esc_html__( 'Large Image Height', 'socialize-plugin' ),
						'description' => esc_html__( 'The height of the large featured image.', 'socialize-plugin' ),
						'param_name' => 'large_image_height',
						'value' => '220',
						'type' => 'textfield',
						'dependency' => array( 'element' => 'large_featured_image', 'value' => 'enabled' ),
						),
						array( 
						'heading' => esc_html__( 'Small Featured Image', 'socialize-plugin' ),
						'description' => esc_html__( 'Display the small featured image.', 'socialize-plugin' ),
						'param_name' => 'small_featured_image',
						'value' => array( esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled', esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled' ),
						'type' => 'dropdown',
						),				
						array( 
						'heading' => esc_html__( 'Small Image Width', 'socialize-plugin' ),
						'description' => esc_html__( 'The width of the small featured images.', 'socialize-plugin' ),
						'param_name' => 'small_image_width',
						'value' => '100',
						'type' => 'textfield',
						'dependency' => array( 'element' => 'small_featured_image', 'value' => 'enabled' ),
						),		 
						array( 
						'heading' => esc_html__( 'Small Image Height', 'socialize-plugin' ),
						'description' => esc_html__( 'The height of the small featured images.', 'socialize-plugin' ),
						'param_name' => 'small_image_height',
						'value' => '65',
						'type' => 'textfield',
						'dependency' => array( 'element' => 'small_featured_image', 'value' => 'enabled' ),
						),					
						array( 
						'heading' => esc_html__( 'Hard Crop', 'socialize-plugin' ),
						'description' => esc_html__( 'Images are cropped even if it is smaller than the dimensions you want to crop it to.', 'socialize-plugin' ),
						'param_name' => 'hard_crop',
						'value' => array( esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled', esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled' ),
						'type' => 'dropdown',
						),	
						array( 
						'heading' => esc_html__( 'Large Image Alignment', 'socialize-plugin' ),
						'description' => esc_html__( 'Choose how the large image aligns with the content.', 'socialize-plugin' ),
						'param_name' => 'large_image_alignment',
						'value' => array( esc_html__( 'Above Content', 'socialize-plugin' ) => 'image-above', esc_html__( 'Left Wrap', 'socialize-plugin' ) => 'gp-image-wrap-left', esc_html__( 'Right Wrap', 'socialize-plugin' ) => 'gp-image-wrap-right', esc_html__( 'Left Align', 'socialize-plugin' ) => 'gp-image-align-left', esc_html__( 'Right Align', 'socialize-plugin' ) => 'gp-image-align-right' ),
						'type' => 'dropdown',
						'dependency' => array( 'element' => 'large_featured_image', 'value' => 'enabled' ),
						),
						array( 
						'heading' => esc_html__( 'Small Image Alignment', 'socialize-plugin' ),
						'description' => esc_html__( 'Choose how the small image aligns with the content.', 'socialize-plugin' ),
						'param_name' => 'small_image_alignment',
						'value' => array( esc_html__( 'Left Align', 'socialize-plugin' ) => 'gp-image-align-left', esc_html__( 'Left Wrap', 'socialize-plugin' ) => 'gp-image-wrap-left', esc_html__( 'Right Wrap', 'socialize-plugin' ) => 'gp-image-wrap-right', esc_html__( 'Above Content', 'socialize-plugin' ) => 'image-above', esc_html__( 'Right Align', 'socialize-plugin' ) => 'gp-image-align-right' ),
						'type' => 'dropdown',
						'dependency' => array( 'element' => 'small_featured_image', 'value' => 'enabled' ),
						),				
						array( 
						'heading' => esc_html__( 'Large Excerpt Length', 'socialize-plugin' ),
						'description' => esc_html__( 'The number of characters in large excerpts.', 'socialize-plugin' ),
						'param_name' => 'large_excerpt_length',
						'value' => '80',
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'Small Excerpt Length', 'socialize-plugin' ),
						'description' => esc_html__( 'The number of characters in small excerpts.', 'socialize-plugin' ),
						'param_name' => 'small_excerpt_length',
						'value' => '0',
						'type' => 'textfield',
						),					
						array(
						'heading' => esc_html__( 'Large Post Meta', 'socialize-plugin' ),
						'param_name' => 'large_meta_author',
						'value' => array( esc_html__( 'Author Name', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),	
						array(
						'param_name' => 'large_meta_date',
						'value' => array( esc_html__( 'Post Date', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),		
						array(
						'param_name' => 'large_meta_comment_count',
						'value' => array( esc_html__( 'Comment Count', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),	
						array(
						'param_name' => 'large_meta_views',
						'value' => array( esc_html__( 'Views', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),
						array( 
						'param_name' => 'large_meta_cats',
						'value' => array( esc_html__( 'Post Categories', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),	
						array(
						'description' => esc_html__( 'Select the large meta data you want to display.', 'socialize-plugin' ),
						'param_name' => 'large_meta_tags',
						'value' => array( esc_html__( 'Post Tags', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),
						array(
						'heading' => esc_html__( 'Small Post Meta', 'socialize-plugin' ),
						'param_name' => 'small_meta_author',
						'value' => array( esc_html__( 'Author Name', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),	
						array(
						'param_name' => 'small_meta_date',
						'value' => array( esc_html__( 'Post Date', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),		
						array(
						'param_name' => 'small_meta_comment_count',
						'value' => array( esc_html__( 'Comment Count', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),	
						array(
						'param_name' => 'small_meta_views',
						'value' => array( esc_html__( 'Views', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),
						array( 
						'param_name' => 'small_meta_cats',
						'value' => array( esc_html__( 'Post Categories', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),
						array(
						'description' => esc_html__( 'Select the small meta data you want to display.', 'socialize-plugin' ),
						'param_name' => 'small_meta_tags',
						'value' => array( esc_html__( 'Post Tags', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),	
						array( 
						'heading' => esc_html__( 'Large Read More Link', 'socialize-plugin' ),
						'description' => esc_html__( 'Add a read more link below the large content.', 'socialize-plugin' ),
						'param_name' => 'large_read_more_link',
						'value' => array( esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled', esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled' ),
						'type' => 'dropdown',
						),												
						array( 
						'heading' => esc_html__( 'Small Read More Link', 'socialize-plugin' ),
						'description' => esc_html__( 'Add a read more link below the small content.', 'socialize-plugin' ),
						'param_name' => 'small_read_more_link',
						'value' => array( esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled', esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled' ),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Pagination (Arrows)', 'socialize-plugin' ),
						'description' => esc_html__( 'Add pagination arrows.', 'socialize-plugin' ),
						'param_name' => 'page_arrows',
						'value' => array( esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled', esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled' ),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Pagination (Numbers)', 'socialize-plugin' ),
						'description' => esc_html__( 'Add pagination numbers.', 'socialize-plugin' ),
						'param_name' => 'page_numbers',
						'value' => array( esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled', esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled' ),
						'type' => 'dropdown',
						),						
						array( 
						'heading' => esc_html__( 'See All', 'socialize-plugin' ),
						'description' => esc_html__( 'Add a "See All" link.', 'socialize-plugin' ),
						'param_name' => 'see_all',
						'value' => array( esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled', esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled' ),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'See All Link', 'socialize-plugin' ),
						'description' => esc_html__( 'URL for the "See All" link.', 'socialize-plugin' ),
						'param_name' => 'see_all_link',
						'type' => 'textfield',
						'dependency' => array( 'element' => 'see_all', 'value' => 'enabled' ),
						),				 			 
						array( 
						'heading' => esc_html__( 'See All Text', 'socialize-plugin' ),
						'description' => esc_html__( 'Custom text for the "See All" link.', 'socialize-plugin' ),
						'param_name' => 'see_all_text',
						'type' => 'textfield',
						'value' => esc_html__( 'See All Items', 'socialize-plugin' ),
						'dependency' => array( 'element' => 'see_all', 'value' => 'enabled' ),
						),	 			 				 		   			 			 
						array( 
						'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
						'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
						'param_name' => 'classes',
						'value' => '',
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
						'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
						'param_name' => 'title_format',
						'value' => array( esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title', esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title' ),
						'type' => 'dropdown',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),
						array( 
						'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
						'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
						'param_name' => 'title_color',
						'value' => '',
						'type' => 'colorpicker',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),	
						array( 
						'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
						'param_name' => 'icon',
						'value' => $icons,
						'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
						'type' => 'icon_selection',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),																																											
					 )
				) );
		

				/*--------------------------------------------------------------
				Slider Shortcode
				--------------------------------------------------------------*/

				require_once( sprintf( "%s/gp_vc_slider.php", dirname( __FILE__ ) ) );
		
				vc_map( array( 
					'name' => esc_html__( 'Slider', 'socialize-plugin' ),
					'base' => 'slider',
					'description' => esc_html__( 'Display a slider.', 'socialize-plugin' ),
					'class' => 'wpb_vc_slider',
					'controls' => 'full',
					'icon' => 'gp-icon-slider',
					'category' => esc_html__( 'Content', 'socialize-plugin' ),
					'front_enqueue_js' => $flexslider_js,
					'params' => array(					
						array( 
						'heading' => esc_html__( 'Title', 'socialize-plugin' ),
						'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
						'param_name' => 'widget_title',
						'type' => 'textfield',
						'admin_label' => true,
						'value' => '',
						),				
						array( 
						'heading' => esc_html__( 'Categories', 'socialize-plugin' ),
						'description' => esc_html__( 'Enter the slugs or IDs separating each one with a comma e.g. xbox,ps3,pc.', 'socialize-plugin' ),
						'param_name' => 'cats',
						'type' => 'textfield',
						),					
						array( 
						'heading' => esc_html__( 'Page IDs', 'socialize-plugin' ),
						'description' => esc_html__( 'Enter the page IDs.', 'socialize-plugin' ),
						'param_name' => 'page_ids',
						'type' => 'textfield',
						),		
						array( 
						'heading' => esc_html__( 'Post Types', 'socialize-plugin' ),
						'description' => esc_html__( 'The post types to display.', 'socialize-plugin' ),
						'param_name' => 'post_types',
						'type' => 'posttypes',
						'value' => 'post',
						),
						array( 
						'heading' => esc_html__( 'Format', 'socialize-plugin' ),
						'description' => esc_html__( 'The format of the slider.', 'socialize-plugin' ),
						'param_name' => 'format',
						'value' => array(
							esc_html__( 'Two Columns', 'socialize-plugin' ) => 'gp-slider-two-cols',
							esc_html__( 'One Column', 'socialize-plugin' ) => 'gp-slider-one-col',
						),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Large Image Width', 'socialize-plugin' ),
						'description' => esc_html__( 'The width of the large slider image.', 'socialize-plugin' ),
						'param_name' => 'large_image_width',
						'value' => '',
						'type' => 'textfield',
						),		
						array( 
						'heading' => esc_html__( 'Large Image Height', 'socialize-plugin' ),
						'description' => esc_html__( 'The height of the large slider image.', 'socialize-plugin' ),
						'param_name' => 'large_image_height',
						'value' => '',
						'type' => 'textfield',
						),	
						array( 
						'heading' => esc_html__( 'Small Image Width', 'socialize-plugin' ),
						'dependency' => array( 'element' => 'format', 'value' => array( 'three-cols', 'gp-slider-two-cols' ) ),
						'description' => esc_html__( 'The width of the small slider image.', 'socialize-plugin' ),
						'param_name' => 'small_image_width',
						'value' => '',
						'type' => 'textfield',
						),		
						array( 
						'heading' => esc_html__( 'Small Image Height', 'socialize-plugin' ),
						'dependency' => array( 'element' => 'format', 'value' => array( 'three-cols', 'gp-slider-two-cols' ) ),
						'description' => esc_html__( 'The height of the small slider image.', 'socialize-plugin' ),
						'param_name' => 'small_image_height',
						'value' => '',
						'type' => 'textfield',
						),			
						array( 
						'heading' => esc_html__( 'Hard Crop', 'socialize-plugin' ),
						'description' => esc_html__( 'Images are cropped even if it is smaller than the dimensions you want to crop it to.', 'socialize-plugin' ),
						'param_name' => 'hard_crop',
						'value' => array( esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled', esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled' ),
						'type' => 'dropdown',
						),							
						array( 
						'heading' => esc_html__( 'Caption Title', 'socialize-plugin' ),
						'description' => esc_html__( 'Display the caption titles.', 'socialize-plugin' ),
						'param_name' => 'caption_title',
						'value' => array( esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled', esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled' ),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Caption Text', 'socialize-plugin' ),
						'description' => esc_html__( 'Display the caption text (only displays in the main slide).', 'socialize-plugin' ),
						'param_name' => 'caption_text',
						'value' => array( esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled', esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled' ),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Animation', 'socialize-plugin' ),
						'description' => esc_html__( 'The slider animation.', 'socialize-plugin' ),
						'param_name' => 'animation',
						'value' => array(
							esc_html__( 'Slide', 'socialize-plugin' ) => 'slide',
							esc_html__( 'Fade', 'socialize-plugin' ) => 'fade',
						),
						'type' => 'dropdown',
						),								
						array( 
						'heading' => esc_html__( 'Slider Speed', 'socialize-plugin' ),
						'description' => esc_html__( 'The number of seconds before the slider goes to the next slide.', 'socialize-plugin' ),
						'param_name' => 'slider_speed',
						'value' => '6',
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'Animation Speed', 'socialize-plugin' ),
						'description' => esc_html__( 'The speed of the slider animation in seconds.', 'socialize-plugin' ),
						'param_name' => 'animation_speed',
						'value' => '0.6',
						'type' => 'textfield',		
						),	
						array( 
						'heading' => esc_html__( 'Navigation Buttons', 'socialize-plugin' ),
						'description' => esc_html__( 'Display the slider navigation buttons.', 'socialize-plugin' ),
						'param_name' => 'buttons',
						'value' => array(
							esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled',
							esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled',
						),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Navigation Arrows', 'socialize-plugin' ),
						'description' => esc_html__( 'Display the slider navigation arrows.', 'socialize-plugin' ),
						'param_name' => 'arrows',
						'value' => array(
							esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled',
							esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled',
						),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Order By', 'socialize-plugin' ),
						'description' => esc_html__( 'The criteria which the items are ordered by.', 'socialize-plugin' ),
						'param_name' => 'orderby',
						'value' => array(
							esc_html__( 'Newest', 'socialize-plugin' ) => 'newest',
							esc_html__( 'Oldest', 'socialize-plugin' ) => 'oldest',
							esc_html__( 'Title (A-Z)', 'socialize-plugin' ) => 'title_az',
							esc_html__( 'Title (Z-A)', 'socialize-plugin' ) => 'title_za',
							esc_html__( 'Most Comments', 'socialize-plugin' ) => 'comment_count',
							esc_html__( 'Most Views', 'socialize-plugin' ) => 'views',
							esc_html__( 'Menu Order', 'socialize-plugin' ) => 'menu_order',
							esc_html__( 'Random', 'socialize-plugin' ) => 'rand',
						),
						'type' => 'dropdown',
						),	
						array( 
						'heading' => esc_html__( 'Date Posted', 'socialize-plugin' ),
						'description' => esc_html__( 'The date the items were posted.', 'socialize-plugin' ),
						'param_name' => 'date_posted',
						'value' => array(
							esc_html__( 'Any date', 'socialize-plugin' ) => 'all',
							esc_html__( 'In the last year', 'socialize-plugin' ) => 'year',
							esc_html__( 'In the last month', 'socialize-plugin' ) => 'month',
							esc_html__( 'In the last week', 'socialize-plugin' ) => 'week',
							esc_html__( 'In the last day', 'socialize-plugin' ) => 'day',
						),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'Date Modified', 'socialize-plugin' ),
						'description' => esc_html__( 'The date the items were modified.', 'socialize-plugin' ),
						'param_name' => 'date_modified',
						'value' => array(
							esc_html__( 'Any date', 'socialize-plugin' ) => 'all',
							esc_html__( 'In the last year', 'socialize-plugin' ) => 'year',
							esc_html__( 'In the last month', 'socialize-plugin' ) => 'month',
							esc_html__( 'In the last week', 'socialize-plugin' ) => 'week',
							esc_html__( 'In the last day', 'socialize-plugin' ) => 'day',
						),
						'type' => 'dropdown',
						),	
						array( 
						'heading' => esc_html__( 'Number Of Slides', 'socialize-plugin' ),
						'description' => esc_html__( 'The number of slides.', 'socialize-plugin' ),
						'param_name' => 'per_page',
						'value' => '9',
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'Offset', 'socialize-plugin' ),
						'description' => esc_html__( 'The number of posts to offset by e.g. set to 3 to exclude the first 3 posts.', 'socialize-plugin' ),
						'param_name' => 'offset',
						'value' => '',
						'type' => 'textfield',
						),	
						array( 
						'heading' => esc_html__( 'See All', 'socialize-plugin' ),
						'description' => esc_html__( 'Add a "See All" link.', 'socialize-plugin' ),
						'param_name' => 'see_all',
						'value' => array( esc_html__( 'Disabled', 'socialize-plugin' ) => 'disabled', esc_html__( 'Enabled', 'socialize-plugin' ) => 'enabled' ),
						'type' => 'dropdown',
						),
						array( 
						'heading' => esc_html__( 'See All Link', 'socialize-plugin' ),
						'description' => esc_html__( 'URL for the "See All" link.', 'socialize-plugin' ),
						'param_name' => 'see_all_link',
						'type' => 'textfield',
						'dependency' => array( 'element' => 'see_all', 'value' => 'enabled' ),
						),				 			 
						array( 
						'heading' => esc_html__( 'See All Text', 'socialize-plugin' ),
						'description' => esc_html__( 'Custom text for the "See All" link.', 'socialize-plugin' ),
						'param_name' => 'see_all_text',
						'type' => 'textfield',
						'value' => esc_html__( 'See All Items', 'socialize-plugin' ),
						'dependency' => array( 'element' => 'see_all', 'value' => 'enabled' ),
						),																	 						 		   			 			 
						array( 
						'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
						'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
						'type' => 'textfield',
						'param_name' => 'classes',
						'value' => '',
						),		
						array( 
						'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
						'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
						'param_name' => 'title_format',
						'value' => array( esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title', esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title' ),
						'type' => 'dropdown',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),
						array( 
						'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
						'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
						'param_name' => 'title_color',
						'value' => '',
						'type' => 'colorpicker',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),					
						array( 
						'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
						'param_name' => 'icon',
						'value' => $icons,
						'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
						'type' => 'icon_selection',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),																																							
					 )
				) );


				/*--------------------------------------------------------------
				Statistics Shortcode
				--------------------------------------------------------------*/

				require_once( sprintf( "%s/gp_vc_statistics.php", dirname( __FILE__ ) ) );
		
				vc_map( array( 
					'name' => esc_html__( 'Statistics', 'socialize-plugin' ),
					'base' => 'statistics',
					'description' => esc_html__( 'Display a list of site statistics.', 'socialize-plugin' ),
					'class' => 'wpb_vc_statistics',
					'controls' => 'full',
					'icon' => 'gp-icon-statistics',
					'category' => esc_html__( 'Content', 'socialize-plugin' ),
					'params' => array(				
						array( 
						'heading' => esc_html__( 'Title', 'socialize-plugin' ),
						'description' => esc_html__( 'The title at the top of the element.', 'socialize-plugin' ),
						'param_name' => 'widget_title',
						'type' => 'textfield',
						'admin_label' => true,
						'value' => '',
						),				
						array( 
						'heading' => esc_html__( 'Statistics', 'socialize-plugin' ),
						'param_name' => 'posts',
						'value' => array( esc_html__( 'Posts', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),			
						array( 
						'param_name' => 'comments',
						'value' => array( esc_html__( 'Comments', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),			
						array( 
						'param_name' => 'blogs',
						'value' => array( esc_html__( 'Blogs', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),				
						array( 
						'param_name' => 'activity',
						'value' => array( esc_html__( 'BuddyPress Activity Updates', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),				
						array( 
						'param_name' => 'members',
						'value' => array( esc_html__( 'BuddyPress Members', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),				
						array( 
						'param_name' => 'groups',
						'value' => array( esc_html__( 'BuddyPress Groups', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),				
						array( 
						'param_name' => 'forums',
						'value' => array( esc_html__( 'bbPress Forums', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						),				
						array( 
						'param_name' => 'topics',
						'value' => array( esc_html__( 'bbPress Forum Topics', 'socialize-plugin' ) => '1' ),
						'type' => 'checkbox',
						'description' => esc_html__( 'Choose what statistics to show.', 'socialize-plugin' ),
						),							
						array( 
						'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
						'param_name' => 'classes',
						'value' => '',
						'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
						'type' => 'textfield',
						),	
						array( 
						'heading' => esc_html__( 'Title Format', 'socialize-plugin' ),
						'description' => esc_html__( 'Choose the title format.', 'socialize-plugin' ),
						'param_name' => 'title_format',
						'value' => array( esc_html__( 'Standard', 'socialize-plugin' ) => 'gp-standard-title', esc_html__( 'Fancy', 'socialize-plugin' ) => 'gp-fancy-title' ),
						'type' => 'dropdown',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),
						array( 
						'heading' => esc_html__( 'Title Color', 'socialize-plugin' ),
						'description' => esc_html__( 'The title color.', 'socialize-plugin' ),
						'param_name' => 'title_color',
						'value' => '',
						'type' => 'colorpicker',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),				
						array( 
						'heading' => esc_html__( 'Statistics Icon Background Color', 'socialize-plugin' ),
						'description' => esc_html__( 'The statistics icon background color.', 'socialize-plugin' ),
						'param_name' => 'icon_color',
						'value' => '#e93100',
						'type' => 'colorpicker',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),	
						array( 
						'heading' => esc_html__( 'Title Icon', 'socialize-plugin' ),
						'param_name' => 'icon',
						'value' => $icons,
						'description' => esc_html__( 'The icon you want to display next to the title.', 'socialize-plugin' ),
						'type' => 'icon_selection',
						'group' => esc_html__( 'Design options', 'socialize-plugin' ),
						),																																								
					 )
				) );
		
		
				/*--------------------------------------------------------------
				Team Shortcode
				--------------------------------------------------------------*/

				// Team Wrapper
				vc_map( array( 
					'name' => esc_html__( 'Team', 'socialize-plugin' ),
					'base' => 'team',
					'description' => esc_html__( 'Display your team members.', 'socialize-plugin' ),
					'as_parent' => array( 'only' => 'team_member' ), 
					'class' => 'wpb_vc_team',
					'controls' => 'full',
					'icon' => 'gp-icon-team',
					'category' => esc_html__( 'Content', 'socialize-plugin' ),
					'js_view' => 'VcColumnView',
					'params' => array( 	
						array( 
						'heading' => esc_html__( 'Columns', 'socialize-plugin' ),
						'param_name' => 'columns',
						'value' => '3',
						'description' => esc_html__( 'The number of columns.', 'socialize-plugin' ),
						'type' => 'textfield',
						),		
						array( 
						'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
						'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
						'param_name' => 'classes',
						'value' => '',
						'type' => 'textfield',
						),																																								
					 ),
				) );

				// Team Member
				vc_map( array( 
					'name' => esc_html__( 'Team Member', 'socialize-plugin' ),
					'base' => 'team_member',
					'icon' => 'gp-icon-team',
					'content_element' => true,
					'as_child' => array( 'only' => 'team' ),
					'params' => array( 	
						array( 
						'heading' => esc_html__( 'Image', 'socialize-plugin' ),
						'description' => esc_html__( 'The team member image.', 'socialize-plugin' ),
						'param_name' => 'image_url',
						'value' => '',
						'type' => 'attach_image'
						),
						array( 
						'heading' => esc_html__( 'Image Width', 'socialize-plugin' ),
						'description' => esc_html__( 'The width of the team member image.', 'socialize-plugin' ),
						'param_name' => 'image_width',
						'value' => '230',
						'type' => 'textfield',
						),		
						array( 
						'heading' => esc_html__( 'Image Height', 'socialize-plugin' ),
						'description' => esc_html__( 'The height of the team member image.', 'socialize-plugin' ),
						'param_name' => 'image_height',
						'value' => '230',
						'type' => 'textfield',
						),			
						array( 
						'heading' => esc_html__( 'Name', 'socialize-plugin' ),
						'description' => esc_html__( 'The name of the team member.', 'socialize-plugin' ),
						'param_name' => 'name',
						'admin_label' => true,
						'value' => '',
						'type' => 'textfield'
						),	
						array( 
						'heading' => esc_html__( 'Position', 'socialize-plugin' ),
						'description' => esc_html__( 'The position of the team member e.g. CEO', 'socialize-plugin' ),
						'param_name' => 'position',
						'value' => '',
						'type' => 'textfield',
						),
						array( 
						'heading' => esc_html__( 'Link', 'socialize-plugin' ),
						'description' => esc_html__( 'Add a link for the team member image.', 'socialize-plugin' ),
						'param_name' => 'link',
						'value' => '',
						'type' => 'textfield',
						),	
						array( 
						'heading' => esc_html__( 'Link Target', 'socialize-plugin' ),
						'description' => esc_html__( 'The link target for the team member image.', 'socialize-plugin' ),
						'param_name' => 'link_target',
						'value' => array( esc_html__( 'Same Window', 'socialize-plugin' ) => '_self', esc_html__( 'New Window', 'socialize-plugin' ) => '_blank' ),
						'type' => 'dropdown',
						'dependency' => array( 'element' => 'link', 'not_empty' => true ),
						),				
						array( 
						'heading' => esc_html__( 'Description', 'socialize-plugin' ),
						'description' => esc_html__( 'The description of the team member.', 'socialize-plugin' ),
						'param_name' => 'content',
						'value' => '',
						'type' => 'textarea_html',
						),																																								
					 )
				 ) );


				/*--------------------------------------------------------------
				Testimonials Shortcode
				--------------------------------------------------------------*/

				// Testimonial Slider
				vc_map( array( 
					'name' => esc_html__( 'Testimonial Slider', 'socialize-plugin' ),
					'base' => 'testimonial_slider',
					'description' => esc_html__( 'Show your testimonials in a slider.', 'socialize-plugin' ),
					'as_parent' => array( 'only' => 'testimonial' ), 
					'class' => 'wpb_vc_testimonial',
					'controls' => 'full',
					'icon' => 'gp-icon-testimonial-slider',
					'category' => esc_html__( 'Content', 'socialize-plugin' ),
					'js_view' => 'VcColumnView',
					'params' => array( 	
						array( 
						'heading' => esc_html__( 'Effect', 'socialize-plugin' ),
						'param_name' => 'effect',
						'value' => array( esc_html__( 'Slide', 'socialize-plugin' ) => 'slide', esc_html__( 'Fade', 'socialize-plugin' ) => 'fade' ),
						'description' => esc_html__( 'The slider effect.', 'socialize-plugin' ),
						'type' => 'dropdown'
						),
						array( 
						'heading' => esc_html__( 'Slider Speed', 'socialize-plugin' ),
						'param_name' => 'speed',
						'value' => '0',
						'description' => esc_html__( 'The number of seconds between slide transitions, set to 0 to disable the autoplay.', 'socialize-plugin' ),
						'type' => 'textfield',
						),		
						array( 
						'heading' => esc_html__( 'Buttons', 'socialize-plugin' ),
						'param_name' => 'buttons',
						'value' => array( esc_html__( 'Show', 'socialize-plugin' ) => 'true', esc_html__( 'Hide', 'socialize-plugin' ) => 'false' ),
						'description' => esc_html__( 'The slider buttons.', 'socialize-plugin' ),
						'type' => 'dropdown',
						),				
						array( 
						'heading' => esc_html__( 'Extra Class Names', 'socialize-plugin' ),
						'description' => esc_html__( 'If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'socialize-plugin' ),
						'param_name' => 'classes',
						'value' => '',
						'type' => 'textfield',
						),																																								
					 ),
				 ) );


				// Testimonial Slide
				vc_map( array( 
					'name' => esc_html__( 'Testimonial', 'socialize-plugin' ),
					'base' => 'testimonial',
					'content_element' => true,
					'as_child' => array( 'only' => 'testimonial_slider' ),
					'icon' => 'gp-icon-testimonial-slider',
					'params' => array( 	
						array( 
						'heading' => esc_html__( 'Image', 'socialize-plugin' ),
						'description' => esc_html__( 'The testimonial slide image.', 'socialize-plugin' ),
						'param_name' => 'image_url',
						'value' => '',
						'type' => 'attach_image'
						),
						array( 
						'heading' => esc_html__( 'Image Width', 'socialize-plugin' ),
						'description' => esc_html__( 'The width the testimonial slide image.', 'socialize-plugin' ),
						'param_name' => 'image_width',
						'value' => '120',
						'description' => '',
						'type' => 'textfield',
						),		
						array( 
						'heading' => esc_html__( 'Image Height', 'socialize-plugin' ),
						'description' => esc_html__( 'The height of the testimonial slide images.', 'socialize-plugin' ),
						'param_name' => 'image_height',
						'value' => '120',
						'type' => 'textfield',
						),		
						array( 
						'heading' => esc_html__( 'Quote', 'socialize-plugin' ),
						'description' => esc_html__( 'The testimonial quote.', 'socialize-plugin' ),
						'param_name' => 'content',
						'value' => '',
						'type' => 'textarea',
						),		
						array( 
						'heading' => esc_html__( 'Name', 'socialize-plugin' ),
						'description' => esc_html__( 'The name of the person who gave the testimonial.', 'socialize-plugin' ),
						'param_name' => 'name',
						'value' => '',
						'type' => 'textfield',
						),																																								
					 )
				 ) );																													

			}

		}
	
	}	

}

?>