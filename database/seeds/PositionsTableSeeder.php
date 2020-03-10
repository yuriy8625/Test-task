<?php

use Illuminate\Database\Seeder;

class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $positions = [
           'Junior Front End Developer',
           'Middle Front End Developer',
           'Senior Front End Developer',
           'Trainee Front End Developer',
           'Lead Front End Developer',
           'Junior Back End Developer',
           'Middle Back End Developer',
           'Senior Back End Developer',
           'Trainee Back End Developer',
           'Lead Back End Developer',
           'Lead Web Developer',
           'Junior Designer Developer',
           'Middle Designer Developer',
           'Senior Designer Developer',
           'Trainee Designer Developer',
           'Lead Web Designer',
           'Full Stack Developer',
       ];

       foreach ($positions as $position){
           $model = new \App\Models\Position();
           $model->name = $position;
           $model->admin_created_id = 1;
           $model->admin_updated_id = 1;
           $model->save();
       }
    }
}
