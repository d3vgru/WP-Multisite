<div id="jw-container">JW Player® appears here.</div>
<script type="text/javascript" src="/jwplayer/jwplayer.js"></script>

<script type="text/javascript">
	/* The Shortcode here will return a JSON object for JavaScript notation. */
	/* A direct URL to the RTMP source; counting the file against the current User in real-time. */
	/* API Shortcode `s2File` returns a null object if access is denied to the current User/Member. */
	var mp4 = [s2File download="video.mp4" url_to_storage_source="true" count_against_user="true" get_streamer_json="true" /];
</script>

<script type="text/javascript">
	if(typeof mp4 === 'object') /* `s2File` returns a null object if access is denied to the current User. */
		{
			jwplayer("jw-container").setup({modes: /* JW Player®. */
			[
				/* First try real-time streaming with Flash® player. */
				{type: "flash", provider: "rtmp", src: "/jwplayer/player.swf",
					config: {streamer: mp4['streamer'], file: mp4['file']}},
			
				/* Else, try an HTML5 video tag. */
				{type: "html5", provider: "video",
					config: {file: mp4['url']}},
			
				/* Else, this is a safe fallback. */
				{type: "download", /* Download the file. */
					config: {file: mp4['url']}}
			],
			/* Set video dimensions. */ width: 480, height: 270
			});
		}
	else /* Else, `s2File` returned a null object value. */
		{
			document.write('Sorry, you do NOT have access to this file.');
		}
</script>