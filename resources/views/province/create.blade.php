@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <div class="container-fluid">

        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col">
                        <h5 class="mb-md-0 h6">Create Province</h5>
                    </div>
                    
                </div>
              <div class="card-body" >
                <form action="{{route('province.store')}}" method="POST">
                    @csrf
                    {{-- Country Name --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Country Name</label>
                        <div class="col-md-8">
                            <select class="form-control aiz-selectpicker" name="country_id" id="country_id">
                                    @foreach ($country as $country_data)
                                        <option value="{{ $country_data->id }}">{{ $country_data->country_name }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Province Name</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="province_name" placeholder="Province Name">
                        </div>
                    </div>
                    {{-- Country Code --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Province Code</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="province_code" placeholder="Province Name">
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