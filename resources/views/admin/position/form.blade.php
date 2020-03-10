@extends('adminlte::page')

@section('title', 'Employees')

@section('content_header')
    <h1>Position Form</h1>
@stop
@section('content')

    <form role="form" method="POST" id="positionForm"
          action=" @if($position) {{ route('admin.position.edit', ['position' => $position->id] ) }} @else {{ route('admin.position.create') }} @endif ">
        {{ csrf_field() }}
        <div class="card-body">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control" id="name" placeholder="Name"
                       value=" @if(old('name')) {{ old('name') }} @elseif($position) {{ $position->name }} @endif ">
                @if($errors->has('name'))
                    <div style="color: red" class="error" >{{ $errors->first('name') }}</div>
                @endif
            </div>
            @if($position)
                <div class="row w-100">
                    <div class="col-6">
                        <p>Created at: {{ $position->created_at->format('d.m.y') }}</p>
                        <p>Updated at: {{ $position->updated_at->format('d.m.y') }}</p>
                    </div>
                    <div class="col-6">
                        <p>Admin created ID: {{ $position->admin_created_id }}</p>
                        <p>Admin updated ID: {{ $position->admin_updated_id }}</p>
                    </div>
                </div>
            @endif
            <div class="card-footer">
                <a type="submit" class="btn btn-primary" href="{{ route('admin.position.list') }}">Cancel</a>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
@stop
@section('js')
    <script src="/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="/vendor/jquery-validation/additional-methods.min.js"></script>
<script>
    $(document).ready(function () {

        // $.validator.setDefaults({
        //     submitHandler: function () {
        //         $('#quickForm').submit();
        //     }
        // });
        // $('#positionForm').validate({
        //     rules: {
        //         name: {
        //             required: true,
        //             maxlength: 256,
        //         },
        //     },
        //     messages: {
        //         name: {
        //             required: "Please enter position name",
        //             maxlength: "Maximum 256 characters"
        //         },
        //     },
        //     errorElement: 'span',
        //     errorPlacement: function (error, element) {
        //         error.addClass('invalid-feedback');
        //         element.closest('.form-group').append(error);
        //         console.log(element[0], error[0]);
        //     },
        //     highlight: function (element, errorClass, validClass) {
        //         $(element).addClass('is-invalid');
        //     },
        //     unhighlight: function (element, errorClass, validClass) {
        //         $(element).removeClass('is-invalid');
        //     }
        // });
    });
</script>
@stop
