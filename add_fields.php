<div class="wrap">
	<h2> Contact Form - Add Fields</h2>
	<hr />

<?php 
	global $wpdb;
	$error= "";
	
	$CF_fields = get_option('CF_fields');
	$CF_records = get_option('CF_records');
	if (@$_POST['CF_new_submit']){
		$CF_new_fieldid = $_POST['CF_new_fieldid'];
		$CF_new_fieldname = $_POST['CF_new_fieldname'];
		$CF_new_required = $_POST['CF_new_required'];
		$CF_new_type = $_POST['CF_new_type'];
		$CF_new_values = $_POST['CF_new_values'];
		$CF_new_validation = $_POST['CF_new_validation'];
		$CF_new_size = $_POST['CF_new_size'];
		$CF_new_display = $_POST['CF_new_display'];
		
//		$CF_colname_records = $CF_new_fieldid."_".CF_new_fieldname;

		if($CF_new_fieldname == "")
			$err = "Field Name cannot be empty <br>";
		if($CF_new_required == "")
			$err .= "Field reuired should be either YES or NO<br>";
		if($CF_new_display == "")
			$err .= "Field display should be either YES or NO<br>";
		if($CF_new_type == "")
			$err .= "Field Type cannot be empty<br>";
		if($CF_new_values == "" && ($CF_new_type=="dropdown" || $CF_new_type=="radio")  )
			$err .= "Field Values cannot be blank for Radio Button and Dropdown type of fields<br>";
		if($CF_new_validation == "")
			$err .= "Field Validation cannot be empty<br>";
		if($CF_new_size == "")
			$err .= "Field Size cannot be empty<br>";

		if($err ==""){
			$sql = "insert into $CF_fields"
			. " set `fieldid`='" . mysql_real_escape_string(trim($CF_new_fieldid))
			. "', `fieldname`='" . mysql_real_escape_string(trim($CF_new_fieldname))
			. "', `required`='" . mysql_real_escape_string(trim($CF_new_required))
			. "', `type`='" . mysql_real_escape_string(trim($CF_new_type))
			. "', `values`='" . mysql_real_escape_string(trim($CF_new_values))
			. "', `validation`='" . mysql_real_escape_string(trim($CF_new_validation))
			. "', `size`='" . mysql_real_escape_string(trim($CF_new_size))
			. "', `display`='" . mysql_real_escape_string(trim($CF_new_display))."'";
			
			$wpdb->get_results($sql);
			
			$sql_rec = "ALTER TABLE $CF_records ADD ".mysql_real_escape_string(trim($CF_new_fieldid))." VARCHAR(".mysql_real_escape_string(trim($CF_new_size)).")";
			$wpdb->get_results($sql_rec);
		
			wp_redirect( get_option('siteurl').'/wp-admin/admin.php?page=my-top-level-handle&val=add', 301 );
		
			exit;
		}
		else
			echo "<p style = 'font-size: 15px; color:RED'>".$err."</p>";
	}
?>
		<form name="form_CF" method="post" action="">
			<table width="100%" border="0" cellspacing="2" cellpadding="5">
				<tr>
					<td align="left">
						<h3>Add fields</h3>
					</td>
				</tr>
				<tr>	
					<td align="left">
						ID:
					</td>
					<td align="left">
						<?php 
							$key = $wpdb->get_results("select * from $CF_fields order by id desc");
							foreach ( $key as $data ){
								$key_id = $data->id;
								break;
							}
							$key_id = $key_id + 1;
						?>
						<input type="text" name="CF_new_id" id="CF_new_id" value="<?php echo $key_id ?>" disabled="disabled"/>
						<span style="font-size:10px; color:blue;"> (will be hidden)</span>
					</td>
				</tr>				
				<tr>
					<td align="left">
						Field ID:
					</td>
					<td align="left">
						<input type="text" name="CF_new_fieldid" id="CF_new_fieldid" value="<?php echo "CF_fieldid_".$key_id; ?>" />
						<span style="font-size:10px; color:blue;"> (will be disabled)</span>
					</td>
				</tr>				
				<tr>
					<td align="left">
						Field Name:
					</td>
					<td align="left">
						<input type="text" name="CF_new_fieldname" id="CF_new_filedname"/>
					</td>
				</tr>				
				<tr>
					<td align="left">
						Field display:
					</td>
					<td align="left">
						<input type="radio" name="CF_new_display" id="CF_new_display" value="YES" checked="checked">YES
						<input type="radio" name="CF_new_display" id="CF_new_display" value="NO">NO
				</tr>				
				<tr>
					<td align="left">
						Field required:
					</td>
					<td align="left">
						<input type="radio" name="CF_new_required" id="CF_new_required" value="YES" checked="checked">YES
						<input type="radio" name="CF_new_required" id="CF_new_required" value="NO">NO
				</tr>				
				<tr>
					<td align="left">
						Field type:
					</td>
					<td align="left"><p>
						<select name="CF_new_type">
							<option value="text" selected="selected">Text</option>
							<option value="textarea">Textarea</option>
							<option value="password" >Password</option>
							<option value="radio">Radio Button</option>
							<option value="dropdown" >Dropdown</option>
						</select>
					</td>
				</tr>				
				<tr>
					<td align="left">
						Field values:
					</td>
					<td align="left">
						<input type="text" name="CF_new_values" id="CF_new_values">
						<strong>(add comma separated multiple values)</strong><br />
						(only use in case when Field Type is "Radio Button" or "Dropdown")
					</td>
				</tr>				
				<tr>
					<td align="left">
						Field validation:
					</td>
					<td align="left">
						<select name="CF_new_validation">
							<option value="none" selected="selected">None</option>
							<option value="text" >Text</option>
							<option value="number">Number</option>
							<option value="email">Email</option>
						</select>
					</td>
				</tr>				
				<tr>
					<td align="left">
						Field size:
					</td>
					<td align="left">
						<input type="text" name="CF_new_size" id="CF_new_size">
					</td>
				</tr>				
				<tr>
					<td>
						<br />
						<input type="submit" id="CF_new_submit" name="CF_new_submit" lang="publish" class="button-primary" value="Add New Field" />
					</td>
				</tr>
			</table>
		</form>
</div>

<?php


?>