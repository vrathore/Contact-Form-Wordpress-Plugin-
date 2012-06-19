<div class="wrap">
	<h2> Contact Form</h2>
	<hr />
	<?php
		global $wpdb, $wp_version;
		
		/* Fetch the values form option table */
		
		/* Contact Form Title as shown on the front end */
		$CF_title = get_option('CF_title');
		/* Contact Form Admin Settings */		
		$CF_On_SendEmail = get_option('CF_On_SendEmail');
		$CF_On_MyEmail = get_option('CF_On_MyEmail');
		$CF_On_MySubject = get_option('CF_On_MySubject');
		/* Contact Form features */
		$CF_On_Captcha = get_option('CF_On_Captcha');
		
		/* Fetching value form URL using $_GET from ADD NEW FIELD page, on successful addtion of a new field */
		$get_value = $_GET['val'];
		if($get_value == add)
		{
			echo "<div id='message' class='updated below-h2'>New field has been added successfully</div>";
		}
		if($get_value == del)
		{
			echo "<div id='message' class='updated below-h2'>Field has been deleted successfully</div>";
		}
		
		
		/* Update the option table when the admin form is submitted*/
		if (@$_POST['CF_submit']) 
		{
			$CF_title = stripslashes($_POST['CF_title']);
			$CF_On_SendEmail = stripslashes($_POST['CF_On_SendEmail']);
			$CF_On_MyEmail = stripslashes($_POST['CF_On_MyEmail']);
			$CF_On_MySubject = stripslashes($_POST['CF_On_MySubject']);
			$CF_On_Captcha = stripslashes($_POST['CF_On_Captcha']);
			$CF_Captcha_field_length = stripslashes($_POST['CF_Captcha_field_length']);
			
			update_option('CF_title', $CF_title );
			update_option('CF_On_SendEmail', $CF_On_SendEmail );
			update_option('CF_On_MyEmail', $CF_On_MyEmail );
			update_option('CF_On_MySubject', $CF_On_MySubject );
			update_option('CF_On_Captcha', $CF_On_Captcha );
			
		}
		?>
		<!-- Contact Form display on wordpress admin side -->
		<form name="form_CF" method="post" action="">
			<table width="100%" border="0" cellspacing="2" cellpadding="2">
				<tr>
					<td align="left">
						<h3>Contact form display fields</h3>
					</td>
				</tr>
				<tr>
					<td align="left">
						Title:
					</td>
					<td align="left">
						<input  style="width: 300px;" type="text" value="<?php echo $CF_title ?>" name="CF_title" id="CF_title" />
						(The name of the form to be displayed on front page)
					</td>
				</tr>				
			</table>
			<br />
			<h3>The following fields will be displayed on the Contact Form page</h3>
			You can modify the field name, its requirement, type, validation and size by update option and delete the whole field by delete option
			<br />
			<table class="widefat post fixed" cellspacing="0">
				<thead>
					<tr>
						<th class="manage-column" width="70" scope="col">ID</th>
						<th class="manage-column column-title" scope="col">Field Name</th>
						<th width="70" scope="col">Required</th>
						<th width="100" scope="col">Type</th>
						<th width="250" scope="col">Values</th>
						<th width="100" scope="col">Validation</th>
						<th width="70" scope="col">Size</th>
						<th width="70" scope="col">Display</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<th class="manage-column" width="70" scope="col">ID</th>
						<th class="manage-column column-title" scope="col">Field Name</th>
						<th width="70" scope="col">Required</th>
						<th width="100" scope="col">Type</th>
						<th width="250" scope="col">Values</th>
						<th width="100" scope="col">Validation</th>
						<th width="70" scope="col">Size</th>
						<th width="70" scope="col">Display</th>
					</tr>
				</tfoot>
				<!-- Featch the results of the field added -->
				<?php 
					$CF_fields = get_option('CF_fields');

					$key = $wpdb->get_results("select * from $CF_fields order by id asc");
					foreach ( $key as $result ){?>
					<tbody>
						<td><?php echo $result->id; ?></td>
						<td><?php echo $result->fieldname; ?>
						<div class="row-actions">
							<!--<span class="mark"><a href="options-general.php?page=Contact_Form/update_fields.php&val=<?php //echo $result->id; ?>">Update</a> | </span>-->
							<span class="delete"><a href="options-general.php?page=Contact_Form/delete_fields.php&val=<?php echo $result->id; ?>" onclick="return confirm('This will delete all the data available in this field. \n\nDo you want to delete?')" >Delete</a></span>
						</div>
						</td>
						<td><?php echo $result->required; ?></td>
						<td><?php echo $result->type; ?></td>
						<td><?php echo $result->values; ?></td>
						<td><?php echo $result->validation; ?></td>
						<td><?php echo $result->size; ?></td>
						<td><?php echo $result->display;?></td>
				</tbody>
			<?php	} 	?>
			</table>
			
			<p><a href="options-general.php?page=Contact_Form/add_fields.php" class="button-secondary" id="addfield">Add Field</a></p>				
			<table cellpadding="5">
				<tr>
					<td>
						<br /><br />
						<h3>Contact form features</h3>
					</td>
				</tr>
				<tr>
				<!-- Captch Field to be display -->
					<td align="left">
						Captcha:
					</td>
					<td align="left">
						<input type="radio" name="CF_On_Captcha" <?php if (strtoupper($CF_On_Captcha) == "YES") echo 'checked="checked"';  ?> value="YES">YES
						<input type="radio" name="CF_On_Captcha" <?php if (strtoupper($CF_On_Captcha) == "NO") echo 'checked="checked"';  ?> value="NO">NO
					</td>
				</tr>

				<tr>
					<td align="left">
						Enter length of Captcha:
					</td>
					<td align="left">
						<input  style="width: 300px;" type="text" value="<?php echo $CF_Captcha_field_length ?>" name="CF_Captcha_field_length" id="CF_Captcha_field_length" /> ( Enter the number of characters to be displayed in captcha )
					</td>
				</tr>
				<tr>
					<td>
						<br /><br />
						<h3>Contact form admin details</h3>
					</td>
				</tr>
				<tr>
					<td>
						<p>Send Email to admin on form submission:</p>
					</td>
					<td align="left">
						<input type="radio" name="CF_On_SendEmail" <?php if (strtoupper($CF_On_SendEmail) == "YES") echo 'checked="checked"';  ?> value="YES">YES
						<input type="radio" name="CF_On_SendEmail" <?php if (strtoupper($CF_On_SendEmail) == "NO") echo 'checked="checked"';  ?> value="NO">NO
					</td>
				</tr>
				<tr>
					<td>
						<p>Enter Email Address:</p>
					</td>
					<td align="left">
						<input  style="width: 300px;" type="text" value="<?php echo $CF_On_MyEmail ?>" name="CF_On_MyEmail" id="CF_On_MyEmail" /> ( Enter your email id to receive emails )
					</td>
				</tr>
				<tr>
					<td align="left">
						Enter Email Subject:
					</td>
					<td align="left">
						<input  style="width: 300px;" type="text" value="<?php echo $CF_On_MySubject ?>" name="CF_On_MySubject" id="CF_On_MySubject" /> ( Email subject)
					</td>
				</tr>
				<tr>
					<td>
						<br />
						<input type="submit" id="CF_submit" name="CF_submit" lang="publish" class="button-primary" value="Update Setting" value="1" />
					</td>
				</tr>
			</table>
		</form>	
	<br />
	<hr />
	<h2>Plugin Information</h2>		
		<h3>Option 1. Drag and drop the widget!</h3>
			Go to widget menu and drag and drop the "Contact Form" widget to your sidebar location.<br />
		<h3>Option 2. Paste the below code to your desired template location.</h3>
			<div style="padding-top:7px;padding-bottom:7px;">
				<code style="padding:7px;">[Contact] </code>
			</div>
			<h4>Plugin short code for pages/post</h4>
</div>
