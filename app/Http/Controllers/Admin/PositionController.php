<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Position;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class PositionController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $data = Datatables::of(Position::query())
                ->addColumn('action', function ($position) {
                    return '<a href="' . route('admin.position.form', ['position' => $position->id]) . '" style="color: grey" title="edit"><i class="fa fa-edit"></i></a>
                            <a class="deletePosition" data-id="' . $position->id . '" data-name="' . $position->name . '" title="delete"><i style="color: grey" class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['action'])
                ->addIndexColumn();

            return $data->make(true);
        }

        return view('admin.position.position');
    }

    public function form(Position $position = null)
    {
        return view('admin.position.form', [
            'position' => $position,
        ]);
    }

    public function edit(Request $request, Position $position)
    {
        request()->validate([
            'name' => 'required|max:191|unique:positions,name,' . $position->id,
        ]);

        $position->fill($request->all('name'));
        $position->save();

        return redirect(route('admin.position.form', ['position' => $position]));
    }

    public function create(Request $request)
    {
        request()->validate([
            'name' => 'required|max:256|unique:positions,name',
        ]);

        $position = new Position();
        $position->fill($request->all('name'));
        $position->save();

        return redirect(route('admin.position.form', ['position' => $position]));
    }

    public function delete(Position $position)
    {
        $position->delete();

        return response()->json($position, 200);
    }
}
