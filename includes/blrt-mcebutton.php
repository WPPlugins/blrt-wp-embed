<?php 
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );

global $wpdb;
$table = $wpdb->prefix . "blrtwpembed";
$result = $wpdb->get_results("SELECT * FROM $table");

?>

<style>
.blrt-mce-container{
	font-family: Open sans;
}
.blrt-mce-container .header{
    padding-top : 10px;
    font-size: 16px;
    font-weight: 600;
}
.blrt-mce-container select{
	margin-bottom: 10px;
	margin-top: 5px;
	width: 100%;
	font-size: 16px;
}
.blrt-mce-container input[type="submit"]{
	padding: 5px 25px;
    border: none;
    border-radius: 5px;
    font-size: 18px;
    background: #00e7a8;
    cursor: pointer;
    margin-top: 20px;
}

.blrt-mce-container input[type="text"]{
	width: 100%;
	border-radius: 5px;
    font-size: 18px;
}

.blrt-mce-container .row-action{
	margin-top: 5px;
}

.blrt-mce-container .row-option{
	margin-top: 20px;
}

.blrt-mce-container .radio{
	padding: 20px;
	border-bottom: 3px solid #ccc;
	margin-bottom: 10px;
}

.blrt-mce-container .tab{
	padding: 0 20px;
}

.hidden{
	display: none;
}

</style>

<div class='blrt-mce-container'>	
	<div class="radio">
		<input type="radio" name="blrt_gallery_embed" value="insert_blrt_gallery" checked><label>Insert Blrt Gallery</label>
		<input type="radio" name="blrt_gallery_embed" value="embed_blrt"><label>Embed Blrt</label>
	</div>
	<div class="tab">
		<form id ="insert-gallery-form">
			<div class="header"> Select Gallery </div>
			<select id="gallerylist">
				<?php foreach($result as $row){ ?>
	    			<option value="<?php echo $row->id ?>"> <?php echo $row->name ?></option>
	    		<?php } ?>
			</select>
			<div class="option">
				<div class = "row-action">
		            <span class = "edit"> 
		                <a class ="row-title" href = "<?php echo admin_url(); ?>admin.php?page=blrt-add-gallery&gallery=1&action=edit" target="_parent"> Edit </a> |
		            </span>
		            
		            <span class = "trash">
		                <a class ="row-title" href = "<?php echo admin_url(); ?>admin.php?page=blrt-add-gallery" target="_parent"> Create new </a>
		            </span>
		        </div>
			</div>
			<div class="header"> Slider position </div>
			<select id="position">
				<option value="right"> Right </option>
				<option value="bottom"> Bottom </option>
				<option value="left"> Left </option>
				<option value="top"> Top </option>
			</select>
			<div class="header"> Gallery size </div>
			<select id="size">
				<option value="small"> Small </option>
				<option value="medium"> Medium </option>
				<option value="large"> Large </option>
				<option value="extra"> Extra large </option>
			</select>
			<div class="header"> Gallery skin </div>
			<select id="skin">
				<option value="light"> Light </option>
				<option value="dark"> Dark </option>
			</select>
			<input type='submit' value="Insert">

		</form>
	</div>

	<div class="tab hidden">
		
			<div class="header"> Blrt URL </div>
			<input type="text" name="blrt_embed_url" autofocus>
			<div class="row-option">
				<input type="radio" name="blrt_embed_tab" value="webplayer" checked><label>Web Player</label>
				<input type="radio" name="blrt_embed_tab" value="snippetonly"><label>Snippet only</label>
			</div>
			<div class="mini-tab">
				<form id="player-blrt-form">
					<div class="header"> Embed size </div>
					<select id="embed-size">
						<option value="small"> Small(426x320)</option>	
						<option value="medium"> Medium(640x480)</option>	
						<option value="large"> Large(854x640)</option>	
						<option value="extra"> Extra large(1280x960)</option>	
					</select>
					<input type='submit' value="Insert"><span class="spinner"></span>
				</form>
			</div>
			<div class="mini-tab hidden">
				<form id="snippet-blrt-form">
					<div class="header"> Theme </div>
					<input type="radio" name="blrt_snippet_theme" value="0" checked><label>Light</label>
					<input type="radio" name="blrt_snippet_theme" value="1"><label>Dark</label>
					<div class="header"> Orientation </div>
					<div>
						<input type="radio" name="blrt_snippet_orientation" value="0" checked><label>Horizontal</label>
						<input type="radio" name="blrt_snippet_orientation" value="1"><label>Vertical</label>
					</div>
					<input type='submit' value="Insert"><span class="spinner"></span>
				</form>
			</div>
			
		</form>
	</div>


</div>

<script type="text/javascript">
	var passed_arguments = top.tinymce.activeEditor.windowManager.getParams();
	var $ = passed_arguments.jquery;
	var jq_context = document.getElementsByTagName("body")[0];

	$('input[name="blrt_gallery_embed"]', jq_context).change(function(){
		$('.tab',  jq_context).toggleClass('hidden');
	});

	$('input[name="blrt_embed_tab"]', jq_context).change(function(){
		$('.mini-tab',  jq_context).toggleClass('hidden');
	});

	//update link of edit button when dropdown value changes
	$('#gallerylist', jq_context).change(function(){
		var newval = $( "#gallerylist option:selected", jq_context ).val();
		var current = $('.row-action .edit .row-title', jq_context).attr("href");
	    var elink = current.indexOf('page=blrt-add-gallery&gallery=');
	    var change = current.substr(0, elink) + 'page=blrt-add-gallery&gallery=' + newval + '&action=edit';
	    $('.row-action .edit .row-title', jq_context).attr("href", change);
	});
	
	$("#insert-gallery-form", jq_context).submit(function(event) {
        event.preventDefault();

        //  Get the inputs 
        var id = $("#gallerylist", jq_context).val();
        var size = $("#size", jq_context).val();
        var pos = $("#position", jq_context).val();
        var skin = $("#skin", jq_context).val();

        //  Construct the shortcode
        var shortcode = '[blrt-gallery';

        //  Do we have a value in the input?
        if( id != "" ) {
            //  Yes, we do. Add the text argument to the shortcode.
            shortcode += ' id="' + id + '"';
            shortcode += ' size="' + size + '"';
            shortcode += ' position="' + pos + '"';
            shortcode += ' skin="' + skin + '"';
        }

        //  Close the shortcode
        shortcode += ']';


        //  Insert the shortcode into the editor
        passed_arguments.editor.selection.setContent(shortcode);
        passed_arguments.editor.windowManager.close();
	});

	function checkBlrt(val){
		
		var index = val.indexOf('/blrt/');
		if(index < 0){
			alert('Invalid Blrt link');
		}
		else{
			index = index + ('/blrt/').length;
            var id = val.substr(index, 10);
            var fallback = val.substr(index+10,val.length);
            if(!fallback.startsWith('?') && fallback != ''){
                alert('Invalid Blrt link');
            }
            else{
            	$.ajax({
                    method: "GET",
                    url: "https://m.blrt.co/blrt/"+id+".json",
                    dataType: 'jsonp',
                    crossDomain: true,
                    success : function(response){
                        if(response.success){
                            var link  = "https://e.blrt.com/embed/blrt/"+id+fallback;  
                            alert(link);
                        }
                        else{
                            alert('Invalid Blrt link');
                        }  
                    },
                    error: function(){
                        alert('Fail to query data');
                    }
                })
            }
		}
	}

	$("#player-blrt-form", jq_context).submit(function(event) {
        event.preventDefault();

        //  Get the inputs 
        var blrt= $('input[name="blrt_embed_url"]',jq_context).val();
        var size = $("#embed-size", jq_context).val();
        

        //  Construct the shortcode
        var shortcode = '<iframe frameborder="0" allowfullscreen';
        if(size == "small"){
        	shortcode += ' width="426" height="320" ';
        }else if(size = "medium"){
        	shortcode += ' width="640" height="480" ';
        }else if(size = "large"){
        	shortcode += ' width="854" height="640" ';
        }else {
        	shortcode += ' width="1280" height="960" ';
        }
        //  Do we have a value in the input?
        if( blrt != "" ) {
        	var index = blrt.indexOf('/blrt/');
			if(index < 0){
				alert('Invalid Blrt link');
			}
			else{
				index = index + ('/blrt/').length;
	            var id = blrt.substr(index, 10);
	            
	            if(id.length < 10 ){
	                alert('Invalid Blrt link');
	            }
	            else{
	            	$.ajax({
	                    method: "GET",
	                    url: "https://m.blrt.co/blrt/"+id+".json",
	                    dataType: 'jsonp',
	                    crossDomain: true,
	                    success : function(response){
	                        if(response.success){
	                            var link  = "https://e.blrt.com/embed/blrt/"+id;  
	                            shortcode += ' src="' + link + '"';
	                            //  Close the shortcode
						        shortcode += '></iframe>';


						        //  Insert the shortcode into the editor
						        passed_arguments.editor.selection.setContent(shortcode);
						        passed_arguments.editor.windowManager.close();
	                        }
	                        else{
	                            alert('Invalid Blrt link');
	                        }  
	                    },
	                    error: function(){
	                        alert('Fail to query data');
	                    }
	                })
	            }
			}
           
            
        }
        else{
        	alert('Invalid Blrt url');
        }
        
	});

	$("#snippet-blrt-form", jq_context).submit(function(event) {
        event.preventDefault();
        const ParseServerURL = 'https://m.blrt.co';
        const SnippetTheme_Light    = 0;
		const SnippetTheme_Dark     = 1;
		const SnippetOrientation_Horizontal = 0;
		const SnippetOrientation_Vertical   = 1;
		var theme = 0;//light theme
		var orientation = 0; //horizontal snippet
        //  Get the inputs 
        var blrt= $('input[name="blrt_embed_url"]',jq_context).val();
        theme = $("input[name='blrt_snippet_theme']:checked",jq_context).val();
        orientation = $("input[name='blrt_snippet_orientation']:checked",jq_context).val();
        //  Construct the shortcode
        var shortcode = '';

        //  Do we have a value in the input?
        if( blrt != "" ) {
        	var index = blrt.indexOf('/blrt/');
			if(index < 0){
				alert('Invalid Blrt link');
			}
			else{
				index = index + ('/blrt/').length;
	            var id = blrt.substr(index, 10);
	            
	            if(id.length < 10 ){
	                alert('Invalid Blrt link');
	            }
	            else{
	            	$.ajax({
	                    method: "GET",
	                    url: "https://m.blrt.co/blrt/"+id+".json",
	                    dataType: 'jsonp',
	                    crossDomain: true,
	                    success : function(response){
	                        if(response.success){
	                        	var data = response.data;
	                            var embedURL  = "https://e.blrt.com/embed/blrt/"+id;  
	                            var width = 450;
        						var height = 750;
        						var newURL = embedURL + ((embedURL.indexOf('?') > 0) ? '&' : '?') + 'player=0&maxwidth=' + width + '&maxheight=' 
        							+ height + (SnippetTheme_Dark == theme ? "&theme=dark" : "") + (SnippetOrientation_Vertical == orientation ? "&orientation=vertical" : "");
        						var landingPageURL = ParseServerURL + '/blrt/' + id;
        						var isPublic = data.isPublicBlrt;
        						var title = data.title;
        						var titlePrefix = isPublic ? "Public" : "Private";
        						var numOfPages = data.pages;
        						var durationInSeconds = data.duration;
        						var duration = (function(seconds) {
						            var h = Math.floor(seconds / 3600);
						            var m = Math.floor(seconds / 60) % 60;
						            var s = seconds % 60;

						            var output = h ? (h + ':') : '';
						            output += String(h && m < 10 ? '0' : '') + m + ':';
						            output += String(s < 10 ? '0' : '') + s;

						            return output
						        })(durationInSeconds);
						        var pageStr = "" + numOfPages + " page" + (numOfPages > 1 ? 's' : '');
						        var author = data.creator;
	                            
						        shortcode = '<blockquote data-blrt-oembed=\'{"iframe":"' + newURL + '","maxwidth":' + width + ',"maxheight":' + height 
								            + '}\' style="max-width:' + width + 'px;max-height:' + height + 'px" class="blrt-oembed"><p class="blrt-title"><strong><a href="' 
								            + landingPageURL + '" target="_blank">' + title + '</a></strong></p><div class="blrt-callout">- <em><strong>' + titlePrefix 
								            + ' Blrt</strong></em> with ' + pageStr 
								            + ' (<a href="//www.blrt.com/whats-a-blrt" target="_blank">what is a Blrt?</a>)</div><div class="blrt-meta"><span class="blrt-meta-author">- <em>Author:</em> ' 
								            + author + ' </span> <span class="blrt-meta-duration">- <em>Duration:</em> ' + duration 
								            + '</span></div><div style="display:none" class="blrt-script"><script async="async" src="https://e.blrt.com/js/oembed.js"><\/script></div></blockquote>';


						        //  Insert the shortcode into the editor
						        passed_arguments.editor.selection.setContent(shortcode);
						        passed_arguments.editor.windowManager.close();
	                        }
	                        else{
	                            alert('Invalid Blrt link');
	                        }  
	                    },
	                    error: function(){
	                        alert('Fail to query data');
	                    }
	                })
	            }
			}
           
            
        }
        else{
        	alert('Invalid Blrt url');
      	}
	});
</script>