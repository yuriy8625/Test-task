@extends('adminlte::page')
@section('title', 'Positions')
@section('content_header')
    <div class="row">
        <div class="col-10">
            <h1>Employees</h1>
            {{ csrf_field() }}
        </div>
        <div class="col-2" style="text-align: right">
            <a href="{{ route('admin.position.form') }}" class="btn btn-secondary btn-sm" style="color: white">Add
                position</a>
        </div>
    </div>

@stop
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-body">
                    <table id="position_datatable" class="table table-bordered table-striped" style="width: 100%">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Last update</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
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
                        <p>Are you sure you want to remove position <span id="position-name"></span></p>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-secondary" id="delete-position">Remove</button>
                    </div>
                </div>

            </div>
        </div>

    </div>
@stop
@section('js')
    <script>

        $(document).ready(function () {

            table();

            function table() {
                $('#position_datatable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    paging: true,
                    autoWidth: true,
                    ajax: {
                        url: "{!! route('admin.position.list') !!}",
                        data: function (d) {
                        }
                    },
                    columns: [
                        {data: 'name', name: 'name', searchable: true},
                        {data: 'updated_at', name: 'updated_at'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
                });
            }

            $('#position_datatable').on('click', '.deletePosition', function () {

                $('#modal-sm').attr('data-id', $(this).data('id'));
                $('#position-name').text($(this).data('name'));
                $('#modal-sm').modal('show');

                $('#delete-position').on('click', function (e) {
                    e.preventDefault();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('input[name="_token"]').val()
                        }
                    });
                    var url = '/admin/position/delete/' + $('#modal-sm').data('id');
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
