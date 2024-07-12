@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <div class="container-fluid">

        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col">
                        <h5 class="mb-md-0 h6">All Farm Land</h5>
                    </div>
                </div>
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                          <th>Farmland Name</th>
                          <th>Farmer Code</th>
                          <th>Farmer Name</th>
                          <th>Farmer Phone</th>
                          <th>FO Name</th>
                          <th>Land Ownership</th>
                          <th>Total Landholding</th>
                          <th>Actual Area</th>
                          <th>Lat</th>
                          <th>Lng</th>
                          <th>Action</th>
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
                scrollX: true,
                lengthChange: true,
                // responsive: true,
                processing: true,
                searching: false,
                bSort:false,
                serverSide: true,
                    ajax: "{{route('farmer_report.farmland_report_ajax')}}",
                    // error: function (xhr) {
                    //     if (xhr.status == 401) {
                    //     window.location.href = "{!! route('login') !!}";
                    //     }
                    // },
                    columns: [
                          {data: 'farm_name', name: 'farm_name', render: function(data,type,row){
                            return (data=="")?"":data;
                          }},
                          {data: 'farmer_details', name: 'farmer_code',render: function (data) {
                            return (data.farmer_code=="")?"":data.farmer_code;
                          }},
                          {data: 'farmer_details', name: 'full_name',render: function (data) {
                            return (data.full_name=="")?"":data.full_name;
                          }},
                          {data: 'farmer_details', name: 'phone_number',render: function (data) {
                            return (data.phone_number=="")?"":data.phone_number;
                          }},
                          {data: 'staff_name', name: 'staff_name',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'land_ownership', name: 'farmer_code',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'total_land_holding', name: 'total_land_holding',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'actual_area', name: 'full_name',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'lat', name: 'phone_number',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'lng', name: 'staff_name',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'action', name: 'action',render: function (data) {
                            return (data=="")?"":data;
                          }},
                      ]
        });
    });

    
</script>
@endpush