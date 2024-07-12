@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <div class="container-fluid">

        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col">
                        <h5 class="mb-md-0 h6">All Staff</h5>
                    </div>
                    <div class="col">
                        <div class="mar-all mb-2" style=" text-align: end;">
                            <a href="{{route('staff.create')}}">
                                <button type="submit" name="button" value="publish"
                                    class="btn btn-primary">Create</button>
                            </a>
                        </div>
                    </div>
                </div>
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                          <th>Crop Variety</th>
                          <th>FarmLand Name</th>
                          <th>Farmer Code</th>
                          <th>Farmer Name</th>
                          <th>Phone Number</th>
                          <th>FO Staff</th>
                          <th>Season</th>
                          <th>Sowing Date</th>
                          <th>Expect Date</th>
                          <th>Est Yield</th>
                      </tr>
                    </thead>
                    <tbody>
                        <tr>
                        </tr>
                    </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <script>
        @if(Session::has('success'))
        toastr.options =
        {
          "closeButton" : true,
          "progressBar" : true
        }
        toastr.success("{{ session('success') }}");
        @endif
        @if(Session::has('add'))
        toastr.options =
        {
          "closeButton" : true,
          "progressBar" : true
        }
        toastr.success("{{ session('add') }}");
        @endif
        @if(Session::has('delete'))
        toastr.options =
        {
          "closeButton" : true,
          "progressBar" : true
        }
            toastr.success("{{ session('delete') }}");
        @endif
      </script>
@stop

@push('scripts')
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}" ></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}" ></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}" ></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}" ></script>

<script type="text/javascript">

    function userCheated() {
            // The user cheated by leaving this window (e.g opening another window)
            // Do something
        alert("New Tab Opened");
    }

    $(document).ready(function()
    {   
        var rfq_table = $("#example1").DataTable
        ({
                lengthChange: true,
                responsive: true,
                processing: true,
                searching: false,
                bSort:false,
                serverSide: true,
                    ajax: "{{route('farmer_report.cultivation_report_ajax')}}",
                    // error: function (xhr) {
                    //     if (xhr.status == 401) {
                    //     window.location.href = "{!! route('login') !!}";
                    //     }
                    // },
                    columns: [
                          {data: 'crop_variety', name: 'crop_variety', render: function(data,type,row){
                            return (data=="")?"":data;
                          }},
                          {data: 'farm_land.farm_name', name: 'farmland_name',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'farmer_data.farmer_code', name: 'farmer_code',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'farmer_data.full_name', name: 'full_name',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'farmer_data.phone_number', name: 'phone_number',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'staff_name', name: 'staff_name',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'season', name: 'season',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'sowing_date', name: 'sowing_date',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'expect_date', name: 'expect_date',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'est_yield', name: 'est_yield',render: function (data) {
                            return (data=="")?"":data;
                          }},
                      ]
        });
    });

    
</script>
@endpush