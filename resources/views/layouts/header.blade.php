<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Floor Solution CRM-Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{asset('/assets/vendors/feather/feather.css')}}">
  <link rel="stylesheet" href="{{asset('/assets/vendors/ti-icons/css/themify-icons.css')}}">
  <link rel="stylesheet" href="{{asset('/assets/vendors/css/vendor.bundle.base.css')}}">
  <!-- endinject -->
  <!-- Plugin css for this page -->
	  <link rel="stylesheet" href="{{asset('/assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
	  <link rel="stylesheet" href="{{asset('/assets/vendors/ti-icons/css/themify-icons.css')}}">
	  <link rel="stylesheet" type="text/css" href="{{asset('/assets/js/select.dataTables.min.css')}}">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{asset('/assets/css/vertical-layout-light/style.css')}}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{asset('/assets/images/fs_favicon.png')}}" />
  <script>
  function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
    alert('API Key Copied!');
  }
  </script>

</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="{{ URL::to('dashboard') }}"><img src="{{asset('/assets/images/floor_solution_logo.png')}}" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="{{ URL::to('dashboard') }}"><img src="{{asset('/assets/images/fs_favicon.png')}}" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav mr-lg-2">
          <li class="nav-item nav-search d-none d-lg-block">
            <div class="input-group">
              <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                <span class="input-group-text" id="search">
                  <i class="icon-search"></i>
                </span>
              </div>
              <input type="text" class="form-control" id="navbar-search-input" placeholder="Search now" aria-label="search" aria-describedby="search">
            </div>
          </li>
        </ul>
        
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
              <!-- <i class="icon-bell mx-0"></i> -->
              <i class="ti-settings mx-0"></i>
              <span class="count"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
              <p class="mb-0 font-weight-normal float-left dropdown-header">Settings</p>
              <!-- <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-success">
                    <i class="ti-info-alt mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">Integrations</h6>
                </div>
              </a> -->
              <a class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-warning">
                    <i class="ti-key mx-0"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <h6 class="preview-subject font-weight-normal">API Key</h6>
                  <p class="font-weight-light small-text mb-0 text-muted" id="apicopy">ee755b33b27b94ecb0b896edb8d9b691</p>
                  <button class="btn btn-outline-primary btn-sm" onclick="copyToClipboard('#apicopy')">Copy to clipboard</button>
                </div>
              </a>
            </div>
          </li>
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="{{asset('/assets/images/user.png')}}" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item">
              @php $user = auth()->user(); @endphp
                <i class="ti-user text-primary"></i>
                {{ $user->fname }} {{ $user->lname }}
              </a>
              <!-- <a href="{{ URL::to('logout') }}" class="dropdown-item">
                <i class="ti-power-off text-primary"></i>
                Logout
              </a> -->
              <!-- Authentication -->
              <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <a href="{{ route('logout') }}" class="dropdown-item"
                            onclick="event.preventDefault();
                                  this.closest('form').submit();">
                                  <i class="ti-power-off text-primary"></i>
                      {{ __('Log Out') }}
                  </a>
              </form>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          @can('Dashboard')
          <li class="nav-item ">
            <a class="nav-link" href="{{ URL::to('dashboard') }}">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          @endcan
          @can('user')
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
              <i class="icon-head menu-icon"></i>
              <span class="menu-title">Users</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ URL::to('adduser') }}"> Add User </a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ URL::to('userlist') }}"> User List </a></li>
              </ul>
            </div>
          </li>
          @endcan
          <!-- <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-layout menu-icon"></i>
              <span class="menu-title">Rotators</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ URL::to('addrotator') }}">Add Rotator</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ URL::to('rotatorlist') }}">Rotator List</a></li>
              </ul>
            </div>
          </li> -->
          @can('integration')
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#apisection" aria-expanded="false" aria-controls="ui-basic">
              <i class="icon-contract menu-icon"></i>
              <span class="menu-title">API Integrations</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="apisection">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{ URL::to('addintegration') }}">Add Integration </a></li>
                <li class="nav-item"> <a class="nav-link" href="{{ URL::to('integrationdoc') }}">Integration Doc</a></li>
              </ul>
            </div>
          </li>
          @endcan
          @can('role')
          <li class="nav-item ">
            <a class="nav-link" href="{{ URL::to('roles') }}">
              <i class="ti-check-box menu-icon"></i>
              <span class="menu-title">Manage Roles</span>
            </a>
          </li>
          @endcan
        </ul>
      </nav>