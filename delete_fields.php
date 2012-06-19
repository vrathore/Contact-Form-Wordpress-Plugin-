<?php 
// Fetch value of an id which is to be deleted.
$get_field = $_GET['val'];
global $wpdb;
	$CF_fields = get_option('CF_fields');
	$CF_records = get_option('CF_records');
	
	$key = $wpdb->get_results("SELECT fieldid FROM $CF_fields WHERE id = '".$get_field."'");
	foreach ( $key as $data ){
		$col_name = $data->fieldid;
			break;
	}
	echo $col_name;
	$wpdb->get_results("DELETE FROM $CF_fields WHERE id = '".$get_field."'");
	
	$wpdb->get_results("ALTER TABLE $CF_records DROP COLUMN ".$col_name);

	wp_redirect( get_option('siteurl').'/wp-admin/admin.php?page=my-top-level-handle&val=del', 301 );
	
	
	exit;
			
?>
