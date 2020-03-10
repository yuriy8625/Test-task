@extends('adminlte::page')
@section('title', 'Employees')
@section('content_header')
    <div class="row">
        <div class="col-10">
            <h1>Employees</h1>
            {{ csrf_field() }}
        </div>
        <div class="col-2" style="text-align: right">
            <a href="{{ route('admin.employees.form') }}" class="btn btn-secondary btn-sm" style="color: white">Add
                employee</a>
        </div>
    </div>

@stop
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-body">
                    <table id="employee_datatable" class="table table-bordered table-striped" style="width: 100%">
                        <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Date of employment</th>
                            <th>Phone number</th>
                            <th>Email</th>
                            <th>Salary</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <!-- /.box -->
        </div>

        {{--        MODAL--}}
        <div class="modal fade" id="modal-sm">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Remove employee</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to remove employee <span id="epmployee-name"></span></p>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-secondary" id="delete-employee">Remove</button>
                    </div>
                </div>


            {{--                TOAST--}}

            <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!-- /.col -->
    </div>
@stop
@section('js')
    <script>

        $(document).ready(function () {

            table();

            function table() {
                $('#employee_datatable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    paging: true,
                    autoWidth: true,
                    ajax: {
                        url: "{!! route('admin.employees.list') !!}",
                        data: function (d) {
                        }
                    },
                    columns: [
                        {
                            data: 'photo', name: 'photo', "render": function (data, type, full, meta) {
                                return "<img src=\"" + data + "\" height=\"50\"/>";
                            },
                        },
                        {data: 'name', name: 'name', searchable: true},
                        {data: 'position', name: 'position_id'},
                        {data: 'employment_at', name: 'employment_at'},
                        {data: 'phone', name: 'phone'},
                        {data: 'email', name: 'email'},
                        {data: 'salary', name: 'salary'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });
            }

            $('#employee_datatable').on('click', '.deleteEmployee', function () {

                $('#modal-sm').attr('data-id', $(this).data('id'));
                $('#epmployee-name').text($(this).data('name'));
                $('#modal-sm').modal('show');

                $('#delete-employee').on('click', function (e) {
                    e.preventDefault();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('input[name="_token"]').val()
                        }
                    });
                    var url = '/admin/employees/' + $('#modal-sm').data('id');
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {method: '_DELETE', submit: true}
                    }).then(function (response) {
                        $('#laravel_datatable').DataTable().draw(false);
                        $('#modal-sm').modal('hide');

                        $(document).Toasts('create', {
                            class: 'bg-maroon',
                            title: 'Deleted',
                            subtitle: '',
                            body: response.name
                        })

                    }).catch(function (error) {

                    })
                })
            })
        });

    </script>
@stop
