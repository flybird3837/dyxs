<?php

use Illuminate\Database\Seeder;

class ProjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Faker\Generator::class);

        for ($i=0; $i<100; $i++) {
            $data[] = [
                'name' => $faker->text(20),
                'description' => $faker->text()
            ];
        }

        \App\Models\Project::insert($data);
    }
}
