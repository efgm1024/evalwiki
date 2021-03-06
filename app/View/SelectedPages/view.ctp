<main class="col-md-8 col-md-offset-2">
	<?php 
		echo $this->Session->flash('failure-dismissable');
		echo $this->Session->flash('success-dismissable');
		echo $this->Session->flash('failure');
	?>
	<?php if (!count($pages) == 0 ): ?>
		<div class="row">
			<div class="btn-group">
				<?php if (!is_null($father)): ?>
					<?php echo $this->Html->link('<- Atrás', array('action' => 'view', 'id' => $father), array('class' => 'btn btn-info')); ?>
				<?php endif; ?>

				<?php echo $this->Html->link('Evaluar', array('action' => 'manage'), array('class' => 'btn btn-warning')); ?>
			</div>
		</div>

		<table class="table table-hover">
			<thead>
				<tr>
					<th>Página</th>
					<th>Acciones</th>
				</tr>
			</thead>

			<tbody>
				<?php foreach ($pages as $page): ?>
					<tr>
						<td>
							<?php echo str_replace("_", " ", $page['Page']['page_title']); ?>
						</td>
						<td>
							<?php echo $this->Html->link('Agregar', array('action' => 'addPage', 'id' => $page['Page']['page_id'], 'returnTo' => $returnTo), array('class' => 'btn btn-success')); ?>
							<?php echo $this->Html->link('Ver Hijas', array('action' => 'view', 'id' => $page['Page']['page_id']), array('class' => 'btn btn-primary')); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="row">
			<div class="btn-group">
				<?php if (!is_null($father)): ?>
					<?php echo $this->Html->link('<- Atrás', array('action' => 'view', 'id' => $father), array('class' => 'btn btn-info')); ?>
				<?php endif; ?>
				<?php echo $this->Html->link('Evaluar', array('action' => 'manage'), array('class' => 'btn btn-warning')); ?>
			</div>
		</div>
	<?php else: ?>
		<?php if (!is_null($father)): ?>
			<?php echo $this->Html->link('<- Atrás', array('action' => 'view', 'id' => $father), array('class' => 'btn btn-primary')); ?>
		<?php endif; ?>
		<?php echo $this->Html->link('Evaluar', array('action' => 'manage'), array('class' => 'btn btn-warning')); ?>
	<?php endif; ?>

</main>