<?php include('header.php'); ?>
	
    <div id="content">
		<div class="url-box container">
			<form method="post" id="shorten-form" action="" onsubmit="return koinkoin.shorten(this);">
				<div class="input-append">
					<input type="text" class="long-url" name="url" placeholder="Type an url to shorten..." />
					<button onclick="$('#shorten-form').submit();" class="btn btn-shorten">Shorten !</button>
				</div>
			</form>
		</div>
    </div>

<?php include('footer.php'); ?>
