<?php

namespace APP\Commands;

use JLA\DB;
use Faker\Factory as Faker;
use APP\Models\Task;

class Migrate
{
    public function run()
    {
        $this->migrateSQL('Tablas',$_ENV['BASE_PATH'].'/App/Config/Migrations/task.sql');
        $this->migrateSQL('Datos',$_ENV['BASE_PATH'].'/App/Config/Seeders/task.sql');
        $this->seedTasks();
    }

    private function migrateSQL(string $info, string $filename)
    {
        $db = DB::getConnection();
        echo 'Creando ' . $info . PHP_EOL;
        echo file_get_contents(realpath($filename));
        $db->exec(file_get_contents(realpath($filename)));
        echo PHP_EOL;
    }

    private function seedTasks()
    {
        $statusValues = ['pending','progress', 'done'];

        echo 'Creando Tareas ' . PHP_EOL;
        for($i=0; $i<=500; $i++)
        {
            $faker = Faker::create();
            $faker->locale('es_AR');
            $title = $faker->sentence;

            $task = new Task();
            $task->user_id = 1;
            $task->title = $title;
            $task->status = $statusValues[rand(0,2)];
            $task->save();

            echo $title . PHP_EOL;
        }
    }

}
