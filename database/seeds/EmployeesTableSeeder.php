<?php

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as ImageInt;

class EmployeesTableSeeder extends Seeder
{
    const BATCH_SIZE = 200;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::beginTransaction();

        for ($i = 0, $n = 0; $i < 9000; $i++, $n++) {

            $user  = factory(App\Models\Employee::class, 1)->create()->each(function($user) {
                $user->is_parent = 1;
                $user->level = 1;
                $user->save();
            });;
            $user2  = factory(App\Models\Employee::class, 1)->create()->each(function($user2) use ($user) {
                $user2->parent_id = $user->first()->id;
                $user2->is_parent = 1;
                $user2->level = 2;
                $user2->save();
            });
            $user3  = factory(App\Models\Employee::class, 1)->create()->each(function($user3) use ($user2) {
                $user3->parent_id = $user2->first()->id;
                $user3->is_parent = 1;
                $user3->level = 3;
                $user3->save();
            });
            $user4  = factory(App\Models\Employee::class, 1)->create()->each(function($user4) use ($user3) {
                $user4->parent_id = $user3->first()->id;
                $user4->is_parent = 1;
                $user4->level = 4;
                $user4->save();
            });
            $user5  = factory(App\Models\Employee::class, 1)->create()->each(function($user5) use ($user4) {
                $user5->parent_id = $user4->first()->id;
                $user5->is_parent = 1;
                $user5->level = 5;
                $user5->save();
            });
            $user6  = factory(App\Models\Employee::class, 1)->create()->each(function($user6) use ($user5) {
                $user6->parent_id = $user5->first()->id;
                $user6->is_parent = 0;
                $user6->level = 6;
                $user6->save();
            });

            if ($n == self::BATCH_SIZE) {
                \DB::commit();
                \DB::beginTransaction();
                $n = 0;
            }

        }

        \DB::commit();

        \DB::table('employees')->where('level', '<', 6)->update(['is_parent' => true]);

        $path = public_path(Employee::FILE_PATH);

        if (!File::exists($path)) {
            File::makeDirectory($path , 0777, true, true);
        }

        $image = ImageInt::make(public_path('/images/photo.png'));
        $image->fit(300, 300);
        $mask = ImageInt::canvas(300, 300);
        $mask->circle(300, 150, 150, function ($draw) {
            $draw->background('#fff');
        });

        $image->mask($mask, false);
        $image->save($path. "photo.png");

    }
}
