<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>iCLOP - Administrator Site</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{asset('lte/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('lte/dist/css/adminlte.min.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('js/datatables/dataTables.bootstrap4.min.css') }}">

  <link href="https://unpkg.com/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
    




  <style>
    .pulse {
      animation: pulse-animation 1s infinite;
    }

    @keyframes pulse-animation {
      0% {
        box-shadow: 0 0 0 0px rgba(0, 0, 0, 0.2);
      }
      100% {
        box-shadow: 0 0 0 20px rgba(0, 0, 0, 0);
      }
    }
  </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
	@include('android/admin/header')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('android/admin/sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    
    <!-- /.content-header -->

    <!-- Main content -->
    @yield('content')
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  @include('android/admin/footer')

</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{asset('lte/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('lte/dist/js/adminlte.min.js')}}"></script>

<script src="{{ asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script src="https://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
<script src="https://unpkg.com/@yaireo/tagify"></script>
<script src="https://unpkg.com/@yaireo/tagify@3.1.0/dist/tagify.polyfills.min.js"></script>
<script>
  $(function() {

    

    // The DOM element you wish to replace with Tagify
    


    $('#on-modal-xl').on('click', function(){
      
      let input = document.getElementById("form-a");
      new Tagify(input);

      // display modal 
      $('#modal-xl').modal('show');
    });



    $('#on-modal-submission').on('click', function(){
      
      let input = document.getElementById("form-b");
      new Tagify(input);

      // display modal 
      $('#modal-submission').modal('show');
    });



    
    // $('> #btn-update').on('click', function() {

    //   let index = $(this).data('index');
    //   alert(index);
    // })

    // initialize Tagify on the above input node reference
    $("#datatable").DataTable();
    
  })



  function doUpdate( index ) {
      let input = document.getElementById("form-" + index);
      new Tagify(input);

      $('#modal-update-' + index).modal('show');
  }


  function doSettingTestcase( index ) {

      let input = document.getElementById("form-" + index);
      new Tagify(input);   

      $('#display-testcase-' + index).modal('show'); 
  }
</script>


</body>
</html>
