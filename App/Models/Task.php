<?php

namespace App\Models;

use JLA\Model;

class Task extends Model
{

	protected $table = 'tasks';
	protected $primaryKey = 'id';
	public static $statusValues = ['pending','progress', 'done'];

}
