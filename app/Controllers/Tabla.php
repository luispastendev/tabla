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

	public function index()
	{
			
		return view('tabla', ['columns' => $this->TableLibrary->getDataAllColumns()]);
	}

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
}
