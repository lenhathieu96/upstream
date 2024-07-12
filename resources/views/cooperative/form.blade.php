@extends('layouts.app')

@section('content')
    <div class="container p-0">
        @include('shared.form-alerts')

        <form action="{{ !empty($cooperative->id) ? route('cooperative.update', ['cooperative' => $cooperative]) : route('cooperative.store') }}" method="POST" class="mb-5" data-parsley-validate id="form-cooperative">
            {{ $cooperative->id ? method_field('PUT') : method_field('POST') }}
            @csrf

            <div class="card">
                <div class="card-header">
                    {{ $cooperative->id ? 'Update Cooperative' : 'Create Cooperative' }}
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-12 col-md-2">
                            <label for="js-cooperative-name">Cooperative Name</label>
                        </div>
                        <div class="col-12 col-md-3">
                            <input id="js-cooperative-name" name="name" type="text" placeholder="Cooperative Name" class="form-control" value="{{ old('name', $cooperative->name) }}" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12 col-md-2">
                            <label for="js-formation-date">Formation Date</label>
                        </div>
                        <div class="col-12 col-md-3">
                            <input id="js-formation-date" name="formation_date" type="text" placeholder="Formation Date" class="form-control datatimepicker-enable" value="{{ old('formation_date', $cooperative->formation_date) }}" required>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <div class="col-12 col-md-2">
                            <label for="js-to-status">Status</label>
                        </div>
                        <div class="col-12 col-md-3">
                            <div class="d-flex">
                                <div class="form-check mt-2" style="margin-right: 1rem;">
                                    <input class="form-check-input" type="radio" name="status" id="status-active" value="active" 
                                    {{ $cooperative->status != 'inactive' ? 'checked' : ''}}  
                                    data-parsley-required="true" 
                                    data-parsley-required-message="Please choose status" 
                                    data-parsley-errors-container="#js-status-errors">
                                    <label class="form-check-label" for="status-active">Active</label>
                                </div>
                                <div class="form-check  mt-2">
                                    <input class="form-check-input" type="radio" name="status" id="status-inactive" value="inactive" 
                                    {{ $cooperative->status == 'inactive' ? 'checked' : ''}}  
                                    data-parsley-required="true" 
                                    data-parsley-required-message="Please choose status" 
                                    data-parsley-errors-container="#js-status-errors">
                                    <label class="form-check-label" for="status-inactive">Inactive</label>
                                </div>
                            </div>
                            <div id="js-status-errors"></div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12 col-md-2">
                            <label for="js-email">Email</label>
                        </div>
                        <div class="col-12 col-md-3">
                            <input id="js-email" name="email" type="email" placeholder="Email" class="form-control" value="{{ old('email', $cooperative->email) }}"
                                required
                                data-parsley-type="email" 
                                data-parsley-remote="{{ route('ajax_options.is-email-exist', ['cooperative' => $cooperative->id]) }}"
                                data-parsley-remote-message="This contact email is already registered"
                                data-parsley-trigger="change">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12 col-md-2">
                            <label for="js-phone-number">Phone Number</label>
                        </div>
                        <div class="col-12 col-md-3">
                            <input id="js-phone-number" name="phone_number" type="number" placeholder="Phone Number" class="form-control" value="{{ old('phone_number', $cooperative->phone_number) }}"
                                required
                                data-parsley-remote="{{ route('ajax_options.is-phone-exist', ['cooperative' => $cooperative->id]) }}"
                                data-parsley-remote-message="This phone number is already exists"
                                data-parsley-trigger="change">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-12 col-md-2">
                            <label for="js-service">Service</label>
                        </div>
                        <div class="col-12 col-md-3">
                            <select name="services[]" id="js-service" class="form-control js-select2" multiple required data-parsley-errors-container="#js-services-errors">
                                <option class="invisible" value="">- Select Service -</option>
                                @foreach(['fertilizer', 'harvester', 'soil preparation', 'seeds', 'plant protection products', 'compost'] as $service)
                                    <option value="{{ $service }}" {{ in_array($service, explode(',' , $cooperative->services))  ? 'selected' : '' }}>{{ $service }}</option>
                                @endforeach
                            </select>
                            <div id="js-services-errors"></div>
                        </div>
                    </div>

                    <div class="form-group row mt-3">
                        <div class="col-12 col-md-2">
                        </div>
                        <div class="col-12 col-md-3">
                            <button type="submit" class="btn btn-primary">submit</button>
                        </div>
                    </div>
                </div>
              </div>
              
        </form>
    </div>
@endsection 

@section('style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@push('scripts')
    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{ asset('custom/js/jquery.datetimepicker.full.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.js-select2').select2();

            $('.datatimepicker-enable').datetimepicker({
                format: 'Y-m-d',
        		datepicker: true,
                timepicker: false,
            });

            $('#js-from-period, #js-to-period').change(function () {
                $('#form-season-master').parsley().validate();
            })
        });
    </script>
@endpush