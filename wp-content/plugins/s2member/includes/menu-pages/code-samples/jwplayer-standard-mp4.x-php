<div id="jw-container">JW Player® appears here.</div>
<script type="text/javascript" src="/jwplayer/jwplayer.js"></script>
<script type="text/javascript">
	jwplayer("jw-container").setup({modes: /* JW Player®. */
	[
		/* First try psuedo-streaming with Flash® player. */
		{type: "flash", provider: "http", src: "/jwplayer/player.swf",
			config: {file: "/wp-content/plugins/s2member-files/s2member-file-inline/video.mp4"}},
			/* Shortcode equivalent: [s2File rewrite="yes" inline="yes" download="video.mp4" /] */
	
		/* Else, try an HTML5 video tag. */
		{type: "html5", provider: "video",
			config: {file: "/wp-content/plugins/s2member-files/s2member-file-inline/video.mp4"}},
			/* Shortcode equivalent: [s2File rewrite="yes" inline="yes" download="video.mp4" /] */
	
		/* Else, this is a safe fallback. */
		{type: "download", /* Download the file. */
			config: {file: "/wp-content/plugins/s2member-files/s2member-file-inline/video.mp4"}}
			/* Shortcode equivalent: [s2File rewrite="yes" inline="yes" download="video.mp4" /] */
	],
	/* Set video dimensions. */ width: 480, height: 270
	});
</script>