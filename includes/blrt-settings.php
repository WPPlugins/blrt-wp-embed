<div class="wrap blrt-embed-plugin">
    <h2>Blrt plugin settings</h2>
    <form action='options.php' method='post'><?php
    	settings_fields( 'blrt_settings' );
    	do_settings_sections( 'blrt_settings' );
    	submit_button();
	?></form>
	<h4>Debug data</h4>
	<textarea>{ jal_db_version: '<?= get_option('jal_db_version'); ?>', blrtwpembed_table_gallery_version: '<?= get_option('blrtwpembed_table_gallery_version'); ?>', blrtwpembed_upgraded_from_jal: '<?= get_option('blrtwpembed_upgraded_from_jal'); ?>', BLRT_WP_EMBED_VERSION: '<?= BLRT_WP_EMBED_VERSION; ?>' }</textarea>
</div>