@extends('layouts.app')

@section('content')
    <div class="row head-block">
        <div class="col-lg-12">
            <h2>Calendars</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('home')}}">Home</a>
                </li>
                <li class="active">
                    <strong>Calendars</strong>
                </li>

            </ol>
        </div>
    </div>

    <div class="md-skin">
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Calendars</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="table">
                                <table class="table table-bordered table-striped table-hover my-table">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($calendars as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <a href="{{route('calendar', [$item->id, 'primary'])}}"
                                                   class="btn btn-success btn-xs" title="View Discount"><span
                                                            class="fa fa-eye"
                                                            aria-hidden="true"></span></a>
                                                <a class="btn btn-primary btn-xs editable" data-id="{{$item->id}}" title="Update Access Token"><span
                                                            class="fa fa-edit"
                                                            aria-hidden="true"></span></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <br>
                                <hr>
                                {!! Form::open(['url' => route('addCalendar'), 'class' => 'form-horizontal']) !!}
                                <h3>Add New Calendar</h3>
                                <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                                    {!! Form::label('name', 'Name', ['class' => 'col-md-2 col-sm-3 col-xs-5 control-label']) !!}
                                    <div class="col-sm-9 col-md-10 col-xs-7">
                                        {!! Form::text('name', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-9 col-sm-3">
                                        {!! Form::submit('Add', ['class' => 'btn btn-primary pull-right']) !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.editable').editable({
                type: 'text',
                pk: 1,
                name: 'access_token',
                emptytext: '',
                success: function (response) {
                   alert(response);
                },
                params: function (params) {
                    params.id = $(this).data('id');
                    return params;
                },
                display: function () {},
                url: '/update-access-token',
                title: 'UpdateAccessToken'
            });
        });
    </script>

@endsection