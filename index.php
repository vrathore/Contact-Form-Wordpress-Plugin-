<?php
/*
Plugin Name: Contact Form
Plugin URI: 
Description: A general contact form with add fields and a shortcode to be used on page or post. The fields type can be text,text area,radio buttom,dropdown,password. The form can also be used as a widget. Also, to enhance the contact form usability, a security code feature(captcha) is also added and all the form details can be viewed at the admin side.
Version: 1.1
Author: Varsha Rathore
Author URI: http://www.avika.in
*/

session_start();

function CF() {


	if($_POST['CF_submit'])
	{	
		echo "Thank you !";
		//include('mail.php');
	}
?>

<!-- Wordpress plugin form tag for displaying and submitting the contact form filled at front end -->
	<form action="" name="CF" id="CF" method="post">
	<div class="CF_title"> <span id="CF_alertmessage"></span> </div>
<?php 


		global $wpdb;
		$CF_fields = get_option('CF_fields');
		$key = $wpdb->get_results("select * from $CF_fields order by id asc");
		foreach ( $key as $result ){
			if($result->display == "YES"){
				if($result->type == "text"){?>
				<div class="CF_title">
					<label class="contact-label"><?php echo $result->fieldname;?><br />
					<div class="CF_title">
						<input type="text" id="<?php echo $result->fieldid;?>" name="<?php echo $result->fieldid;?>"  />
					</div>
				</div>
<?php			}
				else if($result->type == "textarea"){?>
				<div class="CF_title">
					<label class="contact-label"><?php echo $result->fieldname;?><br />
					<div class="CF_title">
						<input type="textarea" id="<?php echo $result->fieldid;?>" name="<?php echo $result->fieldid;?>"  />
					</div>
				</div>
<?php			}
				else if($result->type == "password"){?>
				<div class="CF_title">
					<label class="contact-label"><?php echo $result->fieldname;?><br />
					<div class="CF_title">
						<input type="password" id="<?php echo $result->fieldid;?>" name="<?php echo $result->fieldid;?>"  />
					</div>
				</div>
<?php			}
				else if($result->type == "radio"){?>
				<div class="CF_title">
					<label class="contact-label"><?php echo $result->fieldname;?><br />
					<div class="CF_title">
<?php					$array = explode(",", $result->values);
						$val_size = count($array);
						for ($i = 0; $i < $val_size; $i++) {?>
						<input type="radio" id="<?php echo $result->fieldid;?>" name="<?php echo $result->fieldid;?>"  value ="<?php echo $array[$i];?>" ><?php echo $array[$i];
						}?>
					</div>
				</div>
<?php			}
				else if($result->type == "dropdown"){?>
				<div class="CF_title">
					<label class="contact-label"><?php echo $result->fieldname;?><br />
					<div class="CF_title">
						<select id="<?php echo $result->fieldid;?>" name="<?php echo $result->fieldid;?>">
<?php					$array = explode(",", $result->values);
						$val_size = count($array);
						for ($i = 0; $i < $val_size; $i++) {?>
							<option value ="<?php echo $array[$i];?>" ><?php echo $array[$i];?></option>
<?php					}?>
						</select>
					</div>
				</div>
<?php			}
			}
		}?>
		
		
<?php					/* If condition to check whether 'Captcha' field is enabled */		
		if(get_option('CF_On_Captcha') == 'YES') {
			$linkss = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); 
	?>
			<div class="CF_title">
			<label class="contact-label" for="antispam">Captcha<br /><img src="<?php echo $linkss; ?>captcha.php" alt="captcha image"></label>
			<div class="CF_title">
				<input type="text" id="CF_captcha" name="CF_captcha" value="<?php //echo $_SESSION['captcha'] ?>" size="6" maxlength="6"/><?php //echo $_SESSION['captcha'] ?>
	<?php
		}

	?>
				<input type="submit" name="CF_submit" value="Submit" id="CF_submit" >
		</div>
	</form>
<?php	
}
/* Function CF() ends here */


/* Shortcode created, which can be used on any page or post */	
add_shortcode('Contact', 'CF');

/* CF_install() will add options in the option database table and set the default values provided against their option 
	Also, in addition to a separate table is also created, which stores the form details submitted */
function CF_install() {

	global $wpdb, $wp_version;
	/* Contact Form table creation for storing details of the contact form user */
	$CF_records = $wpdb->prefix . "CF_records";
	add_option('CF_records', $CF_records);
	/* Contact Form table creation for storing fields to be displayed on the contact form */
	$CF_fields = $wpdb->prefix . "CF_fields";
	add_option('CF_fields', $CF_fields);
	
	/* It checks the non-existance of the table to be created for contact form user details */
	if($wpdb->get_var("show tables like '". $CF_records . "'") != $CF_records) 
	{
		$wpdb->query("
			CREATE TABLE IF NOT EXISTS `". $CF_records . "` (
			  `id` int(6) NOT NULL auto_increment,
			  PRIMARY KEY  (`id`) )
			");
	}
	
	
	/* It checks the non-existance of the table to be created for contact form details */
	if($wpdb->get_var("show tables like '". $CF_fields . "'") != $CF_fields) 
	{
		$wpdb->query("
			CREATE TABLE IF NOT EXISTS `". $CF_fields . "` (
			  `id` int(3) NOT NULL auto_increment,
			  `fieldid` varchar(50) NOT NULL,
			  `fieldname` varchar(100) NOT NULL,
			  `required` varchar(4) NOT NULL,
			  `type` varchar(20) NOT NULL,
			  `values` varchar(200),
			  `validation` varchar(20) NOT NULL,
			  `size` int(4) NOT NULL,
			  `display` varchar(4) NOT NULL,
			  PRIMARY KEY  (`id`) )
			");
	}

	add_option('CF_title', "Contact Us");
	add_option('CF_On_SendEmail', "YES");
	add_option('CF_On_MyEmail', get_settings('admin_email'));
	add_option('CF_On_MySubject', "contact form");
	add_option('CF_On_Captcha', "YES");
}

/* CF_widget() displays the contact form in the widget area */
function CF_widget($args) 
{
		extract($args);
		echo $before_widget . $before_title;
		echo get_option('CF_title');
		echo $after_title;
		CF();
		echo $after_widget;
}

/* CF_widget_init() initializes widget feature for the contact form */
function CF_widget_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('Contact Form', 'Contact Form', 'CF_widget');
	}
}

/* conatct_admin() includes the contact_admin.php page, which is the admin panel for the contact form plugin */
function contact_admin() {  
    include('contact_admin.php');
}
	

/* Add the 'Contact Form' menu and records sub menu on wordpress dashboard */
function CF_add_to_menu() {
	add_menu_page('Contact Form', 'Contact Form', 'manage_options', 'my-top-level-handle', 'contact_admin');
	add_options_page('Contact Form', '', 'manage_options', "Contact_Form/add_fields.php",'' );
	
}

/* Checks for the admin account and calls the CF_add_to_menu() at the admin side */
if (is_admin()) {
	add_action('admin_menu', 'CF_add_to_menu');
}

/* Checks for the non admin account and includes the javascript file used for validation purpose at the front end */
function CF_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'Contact Form', get_option('siteurl').'/wp-content/plugins/Contact_Form/contact_form.js');
	}
}
add_action('init', 'CF_add_javascript_files');

add_action("plugins_loaded", "CF_widget_init");

register_activation_hook(__FILE__, 'CF_install');

?>