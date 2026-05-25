<nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: lavender;">
    <!-- Left navbar links -->

    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{URL::to('teacher')}}" class="nav-link">Home</a>
      </li>
      <!--
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    -->
    <li class="nav-item">
      <a href="{{ route('logout')}}" class="nav-link"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Logout
       </a>

       <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
           {{ csrf_field() }}
       </form>

    </li>
    </ul>

  </nav>
