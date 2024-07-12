@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <div class="container-fluid">

        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col">
                        <h5 class="mb-md-0 h6">All Province</h5>
                    </div>
                    <div class="col">
                        <div class="mar-all mb-2" style=" text-align: end;">
                            <a href="{{route('province.create')}}">
                                <button type="submit" name="button" value="publish"
                                    class="btn btn-primary">Create</button>
                            </a>
                        </div>
                    </div>
                </div>
              <div class="card-body" >
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Country Name</th>
                      <th>Province Name</th>
                      <th>Province Code</th>
                      <th>Status</th>
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
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}" ></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}" ></script>
<script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}" ></script>
<script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}" ></script>

<script type="text/javascript">
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
                    ajax: "{{route('province.dtajax')}}",
                    // error: function (xhr) {
                    //     if (xhr.status == 401) {
                    //     window.location.href = "{!! route('login') !!}";
                    //     }
                    // },
                    columns: [
                          {data: 'country_name', name: 'country_name', render: function(data){
                              return (data=="")?"":data;
                          }},
                          {data: 'province_name', name: 'province_name',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'province_code', name: 'province_code',render: function (data) {
                            return (data=="")?"":data;
                          }},
                          {data: 'status', name: 'status',render: function (data) {
                            return (data=="active")?"Active":"In Active";
                          }},
                      ]
        });
    });

    
</script>
@endpush