<?php

namespace App\Models;

use CodeIgniter\Model;
use Faker\Generator;

class Tabla extends Model
{
	protected $table                = 'programacion';
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
		return $this->distinct()->select($column)->findAll();
	}

}
