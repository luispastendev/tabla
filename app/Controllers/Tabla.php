<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Libraries\TableLibrary;

class Tabla extends BaseController
{
	
	use ResponseTrait; 

	protected $TableLibrary;

	public function __construct(){
		$this->TableLibrary = new TableLibrary;
	}

	/**
	 * Endpoint para renderizar la vista principal he inyectar filtros de las 
	 * columnas a js 
	 *
	 * @return View // ver view/tabla.php
	 */
	public function index()
	{
		return view('tabla', ['columns' => $this->TableLibrary->getDataAllColumns()]);
	}

	/**
	 * Endpoint para realizar el reRender de filtros de tabla cada que 
	 * se apliquen nuevos filtros
	 *
	 * @return HTTP application/json
	 */	
	public function reRenderColumns(){

		$query = $this->request->getPost();

		$payload = $this->TableLibrary->getColumnsByMatches([
			'page'          => $query['draw'],
			'len'           => $query['length'],
			'start'			=> $query['start'],
			'column_search' => $query['columns'],
			'sort'          => $query['order'],
			'search'        => $query['search']['value']
		], false);

		return $this->respond($payload);
	}

	/**
	 * Método para generar los valores de la tabla paginados
	 *
	 * @return HTTP application/json
	 */
	public function getData(){

		$page    = $this->request->getVar('draw');
		$len     = $this->request->getVar('length');
		$start   = $this->request->getVar('start');
		$columns = $this->request->getVar('columns');
		$order   = $this->request->getVar('order');
		$search  = $this->request->getVar('search');

		$payload = $this->TableLibrary->getTable([
			'page'          => $page,
			'len'           => $len,
			'start'         => $start,
			'column_search' => $columns,
			'sort'          => $order,
			'search'        => $search['value']
		]);
		
		return $this->respond($payload);
	}

	/**
	 * Genera los datos necesarios segun el estado actual de la tabla
	 * para usarlos en la exportacion de excel
	 *
	 * @return HTTP application/json
	 */
	public function excelGenerate(){

		$query = $this->request->getPost();

		$payload = $this->TableLibrary->getTable([
			'page'          => $query['draw'],
			'len'           => $query['length'],
			'start'			=> $query['start'],
			'column_search' => $query['columns'],
			'sort'          => $query['order'],
			'search'        => $query['search']['value']
		], false);

		return $this->respond($payload);
	}

}
