@extends('layouts.app')

@section('content')
    <div class="container p-0">
        @include('shared.form-alerts')

        <form action="{{ !empty($cropActivity->id) ? route('crop-activities.update', ['crop_activity' => $cropActivity]) : route('crop-activities.store') }}" method="POST" class="mb-5" data-parsley-validate>
            {{ $cropActivity->id ? method_field('PUT') : method_field('POST') }}
            @csrf

            <div class="card">
                <div class="card-header">
                  Create Crop Activity
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label for="js-season-code">Name</label>
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ $cropActivity->name }}" data-parsley-required="true"/>
                            </div>
                        </div>
    
                        <div class="form-group row">
                            <div class="col-md-2">
                                <label for="js-season-code">Status</label>
                            </div>
                            <div class="col-md-3 d-flex">
                                <div class="form-check me-4">
                                    <input class="form-check-input" type="radio" name="status" id="status-active" value="active" {{ $cropActivity->status == 'active' ? 'checked' : ''}} data-parsley-required="true" data-parsley-errors-container=".status-error">
                                    <label class="form-check-label" for="status-active">
                                      Active
                                    </label>
                                  </div>
                                  <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="status-inactive" value="inactive" {{ $cropActivity->status == 'inactive' ? 'checked' : ''}} data-parsley-required="true" data-parsley-errors-container=".status-error">
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