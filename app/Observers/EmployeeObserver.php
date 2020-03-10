<?php

namespace App\Observers;

use App\Models\Employee;

class EmployeeObserver
{
    public function updating(Employee $employee)
    {
        if ($employee->isDirty('parent_id')) {
            $this->updateOldTree($employee);
            $this->updateNewTree($employee);
        }
    }

    /**
     * @param Employee $employee
     */
    public function creating(Employee $employee)
    {
        $employee->is_parent = false;
        $employee->level = (($employee->parent_id)) ? $employee->parent->level + 1 : 1;

        if($employee->parent_id){
            $parent = Employee::find($employee->parent_id);
            $parent->is_parent = false;
            $parent->save();
        }
    }

    /**
     * @param Employee $employee
     */
    public function deleting(Employee $employee)
    {
        $this->updateOldTree($employee);
    }


    /**
     * @param Employee $employee
     * @return Employee
     */
    protected function updateOldTree($employee)
    {
        $old = Employee::find($employee->id);

        if ($old->children->count()) {
            $id = $this->getIdChildren($employee);
            $level = $old->level;

            foreach ($id as $key => $item){
                if($key == 0){
                    \DB::table('employees')->where('id', '=', $item)->update([
                        'level' => $level,
                        'parent_id' => $old->parent_id,
                        'is_parent' => (Employee::find($item)->children->count()) ? true : false,
                    ]);
                }else{
                    \DB::table('employees')->where('id', '=', $item)->update([
                        'level' => $level,
                        'is_parent' => (Employee::find($item)->children->count()) ? true : false,
                    ]);
                }

                $level++;

            }
        }
        return $employee;
    }

    /**
     * @param Employee $employee
     * @return Employee
     */
    protected function updateNewTree(Employee $employee)
    {
        if ($employee->parent_id) {
            \DB::table('employees')->where('id', '=', $employee->parent_id)->update(['is_parent' => true]);
            $level = $employee->parent->level;
            $employee->level = $level + 1;
            $employee->is_parent = false;
        }

        if(!$employee->parent_id){
            $employee->level = 1;
        }

        return $employee;
    }


    protected function getIdChildren($employee)
    {
        static $id = [];
        $children = $employee->children->first();

        $id[] = $children->id;

        if($children->children->count()){
            $this->getIdChildren($children);
        }

        return $id;
    }
}
