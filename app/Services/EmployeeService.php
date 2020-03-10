<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image as ImageInt;

class EmployeeService
{

    /**
     * @param Employee $employee
     * @param null $photo
     * @return Employee
     */
    public static function savePhoto(Employee $employee, $photo = null)
    {
        $path = public_path(Employee::FILE_PATH);

        if (!File::exists($path)) {
            File::makeDirectory($path , 0777, true, true);
        }

        $image = ImageInt::make($photo);
        $image->orientate();
        $image->encode('png');
        $image->fit(300, 300);
        $mask = ImageInt::canvas(300, 300);
        $mask->circle(300, 150, 150, function ($draw) {
            $draw->background('#fff');
        });

        $image->mask($mask, false);
        $image->save($path. $employee->id . "_photo.png");

        $employee->photo = $employee->id . "_photo.png";
        $employee->save();

        return $employee;
    }
}
