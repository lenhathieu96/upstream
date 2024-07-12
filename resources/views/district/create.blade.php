@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <div class="container-fluid">

        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col">
                        <h5 class="mb-md-0 h6">Create District</h5>
                    </div>
                    
                </div>
              <div class="card-body" >
                <form action="{{route('district.store')}}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Province Name</label>
                        <div class="col-md-8">
                            <select class="form-control aiz-selectpicker" name="province_id" id="province_id">
                                    @foreach ($province as $province_data)
                                        <option value="{{ $province_data->id }}">{{ $province_data->province_name }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Country Name --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">District Name</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="district_name" placeholder="District Name">
                        </div>
                    </div>
                    {{-- Country Code --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">District Code</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="district_code" placeholder="District Code">
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
                            <button type="submit" name="button" value="publish"
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