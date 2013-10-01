<?php
	//Setting the $selected variable on element call will control how many items can be selected
	$multiple = isset($multiple) && is_bool($multiple) ? $multiple : false;
	$galleryid = isset($galleryid) ? '?galleryid='.$galleryid : '';
?>

<div id="MediaSelector">
	<a class="btn btn-primary" href="/media/media/filebrowser">Select Media</a>
	
	<div id="MediaSelected" class="clearfix">
		<?php if (isset($media) && !empty($media)) {
			foreach($media as $m) {
				echo $this->Media->display($m, array('width' => 150, 'height' => 150));	
			}
		}?>
	</div>

</div>

<script type="text/javascript">

var multiple = <?php echo $multiple ? 'true' : 'false'; ?>;

(function($) {
	
	var mediaNum = 0;
	var mediaSelected = [];
	var loaded = false;

	$('a[href="/media/media/filebrowser"]').click(function(e) {
		e.preventDefault();
		if(loaded) {
			$('body').append('<div class="modal-backdrop fade in"></div>');
			$('#MediaBrowserPopUp').show('slow');
		}else {
			$('#MediaSelected div.media-item').each(function(index, el) {
				var obj = $(el).clone();
				mediaSelected.push(obj);
			});
			
			$.post("/media/media/filebrowser<?php echo $galleryid; ?>", { multiple: multiple })
				.done(function(html) {
					html = '<div id="MediaBrowserPopUp"><a href="#insert" class="btn btn-primary">Insert</a><a href="#close" class="btn btn-primary">Close</a>'+html+'</div>';
	  				$('body').append(html);
	  				$('#MediaBrowserPopUp').css('left', ($(window).width()*.5)-($('#MediaBrowserPopUp').width()/2)).css('top', '30px');
	  				$('body').append('<div class="modal-backdrop fade in"></div>');
	  				for(var i=0 ; i < mediaSelected.length ; i++ ) {
	  					$('#mediaBrowser').find('#'+mediaSelected[i].attr('id')).closest('.media-item-con').addClass('selected');
	  				}
	  				$('#MediaBrowserPopUp').show('slow');
	  				loaded = true;
				});
		}
		
		
	});
	
	$(document).on('click', 'a[href="#insert"]', function(e) {
		e.preventDefault();
		mediaSelected = [];
		$('#mediaBrowser .selected').each(function(index, el) {
			var obj = $(el).find('.media-item').clone();
			mediaSelected.push(obj);
		});
		
		renderSelectedItems();
		
		$('#MediaBrowserPopUp').hide('slow');
		$('.modal-backdrop').remove();
	});
	
	
	
	$(document).on('click', 'a[href="#close"]', function(e) {
		e.preventDefault();
		$('#MediaBrowserPopUp').hide('slow');
		$('.modal-backdrop').remove();
	});
	
	function renderSelectedItems() {
		$('#MediaSelected').children().remove();
		if(mediaSelected.length > 0){
			
			for(var i=0 ; i < mediaSelected.length ; i++) {
				mediaSelected[i].append('<input type="hidden" value="'+mediaSelected[i].attr('id')+'" name="data[MediaAttachment]['+i+'][media_id]">');
				mediaSelected[i].appendTo($('#MediaSelected'));
			}
		}
	}
	
	
	
})(jQuery);
</script>

<style>

	#MediaBrowserPopUp {
		display:none;
		width: 60%;
		position: absolute;
		z-index: 9999;
		background: #fff;
		padding:30px;
		border-radius: 30px;
	}
	
	#MediaSelected .media-item {
		float: left;
		padding:10px;
		background: #fff;
	}
	
	div.jp-audio {
		width:100%;
	}
	div.jp-audio ul.jp-controls {
		width: 100%;
		clear: both;
	}
	div.jp-audio div.jp-progress {
		float:none;
		margin: 0 auto;
	}
	div.jp-audio div.jp-type-single div.jp-progress {
	    clear: both;
	    left: 0;
	    position: relative;
	    top: 3px;
	    width: 73%;
	}
	div.jp-current-time, div.jp-duration {
	    font-size: 0.64em;
	    font-style: oblique;
	    width: 60px;
	    padding-bottom:3px;
	}
	div.jp-audio a.jp-volume-max, div.jp-audio-stream a.jp-volume-max {
	   margin-left: 57px;
    margin-top: 11px;
	}	
	div.jp-audio div.jp-volume-bar {
	    left: 128px;
	    top: 36px;
	}
	div.jp-audio div.jp-type-single a.jp-mute, div.jp-audio div.jp-type-single a.jp-unmute {
    	margin-left: 9px;
	}
	div.jp-current-time, div.jp-duration {
	    font-size: 0.64em;
	    font-style: oblique;
	    margin-top: -14px;
	    padding-bottom: 3px;
	    width: 60px;
	}
	
</style>