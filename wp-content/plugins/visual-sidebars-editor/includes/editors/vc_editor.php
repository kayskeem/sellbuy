<div class="wrap <?php if( $this->enabled_vc_composer ) echo "vc_wrap" ?>">
	<form id="post" name="post" method="post" action="">
		<!-- hidden fields -->
		<?php wp_nonce_field('epx_vcsb_save') ?>
		<input type="hidden" name="sidebar_id" value="<?php echo $current_sidebar['id'] ?>">
		<input type="hidden" name="save" value="<?php echo self::post_type ?>">
		<input type='hidden' id='post_ID' name='post_ID' value='<?php echo $post_ID ?>'>
		
		<div id="poststuff">
			<div class="bt_row">
				
				<!-- Sidebar editor -->
				<?php
				$class = "bt_col-sm-8 bt_col-lg-9";
				if( count($wp_registered_sidebars)==1 ) {
					$class = "bt_col-sm-12 bt_col-lg-10 bt_col-lg-offset-1";
				}
				?>
				<div class="<?php echo $class ?>">
					<h3 class="section_title">Edit Sidebar</h3>
					
					<div id="messages">
						<?php if( $this->message ): ?>
						<div class="<?php echo $this->message[0] ?> notice notice-success is-dismissible below-h2"><p><?php echo $this->message[1] ?></p><button class="notice-dismiss" type="button"><span class="screen-reader-text">Dismiss this notice.</span></button></div>
						<?php endif ?>
					</div>

					<div class="postbox">
						<div class="postbox_header bt_clearfix">
							<h3><?php echo ucwords($current_sidebar['name']) ?></h3>

							<div class="postbox_tools">
								<div class="onoffswitch">
									<input type="hidden" name="post_status" value="private"> 
									<input type="checkbox" name="post_status" id="widget_status" value="publish" <?php checked( $post_status, 'publish' ) ?>> 
									<label for="widget_status" title="Override widgets">
										<div class="inner"></div> 
										<div class="switch"></div> 
									</label>
								</div>
								<div class="buttons bootstrap">
									<div class="btn-group">
										<a class="dropdown-toggle" data-toggle="dropdown"><i class="xico xico-menu"></i></a>
										<ul class="dropdown-menu dropdown-menu-right" id="post_actions">
											<li><a href="#" class="action_save"><i class="xico xico-ok"></i> Save</a></li>
										<?php
										if( $current_sidebar['revisions'] ) {
											$link = get_edit_post_link( $current_sidebar['revision_id'] );
											$count = $current_sidebar['revisions_count'];
											?>
											<li><a href="<?php echo $link ?>"><i class="xico xico-history"></i> Revisions (<?php echo $count ?>)</a></li>
											<?php
										}
										?>
											<li class="divider"></li>
										<?php
										if( $post_content ) {
											?>
											<li><a href="#sidebar_content_exporter" class="mce_window" title="Export Sidebar Content"><i class="xico xico-download"></i> Export</a></li>
											<?php
										}
										?>
											<li><a href="#sidebar_content_importer" class="mce_window" title="Import Sidebar Content"><i class="xico xico-upload"></i> Import</a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<div class="inside">

							<!-- Sidebar title -->
							<div id="titlediv" class="section">
								<input type="text" name="post_title" placeholder="Sidebar title" class="widefat" value="<?php echo $post_title ?>" autocomplete="off">
							</div>

							<!-- Hidden editor, used for dependency purposes -->
							<div style="display: none !important">
							<div style="display: none !important">
								<?php
								wp_editor( '', 'nothing_to_do_with_it', array(
									'quicktags' => false,
									'media_buttons' => false,
									'textarea_rows' => 1,
								))
								?>
							</div>
							</div>

							<!-- Classic wordpress editor -->
							<div id="postdivrich" style="display: block">
							<?php
								wp_editor( $post_content, 'content', array(
									'dfw' => true,
									'drag_drop_upload' => true,
									'tabfocus_elements' => 'insert-media-button,save-post',
									'editor_height' => 300,
									'tinymce' => array(
										'resize' => false,
										'wp_autoresize_on' => true,
										'add_unload_trigger' => false,
									),
								));
							?>
							</div>

							<!-- Visual composer editor -->
							<div id="wpb_visual_composer" style="display: none">
							<?php
								if( $this->enabled_vc_composer ) {
									add_filter( 'wpb_vc_js_status_filter', create_function('', "return 'true';") );
									$vc_manager->backendEditor()->renderEditor($post);
								}
							?>
							</div>
							
							<div class="section bt_clearfix">
								<!-- Submit button -->
								<div class="bt_pull-right">
									<?php if( $post_status != 'publish' ): ?>
									<button type="submit" name="action" value="publish" class="xbtn xbtn-success">Publish</button>
									<?php endif ?>
									<button type="submit" id="publish" name="action" value="save" class="xbtn xbtn-primary">Save</button>
								</div>
								
								<!-- Delete button -->
								<div class="bt_pull-left">
									<?php if( $post_ID ): ?>
									<button type="submit" id="delete" name="action" value="delete" class="ebtn ebtn-danger">Delete</button>
									<?php endif ?>
								</div>
							</div>
							
						</div>
					</div>


					<div class="postbox" id="sidebar_ssettings">
						<div class="postbox_header bt_clearfix">
							<h3>Sidebar Settings</h3>
						</div>

						<div class="inside">
							<div class="epx_vcsb_field bt_row">
								<div class="epx_vcsb_field_label bt_col-sm-4 bt_col-md-3 bt_col-lg-2">
									<label>Override behavior</label>
								</div>
								<div class="epx_vcsb_field_control bt_col-sm-8 bt_col-md-9 bt_col-md-10 bt_clearfix">
									<select id="settings_behavior" name="settings[behavior]" class="bt_pullleft">
									<?php
									if( !$settings->behavior ) {
										$settings->behavior = apply_filters('vcse_default_behavior', 'replace', $current_sidebar);
									}
									$this->dd_options(
										array(
											'replace' => "Replace all sidebar widgets",
											'before' => "Place before existing widgets",
											'after' => "Place after existing widgets",
											'position' => "Place in the following position",
										),
										$settings->behavior
									)
									?>
									</select>
									<input type="text" id="settings_behavior_value" name="settings[behavior_value]" class="bt_pullleft hidden" value="<?php echo $settings->behavior_value ?>" placeholder="Custom position"/>
								</div>
							</div>
							<div class="epx_vcsb_field bt_row">
								<div class="epx_vcsb_field_label bt_col-sm-4 bt_col-md-3 bt_col-lg-2">
									<label>Widget container</label>
								</div>
								<div class="epx_vcsb_field_control bt_col-sm-8 bt_col-md-9 bt_col-lg-10 bt_clearfix">
									<select name="settings[container]" class="bt_pullleft">
									<?php
									$this->dd_options(
										array(
											'default' => "Default container",
											'none' => "No container",
										),
										$settings->container
									)
									?>
									</select>
								</div>
							</div>
							<div class="epx_vcsb_field bt_row">
								<div class="epx_vcsb_field_label bt_col-sm-4 bt_col-md-3 bt_col-lg-2">
									<label>Override Global Post</label>
								</div>
								<div class="epx_vcsb_field_control bt_col-sm-8 bt_col-md-9 bt_col-lg-10 bt_clearfix">
									<select name="settings[global]" class="bt_pullleft">
									<?php
									$this->dd_options(
										array(
											'no' => "No",
											'yes' => "Yes",
										),
										$settings->global
									)
									?>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>

				<!-- Available sidebars -->
				<?php if( count($wp_registered_sidebars) > 1 ): ?>
				<div class="bt_col-sm-4 bt_col-lg-3">
					<h3 class="section_title">Available Sidebars</h3>

					<div id="epx_vcsb_sidebars">
					<?php
					$csid = $current_sidebar['id'];
					foreach( $wp_registered_sidebars as $sidebar_id=>$sidebar ) {
						$class = 'epx_vcsb_sidebar';
						if( $sidebar_id == $csid )
							$class .= ' current';

						?>
						<div class="<?php echo $class ?>">
							<a href="<?php echo $this->admin_url ?>&amp;sidebar=<?php echo $sidebar_id ?>">
								<?php echo ucwords($sidebar['name']) ?>
							</a>
						</div>
						<?php
					}
					?>
					</div>
				</div>
				<?php endif ?>

			</div>
		</div>
	</form>

	<script type="text/javascript">
	var selectAllText = function( elem ) {
        elem.select();
        elem.onmouseup = function() {
            elem.onmouseup = null;
            return false;
        };
    };
	</script>
	<?php
	if( $post_content ) {
		?>
		<div id="sidebar_content_exporter" class="hidden" style="width:600px;height:350px" data-cancel="Close">
			<div style="height:100%">
				<textarea class="imexport" onfocus="selectAllText(this)" readonly><?php echo $post_content ?></textarea>
			</div>
		</div>
		<?php
	}
	?>

	<div id="sidebar_content_importer" class="hidden" style="width:600px;height:350px" data-cancel="Close" data-submit="Import">
		<form style="height:100%" method="post" action="">
			<textarea name="content" class="imexport" placeholder="Paste content here" required></textarea>
			
			<label id="import_override">
				<input name="override" type="checkbox" checked/> Override Existing Content?
			</label>

			<!-- hidden fields -->
			<?php wp_nonce_field('epx_vcsb_save') ?>
			<input type="hidden" name="sidebar_id" value="<?php echo $current_sidebar['id'] ?>">
			<input type="hidden" name="save" value="<?php echo self::post_type ?>">
			<input type="hidden" name="action" value="import">
			<input type="submit" class="hidden"/>
		</form>
	</div>

	<script type="text/javascript">
	!function($){
		var __return_true = function(){ return true };
		var __return_false = function(){ return false };

		var $post = $("#post");
		var $vc_logo = $("#vc_logo");
		
		$("#settings_behavior").on('change', function(){
			$input = $("#settings_behavior_value");
			if( this.value == 'position' ) {
				$input.removeClass('hidden');
			} else {
				$input.addClass('hidden');
			}
		}).trigger('change');

		$('a.mce_window').on( 'click', function(e){
			e.preventDefault();

			var target = $($(this).attr('href'));
			var title = $(this).attr('title');
			var buttons = [];

			if( !target.length ) return;

			var win = null;

			if( label=target.data('cancel') ) {
				buttons.push({
					text: label,
					onclick: function() {
						win.close();
					}
				});
			}

			if( label=target.data('submit') ) {
				buttons.push({
					text: label,
					subtype: 'primary',
					style: 'width:100px',
					onclick: function() {
						$('#'+win._id).find('form').submit();
					}
				});
			}

			win = tinymce.activeEditor.windowManager.open({
				html: target.html(),
				title: title,
				width: target.outerWidth(),
				height: target.outerHeight(),
				buttons: buttons
			});

		});

		$('.dropdown-toggle').dropdown()
		$('.dropdown-menu .disabled a').click( __return_false );

		$("#post_actions .action_save").on( 'click', function(){
			$post.submit();
			return false;
		});

		$("#delete").on( 'click', function(){
			$this = $(this);
			tinymce.activeEditor.windowManager.confirm("You are about to permanently delete the sidebar content and settings!", function( yes ) {
				if( yes ) {
					$this.off('click').click();
				}
			});
			return false;
		});

		$post.on( 'submit', function(e){
			$vc_logo.addClass('vc_ajax-loading');
		})

		$(window).load(function(){
			$("#wpb-save-post").html( $("#publish").html() );
			$("#poststuff").fadeIn(400);
		});
	}(jQuery);
	</script>
</div>