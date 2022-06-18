<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

    <!-- Bootstrap core CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">

    <!-- Material Design Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">

    {{-- DataTable --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

    {{-- Date Rage Picker --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- Viewer.Js --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.10.1/viewer.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @yield('extra_css')

</head>
<body>

    {{-- Sidebar start --}}


    <div class="page-wrapper chiller-theme">
        <nav id="sidebar" class="sidebar-wrapper">
          <div class="sidebar-content">
            <div class="sidebar-brand">
              <a href="#"><i class="fas fa-user-ninja"></i> Ninja HR</a>
              <div id="close-sidebar">
                <i class="fas fa-times"></i>
              </div>
            </div>
            <div class="sidebar-header">
              <div class="user-pic">
                <img class="img-responsive img-rounded" src="{{ auth()->user()->profile_img_path() }}"
                  alt="User picture">
              </div>
              <div class="user-info">
                <span class="user-name">
                  <strong>{{ ucfirst(auth()->user()->name) }}</strong>
                </span>
                <span class="user-role">{{ auth()->user()->department ? auth()->user()->department->title : 'None Department' }}</span>
                <span class="user-status">
                  <i class="fa fa-circle"></i>
                  <span>Online</span>
                </span>
              </div>
            </div>

            <div class="sidebar-menu">
              <ul>
                <li class="header-menu">
                  <span>Menu</span>
                </li>

                <li>
                    <a href="{{ route('home') }}">
                      <i class="fas fa-home"></i>
                      <span>Home</span>
                    </a>
                </li>

                @can('employee_view')
                <li>
                    <a href="{{ route('employee.index') }}">
                      <i class="fas fa-users"></i>
                      <span>Employees</span>
                    </a>
                </li>
                @endcan

                @can('companySetting_view')
                <li>
                    <a href="{{ route('companySetting.show',1) }}">
                        <i class="fas fa-building"></i>
                      <span>Company Setting</span>
                    </a>
                </li>
                @endcan

                @can('department_view')
                <li>
                    <a href="{{ route('department.index') }}">
                        <i class="fas fa-network-wired"></i>
                      <span>Departments</span>
                    </a>
                </li>
                @endcan

                @can('role_view')
                <li>
                    <a href="{{ route('role.index') }}">
                        <i class="fas fa-user-shield"></i>
                      <span>Role</span>
                    </a>
                </li>
                @endcan

                @can('permission_view')
                <li>
                    <a href="{{ route('permission.index') }}">
                        <i class="fas fa-shield-alt"></i>
                      <span>Permission</span>
                    </a>
                </li>
                @endcan

                @can('attendance_view')
                <li>
                    <a href="{{ route('attendance.index') }}">
                        <i class="fas fa-calendar-check"></i>
                      <span>Attendance Employee</span>
                    </a>
                </li>
                @endcan

                @can('attendance_view')
                <li>
                    <a href="{{ route('attendance_overview') }}">
                        <i class="fas fa-calendar-alt"></i>
                      <span>Attendance Overview</span>
                    </a>
                </li>
                @endcan

                @can('salary_view')
                <li>
                    <a href="{{ route('salary.index') }}">
                        <i class="fas fa-money-bill-alt"></i>
                      <span>Salary</span>
                    </a>
                </li>
                @endcan

                @can('payroll_view')
                <li>
                    <a href="{{ route('payroll') }}">
                        <i class="fab fa-cc-amazon-pay"></i>
                      <span>Payroll</span>
                    </a>
                </li>
                @endcan

                @can('project_view')
                <li>
                    <a href="{{ route('project.index') }}">
                        <i class="fas fa-project-diagram"></i>
                      <span>Project</span>
                    </a>
                </li>
                @endcan

                {{-- <li class="sidebar-dropdown">
                  <a href="#">
                    <i class="fa fa-globe"></i>
                    <span>Maps</span>
                  </a>
                  <div class="sidebar-submenu">
                    <ul>
                      <li>
                        <a href="#">Google maps</a>
                      </li>
                      <li>
                        <a href="#">Open street map</a>
                      </li>
                    </ul>
                  </div>
                </li> --}}

              </ul>
            </div>
            <!-- sidebar-menu  -->
          </div>
          <!-- sidebar-content  -->
        </nav>
        <!-- sidebar-wrapper  -->
        <div class="content-header">
            <div class="d-flex justify-content-center">
                <div class="col-md-10">
                    <div class="d-flex justify-content-between">
                        {{-- @if(request()->is('/')) --}}
                        <a href="#" id="show-sidebar" class="text-decoration-none text-dark">
                            <i class="fas fa-bars"></i>
                        </a>
                        {{-- @else
                        <a href="#" id="back-btn" class="text-decoration-none text-dark">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                        @endif --}}
                        <h5>@yield('title')</h5>
                        <a href=""></a>
                    </div>
                </div>
            </div>
           </div>

           <div class="d-flex justify-content-center content">
               <div class="col-md-10">
                <main class="py-4 page-content">
                    @yield('content')
                </main>
               </div>
           </div>

            <div class="content-bottom">
                <div class="d-flex justify-content-center">
                    <div class="col-md-10">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('home') }}">
                                <i class="fas fa-home"></i>
                                <p>Home</p>
                            </a>

                            <a href="{{ route('attendance_scan') }}">
                                <i class="fas fa-user-clock"></i>
                                <p>Attendance</p>
                            </a>

                            <a href="{{ route('my-project.index') }}">
                                <i class="fas fa-briefcase"></i>
                                <p>Project</p>
                            </a>

                            <a href="{{ route('profile#profile') }}">
                                <i class="fas fa-user-ninja"></i>
                                <p>Profile</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
      </div>
      <!-- page-wrapper -->

    {{-- Sidebar end --}}

    <div id="app">
        {{-- <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav> --}}
    </div>

    <!-- JQuery -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>

    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/js/mdb.min.js"></script>

    {{-- <script src="{{ asset('js/style.js') }}"></script> --}}

    {{-- DataTable --}}
    <script src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/g/mark.js(jquery.mark.min.js)"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.10.13/features/mark.js/datatables.mark.js"></script>

    {{-- Date Range Picker --}}
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    {{-- Sweet Alert2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Sweet Alert1 --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    {{-- Select2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    {{-- Larapass auth --}}
    <script src="{{ asset('vendor/larapass/js/larapass.js') }}"></script>

    {{-- Viewer.Js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.10.1/viewer.min.js"></script>

    <script>

        const Toast = Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
        $(function($){

            let token = document.head.querySelector('meta[name="csrf-token"]');
            if(token){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN':token.content
                    }
                });
            }else{
                console.error('csrf token not found');
            }

            $(".sidebar-dropdown > a").click(function() {
                $(".sidebar-submenu").slideUp(200);
                    if ($(this).parent().hasClass("active")) {
                        $(".sidebar-dropdown").removeClass("active");
                        $(this).parent().removeClass("active");
                    } else {
                        $(".sidebar-dropdown").removeClass("active");
                        $(this).next(".sidebar-submenu").slideDown(200);
                        $(this).parent().addClass("active");
                    }
                    });
                    $("#close-sidebar").click(function(e) {
                        e.preventDefault();
                        $(".page-wrapper").removeClass("toggled");
                    });
                    $("#show-sidebar").click(function(e) {
                        e.preventDefault();
                        $(".page-wrapper").addClass("toggled");
                    });

                    document.addEventListener('click',function(event){
                        if(document.getElementById('show-sidebar').contains(event.target)){
                            $(".page-wrapper").addClass("toggled");
                        }else if(!document.getElementById('sidebar').contains(event.target)){
                            $(".page-wrapper").removeClass("toggled");
                        }
                    });

                    @if (session('create'))
                        Swal.fire({
                            icon: 'success',
                            title: 'Successfully Created',
                            text: "{{ session('create') }}",
                        });
                    @endif

                    @if (session('update'))
                        Swal.fire({
                            icon: 'success',
                            title: 'Successfully Updated',
                            text: "{{ session('update') }}",
                        });
                    @endif

                    $.extend(true, $.fn.dataTable.defaults, {

                        responsive: true,
                        processing: true,
                        serverSide: true,
                        mark: true,

                        "columnDefs": [

                            {
                                "targets": 'no-sort',
                                "orderable": false
                            },

                            {
                                "targets": 'no-search',
                                "searchable": false
                            },

                            {
                                "targets": 'hidden',
                                "visible": false
                            },

                            {
                                "targets": [0],
                                "class": "control"
                            },
                        ],

                        "language": {
                            "paginate": {
                            "next": "<i class='fas fa-arrow-circle-right'></i>",
                            "previous": "<i class='fas fa-arrow-circle-left'></i>",
                            },

                        "processing": "<img src='/image/loading.gif' style='width:50px;'/><p>Loading...</p>",
                        },
                    });

                        $('#back-btn').on("click",function(e){
                            e.preventDefault();
                            window.history.go(-1);
                            return false;
                        });

                        $('.select-box').select2({
                            placeholder: "Select an option",
                            allowClear: true
                        });

                        $('.select-ninja').select2({
                            placeholder: "Please Choose...",
                            allowClear: true
                        });

                        $('.select-month').select2({
                            placeholder: "Choose a month",
                            allowClear: true
                        });

                        $('.select-year').select2({
                            placeholder: "Choose a year",
                            allowClear: true
                        });
                });
    </script>

    @yield('script')

</body>
</html>
