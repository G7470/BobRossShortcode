<?php
 
/**
 
 * @package H
 
 */
 
/*
 
Plugin Name: Bob Ross Shortcode
 
Description: This plugin will take a title and generate a Bob Ross quote onto a page.
 
Version: 1.0.0
 
Author: Adam Houghton
 
Text Domain: H

*/

namespace H;

/**
	* Function Name: HBobRoss_title
	* Description: Displays the title setting for the Bob Ross Shortcode plugin settings page
	* Author: Adam Houghton
	* Change History:
	*	08/14/2022 - AH - Created.
**/	
function HBobRoss_title() {
	$title = get_option('HBobRoss_defaulttitle');
	If (empty($title)) {
		$title = 'Bob Ross Quote';
	}
	echo "<input id='HBobRoss_defaulttitle' name='HBobRoss_defaulttitle' type='text' value='" . $title . "'>";
}

/**
	* Function Name: H_Sanitize
	* Description: Escapes and Sanitizes input from the user's input on the default title
	* Author: Adam Houghton
	* Change History:
	*	08/14/2022 - AH - Created.
**/	
function H_Sanitize($data) {
	return esc_attr(sanitize_text_field($data));
}

/**
	* Function Name: H_BobRossInit
	* Description: Sets up the settings for the Bob Ross Shortcode plugin
	* Author: Adam Houghton
	* Change History:
	*	08/14/2022 - AH - Created.
**/	
function H_BobRossInit() {
	
	// Create the settings section
	add_settings_section(
        'H_BobRossShortcode_section',
        'H Bob Ross Shortcode Section', 
		'H\HBobRoss_text',
        'HPluginSettings'
    );
	
	// Create the setting field
	add_settings_field('HBobRoss_defaulttitle', 'BobRoss Quote Title', 'H\HBobRoss_title', 'HPluginSettings', 'H_BobRossShortcode_section');
	
	// Register the setting field. Runs the sanitize callback on input
	register_setting('HPluginSettings', 'HBobRoss_defaulttitle', [
	'type'              => 'array',
    'sanitize_callback' => 'H\H_Sanitize',
	]);
}

/**
	* Function Name: HBobRoss_text
	* Description: Displays settings page description for the Bob Ross Shortcode plugin settings page
	* Author: Adam Houghton
	* Change History:
	*	08/14/2022 - AH - Created.
**/	
function HBobRoss_text() {
	echo '<p>Here you can set the defaults for the Bob Ross Shortcode Plugin</p>'; 
}

/**
	* Function Name: H_BobRossOptions
	* Description: The HTML to display the settings page for the Bob Ross Shortcode plugin
	* Author: Adam Houghton
	* Change History:
	*	08/14/2022 - AH - Created.
**/	
function H_BobRossOptions() {
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
	?>
	<div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
			
			// Get Settings for the plugin
            settings_fields('HPluginSettings');
            do_settings_sections('HPluginSettings');
			
            // output save settings button
			?>
            <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e('Save'); ?>" />
        </form>
    </div>
	<?php
}

/**
	* Function Name: H_BobRossOptionsPage
	* Description: Adds the plugin settings page to the settings section 
	* Author: Adam Houghton
	* Change History:
	*	08/14/2022 - AH - Created.
**/
function H_BobRossOptionsPage()
{
    add_options_page(
        'Custom Plugin Settings',
        'Custom Plugin Settings',
        'manage_options',
        'HPluginSettings',
        'H\H_BobRossOptions'
    );
}


/**
	* Function Name: H_BobRossText
	* Description: Runs the shortcode function to display the Bob Ross lorem ipsum text on a post or page
	* Author: Adam Houghton
	* Change History:
	*	08/14/2022 - AH - Created.
**/
function H_BobRossText($title) {
	
	// Set up defaults
	$default = array('title' => get_option('HBobRoss_defaulttitle'));
	
	// Hard-coded paragraphs from Bob Ross Lorem Ipsum (https://www.bobrosslipsum.com/)
	$paragraph1 = "<p>There are no mistakes. You can fix anything that happens. I'm gonna start with a little Alizarin crimson and a touch of Prussian blue You can work and carry-on and put lots of little happy things in here. Let's make a happy little mountain now.</p>";
	$paragraph2 = "<p>Trees get lonely too, so we'll give him a little friend. I spend a lot of time walking around in the woods and talking to trees, and squirrels, and little rabbits and stuff. Look around, look at what we have. Beauty is everywhere, you only have to look to see it.</p>";
	$paragraph3 = "<p>Everyone is going to see things differently - and that's the way it should be. Let your imagination just wonder around when you're doing these things. Now, we're going to fluff this cloud. If you do too much it's going to lose its effectiveness.</p>";
	
	// Translate shortcode
	$titlearr = shortcode_atts($default, $title);
	
	// If no value, set default value
	If (empty($titlearr['title'])) {
		$titlearr['title'] = 'Bob Ross Quote';
	}
	
	// Escape text
	$safe_title = esc_attr($titlearr['title']);
	
	return '<h2>' . $safe_title . '</h2>' . $paragraph1 . $paragraph2 . $paragraph3;
	
}

// Creates the settings hooks
add_action('admin_init', 'H\H_BobRossInit');
add_action('admin_menu', 'H\H_BobRossOptionsPage');

// Add the Shortcode
add_shortcode('H_BobRoss', 'H\H_BobRossText');

?>