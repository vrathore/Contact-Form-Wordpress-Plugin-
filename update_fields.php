<div class="wrap">
	<h2> Contact Form - Add Fields</h2>
	<hr />

<?php
//Featch a value id to update the value of that particukar field
$get_field = $_GET['val'];
echo $get_field;
	global $wpdb;
	$success= "";
	$error = "";
	
	$CF_fields = get_option('CF_fields');
	$CF_records = get_option('CF_records');
	if (@$_POST['CF_update_submit']) 
	{
	//update the fields 
		$CF_update_fieldid = $_POST['CF_update_fieldid'];
		$CF_update_fieldname = $_POST['CF_update_fieldname'];
		$CF_update_required = $_POST['CF_update_required'];
		$CF_update_type = $_POST['CF_update_type'];
		$CF_update_values = $_POST['CF_update_values'];
		$CF_update_validation = $_POST['CF_update_validation'];
		$CF_update_size = $_POST['CF_update_size'];
		$CF_update_display = $_POST['CF_update_display'];
		$CF_colname_records = $CF_update_fieldid."_".CF_update_fieldname;
		
		//validation on the fields.
		
		if($CF_update_fieldname == "")
			$err = "Field Name cannot be empty <br>";
		if($CF_update_required == "")
			$err .= "Field reuired should be either YES or NO<br>";
		if($CF_update_display == "")
			$err .= "Field display should be either YES or NO<br>";
		if($CF_update_type == "")
			$err .= "Field Type cannot be empty<br>";
		if($CF_update_values == "" && ($CF_update_type=="dropdown" || $CF_update_type=="radio")  )
			$err .= "Field Values cannot be blank for Radio Button and Dropdown type of fields<br>";
		if($CF_update_validation == "")
			$err .= "Field Validation cannot be empty<br>";
		if($CF_update_size == "")
			$err .= "Field Size cannot be empty<br>";

		if($err ==""){

			$sql = "UPDATE wp_cf_fields"
			. " SET `fieldid`='" . mysql_real_escape_string(trim($CF_update_fieldid))
			. "', `fieldname`='" . mysql_real_escape_string(trim($CF_update_fieldname))
			. "', `required`='" . mysql_real_escape_string(trim($CF_update_required))
			. "', `type`='" . mysql_real_escape_string(trim($CF_update_type))
			. "', `values`='" . mysql_real_escape_string(trim($CF_update_values))
			. "', `validation`='" . mysql_real_escape_string(trim($CF_update_validation))
			. "', `size`='" . mysql_real_escape_string(trim($CF_update_size))
			. "', `display`='" . mysql_real_escape_string(trim($CF_update_display))."' WHERE id = '".$get_field."'";
		
			$wpdb->get_results($sql);
			echo $sql;
		
			$sql_rec = "ALTER TABLE $CF_records MODIFY ".mysql_real_escape_string(trim($CF_update_fieldid))." VARCHAR(".mysql_real_escape_string(trim($CF_update_size)).")";
			$wpdb->get_results($sql_rec);
			
			wp_redirect(get_option('siteurl'). '/wp-admin/admin.php?page=my-top-level-handle&val=updt', 301 );
			exit();
		}
		else //Display Error Message
			echo "<p style = 'font-size: 15px; color:RED'>".$err."</p>";
	}
	

?>	<!-- Admin side form to display update options -->
		<form name="form_CF" method="post" action="">
			<table width="100%" border="0" cellspacing="2" cellpadding="5">
				<tr>
					<td align="left">
						<h3>Update fields</h3>
					</td>
				</tr>
				<tr>
					<td align="left">
						ID:
					</td>
					<td align="left">
						<?php 
							$key = $wpdb->get_results("select * from $CF_fields where id = '".$get_field."'");
							foreach ( $key as $result )
						?>
						<input type="text" name="CF_update_id" id="CF_update_id" value="<?php echo $result->id; ?>" />
						<span style="font-size:10px; color:blue;"> (will be hidden)</span>
					</td>
				</tr>				
				<tr>
					<td align="left">
						Field ID:
					</td>
					<td align="left">
						<input type="text" name="CF_update_fieldid" id="CF_update_fieldid" value="<?php echo $result->fieldid; ?>" />
						<span style="font-size:10px; color:blue;"> (will be hidden)</span>
					</td>
				</tr>				
				<tr>
					<td align="left">
						Field Name:
					</td>
					<td align="left">
						<input type="text" name="CF_update_fieldname" id="CF_update_filedname" value="<?php echo $result->fieldname; ?>"/>
					</td>
				</tr>
				<tr>
					<td align="left">
						Field display:
					</td>
					<td align="left">
					<?php $get_display = $result->display;?>
						<input type="radio" name="CF_update_display" <?php if ($get_display == "YES") echo 'checked="checked"';  ?> id="CF_update_display" value="YES">YES
						<input type="radio" name="CF_update_display" <?php if ($get_display == "NO") echo 'checked="checked"';  ?> id="CF_update_display" value="NO">NO
				</tr>				
				<tr>
					<td align="left">
						Field required:
					</td>
					<td align="left">
					<?php $get_required = $result->required;?>
						<input type="radio" name="CF_update_required" <?php if ($get_required == "YES") echo 'checked="checked"';  ?> id="CF_update_required" value="YES">YES
						<input type="radio" name="CF_update_required" <?php if ($get_required == "NO") echo 'checked="checked"';  ?> id="CF_update_required" value="NO">NO
				</tr>				
				<tr>
					<td align="left">
						Field type:
					</td>
					<td align="left"><p>
						<select name="CF_update_type">
						<?php $get_type = $result->type;?>
							<option value="text" <?php if ($get_type == "text") echo 'selected="selected"';  ?> >Text</option>
							<option value="textarea" <?php if ($get_type == "textarea") echo 'selected="selected"';  ?> >Textarea</option>
							<option value="password" <?php if ($get_type == "password") echo 'selected="selected"';  ?> >Password</option>
							<option value="radio" <?php if ($get_type == "radio") echo 'selected="selected"';  ?> >Radio Button</option>
							<option value="dropdwon" <?php if ($get_type == "dropdwon") echo 'selected="selected"';  ?>  >Dropdown</option>
						</select>
					</td>
				</tr>				
				<tr>
					<td align="left">
						Field values:
					</td>
					<td align="left">
						<input type="text" name="CF_update_values" id="CF_update_values" value="<?php echo $result->values; ?>">
						<strong>(add comma separated multiple values)</strong><br />
						(only use in case when Field Type is "Radio Button" or "Dropdown")
					</td>
				</tr>				
				<tr>
					<td align="left">
						Field validation:
					</td>
					<td align="left">
					<?php $get_validation= $result->validation;?>
						<select name="CF_update_validation">
							<option value="none"<?php if ($get_validation == "none") echo 'selected="selected"';  ?> >None</option>
							<option value="text" <?php if ($get_validation == "text") echo 'selected="selected"';  ?> >Text</option>
							<option value="number" <?php if ($get_validation == "number") echo 'selected="selected"';  ?> >Number</option>
							<option value="email" <?php if ($get_validation == "email") echo 'selected="selected"';  ?> >Email</option>
						</select>
					</td>
				</tr>				
				<tr>
					<td align="left">
						Field size:
					</td>
					<td align="left">
						<input type="text" name="CF_update_size" id="CF_update_size" value="<?php echo $result->size; ?>">
					</td>
				</tr>				
				<tr>
					<td>
						<br />
						<input type="submit" id="CF_update_submit" name="CF_update_submit" lang="publish" class="button-primary" value="Update Field" />
					</td>
				</tr>
			</table>
		</form>
</div>

