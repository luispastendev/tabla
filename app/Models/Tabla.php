<?php

namespace App\Models;

use CodeIgniter\Model;
use Faker\Generator;

class Tabla extends Model
{
	protected $table                = 'tbl_programacion';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $returnType           = 'array';
	protected $allowedFields        = ['name','country'];

	// Dates
	protected $useTimestamps        = false;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';


	public function fake(Generator &$faker)
    {

		// $faker->addProvider(new \Faker\Provider\en_US\Address($faker));
		// $faker->addProvider(new \Faker\Provider\en_US\Address($faker));
        return [
            'name' => $faker->firstName . " " . $faker->lastName,
            'country' => $faker->Country,
        ];
    }


	// QUERIES

	public function getValuesColumn($column){
		return $this->distinct()->select($column)->orderBy($column,'asc')->findAll();
	}


	public function withMultiSearch(string $match){
		
		return $this->like('id', $match, 'both', null, true)
					->orLike('title', $match, 'both', null, true)
					->orLike('status', $match, 'both', null, true);
	}

}
