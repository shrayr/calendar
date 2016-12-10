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
                                        <th>Calendar ID</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {{-- */$x=0;/* --}}
                                    @foreach($calendars as $item)
                                        {{-- */$x++;/* --}}
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->calendar_id }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <a href="{{route('calendar', $item->id) }}"
                                                   class="btn btn-success btn-xs" title="View Discount"><span
                                                            class="fa fa-eye"
                                                            aria-hidden="true"></span></a>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <form action="{{route('addCalendar')}}" method="post">
                                    {{ csrf_field() }}
                                <label>Add new <input class="form-control" type="text" placeholder="Name" name="name">
                                    <input class="form-control" type="text" name="calendar_id" placeholder="Calendar Id">
                                    <input class="form-control" type="text" name="access_token" placeholder="Access Token"></label>
                                    <input class="btn btn-primary" type="submit" value="Add">
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection