@extends('layouts.app')

@section('content')
    <div class="row head-block">
        <div class="col-lg-12">
            <h2>My Account</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('home')}}">Home</a>
                </li>
                <li class="active">
                    <strong>My Account</strong>
                </li>

            </ol>
        </div>
    </div>

    <div class="md-skin">
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-sm-12">
                    <div class="ibox">
                        <div class="ibox-content">

                            <h1>My Account</h1>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <tbody>
                                    <tr>
                                        <th>ID</th>
                                        <td>{{ $user->id }}</td>
                                    </tr>
                                    <tr>
                                        <th> Name </th>
                                        <td> {{ $user->name }} </td>
                                    </tr>

                                    <tr>
                                        <th> Email </th>
                                        <td> {{ $user->email }} </td>
                                    </tr>

                                    <tr>
                                        <th> Registered At </th>
                                        <td> {{ $user->created_at }} </td>
                                    </tr>

                                    </tbody>
                                    <tfoot>

                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection