@extends('layouts.app')

@section('content')
    <div class="container p-0">
        @include('shared.form-alerts')

        <form action="{{ !empty($cropStage->id) ? route('crop-stages.update', ['crop_stage' => $cropStage]) : route('crop-stages.store') }}" method="POST" class="mb-5" data-parsley-validate>
            {{ $cropStage->id ? method_field('PUT') : method_field('POST') }}
            @csrf

            <div class="card">
                <div class="card-header">
                  Create Crop Stage
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label for="js-season-code">Name</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ $cropStage->name }}" data-parsley-required="true"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label for="js-season-code">Crop Information</label>
                            </div>
                            <div class="col-md-3">
                                <select name="crop_information_id" class="form-control" required
                                    data-fetch-child="true"
                                    data-fetch-target="#js-crop-variety"
                                    data-fetch-url="{{ route('ajax_options.get-varieties') }}"
                                    data-fetch-param-name="crop_information_id">

                                    <option value="">Select Crop Information</option>
                                    @foreach($cropInformations as $cropInformation)
                                        <option value="{{ $cropInformation->id }}" {{ $cropInformation->id == $cropStage->crop_information_id ? 'selected' : ''}}>
                                            {{$cropInformation->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label for="js-crop-variety">Variety</label>
                            </div>
                            <div class="col-md-3">
                                <select name="crop_variety_id" id="js-crop-variety" class="form-control">
                                    <option value="">Select Crop Variety</option>
                                    @foreach($cropVarieties as $cropVarietie)
                                        <option value="{{ $cropVarietie->id }}" {{ $cropVarietie->id == $cropStage->crop_variety_id ? 'selected' : ''}}>
                                            {{$cropVarietie->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label for="js-date">Date</label>
                            </div>
                            <div class="col-md-3">
                                <input type="number" id="js-date" name="date" class="form-control" placeholder="Date" value="{{ $cropStage->date }}"/>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-2">
                                <label for="js-season-code">Status</label>
                            </div>
                            <div class="col-md-3 d-flex">
                                <div class="form-check me-4">
                                    <input class="form-check-input" type="radio" name="status" id="status-active" value="active" {{ $cropStage->status == 'active' ? 'checked' : ''}} data-parsley-required="true" data-parsley-errors-container=".status-error">
                                    <label class="form-check-label" for="status-active">
                                      Active
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status-inactive" value="inactive" {{ $cropStage->status == 'inactive' ? 'checked' : ''}} data-parsley-required="true" data-parsley-errors-container=".status-error">
                                    <label class="form-check-label" for="status-inactive">
                                        Inactive
                                    </label>
                                  </div>
                            </div>
                            <div class="col-9 offset-2 status-error">

                            </div>
                        </div>
                    
                        <div class="form-group row">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">submit</button>
                            </div>
                        </div>
                    </div>

                </div>
              </div>
              
        </form>
    </div>
@endsection 

@section('style')

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            
        });
    </script>
@endpush