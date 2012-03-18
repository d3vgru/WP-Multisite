<div id="jw-container">JW Player® appears here.</div>
<script type="text/javascript" src="/jwplayer/jwplayer.js"></script>

<?php /* A direct URL to the RTMP source; counting the file against the current User in real-time. */
$cfg = array ("file_download" => "video.mp4", "url_to_storage_source" => true, "count_against_user" => true); ?>

<?php /* API Function `s2member_file_download_url()` returns false if access is denied to the current User. */
if (($mp4 = s2member_file_download_url ($cfg, "get-streamer-array"))) { ?>

	<script type="text/javascript">
		jwplayer("jw-container").setup({modes: /* JW Player®. */
		[
			/* First try real-time streaming with Flash® player. */
			{type: "flash", provider: "rtmp", src: "/jwplayer/player.swf",
				config: {streamer: "<?php echo $mp4["streamer"]; ?>", file: "<?php echo $mp4["file"]; ?>"}},
		
			/* Else, try an HTML5 video tag. */
			{type: "html5", provider: "video",
				config: {file: "<?php echo $mp4["url"]; ?>"}},
		
			/* Else, this is a safe fallback. */
			{type: "download", /* Download the file. */
				config: {file: "<?php echo $mp4["url"]; ?>"}}
		],
		/* Set video dimensions. */ width: 480, height: 270
		});
	</script>

<?php } else /* Access is denied to the current User. */ { ?>
	Sorry, you do NOT have access to this file.
<?php } ?>