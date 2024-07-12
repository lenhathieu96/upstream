@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <div class="container-fluid">

        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col">
                        <h5 class="mb-md-0 h6">Create Country</h5>
                    </div>
                    
                </div>
              <div class="card-body" >
                <form action="{{route('country.store')}}" method="POST" id="country_from">
                    @csrf
                    {{-- Country Name --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Country Name</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="country_name" placeholder="Country Name">
                        </div>
                    </div>
                    {{-- Country Code --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Country Code</label>
                        <div class="col-md-8">
                            <input type="text" onkeyup="myFunction()" class="form-control" name="country_code" placeholder="Country Name">
                        </div>
                    </div>
                    {{-- Status --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Status</label>
                        <div class="col-md-8">
                            <div style="display: flex;align-items: center">
                                <input  class="radio_button_checkout"  type="radio" id="status" name="status" value="active"  checked/>
                                <div style="position: relative;left: 16px;">
                                    <span>Active</span>
                                </div>
                            </div>

                            <div style="display: flex;align-items: center">
                                <input  class="radio_button_checkout" type="radio" id="status" name="status" value="in_active"   />
                                <div style="position: relative;left: 16px;">
                                    <span>In Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mar-all mb-2" style=" text-align: end;">
                            <button type="submit" name="button"  value="publish"
                                class="btn btn-primary">Create</button>
                        </div>
                    </div>
                </form>
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
<script type="text/javascript">
    $(document).ready(function()
    {   
        
    });
</script>
@endpush