<?php
        add_action( 'admin_menu', function(){ add_submenu_page( "tools.php", "Include", "Include", "manage_options", "include-plugin", "include_plugin_options"); });	
	
	/**
	 * Plugin Options Panel
	 *
	 * Creates and returns the "include_children" shortcode
	 *
	 * @since 2.0
	 * @author Brendan McSweeney, Mike Flynn
	 */
	function include_plugin_options() {
		if ( !current_user_can( 'manage_options' ) )  { wp_die( __( 'You do not have sufficient permissions to access this page.' ) ); }
		if(!empty($_POST["include_form_submit"]) && $_POST["include_form_submit"] == "y" && include_set_options($_POST['include_options'])) echo "<div class='updated'><p>Updated Successfully</p></div>";
		$options = include_get_options();
                $recursion_types = array("strict", "weak");
                global $include_option_tips;
		echo "<div class='wrap'><h2>Include Options</h2><form action='' method='post'><input type='hidden' name='include_form_submit' value='y'><table class='form-table'><tbody>";
		foreach($options as $optname => $optvalue) {
			if ($optname == "recursion") {
				echo "<tr><th><label for='{$optname}'>".ucwords(strtolower(str_replace('_', ' ', $optname)))."</label></th><td><select name='include_options[{$optname}]' style='width:350px;'>";
				foreach ($recursion_types as $recursion_type) echo "<option value=\"{$recursion_type}\" id=\"{$recursion_type}\" ". ($recursion_type == $optvalue ? "selected":"")." >{$recursion_type}</option>";
				echo "</select><p class='description'>{$include_option_tips['recursion']}</p></td></tr>";
			}
			else if($optvalue !== false) echo "<tr><th><label for='{$optname}'>".ucwords(strtolower(str_replace('_', ' ', $optname)))."</label></th><td><input type='text' name='include_options[{$optname}]' id='{$optname}' class='regular-text code' value=\"{$optvalue}\"><p class='description'>{$include_option_tips[$optname]}</p></td></tr>";
		}
		echo "<tr><th></th><td><input type='submit' value='Save Changes' class='button button-primary'></td></tr></tbody></table></form></div>";
	}