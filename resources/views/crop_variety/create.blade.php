@extends('layouts.app')
@section('content')
    <!-- Main content -->
    <div class="container-fluid">

        <div class="row">
          <div class="col-12">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col">
                        <h5 class="mb-md-0 h6">Create Crop Variety</h5>
                    </div>
                    
                </div>
              <div class="card-body" >
                <form action="{{route('crop_variety.store')}}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Crop Information Name</label>
                        <div class="col-md-8">
                            <select class="form-control aiz-selectpicker" name="crop_information_id" id="crop_information_id">
                                    @foreach ($crop_information as $crop_information_data)
                                        <option value="{{ $crop_information_data->id }}">{{ $crop_information_data->name }}</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- Country Name --}}
                    <div class="form-group row">
                        <label class="col-md-3 col-from-label">Crop Variety Name</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="crop_variety_name" placeholder="Crop Variety Name">
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