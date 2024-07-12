@php 
@endphp

<div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">Distribution Transaction</h5>
                </div>

            </div>
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                      <th>Date</th>
                      <th>Warehouse</th>
                      <th>Mobile User</th>
                      <th>Category</th>
                      <th>Product</th>
                      <th>Season</th>
                      <th>Unit</th>
                      <th>Total Quantity</th>
                      <th>Gross Amount</th>
                      <th>Tax</th>
                      <th>Final Amount</th>
                      <th>Payment Amount</th>
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

    <div class="row">
        <div class="col-12">
          <div class="card">
              <div class="card-header row gutters-5">
                  <div class="col">
                      <h5 class="mb-md-0 h6">Crop Harvest Transaction</h5>
                  </div>
  
              </div>
            <div class="card-body">
              <table id="example2" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                        <th>Date</th>
                        <th>Farm</th>
                        <th>Mobile User</th>
                        <th>Product</th>
                        <th>Season</th>
                        <th>Variety</th>
                        <th>Grade</th>
                        <th>Total Year Quantity</th>
                        <th>Unit</th>
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

    <div class="row">
        <div class="col-12">
          <div class="card">
              <div class="card-header row gutters-5">
                  <div class="col">
                      <h5 class="mb-md-0 h6">Tranining Status</h5>
                  </div>
  
              </div>
            <div class="card-body">
              <table id="example3" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                        <th>Date</th>
                        <th>Mobile User</th>
                        <th>Training Code</th>
                        <th>Training Assistant name</th>
                        <th>Time taken for training</th>
                        <th>Remarks</th>
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

    <div class="row">
        <div class="col-12">
          <div class="card">
              <div class="card-header row gutters-5">
                  <div class="col">
                      <h5 class="mb-md-0 h6">Farmer Balance Report</h5>
                  </div>
  
              </div>
            <div class="card-body">
              <table id="example4" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                        <th>Date</th>
                        <th>Transaction Type</th>
                        <th>Receipt Number</th>
                        <th>Initial Balance</th>
                        <th>Transaction Amount</th>
                        <th>Balance Amount</th>
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

    <div class="row">
        <div class="col-12">
          <div class="card">
              <div class="card-header row gutters-5">
                  <div class="col">
                      <h5 class="mb-md-0 h6">Procurement Transaction Report</h5>
                  </div>
  
              </div>
            <div class="card-body">
              <table id="example5" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                        <th>Date</th>
                        <th>Mobile User</th>
                        <th>Season</th>
                        <th>Product Name</th>
                        <th>Unit</th>
                        <th>Number Of Bags</th>
                        <th>Net Weight </th>
                        <th>Total Amount</th>
                        <th>Payment Amount</th>
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
                responsive: false,
                scrollX: true,
                processing: true,
                searching: false,
                bSort:false,
                serverSide: true,
                ajax: "{{route('farmer.distribute_transation')}}",
                columns: 
                [
                    {data: 'date', name: 'date',render: function (data) {
                            return (data=="")?"":data;
                    }},
                    {data: 'warehouse', name: 'warehouse', render: function(data){
                        return (data=="")?"":data;
                    }},
                    {data: 'mobile_user', name: 'mobile_user',render: function (data) {
                    return (data=="")?"":data;
                    }},
                    {data: 'category', name: 'category',render: function (data) {
                    return (data=="")?"":data;
                    }},
                    {data: 'product', name: 'product'},
                    {data: 'season', name: 'season'},
                    {data: 'unit', name: 'unit'},
                    {data: 'total_quantity', name: 'total_quantity'},
                    {data: 'gross_amount', name: 'gross_amount'},
                    {data: 'tax', name: 'tax'},
                    {data: 'final_amount', name: 'final_amount'},
                    {data: 'payment_amount', name: 'payment_amount'},
                ]
                    
        });

        var rfq_table = $("#example2").DataTable
        ({
                lengthChange: true,
                responsive: false,
                scrollX: true,
                processing: true,
                searching: false,
                bSort:false,
                serverSide: true,
                    ajax: "{{route('farmer.distribute_transation')}}",
                    // error: function (xhr) {
                    //     if (xhr.status == 401) {
                    //     window.location.href = "{!! route('login') !!}";
                    //     }
                    // },
                    
        });

        var rfq_table = $("#example3").DataTable
        ({
                lengthChange: true,
                responsive: false,
                scrollX: true,
                processing: true,
                searching: false,
                bSort:false,
                serverSide: true,
                    ajax: "{{route('farmer.distribute_transation')}}",
                    // error: function (xhr) {
                    //     if (xhr.status == 401) {
                    //     window.location.href = "{!! route('login') !!}";
                    //     }
                    // },
                    
        });

        var rfq_table = $("#example4").DataTable
        ({
                lengthChange: true,
                responsive: false,
                scrollX: true,
                processing: true,
                searching: false,
                bSort:false,
                serverSide: true,
                    ajax: "{{route('farmer.distribute_transation')}}",
                    // error: function (xhr) {
                    //     if (xhr.status == 401) {
                    //     window.location.href = "{!! route('login') !!}";
                    //     }
                    // },
                    
        });

        var rfq_table = $("#example5").DataTable
        ({
                lengthChange: true,
                responsive: false,
                scrollX: true,
                processing: true,
                searching: false,
                bSort:false,
                serverSide: true,
                    ajax: "{{route('farmer.distribute_transation')}}",
                    // error: function (xhr) {
                    //     if (xhr.status == 401) {
                    //     window.location.href = "{!! route('login') !!}";
                    //     }
                    // },
                    
        });
    });

    
</script>
@endpush