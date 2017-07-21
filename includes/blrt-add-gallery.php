<?php
$id = '';
$title = 'Add new gallery';
$publish = 'Publish';
$generation = 'Publish this Blrt gallery to see the generated shortcode.';
$shortcode = '';
$class = '';
$prev_id = 0;
$url_list = '';
$add_new = 'hidden';
$error = '';

global $wpdb;
$table = $wpdb->prefix . "blrtwpembed"; 
$result =  $wpdb->get_col("SELECT ID FROM $table ORDER BY ID DESC LIMIT 1");

if (!$result) {
    $prev_id = 0;
}
else{
    foreach ( $result as $prev_id ) {
    }
}

if ( isset( $_POST["publish"] ) && $_POST["name"] != "" ) {
    $name = strip_tags($_POST["name"], " ");
    if($_POST['publish'] == 'Update'){
        $id = $_GET['gallery'];
        $wpdb->update( 
            $table, 
            array( 
                'name' => $name,  // string
                'url' => $_POST['url'],   // string
                'time' => current_time('mysql'),
                'title' => $_POST['title'],
                'mobileview' => $_POST['mobile-layout'],
                'desktopview' => $_POST['desktopview']
            ), 
            array( 'ID' => $id ), 
            array( 
                '%s',   // value1
                '%s',    // value2
                '%s',
                '%s',
                '%s',
                '%s'
            ), 
            array( '%d' ) 
        );
    }
    else{
        $wpdb->insert( 
            $table, 
            array( 
                'name' => $name,
                'time' => current_time( 'mysql' ),
                'title' => $_POST['title'],
                'url' => $_POST['url'],
                'mobileview' => $_POST['mobile-layout'],
                'desktopview' => $_POST['desktopview']
            )
        );
    }
}



if(isset($_GET['gallery'])){
    $id = $_GET['gallery'];
    $title = 'Edit gallery';
    $publish = 'Update';
    $generation = 'Paste this shortcode in a page or post to insert this gallery.';
    $shortcode = '[blrt-gallery id="'.$id.'"]';
    $class = 'shortcode-holder';
    $result = $wpdb->get_row("SELECT * FROM $table WHERE ID = $id", ARRAY_A);
    if($result === null){
        $error = "Please enter title and Blrt url(s) for the gallery.";
    }
    $result_name = $result['name'];
    $result_title = $result['title'];
    $url_list = $result['url'];
    $result_time = $result['time'];
    $add_new = "page-title-action";
    $mobileview = $result['mobileview'];
    $desktopview = $result['desktopview'];
}
?>
<div class = "wrap blrt-embed-plugin">
    <h2><?php echo $title?> 
    <a href="./admin.php?page=blrt-add-gallery" class="<?php echo $add_new ?>">Add New</a>
    </h2>
    <form action="./admin.php?page=blrt-add-gallery&gallery=<?php if(isset($_GET['gallery'])){ echo $id; } else { echo $prev_id + 1; } ?>&action=edit" method="post">
        <div class="blrt-embed-col-8">
            <input type='text' spellcheck="true" name='name' placeholder="Gallery name" autocomplete="off" value="<?php if(isset($_GET['gallery'])){ echo $result_name; } ?>" <?php if(isset($_GET['gallery'])){ echo 'readonly'; } ?>>
            <div class = "container-add-new-gallery" >
                <h3> Gallery Title </h3>
                <p> This optional title will appear above the gallery </p>
                <input type='text' spellcheck="true" name='title' placeholder="Title" autocomplete="off" value="<?php if(isset($_GET['gallery'])){ echo $result_title; } ?>">
                <!--<h3> Mobile layout </h3>
                <input type='radio' name='mobile-layout' value='video' id='mobile-layout-video' <?= $mobileview == 'video' ? 'checked' : '' ?>> <label for='mobile-layout-video'>Videos</label> <input type='radio' name='mobile-layout' value='snippet' id='mobile-layout-snippet' <?= $mobileview == 'snippet' ? 'checked' : '' ?>> <label for='mobile-layout-snippet'>Snippets</label>-->
                <h3> Add a Blrt </h3>
                <p>Paste a URL for a Blrt to add to your gallery. Find the direct link to your Blrt in Blrt Web, Blrt for iOS or Android.</p>
                <input type="text" name="link" autocomplete="off" id="blrt-embed-link-input">
                <div class = "button-add-new-link" > Add </div> 
                <span class="spinner"></span>
                <div class = "message-add-new-link"></div>
                <h3> Blrts in Gallery </h3>
                <p> These Blrts will appear in this gallery, in whatever page or post it's inserted into. </p>
                <ul id = "blrt-embed-url-placeholder">
                    <input type="hidden" name='url' value="<?php if(isset($_GET['gallery'])){ echo $url_list; }?>">
                    <?php 
                        if($url_list !== ''){
                            $no_whitespaces = preg_replace( '/\s*,\s*/', ',', filter_var( $url_list, FILTER_SANITIZE_STRING ) ); 
                            $array_url = explode( ',', $no_whitespaces );
                            foreach($array_url as $url){
                                if($url !== ''){
                                    $meta = explode('+',$url);
                                    $link = $meta[0];
                                    $title = $meta[1];
                                    $fallback = $meta[2];
                                    ?>
                                    <li class="blrt-wp-url-single">
                                        <a class='blrt-title-link blrt-link' href="<?php echo $link; ?>" target="_blank"><h4><?php echo $title; ?></h4></a><span class="dashicons dashicons-trash"></span><span class="dashicons dashicons-arrow-up-alt"></span><span class="dashicons dashicons-arrow-down-alt"></span>
                                        <br/>
                                        <p class='fallback-section'>
                                            URL: <input class='blrt-url' type="text" value="<?php echo $link; ?>" readonly/> <a class="fallback-link" href="<?php echo $fallback; ?>"><?= $fallback ? "Edit fallback video" : "Add fallback video"; ?></a>
                                            <span class="fallback-field">
                                                <input type="text" name="fallback_link" class='fallback-input' value="<?php echo $fallback; ?>"><button class="fallback-add">Save</button><?php if ($fallback) { ?><button class="fallback-remove">Remove</button><?php } ?><button class="fallback-cancel">Cancel</button>
                                            </span>
                                        </p>
                                    </li> <?php
                                }
                            }
                        }
                    ?>
                </ul>
            </div>
        </div>
        <div class="blrt-embed-col-4">
            <div class ="container-publish">
                <h3>Publish</h3>
                <div class ="publish-time"><?php if(isset($_GET['gallery'])){ echo "Updated at: ".$result_time; }?></div>
                <div class = "container-publish-action">
                    <div id="publishing-action">
                        <span class="spinner"></span>
                        <input type="submit" name="publish" id="publish" class="button button-primary button-large" value="<?php echo $publish ?>">
                    </div>
                </div>
            </div>
            <div class="container-shortcode" id='shortcode-builder'>
                <h3>Shortcode builder</h3>
                <form>
                <input id='gallery-id' name='gallery-id' type='hidden' class='hidden' value='<?= $id ?>'>
                <h4>Slider position</h4>
                <input type='radio' name='slider-position' value='right' checked id='slider-position-right'> <label for='slider-position-right'>Right</label> <input type='radio' name='slider-position' value='bottom' id='slider-position-bottom'> <label for='slider-position-bottom'>Bottom</label> <input type='radio' name='slider-position' value='left' id='slider-position-left'> <label for='slider-position-left'>Left</label> <input type='radio' name='slider-position' value='top' id='slider-position-top'> <label for='slider-position-top'>Top</label>
                <h4>Gallery size</h4>
                <input type='radio' name='gallery-size' value='small' id='gallery-size-small'> <label for='gallery-size-small'>S</label> <input checked type='radio' name='gallery-size' value='medium' id='gallery-size-medium'> <label for='gallery-size-medium'>M</label> <input type='radio' name='gallery-size' value='large' id='gallery-size-large'> <label for='gallery-size-large'>L</label> <input type='radio' name='gallery-size' value='extra' id='gallery-size-extra'> <label for='gallery-size-extra'>XL</label>
                <h4>Gallery skin</h4>
                <input type='radio' name='gallery-skin' value='light' checked id='gallery-skin-light'> <label for='gallery-skin-light'>Light</label> <input type='radio' name='gallery-skin' value='dark' id='gallery-skin-dark'> <label for='gallery-skin-dark'>Dark</label>
                <h4>Mobile view</h4>
                <input type='radio' name='mobile-view' value='video' checked id='mobile-view-video'> <label for='mobile-view-video'>Video</label> <input type='radio' name='mobile-view' value='snippet' id='mobile-view-snippet'> <label for='mobile-view-snippet'>Snippet</label>
                </form>
                <p><?php echo $generation ?></p> 
                <textarea readonly id='shortcode-output' class="<?php echo $class ?>"><?php echo $shortcode ?></textarea>
            </div>
        </div>
    </form>
</div>
