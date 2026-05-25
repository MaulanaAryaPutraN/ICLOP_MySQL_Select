<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{asset('lte/dist/img/logo-aplas.png')}}" alt="APLAS logo" class="brand-image elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">WebApps</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('lte/dist/img/avatar3.png')}}" class="img-circle elevation-2" alt="User Image">
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


                <li class="treeview">
                    <a href="#" class="nav-link" style="background-color:powderblue;color:black;">
                        <i class="nav-icon fas fa-space-shuttle"></i>
                        <p><b>Start Learning</b></p>
                    </a>
                    <ul role="menu" class="nav nav-pills nav-sidebar flex-column">
                        <li class="nav-item">
                            <a href="{{URL::to('student/tasks')}}" class="nav-link"><i
                                    class="nav-icon fas fa-angle-right"></i>
                                <p>Download Materials</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{URL::to('student/results')}}" class="nav-link">
                                <i class="nav-icon fas fa-angle-right"></i>
                                <p>Submit Your Project </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{URL::to('student/valid')}}" class="nav-link">
                                <i class="nav-icon fas fa-angle-right"></i>
                                <p>
                                    Validation Results
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{URL::to('student/rankview')}}" class="nav-link">
                                <i class="nav-icon fas fa-angle-right"></i>
                                <p>
                                  Top 20 Rank
                                </p>
                              </a>
                         </li>

                         <li class="nav-item">
                            <a href="{{URL::to('android23/material')}}" class="nav-link">
                                <i class="nav-icon fas fa-angle-right"></i>
                                <p>
                                    Android 23
                                </p>
                            </a>
                        </li>

	</ul> 
	</li>

<li class=" treeview">
                                    <a href="#" class="nav-link" style="background-color:#FCF3CF;color:black;">
                                        <i class="nav-icon fas fa-motorcycle"></i>
                                        <p><b>Take Exercise</b>
                                        </p>
                                    </a>
                                    <ul role="menu" class="nav nav-pills nav-sidebar flex-column">
                                        <li class="nav-item">
                                            <a href="{{URL::to('student/exercise')}}" class="nav-link"><i
                                                    class="nav-icon fas fa-angle-right"></i>
                                                <p>List of Exercises <span class="right badge badge-danger">New</span>
                                                </p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{URL::to('student/exercisesubmission')}}" class="nav-link">
                                                <i class="nav-icon fas fa-angle-right"></i>
                                                <p>Submit Your Exercise </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{URL::to('student/exercisevalid')}}" class="nav-link">
                                                <i class="nav-icon fas fa-angle-right"></i>
                                                <p>
                                                    Validation Results
                                                </p>
                                            </a>
                                        </li>


                                    </ul>
                        </li>


                        <li class="treeview">
                            <a href="#" class="nav-link" style="background-color:#EDCBFC;color:black;">
                                <i class="nav-icon fas fa-image"></i>
                                <p>&nbsp;<b>Learning UI Design</b>
                                </p>
                            </a>
                            <ul role="menu" class="nav nav-pills nav-sidebar flex-column">

                                <li class="nav-item">
                                    <a href="{{URL::to('student/uitasks')}}" class="nav-link">
                                        <i class="nav-icon fas fa-angle-right"></i>
                                        <p>
                                            Start Learning <span class="right badge badge-danger">New</span>
                                        </p>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{URL::to('student/uiresview')}}" class="nav-link"><i
                                            class="nav-icon fas fa-angle-right"></i>
                                        <p>Learning Results
                                        </p>
                                    </a>
                                </li>


                            </ul>
                        </li>



                        <li class="treeview">
                            <a href="{{URL::to('student/jplasdown')}}" class="nav-link"
                                style="background-color:#FFBF00;color:black;">
                                <i class="nav-icon fas fa-map"></i>
                                <p><b>Learning JPLAS</b>
                                </p>
                            </a>
                        </li>


                    </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
