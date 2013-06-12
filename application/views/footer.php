	</div>
	<div id="bottom">
		<div class="top"></div>
	</div>
	
	<div class="bottom-links left">
		<a onclick="koinkoin.showMyLinks();">My links</a>
	</div>
	<div class="bottom-links right">
		&copy; 2012 Koinko.in - <a href="http://github.com/ylorant/Koinko.in">Source code</a>
		<?php if($config['debug']): ?>
			- <a class="debug-window-toggle" onclick="$('.debug-window').toggle();">Toggle debug window</a>
		<?php endif; ?>
	</div>
</body>
</html>
