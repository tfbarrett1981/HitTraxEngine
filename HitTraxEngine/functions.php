<?php


//Add thumbnail, automatic feed links and title tag support
add_theme_support( 'post-thumbnails' );
add_theme_support( 'automatic-feed-links' );
add_theme_support( 'title-tag' );

//Add content width (desktop default)
if ( ! isset( $content_width ) ) {
	$content_width = 768;
}

//Add menu support and register main menu
if ( function_exists( 'register_nav_menus' ) ) {
  	register_nav_menus(
  		array(
  		  'main_menu' => 'Main Menu'
  		)
  	);
}


// filter the Gravity Forms button type
add_filter('gform_submit_button', 'form_submit_button', 10, 2);
function form_submit_button($button, $form){
    return "<button class='button btn' id='gform_submit_button_{$form["id"]}'><span>{$form['button']['text']}</span></button>";
}

// Register sidebar
add_action('widgets_init', 'theme_register_sidebar');
function theme_register_sidebar() {
	if ( function_exists('register_sidebar') ) {
		register_sidebar(array(
			'id' => 'sidebar-1',
		    'before_widget' => '<div id="%1$s" class="widget %2$s">',
		    'after_widget' => '</div>',
		    'before_title' => '<h4>',
		    'after_title' => '</h4>',
		 ));
	}
}

// Bootstrap_Walker_Nav_Menu setup

add_action( 'after_setup_theme', 'bootstrap_setup' );

if ( ! function_exists( 'bootstrap_setup' ) ):

	function bootstrap_setup(){

		add_action( 'init', 'register_menu' );

		function register_menu(){
			register_nav_menu( 'top-bar', 'Bootstrap Top Menu' ); 
		}

		class Bootstrap_Walker_Nav_Menu extends Walker_Nav_Menu {


			function start_lvl( &$output, $depth = 0, $args = array() ) {

				$indent = str_repeat( "\t", $depth );
				$output	   .= "\n$indent<ul class=\"dropdown-menu\">\n";

			}

			function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

				if (!is_object($args)) {
					return; // menu has not been configured
				}

				$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

				$li_attributes = '';
				$class_names = $value = '';

				$classes = empty( $item->classes ) ? array() : (array) $item->classes;
				$classes[] = ($args->has_children) ? 'dropdown' : '';
				$classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : '';
				$classes[] = 'menu-item-' . $item->ID;


				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
				$class_names = ' class="' . esc_attr( $class_names ) . '"';

				$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
				$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

				$output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';

				$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
				$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
				$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
				$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
				$attributes .= ($args->has_children) 	    ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';

				$item_output = $args->before;
				$item_output .= '<a'. $attributes .'>';
				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
				$item_output .= ($args->has_children) ? ' <b class="caret"></b></a>' : '</a>';
				$item_output .= $args->after;

				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
			}

			function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {

				if ( !$element )
					return;

				$id_field = $this->db_fields['id'];

				//display this element
				if ( is_array( $args[0] ) )
					$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
				else if ( is_object( $args[0] ) )
					$args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
				$cb_args = array_merge( array(&$output, $element, $depth), $args);
				call_user_func_array(array(&$this, 'start_el'), $cb_args);

				$id = $element->$id_field;

				// descend only when the depth is right and there are childrens for this element
				if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {

					foreach( $children_elements[ $id ] as $child ){

						if ( !isset($newlevel) ) {
							$newlevel = true;
							//start the child delimiter
							$cb_args = array_merge( array(&$output, $depth), $args);
							call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
						}
						$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
					}
						unset( $children_elements[ $id ] );
				}

				if ( isset($newlevel) && $newlevel ){
					//end the child delimiter
					$cb_args = array_merge( array(&$output, $depth), $args);
					call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
				}

				//end this element
				$cb_args = array_merge( array(&$output, $element, $depth), $args);
				call_user_func_array(array(&$this, 'end_el'), $cb_args);
			}
		}
 	}
endif;


// START THEME OPTIONS
// custom theme options for user in admin area - Appearance > Theme Options
function pu_theme_menu()
{
  add_theme_page( 'Theme Option', 'Theme Options', 'manage_options', 'pu_theme_options.php', 'pu_theme_page');  
}
add_action('admin_menu', 'pu_theme_menu');

function pu_theme_page()
{
?>
    <div class="section panel">
      <h1>Custom Theme Options</h1>
      <form method="post" enctype="multipart/form-data" action="options.php">
      <hr>
        <?php 

          settings_fields('pu_theme_options'); 
        
          do_settings_sections('pu_theme_options.php');
          echo '<hr>';
        ?>
            <p class="submit">  
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />  
            </p>
      </form>
    </div>
    <?php
}

add_action( 'admin_init', 'pu_register_settings' );

/**
 * Function to register the settings
 */
function pu_register_settings()
{
    // Register the settings with Validation callback
    register_setting( 'pu_theme_options', 'pu_theme_options' );

    // Add settings section
    add_settings_section( 'pu_text_section', 'Social Links', 'pu_display_section', 'pu_theme_options.php' );

    // Create textbox field
    $field_args = array(
      'type'      => 'text',
      'id'        => 'twitter_link',
      'name'      => 'twitter_link',
      'desc'      => 'Twitter Link - Example: http://twitter.com/username',
      'std'       => '',
      'label_for' => 'twitter_link',
      'class'     => 'css_class'
    );

    // Add twitter field
    add_settings_field( 'twitter_link', 'Twitter', 'pu_display_setting', 'pu_theme_options.php', 'pu_text_section', $field_args );   

    $field_args = array(
      'type'      => 'text',
      'id'        => 'facebook_link',
      'name'      => 'facebook_link',
      'desc'      => 'Facebook Link - Example: http://facebook.com/username',
      'std'       => '',
      'label_for' => 'facebook_link',
      'class'     => 'css_class'
    );

    // Add facebook field
    add_settings_field( 'facebook_link', 'Facebook', 'pu_display_setting', 'pu_theme_options.php', 'pu_text_section', $field_args );

    $field_args = array(
      'type'      => 'text',
      'id'        => 'gplus_link',
      'name'      => 'gplus_link',
      'desc'      => 'Google+ Link - Example: http://plus.google.com/user_id',
      'std'       => '',
      'label_for' => 'gplus_link',
      'class'     => 'css_class'
    );

    // Add Google+ field
    add_settings_field( 'gplus_link', 'Google+', 'pu_display_setting', 'pu_theme_options.php', 'pu_text_section', $field_args );

    $field_args = array(
      'type'      => 'text',
      'id'        => 'youtube_link',
      'name'      => 'youtube_link',
      'desc'      => 'Youtube Link - Example: https://www.youtube.com/channel/channel_id',
      'std'       => '',
      'label_for' => 'youtube_link',
      'class'     => 'css_class'
    );

    // Add youtube field
    add_settings_field( 'youtube_ink', 'Youtube', 'pu_display_setting', 'pu_theme_options.php', 'pu_text_section', $field_args );

    $field_args = array(
      'type'      => 'text',
      'id'        => 'linkedin_link',
      'name'      => 'linkedin_link',
      'desc'      => 'LinkedIn Link - Example: http://linkedin.com/in/username',
      'std'       => '',
      'label_for' => 'linkedin_link',
      'class'     => 'css_class'
    );

    // Add LinkedIn field
    add_settings_field( 'linkedin_link', 'LinkedIn', 'pu_display_setting', 'pu_theme_options.php', 'pu_text_section', $field_args );

    $field_args = array(
      'type'      => 'text',
      'id'        => 'instagram_link',
      'name'      => 'instagram_link',
      'desc'      => 'Instagram Link - Example: http://instagram.com/username',
      'std'       => '',
      'label_for' => 'instagram_link',
      'class'     => 'css_class'
    );

    // Add Instagram field
    add_settings_field( 'instagram_link', 'Instagram', 'pu_display_setting', 'pu_theme_options.php', 'pu_text_section', $field_args );

    // Add settings section title here
    add_settings_section( 'section_name_here', 'Section Title Here', 'pu_display_section', 'pu_theme_options.php' );
    
    // Create textarea field
    $field_args = array(
      'type'      => 'textarea',
      'id'        => 'settings_field_1',
      'name'      => 'settings_field_1',
      'desc'      => 'Setting Description Here',
      'std'       => '',
      'label_for' => 'settings_field_1'
    );

    // section_name should be same as section_name above (line 116)
    add_settings_field( 'settings_field_1', 'Setting Title Here', 'pu_display_setting', 'pu_theme_options.php', 'section_name_here', $field_args );   


    // Copy lines 118 through 129 to create additional field within that section
    // Copy line 116 for a new section and then 118-129 to create a field in that section
}


// allow wordpress post editor functions to be used in theme options
function pu_display_setting($args)
{
    extract( $args );

    $option_name = 'pu_theme_options';

    $options = get_option( $option_name );

    switch ( $type ) {  
          case 'text':  
              $options[$id] = stripslashes($options[$id]);  
              $options[$id] = esc_attr( $options[$id]);  
              echo "<input class='regular-text$class' type='text' id='$id' name='" . $option_name . "[$id]' value='$options[$id]' />";  
              echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";
          break;
          case 'textarea':  
              $options[$id] = stripslashes($options[$id]);  
              //$options[$id] = esc_attr( $options[$id]);
              $options[$id] = esc_html( $options[$id]); 

              printf(
              	wp_editor($options[$id], $id, 
              		array('textarea_name' => $option_name . "[$id]",
              			'style' => 'width: 200px'
              			)) 
				);
              // echo "<textarea id='$id' name='" . $option_name . "[$id]' rows='10' cols='50'>".$options[$id]."</textarea>";  
              // echo ($desc != '') ? "<br /><span class='description'>$desc</span>" : "";  
          break; 
    }
}

function pu_validate_settings($input)
{
  foreach($input as $k => $v)
  {
    $newinput[$k] = trim($v);
    
    // Check the input is a letter or a number
    if(!preg_match('/^[A-Z0-9 _]*$/i', $v)) {
      $newinput[$k] = '';
    }
  }

  return $newinput;
}

// Add custom styles to theme options area
add_action('admin_head', 'custom_style');

function custom_style() {
  echo '<style>
    .appearance_page_pu_theme_options .wp-editor-wrap {
      width: 75%;
    }
    .regular-textcss_class {
    	width: 50%;
    }
    .appearance_page_pu_theme_options h3 {
    	font-size: 2em;
    	padding-top: 40px;
    }
  </style>';
}

// END THEME OPTIONS


/**
 * Load site scripts.
 */
function bootstrap_theme_enqueue_scripts() {
	$template_url = get_template_directory_uri();

	// jQuery.
	wp_enqueue_script( 'jquery' );

	// Bootstrap
	wp_enqueue_script( 'bootstrap-script', $template_url . '/js/bootstrap.min.js', array( 'jquery' ), null, true );

	wp_enqueue_style( 'bootstrap-style', $template_url . '/css/bootstrap.min.css' );

	//Main Style
	wp_enqueue_style( 'main-style', get_stylesheet_uri() );

	// Load Thread comments WordPress script.
	if ( is_singular() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'bootstrap_theme_enqueue_scripts', 1 );

//*********
//*********
//*********

/* Filter Login to use the Central DB */
remove_filter('authenticate', 'wp_authenticate_username_password', 20, 3);
add_filter('authenticate', 'vd_authenticate_username_password', 20, 3);

function vd_authenticate_username_password($user, $username, $password){
	// we're going to check to see if the visitor is trying to sign in with their e-mail address
	
	
	if( is_email( $username ) ){
		/*
			if( $user_obj = get_user_by( 'email' , $username ) ){
			$username = $user_obj->user_login;
			return wp_authenticate_username_password($user, $username, $password );
		} else {

		*/
		$email = $username; //just for easy use

		// if the user doesn't exisit, we're going to want to create it based off the Central DB

		include_once(getcwd() . "/api/helpers.php"); //functions in the custom created api that will help us !
		//let's see if there is a user in the Central DB with the e-mail / password combination
		if( $vd_db_user_array = vd_db_user_check($email, $password) ){
			// hazah! there seems to be a user, let's check to see if there is a wordpress user based on the MasterID key from the central DB
			// if there isn't already a user, we'll create a new one. If there is a user, they must have changed info at a kiosk or something!
			// we'll sync any data that has changed here and proceed to log them in :)
			$mid = $vd_db_user_array['MasterID'];
			$role = $vd_db_user_array['Role'];

			if($mid == '') { return false; }
						
			// check to see if there is a wp user with the mid
			$users = get_users('meta_key=MasterID&meta_value=' . $mid );

			if(!empty($users)){
				//echo $users[0]->ID . " ". $email . " ". $password;
				
				
				// there is a user account with that mid - lets update the password and such
				wp_update_user( array ( 'ID' => $users[0]->ID,  'user_email' => $email, 'user_pass' => $password ) );
					
				// we might need to update the role - maybe they've just been given admin access ? 
				update_user_meta($users[0]->ID, 'VD_Role', $role, true );
				
				// after we have updated everything, we should be able to login normally !

				$new_user_obj = get_user_by( 'ID' , $users[0]->ID );
				
				
				return wp_authenticate_username_password($new_user_obj, $username, $password );

			}else{
				// nothing matching in the user DB, we'll have to make a new WP user and sync up!

				$uid = wp_create_user( $username, $password, $email );

				if(is_wp_error($uid)){
					
					if($uid->get_error_code() == 'existing_user_login'){
						// this user is already in the database but the MasterID has been changed ? or something ?

						/*
						echo $user[0]-ID;
						update_user_meta($user[0]-ID, 'MasterID', $mid, true );

						// try to login ?
						$new_user_obj = get_user_by( 'id' , $users[0]->ID );
						return wp_authenticate_username_password($new_user_obj, $username, $password );

						*/
					}
				}

				//add the master id user meta
				add_user_meta($uid, 'MasterID', $mid, true);

				// add the role (really only useful if the role is != 0)
				if($role != 0) add_user_meta( $uid, 'VD_Role', $role, true );

				// $uid will be an array if there is an error

				if(!is_wp_error($uid)){

					// the user was created, but now we need to "login" and move forward
					$new_user_obj = get_user_by( 'id' , $uid );
					return wp_authenticate_username_password($new_user_obj, $username, $password );
				}
			}

		} else {
			// there was not a user with these credentials, we shouldn't authenticate them
			echo 'no user in db';
			return false; 
		}

		return false; 

		
	}else{
		//trying to use username (for wp admin)
		return wp_authenticate_username_password($user, $username, $password );

	}
}


function vd_authenticate_username_password_md5($user, $username, $password){
	// we're going to check to see if the visitor is trying to sign in with their e-mail address
	if( is_email( $username ) ){
		/*
			if( $user_obj = get_user_by( 'email' , $username ) ){
			$username = $user_obj->user_login;
			return wp_authenticate_username_password($user, $username, $password );
		} else {

		*/
		$email = $username; //just for easy use

		// if the user doesn't exisit, we're going to want to create it based off the Central DB

		include_once(getcwd() . "/api/helpers.php"); //functions in the custom created api that will help us !
		//let's see if there is a user in the Central DB with the e-mail / password combination
		if( $vd_db_user_array = vd_db_user_check_md5($email, md5($password)) ){
			// hazah! there seems to be a user, let's check to see if there is a wordpress user based on the MasterID key from the central DB
			// if there isn't already a user, we'll create a new one. If there is a user, they must have changed info at a kiosk or something!
			// we'll sync any data that has changed here and proceed to log them in :)
			$mid = $vd_db_user_array['MasterID'];
			$role = $vd_db_user_array['Role'];
			// check to see if there is a wp user with the mid
			$users = get_users('meta_key=MasterID&meta_value=' . $mid);

			if(!empty($users)){
				//echo('here');
				// there is a user account with that mid - lets update the password and such
				wp_update_user( array ( 'ID' => $users[0]->ID,  'user_email' => $email, 'user_pass' => $password ) );

				// we might need to update the role - maybe they've just been given admin access ? 
				update_user_meta($user[0]->ID, 'VD_Role', $role, true );

				// after we have updated everything, we should be able to login normally !

				$new_user_obj = get_user_by( 'id' , $users[0]->ID );

				return wp_authenticate_username_password($new_user_obj, $username, $password );

			}else{
				//echo('there');
				// nothing matching in the user DB, we'll have to make a new WP user and sync up!
				$uid = wp_create_user( $username, $password, $email );

				//add the master id user meta
				add_user_meta($uid, 'MasterID', $mid, true);

				// add the role (really only useful if the role is != 0)
				if($role != 0) add_user_meta( $uid, 'VD_Role', $role, true );

				// $uid will be an array if there is an error

				if(!is_array($uid)){

					// the user was created, but now we need to "login" and move forward
					$new_user_obj = get_user_by( 'id' , $uid );
					return wp_authenticate_username_password($new_user_obj, $username, $password );
				}
			}

		} else {
			// there was not a user with these credentials, we shouldn't authenticate them
			echo 'no user in db';
			return false; 
		}

		return false; 

		
	}else{
		//trying to use username (for wp admin)
		return wp_authenticate_username_password($user, $username, $password );

	}
}

add_filter("login_redirect", "vd_login_redirect", 10, 3);

/* Filter the default login page */
function vd_login_redirect( $redirect_to, $request, $user ){
    //is there a user to check?
    global $user;
    if( isset( $user->roles ) && is_array( $user->roles ) ) {
        //check for admins
        if( in_array( "administrator", $user->roles ) ) {
            // redirect them to the default place
            return $redirect_to;
        } else {
        	//find out if they're a facility admin
        	if(get_user_meta( $user->ID, 'VD_Role', true ) != 0) return get_bloginfo("url") . "/engine-search/";
            return get_bloginfo("url") . "/engine-search/";	// to add, check if they're facility or general user and redirect accordingly
        }
    }
    else {
        return $redirect_to;
    }
}

add_action( 'wp_login_failed', 'my_front_end_login_fail' ); // hook failed login

function my_front_end_login_fail( $username ) {
$referrer = $_SERVER['HTTP_REFERER']; // where did the post submission come from?
//$referrer = "https://hittraxstatscenter.com/new-homepage";
// if there's a valid referrer, and it's not the default log-in screen
if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
wp_redirect( get_bloginfo("url") . '/?login=failed' ); // let's append some information (login=failed) to the URL for the theme to use
exit;
}
}
?>