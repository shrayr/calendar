<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Calendars</title>
    <!-- css -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/css/animate.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">

    <!-- css plugins -->
    <link href="/css/plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
    <link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"
          rel="stylesheet"/>

    <!-- js -->
    <script src="/js/jquery-2.1.1.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/inspinia.js"></script>
    <script src="/js/moment.min.js"></script>
    <script src="/js/bootstrap-datetimepicker.min.js"></script>
    <script src="/js/jquery-ui.custom.min.js"></script>

    <!-- js Plugins -->
    <script src="/js/plugins/iCheck/icheck.min.js"></script>
    <script src="/js/plugins/fullcalendar/fullcalendar.min.js"></script>
    <script src="/js/plugins/fullcalendar/moment.min.js"></script>
    <script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/js/plugins/pace/pace.min.js"></script>
    <script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>


</head>
@if (Auth::guest())
    <body class="gray-bg">
    @yield('content')
    @else
        <body>
        <div id="wrapper">

            <nav class="navbar-default navbar-static-side" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav metismenu" id="side-menu">
                        <li class="nav-header">
                            <div class="dropdown profile-element">
                                <a href="{{route('myAccount')}}">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{Auth::user()->name }}</strong>
                             </span> </span> </a>

                            </div>
                            <div class="logo-element">
                                Calendars
                            </div>
                        </li>

                        <li>
                            <a href="{{route('home')}}" class="active"><i class="fa fa-home"></i> <span class="nav-file">Home</span></a>
                            <ul class="nav nav-second-level collapse in">


                                <li class=active">
                                    <a href="{{route('myAccount')}}"><i class="fa fa-user"></i> <span
                                                class="nav-label">My Account</span></a>
                                </li>
                                <li>
                                    <a href="{{route('calendars')}}"><i class="fa fa-calendar"></i> <span
                                                class="nav-label">Calendars</span></a>
                                </li>

                            </ul>
                        </li>

                    </ul>

                </div>
            </nav>
            <div id="page-wrapper" class="gray-bg">
                <div class="row border-bottom">
                    <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                        <form action="/logout" method="post" class="logout-form">
                            {{ csrf_field() }}
                        <ul class="nav navbar-top-links navbar-right">
                            <li>

                                <a class="logout-link">
                                    <i class="fa fa-sign-out"></i> Log out
                                </a>

                            </li>
                        </ul>
                        </form>
                    </nav>
                </div>


                @include('flash::message')
                @yield('content')

            </div>
        </div>
        <script>
            $( document ).ready(function(){
                $('.logout-link').click(function(){
                    $('.logout-form').submit();
                });

                ID = window.setTimeout(function(){
                    $('.alert').fadeOut(1000);
                }, 5000);

            });
        </script>
        <!-- Mainly scripts -->

        @yield('footer')
        @endif
        </body>
</html>
