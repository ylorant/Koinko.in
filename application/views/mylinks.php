<?php global $config; ?>
<table class="my-links table table-bordered table-stripped">
	<thead>
		<tr>
			<th style="width:45%;">Long URL</th>
			<th style="width:45%;">Short URL</th>
			<th style="width:10%;">Clicks</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($list as $url): ?>
			<tr>
				<td>
					<a href="<?php echo $url->url; ?>" target="_blank"><?php echo strlen($url->url) > 40 ? substr($url->url, 0, 37).'...' : $url->url; ?></a>
				</td>
				<td>
					<a href="<?php echo $config['url_prefix'].$url->keyword; ?>" target="_blank"><?php echo $config['url_prefix'].$url->keyword; ?></a>
				</td>
				<td>
					<?php echo $url->clicks; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
