<?php

namespace App\Models
{ 

	use Eywa\Database\Model\Model;

	Class User extends Model
	{

		protected static string $table = 'users';

		protected static int $limit = 20;

	}

}
