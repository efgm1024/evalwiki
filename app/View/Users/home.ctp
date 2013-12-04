<main class="col-md-10 col-md-offset-2">
	<div class="row">
		<section class="col-md-4 home-option">
			<h2>Paginas principales</h2>
			<?php echo $this->Html->link('Agregar', array('controller' => '', 'action' => ''), array('class' => 'btn btn-info')); ?>
			<?php echo $this->Html->link('Administrar', array('controller' => '', 'action' => ''), array('class' => 'btn btn-info')); ?>
		</section>
		<section class="col-md-4 col-md-offset-1 home-option">
			<h2>Profesores</h2>
			<?php echo $this->Html->link('Agregar', array('controller' => 'teachers', 'action' => 'add'), array('class' => 'btn btn-info')); ?>
			<?php echo $this->Html->link('Administrar', array('controller' => '', 'action' => ''), array('class' => 'btn btn-info')); ?>
		</section>
	</div>
	<div class="row">
		<section class="col-md-4 home-option">
			<h2>Periodos</h2>
			<?php echo $this->Html->link('Agregar', array('controller' => '', 'action' => ''), array('class' => 'btn btn-info')); ?>
			<?php echo $this->Html->link('Administrar', array('controller' => '', 'action' => ''), array('class' => 'btn btn-info')); ?>
		</section>
		<section class="col-md-4 col-md-offset-1  home-option">
			<h2>Clases</h2>
			<?php echo $this->Html->link('Listar', array('controller' => '', 'action' => ''), array('class' => 'btn btn-info')); ?>
		</section>
	</div>
</main>