<main class="col-md-6 col-md-offset-3">
	<?php
		echo $this->Session->flash('success-dismissable');
		echo $this->Session->flash('failure-dismissable');
	?>
	<h2>Agregar Periodo</h2>
	<?php 
		echo $this->Form->create('Period', array('class' => 'form-horizontal', 'action' => 'add'));
		
		$periods = array(
			1 => 'Primer Periodo', 
			2 => 'Segundo Periodo',
		);

		$semester = array(
			1 => 'Primer Semestre',
			2 => 'Segundo Semestre' 
		);
		
		echo $this->Form->input('semester', array(
								'options' => $semester, 
								'empty' => false, 
								'class' => 'form-control',
								'div' => 'form-group',
								'label' => 'Semestre'
		));
		
		echo $this->Form->input('period', array(
								'options' => $periods, 
								'empty' => false, 
								'class' => 'form-control',
								'div' => 'form-group',
								'label' => 'Periodo'
		));
	?>

	<div class="form-group">
		<label class="control-label">Año</label>
		<input type="text" class="form-control" name="data[Period][year]" required data-validation="custom" data-validation-regexp="[0-9]{4}" placeholder="2013" data-validation-error-msg="Ingrese un año correcto">
	</div>

	<div class="form-group">
		<label class="control-label">Fecha de inicio</label>
		<input type="text" class="form-control calendar" name="data[Period][start_date]" required data-validation="date" data-validation-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" data-validation-error-msg="Ingrese el formato correcto de fecha" id="StartDate" data-date-format="yyyy-mm-dd" value=<?php echo '"'.date('Y-m-d').'"'; ?>>
	</div>

	<div class="form-group">
		<label class="control-label">Fecha Final</label>
		<input type="text" class="form-control calendar" name="data[Period][end_date]" required data-validation="end_date date" data-validation-format="yyyy-mm-dd" placeholder="yyyy-mm-dd" id="EndDate" data-date-format="yyyy-mm-dd" value=<?php echo '"'.date('Y-m-d', strtotime('+3 months')).'"'; ?>>
	</div>

	<?php
		echo $this->Form->end(array('label' => 'Guardar','div' => 'row', 'class' => 'btn btn-primary'));
	?>
</main>