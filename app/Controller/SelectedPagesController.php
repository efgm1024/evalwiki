<?php

class SelectedPagesController extends AppController {
	public function beforeFilter() {
		if (!$this->Session->check('User.id')) {
			$this->redirect(array('controller' => 'users', 'action' => 'logIn'));
		}
	}

	public function beforeRender() {
		$this->layout = 'normal';
		$this->set('title_for_layout', 'Profesores');
		$this->set('name', $this->Session->read('User.name'));
	}

	public function index() {
		$this->redirect(array('action' => 'view'));
	}

	public function view() {
		$this->loadModel('Page');
		$this->loadModel('MainPage');

		$current_page = -1;
		if (!empty($this->request->named)){
			$current_page = $this->request->named['id'];
		}

		$this->set('returnTo', $current_page);

		if ($current_page == -1) {
			$pages = $this->Page->getDataSource()->fetchAll('SELECT Page.page_id, Page.page_title FROM page Page JOIN main_pages MainPages ON Page.page_id = MainPages.page_id;');
			$this->set('pages', $pages);
			$this->set('father', null);
		}else {
			$pages = $this->Page->getDataSource()->fetchAll('SELECT Page.page_id, Page.page_title FROM page Page JOIN pagelinks PL ON PL.pl_title=Page.page_title WHERE PL.pl_from='.$current_page.';');
			$this->set('pages', $pages);
			$father = $this->Page->getDataSource()->fetchAll('SELECT pl_from FROM pagelinks JOIN page ON pl_title=page_title WHERE page_id='.$current_page.';');
			if ($father[0]['pagelinks']['pl_from'] == 1) {
				$this->set('father', -1);
			} else {
				$this->set('father', $father[0]['pagelinks']['pl_from']);
			}
		}

		if (count($pages) == 0) {
			$this->Session->setFlash('No hay paginas que mostrar.', 'failure', array(), 'failure');
		}
	}

	public function manage() {
		if (!$this->Session->check('SelectedPages.evaluate')) {
			$this->Session->setFlash('Debe seleccionar las paginas a evaluar.', 'failure-dismissable', array(), 'failure-dismissable');
			$this->redirect(array('action' => 'view'));
		} else {
			$this->loadModel('Page');
			$selected_pages = $this->Page->find('list', array(
				'fields' => array('Page.page_id', 'Page.page_title'),
				'conditions' => array('Page.page_id' => $this->Session->read('SelectedPages.evaluate'))
			));
			$this->set('selected_pages', $selected_pages);
		}
	}

	public function addPage() {
		if ($this->request->is('get') && !empty($this->request->named)) {
			if (!$this->Session->check('SelectedPages.evaluate')) {
				$this->Session->write('SelectedPages.evaluate', array($this->request->named['id']));
				$this->Session->setFlash('Se ha añadido la página para calificar.', 'success-dismissable', array(), 'success-dismissable');
			} else {
				$evaluate_pages = $this->Session->read('SelectedPages.evaluate');
				
				if (!in_array($this->request->named['id'], $evaluate_pages)) {
					array_push($evaluate_pages, $this->request->named['id']);
					$this->Session->write('SelectedPages.evaluate', $evaluate_pages);
					$this->Session->setFlash('Se ha añadido la página para calificar.', 'success-dismissable', array(), 'success-dismissable');
				} else {
					$this->Session->setFlash('La página ya está en la lista.', 'failure-dismissable', array(), 'failure-dismissable');
				}

			}
		}
		$this->redirect(array('action' => 'view', 'id' => $this->request->named['returnTo']));
	}

	public function removePage() {
		if ($this->request->is('get') && !empty($this->request->named) && $this->Session->check('SelectedPages.evaluate')) {
			$evaluate = $this->Session->read('SelectedPages.evaluate');
			unset($evaluate[array_search($this->request->named['id'], $this->Session->read('SelectedPages.evaluate'))]);
			$this->Session->write('SelectedPages.evaluate', $evaluate);
			$this->Session->setFlash('La página ha sido removida de la lista de evaluación.', 'success-dismissable', array(), 'success-dimissable');
		}

		$this->redirect(array('action' => 'manage'));
	}

	public function removeAll() {
		if ($this->Session->check('SelectedPages.evaluate')) {
			$this->Session->delete('SelectedPages.evaluate');
			$this->Session->setFlash('Se han removido todas las páginas a evaluar.', 'success-dismissable', array(), 'success-dismissable');
		}
		$this->redirect(array('action' => 'view'));
	}

	public function setParameters() {
		$this->loadModel('Period');
		$periods = $this->Period->find('all', array('fields' => array('Period.id', 'Period.period', 'Period.semester', 'Period.year')));
		$this->set('periods', $periods);
	}

public function evaluate() {
		if($this->request->is('post') && !empty($this->request->data)) {
			$data = $this->request->data['Parameters'];
			$this->set('data', $data);
 
			$sum_percent = (float) $data['contentWeight'] +
							(float) $data['presentationWeight'] + 
							(float) $data['colaborationWeight'] +
							(float) $data['organizationWeight'] +
							(float) $data['referencesWeight'] +
							(float) $data['languageWeight'] +
							(float) $data['consistencyWeight'] +
							(float) $data['contributionWeight'];
 
			if ($sum_percent != (float) 100) {
				$this->Session->setFlash('La suma de los pesos no es igual a 100%', 'failure-dismissable', array(), 'failure-dismissable');
				$this->redirect(array('action' => 'setParameters'));
			} else {
				$correct_grades = true;
 
				if (!empty($data['contentGrade'])) {
					if ((float) $data['contentGrade'] > (float) $data['contentWeight']) {
						$correct_grades = false;
					}
				}
 
				if (!empty($data['presentationGrade'])) {
					if ((float) $data['presentationGrade'] > (float) $data['presentationWeight']) {
						$correct_grades = false;
					}
				}
 
				if (!empty($data['colaborationGrade'])) {
					if ((float) $data['colaborationGrade'] > (float) $data['colaborationWeight']) {
						$correct_grades = false;
					}
				}
 
				if (!empty($data['organizationGrade'])) {
					if ((float) $data['organizationGrade'] > (float) $data['organizationWeight']) {
						$correct_grades = false;
					}
				}
 
				if (!empty($data['referencesGrade'])) {
					if ((float) $data['referencesGrade'] > (float) $data['referencesWeight']) {
						$correct_grades = false;
					}
				}
 
				if (!empty($data['languageGrade'])) {
					if ((float) $data['languageGrade'] > (float) $data['languageWeight']) {
						$correct_grades = false;
					}
				}
 
				if (!$correct_grades) {
					$this->Session->setFlash('Una(s) notas son mayores que sus pesos.', 'failure-dismissable', array(), 'failure-dismissable');
					$this->redirect(array('action' => 'setParameters'));
				}
			}
 
			$grades = array();
			if (!empty($data['contentGrade'])) {
				$grades['contenido'] = (float) $data['contentGrade'];
			} else {
				$grades['contenido'] = (float) $data['contentWeight'] * (float) $data['contentRubric'];
			}
 
			if (!empty($data['presentationGrade'])) {
				$grades['presentación'] = (float) $data['presentationGrade'];
			} else {
				$grades['presentación'] = (float) $data['presentationWeight'] * (float) $data['presentationRubric'];
			}
 
			if (!empty($data['colaborationGrade'])) {
				$grades['colaboración'] = (float) $data['colaborationGrade'];
			} else {
				$grades['colaboración'] = (float) $data['colaborationWeight'] * (float) $data['colaborationRubric'];
			}
 
			if (!empty($data['organizationGrade'])) {
				$grades['organización'] = (float) $data['organizationGrade'];
			} else {
				$grades['organización'] = (float) $data['organizationWeight'] * (float) $data['organizationRubric'];
			}
 
			if (!empty($data['referencesGrade'])) {
				$grades['referencias'] = (float) $data['referencesGrade'];
			} else {
				$grades['referencias'] = (float) $data['referencesWeight'] * (float) $data['referencesRubric'];
			}
 
			if (!empty($data['languageGrade'])) {
				$grades['lenguaje'] = (float) $data['languageGrade'];
			} else {
				$grades['lenguaje'] = (float) $data['languageWeight'] * (float) $data['languageRubric'];
			}
 
			$this->set('grades', $grades);
 
			//Grupales
			$this->loadModel('Page');
			$start_date = null;
			$end_date = null;
 
			if ($data['dates_or_range'] == 'periods') {
				$this->loadModel('Period');
				$period = $this->Period->find('first', array(
						'fields' => array('Period.start_date', 'Period.end_date'),
						'conditions' => array('Period.id' => $data['period_id'])
					));
				$start_date = $period['Period']['start_date'];
				$end_date = $period['Period']['end_date'];
			} else {
				$start_date = $data['start_date'];
				$end_date = $data['end_date'];
			}
			$start_date_format = date_format(date_create($start_date), 'YmdHis');
			$end_date_format = date_format(date_create($end_date), 'YmdHis');
			$query = 'SELECT rev_timestamp, rev_len, user_name FROM page JOIN revision ON page.page_id=revision.rev_page JOIN user ON revision.rev_user=user.user_id WHERE page.page_id IN ('.implode($this->Session->read('SelectedPages.evaluate'), ",").") AND revision.rev_timestamp BETWEEN '".$start_date_format."' AND '".$end_date_format."'";
			$individual_contributions = $this->Page->getDataSource()->fetchAll($query);
 
			$datos = array();
            foreach ($individual_contributions as $ind_contribution) {
                    $datos[] = array(
                    	'user_name' => $ind_contribution['user']['user_name'],
                    	'rev_len' => $ind_contribution['revision']['rev_len'],
                    	'rev_timestamp' => $ind_contribution['revision']['rev_timestamp']
                    	);
            }
 
            $fechas = array();
            $usuarios = array();
 
            foreach ($datos as $registro) {
                    $fecha_actual = substr($registro['rev_timestamp'], 0, 8);
                    $usuario = $registro['user_name'];
 
                    if (!in_array($fecha_actual, $fechas)) {
                            $fechas[] = $fecha_actual;
                    }
 
                    if (!in_array($usuario, $usuarios)) {
                            $usuarios[] = $usuario;
                    }
            }
 
            sort($usuarios);
 
            $tabla_principal;
 
            foreach($usuarios as $usuario) {
                    foreach($fechas as $fecha) {
                            $datos_por_fecha[$fecha] = 0;
                    }
                    $tabla_principal[$usuario] = $datos_por_fecha;
            }
 
            $dato_anterior = 0;
            foreach ($datos as $dato) {
                    $tabla_principal[$dato['user_name']][substr($dato['rev_timestamp'], 0, 8)] += $dato['rev_len'] - $dato_anterior;
                    $dato_anterior = $dato['rev_len'];
            }
 
            $totales_por_usuario = array();
 
            foreach($usuarios as $usuario) {
            	$totales_por_usuario[$usuario] = 0;
            }
 
            foreach ($usuarios as $usuario) {
                    foreach ($fechas as $fecha) {
                            $totales_por_usuario[$usuario] += $tabla_principal[$usuario][$fecha];
                    }
            }
 
            $this->set('users', $usuarios);
 
            if ($data['consistencyAlgorithm'] == 1) {
            	$max_participation = count(array_values($tabla_principal[$usuarios[0]]));
            	$consistencyGrades = array();
 
            	foreach($usuarios as $usuario) {
            		$current_participation = 0;
            		foreach($fechas as $fecha) {
            			if(!$tabla_principal[$usuario][$fecha] == 0) {
            				$current_participation++;
            			}
            		}
            		$consistencyGrades[$usuario] = (float) $current_participation / (float) $max_participation;
            	}
 
            	$this->set('consistencyGrades', $consistencyGrades);
            } else {
            	$max_participation = $data['maxParticipations'];
            	$consistencyGrades = array();
 
            	foreach($usuarios as $usuario) {
            		$current_participation = 0;
            		foreach($fechas as $fecha) {
            			if(!$tabla_principal[$usuario][$fecha] == 0) {
            				$current_participation++;
            			}
            		}
 
            		$grade = (float) $current_participation / (float) $max_participation;
            		$consistencyGrades[$usuario] =  $grade > 1.0 ? 1.0 : $grade;
            	}
 
            	$this->set('consistencyGrades', $consistencyGrades);
            }

            if($data['contributionAlgorithm']==1){
            	$total_contribucion=0;
            	foreach ($usuarios as $usuario){
            		$total_contribucion+=$totales_por_usuario[$usuario];
            	}
            	$contribucion_entre_usuarios=$total_contribucion/count($usuarios);
            	
            	$total_usuario_contribucion=array();
            	foreach ($usuarios as $usuario){
            		$total_usuario_contribucion[$usuario]=$totales_por_usuario[$usuario]/$contribucion_entre_usuarios;
            	}
            	$contribucion_por_usuario=array();
            	$maximo_contribucion=max($total_usuario_contribucion);
            	foreach ($usuarios as $usuario){
            		$contribucion_por_usuario[$usuario]=$total_usuario_contribucion[$usuario]/$maximo_contribucion;
            	}
            	$this->set('contribucion_por_usuario',$contribucion_por_usuario);
            }
            elseif ($data['contributionAlgorithm']==2) {
            	$variable_alpha=100;
            	$variable_tao=0.6;
            	$variable_init=0;
            	$total_contribucion=0;
            	foreach ($usuarios as $usuario){
            		$total_contribucion+=$totales_por_usuario[$usuario];
            	}
            	$contribucion_entre_usuarios=$total_contribucion/count($usuarios);
            	
            	$total_usuario_contribucion=array();
            	foreach ($usuarios as $usuario){
            		$total_usuario_contribucion[$usuario]=$totales_por_usuario[$usuario]/$contribucion_entre_usuarios;
            	}

            	$si_por_usuario=array();
            	foreach ($usuarios as $usuario){
            		$si_por_usuario[$usuario]=($variable_alpha*($variable_alpha-$variable_init)*pow(M_E,(($total_usuario_contribucion[$usuario]/$variable_tao))));
            	}
            	$maximo_si=max($si_por_usuario);
            	$contribucion_por_usuario=array();
            	foreach ($usuarios as $usuario){
            		$contribucion_por_usuario[$usuario]=$si_por_usuario[$usuario]/$maximo_si;
            	}
            	$this->set('contribucion_por_usuario',$contribucion_por_usuario);
            }
		}	
	}
}