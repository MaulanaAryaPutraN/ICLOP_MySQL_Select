<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="{{asset('lte/dist/img/iclop-logo.png')}}" alt="iCLOP logo" class="brand-image elevation-3"
           style="width:120px;height:60px;">
           <br>
      <span class="brand-text font-weight-light" style="font-size:140%;"> &nbsp;  TEACHER</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{asset('lte/dist/img/teacher2.png')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</span>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               <li class="nav-item">
                <a href="{{URL::to('teacher/crooms')}}" class="nav-link">
                  <i class="nav-icon fas fa-building"></i>
                  <p>
                    Class Rooms
                  </p>
                </a>
              </li>
          <li class="nav-item">
            <a href="{{URL::to('teacher/assignstudent')}}" class="nav-link">
              <i class="nav-icon fas fa-check"></i>
              <p>
                Validate Student
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{URL::to('teacher/member')}}" class="nav-link">
              <i class="nav-icon fas fa-inbox"></i>
              <p>
                Student Member
              </p>
            </a>
          </li>
          <li class="nav-item">
                    <a href="{{URL::to('teacher/android23/material')}}" class="nav-link">
                        <i class="nav-icon fas fa-trophy"></i>
                        <p>
                            Android 23 Result
                            <span class="right badge badge-danger">New</span>
                        </p>
                    </a>
                </li>
          {{-- Python --}}
                <li class="nav-item">
                    <a href="{{URL::to('teacher/python/resultstudent')}}" class="nav-link">
                        <i class="nav-icon fas fa-trophy"></i>
                        <p>
                            Python Result
                            <span class="right badge badge-danger">New</span>
                        </p>
                    </a>
                </li>
          <li class="nav-item">
            <a href="{{URL::to('teacher/rankview')}}" class="nav-link">
              <i class="nav-icon fas fa-check"></i>
              <p>
                Top 20 Rank
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{URL::to('teacher/studentres')}}" class="nav-link">
              <i class="nav-icon fas fa-trophy"></i>
              <p>
                Student Result
              </p>
            </a>
          </li>
        <li class="nav-item">
          <a href="{{URL::to('teacher/studentclasssummary')}}" class="nav-link">
            <i class="nav-icon fas fa-trophy"></i>
            <p>
              Classroom Result
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
	<li class="nav-item">
          <a href="{{URL::to('teacher/studentpassedresult')}}" class="nav-link">
            <i class="nav-icon fas fa-trophy"></i>
            <p>
              Passed Topic Result
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
	<li class="nav-item">
            <a href="{{URL::to('teacher/completeness')}}" class="nav-link">
              <i class="nav-icon fas fa-hand-pointer"></i>
              <p>
                Completeness
                <!-- <span class="right badge badge-danger">New</span> -->
              </p>
           </a>
        </li>
	<li class="nav-item">
          <a href="{{URL::to('teacher/uiclasssummary')}}" class="nav-link">
            <i class="nav-icon fas fa-trophy"></i>
            <p>
              UI Class Result
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
	<li class="nav-item">
          <a href="{{URL::to('teacher/uisummaryres')}}" class="nav-link">
            <i class="nav-icon fas fa-trophy"></i>
            <p>
              UI Student Result
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
	<li class="nav-item">
          <a href="{{URL::to('teacher/uiresview')}}" class="nav-link">
            <i class="nav-icon fas fa-trophy"></i>
            <p>
              UI Learning Result
              <!-- <span class="right badge badge-danger">New</span> -->
            </p>
          </a>
        </li>
	<li class="nav-item">
            <a href="{{URL::to('teacher/jplasdown')}}" class="nav-link">
              <i class="nav-icon fas fa-hand-pointer"></i>
              <p>
                JPLAS Result
                <!-- <span class="right badge badge-danger">New</span> -->
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
