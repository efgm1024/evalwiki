<div class="col-md-8 col-md-offset-2">
	<?php foreach ($user_revision as $revision): ?>
		<div class="panel panel-primary">
			<div class="panel-heading">Contrubución</div>
			
			<div class="panel-body">
				<pre><?php echo $revision; ?></pre>
			</div>
		</div>
	<?php endforeach; ?>
</div>