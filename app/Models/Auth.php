<?php

namespace App\Models
{ 

	use Eywa\Database\Model\Model;

	Class Auth extends Model
	{

		protected static string $table = 'auth';

		protected static string $by = 'id';

		protected static int $limit = 20;

	}

}