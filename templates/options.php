<?php
    global $coralRemoteImages;

    function coralGetImageSizeDetails($name) {
        global $_wp_additional_image_sizes;

        $details = [];
        if ( in_array( $name, array('thumbnail', 'medium', 'medium_large', 'large') ) ) {
			$details['width']  = get_option( "{$name}_size_w" );
			$details['height'] = get_option( "{$name}_size_h" );
			$details['crop']   = (bool) get_option( "{$name}_crop" );
		} elseif ( isset( $_wp_additional_image_sizes[ $name ] ) ) {
			$details = array(
				'width'  => $_wp_additional_image_sizes[ $name ]['width'],
				'height' => $_wp_additional_image_sizes[ $name ]['height'],
				'crop'   => $_wp_additional_image_sizes[ $name ]['crop'],
			);
        }
        
        return $details;
    }
?>
<div class="wrap">
    <h1>Coral Remote Images</h1>
    <form method="post" action="options.php"> 

        <?php settings_fields( $coralRemoteImages::OPTIONS_GROUP ); ?>
        <?php do_settings_sections( $coralRemoteImages::OPTIONS_GROUP ); ?>
            
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Live URL</th>
                <td>
                    <?php
                        $disable = '';
                        $url = esc_attr(get_option($coralRemoteImages::OPTIONS_FIELD_NAME));
                        if (defined('BSD_CORAL_LIVE_URL')) {
                            $disable = 'disabled';
                            $url = BSD_CORAL_LIVE_URL;
                        }
                    ?>
                    <input type="text" name="coral_remote_images_live" value="<?php echo $url; ?>" <?php echo $disable; ?> />
                    <p>This should match the URL found in the admin panel by going to Settings > General > Wordpress Address (URL)</p>
                </td>
            </tr>
        </table>
        
        <?php submit_button(); ?>

    </form>

    <div class="available-images">
        <h2>Available Image Sizes</h2>
        <style>
            .available-images table {
                width: 100%;
            }
            .available-images table img {
                max-width: 100%;
                height: auto;
            }
        </style>
        <table>
            <thead>
                <tr>
                    <th width="20%">Name</th>
                    <th width="10%">Width</th>
                    <th width="10%">Height</th>
                    <th width="10%">Crop</th>
                    <th width="50%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $imageSizes = get_intermediate_image_sizes();
                    foreach ($imageSizes as $name) :
                        $sizeDetails = coralGetImageSizeDetails($name);
                        if (!isset($sizeDetails['width'])) continue;
                ?>
                <tr>
                    <td><strong><?php echo $name; ?><strong></td>
                    <td><?php echo $sizeDetails['width']; ?></td>
                    <td><?php echo $sizeDetails['height']; ?></td>
                    <td><?php echo ($sizeDetails['crop'] ? 'Yes' : 'No'); ?></td>
                    <td>
                        <img src="https://placehold.it/<?php echo $sizeDetails['width']; ?>x<?php echo $sizeDetails['height']; ?>";
                    </td>
                </tr>
                <?php 
                    endforeach;
                ?>
            </tbody>
        </table>
    </div>
</div>