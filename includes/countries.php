<div class="wrap kpc-wrap">
<?php
screen_icon( "admin" );
?>
<h2><?php _e( 'COUNTRIES MANAGEMENT' ); ?></h2>
<?php
    global $wpdb; 
    if( isset( $_POST["kpc-update-country"] ) ) {
        $kpcid = trim(strip_tags($_GET["id"]));
        $kpc_google_prefix = trim(strip_tags($_POST["kpc-google-prefix"]));
        $kpc_country = trim(strip_tags($_POST["kpc-country"]));
        $kpc_language = trim(strip_tags($_POST["kpc-language"]));
        if( !empty( $kpcid ) && !empty( $kpc_google_prefix ) && !empty( $kpc_country ) && !empty( $kpc_language ) ) {
            $rs = $wpdb->update( "kpc_place", array( "google_place" => $kpc_google_prefix, "country_name" => $kpc_country, "language" => $kpc_language ), array( "id" => $kpcid ) );
            if( $rs ) {
            ?>
            <div id="message" class="updated below-h2">
                <p>Saved done!</p>
            </div>
            <?php
            }
        }
        else {
        ?>
        <div id="message" class="updated below-h2">
            <p>Error!Some field missing.</p>
        </div>
        <?php
        }
    }
    else if( isset( $_POST["kpc-add-country"] ) ) {
        $kpc_google_prefix = trim(strip_tags($_POST["kpc-google-prefix"]));
        $kpc_country = trim(strip_tags($_POST["kpc-country"]));
        $kpc_language = trim(strip_tags($_POST["kpc-language"]));
        if( !empty( $kpc_google_prefix ) && !empty( $kpc_country ) && !empty( $kpc_language ) ) {
            $rs = $wpdb->insert( "kpc_place", array( "id" => "", "google_place" => $kpc_google_prefix, "country_name" => $kpc_country, "language" => $kpc_language ) );
            if( $rs ) {
            ?>
            <div id="message" class="updated below-h2">
                <p>Saved done!</p>
            </div>
            <?php
            }
            else {
            ?>
            <div id="message" class="updated below-h2">
                <p>Error!Please try again.</p>
            </div>
            <?php
            }
        }
        else {
        ?>
        <div id="message" class="updated below-h2">
            <p>Error!Some field missing.</p>
        </div>
        <?php
        }
    }
    else if( isset( $_GET["delete"] ) && $_GET["delete"] == 1 && isset( $_GET["id"] ) ) {
        $kpcid = trim(strip_tags($_GET["id"]));
        if( !empty( $kpcid ) ) {
            $rs = $wpdb->delete( "kpc_place", array( "id" => $kpcid ) );
            if( $rs ) {
            ?>
            <div id="message" class="updated below-h2">
                <p>Saved done!</p>
            </div>
            <?php
            }
            else {
            ?>
            <div id="message" class="updated below-h2">
                <p>Error!Please try again.</p>
            </div>
            <?php
            }
        }
    }
?>
    <?php
    if( isset( $_GET["edit"] ) && $_GET["edit"] == 1 && isset( $_GET["id"] ) ) {
        $id = trim(strip_tags($_GET["id"]));
        $cargs = $wpdb->get_row( "SELECT * FROM kpc_place WHERE id={$id}" );
        if( !empty( $cargs ) ) {
    ?>
    
    <form action="" method="post" name="form-edit-country" id="form-edit-country">
        <p>
            <label for="kpc-google-prefix">Google Prefix</label>
            <input type="text" name="kpc-google-prefix" id="kpc-google-prefix" value="<?php echo $cargs->google_place; ?>" /><small>&nbsp;(Ex: .com.us )</small>
        </p>
        <p>
            <label for="kpc-country">Name</label>
            <input type="text" name="kpc-country" id="kpc-country" value="<?php echo $cargs->country_name; ?>" />
        </p>
        <p>
            <label for="kpc-language">Language</label>
            <input type="text" name="kpc-language" id="kpc-language" value="<?php echo $cargs->language; ?>" /><small>&nbsp;(Ex: en )</small>
        </p>
        <p>
            <input class="button-primary" type="submit" name="kpc-update-country" id="kpc-update-country" value="Update" />
        </p>
    </form>
    <?php
            }
        }
        else {
    ?>
    <form action="" method="post" name="form-add-country" id="form-add-country">
        <p>
            <label for="kpc-google-prefix">Google Prefix</label>
            <input type="text" name="kpc-google-prefix" id="kpc-google-prefix" /><small>&nbsp;(Ex: .com.us )</small>
        </p>
        <p>
            <label for="kpc-country">Name</label>
            <input type="text" name="kpc-country" id="kpc-country" />
        </p>
        <p>
            <label for="kpc-language">Language</label>
            <input type="text" name="kpc-language" id="kpc-language" /><small>&nbsp;(Ex: en )</small>
        </p>
        <p>
            <input class="button-primary" type="submit" name="kpc-add-country" id="kpc-add-country" value="Add New" />
        </p>
    </form>
    <?php
        }
        $country_args = $wpdb->get_results( "SELECT * FROM kpc_place" );
        if( !empty( $country_args ) ) {
    ?>
    <h4>List all country</h4>
    <table border="1">
        <thead>
            <th>ID</th>
            <th>Google prefix</th>
            <th>Country</th>
            <th>Language</th>
            <th>#</th>
            <th>#</th>
        </thead>
    <?php 
        foreach( $country_args as $country ) {
    ?>
    <tr>
        <td><?php echo $country->id; ?></td>
        <td><?php echo $country->google_place; ?></td>
        <td><?php echo $country->country_name; ?></td>
        <td><?php echo $country->language; ?></td>
        <td><a href="<?php echo admin_url( "admin.php?page=countries&edit=1&id={$country->id}" ); ?>">Edit</a></td>
        <td><a href="<?php echo admin_url( "admin.php?page=countries&delete=1&id={$country->id}" ); ?>">Delete</a></td>
    </tr>
    <?php
        }
    ?>
    </table>
    <?php
        }
    ?>
</div>