<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{asset('lte/dist/img/iclop-logo.png')}}" alt="iCLOP logo" class="brand-image elevation-3"
            style="width:120px;height:60px;">
        <br>
        <span class="brand-text font-weight-light" style="font-size:140%;"> &nbsp; ADMIN</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('lte/dist/img/avatar5.png')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">Administrator</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{URL::to('admin/')}}" class="nav-link">
                        <i class="nav-icon fas fa-clipboard"></i>
                        <p>
                            iCLOP Summary
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{URL::to('admin/topics')}}" class="nav-link">
                        <i class="nav-icon fas fa-clipboard"></i>
                        <p>
                            Learning Topics
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{URL::to('admin/admintasks')}}" class="nav-link">
                        <i class="nav-icon fas fa-spinner"></i>
                        <p>
                            Learning Tasks
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{URL::to('admin/learning')}}" class="nav-link">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>
                            Learning Files
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{URL::to('admin/resources')}}" class="nav-link">
                        <i class="nav-icon fas fa-chevron-circle-right"></i>
                        <p>
                            Topic Resources
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{URL::to('admin/testfiles')}}" class="nav-link">
                        <i class="nav-icon fas fa-rocket"></i>
                        <p>
                            Test Files
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{URL::to('admin/assignteacher')}}" class="nav-link">
                        <i class="nav-icon fas fa-hand-pointer"></i>
                        <p>
                            Assign Teacher

                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{URL::to('admin/tmember')}}" class="nav-link">
                        <i class="nav-icon fas fa-hand-pointer"></i>
                        <p>
                            Teacher Member
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{URL::to('admin/studentres')}}" class="nav-link">
                        <i class="nav-icon fas fa-trophy"></i>
                        <p>
                            Student Result
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{URL::to('admin/resview')}}" class="nav-link">
                        <i class="nav-icon fas fa-trophy"></i>
                        <p>
                            Result Summary

                        </p>
                    </a>
                </li>
                </li>
                <li class="nav-item">
                    <a href="{{URL::to('admin/completeness')}}" class="nav-link">
                        <i class="nav-icon fas fa-trophy"></i>
                        <p>
                            Completeness

                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{URL::to('admin/rankview')}}" class="nav-link">
                        <i class="nav-icon fas fa-rocket"></i>
                        <p>
                            Top 20 Rank
                        </p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{URL::to('admin/uitopic')}}" class="nav-link">
                        <i class="nav-icon fas fa-rocket"></i>
                        <p>
                            UI Learning Topic
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{URL::to('admin/uitestfiles')}}" class="nav-link">
                        <i class="nav-icon fas fa-rocket"></i>
                        <p>
                            UI Test File
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{URL::to('admin/uisummaryres')}}" class="nav-link">
                        <i class="nav-icon fas fa-trophy"></i>
                        <p>
                            UI Student Result

                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{URL::to('admin/uiresview')}}" class="nav-link">
                        <i class="nav-icon fas fa-trophy"></i>
                        <p>
                            UI Result Summary

                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{URL::to('admin/exerciseconf')}}" class="nav-link">
                        <i class="nav-icon fas fa-clipboard"></i>
                        <p>
                            Android Exercise

                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{URL::to('admin/exercisefiles')}}" class="nav-link">
                        <i class="nav-icon fas fa-clipboard"></i>
                        <p>
                            Exercise Files

                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{URL::to('admin/exerciseresources')}}" class="nav-link">
                        <i class="nav-icon fas fa-chevron-circle-right"></i>
                        <p>
                            Exercise Resources

                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{URL::to('admin/exerciseresview')}}" class="nav-link">
                        <i class="nav-icon fas fa-trophy"></i>
                        <p>
                            Exercise Summary

                        </p>
                    </a>
                </li>
                <li class="nav-header">New Features</li>
                <li class="nav-item">
                    <a href="{{URL::to('/android23/topic')}}" class="nav-link">
                        <i class="nav-icon fab fa-android"></i>
                        <p>
                            Android 23 Topics
                            <span class="right badge badge-danger">New</span>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{URL::to('/admin/python/topic')}}" class="nav-link">
                        <i class="nav-icon fab fa-python"></i>
                        <p>
                            Python Learning
                            <span class="right badge badge-danger">New</span>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{URL::to('/admin/python/percobaan')}}" class="nav-link">
                        <i class="nav-icon fab fa-python"></i>
                        <p>
                            Python Percobaan
                            <span class="right badge badge-danger">New</span>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{URL::to('admin/resetpassword')}}" class="nav-link">
                        <i class="nav-icon fas fa-hand-pointer"></i>
                        <p>
                            Reset Password

                        </p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
