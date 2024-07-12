@extends('layouts.app')

@section('content')
    <div class="container p-0">
        @include('shared.form-alerts')

        <form action="{{ !empty($cropInformation->id) ? route('crop-informations.update', ['crop_information' => $cropInformation]) : route('crop-informations.store') }}" method="POST" class="mb-5" data-parsley-validate id="form-crop-information" enctype="multipart/form-data">
            {{ $cropInformation->id ? method_field('PUT') : method_field('POST') }}
            @csrf

            <div class="card">
                <div class="card-header">
                    <div class="col">
                        <h5 class="mb-md-0 h6"  style="color:black;font-size:24px">{{ $cropInformation->id ? 'Edit Crop Master' : 'Create Crop Master' }}</h5>
                    </div>
                    <div class="col">
                        <div class="mar-all mb-2" style=" text-align: end;">
                            <a href="{{route('crop-informations.index')}}">
                                <button type="button" name="button" value="publish" class="btn btn-primary waves-effect waves-light">Back</button>
                            </a>
                        </div>
                    </div>
                  
                </div>
                <div class="card-body">

                    <div class="form-group row mb-3">
                        <div class="col-12 col-md-2">
                            <label for="js-name">Name<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-12 col-md-3">
                            <input id="js-name" name="name" type="text" class="form-control" value="{{ $cropInformation->name }}" autocomplete="off" placeholder="Name"  data-parsley-required="true" data-parsley-trigger="change">
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-12 col-md-2">
                            <label for="s-crop-category-code">Crop Category<span class="text-danger">*</span></label>
                        </div>
                        <div class="col-12 col-md-3">
                            <select name="crop_category_code" id="js-crop-category-code" class="form-control" required>
                                <option value="">Select Season</option>
                                @foreach (\App\Models\CropCategory::get()->pluck('name', 'code') as $code => $name)
                                    <option value="{{  $code }}" {{ $code == $cropInformation->crop_category_code ? 'selected' : ''}}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-12 col-md-2">
                            <label for="js-photo-input">Photo</label>
                        </div>
                        <div class="col-12 col-md-3">
                            <img class="mb-3 js-image-upload {{ $cropInformation->photo_url ? '' : 'd-none' }}" src="{{ $cropInformation->photo_url }}" width="200" alt="Author icon">
                            <input id="js-photo-input" 
                                name="photo" type="file" 
                                accept=".jpeg, .png, .jpg, .gif"
                                data-parsley-trigger="change"
                                data-parsley-filemaxmegabytes="10"
                                data-parsley-fileextensions="jpeg, png, jpg, gif"
                                data-parsley-fileextensions-message="Upload jpeg, png, jpg or gif file"
                                data-parsley-errors-container="#js-photo-error">

                            <div id="js-photo-error" class="text-nowrap mt-2"></div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-12 col-md-2">
                            <label for="js-season-code">Duration</label>
                        </div>
                        <div class="col-12 col-md-3 d-flex">
                            <input id="js-duration" name="duration" type="number" class="form-control" style="margin-right: 15px;" value="{{ $cropInformation->duration }}" autocomplete="off" placeholder="Duration">
                            <select name="duration_type" id="js-duration-type" class="form-control">
                                <option value="">Select Type</option>
                                @foreach (\App\Models\CropInformation::DURATION_TYPE as $code => $name)
                                    <option value="{{  $code }}" {{ $code == $cropInformation->duration_type ? 'selected' : ''}}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-12 col-md-2">
                            <label for="js-season-code">Expected Expense</label>
                        </div>
                        <div class="col-12 col-md-3 d-flex align-items-center">
                            <input id="js-expected-expense" name="expected_expense" type="number" class="form-control" style="margin-right: 15px;" value="{{ $cropInformation->expected_expense }}" autocomplete="off" placeholder="Expected Expense">
                            <span style="font-weight: 500">VND</span>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-12 col-md-2">
                            <label for="js-season-code">Expected Income</label>
                        </div>
                        <div class="col-12 col-md-3 d-flex align-items-center">
                            <input id="js-expected-income" name="expected_income" type="number" class="form-control" style="margin-right: 15px;" value="{{ $cropInformation->expected_income }}" autocomplete="off" placeholder="Expected Income">
                            <span style="font-weight: 500">VND</span>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-12 col-md-2">
                            <label for="js-season-code">Expected Yield</label>
                        </div>
                        <div class="col-12 col-md-3 d-flex align-items-center">
                            <input id="js-expected_yield" name="expected_yield" type="number" class="form-control" style="margin-right: 15px;" value="{{ $cropInformation->expected_yield }}" autocomplete="off" placeholder="Expected Yield">
                            <span style="font-weight: 500">HA</span>
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

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let $photoInput = $('#js-photo-input');

            $photoInput.on('change', function () {
                if ($photoInput.parsley().isValid()) {
                    let reader = new FileReader();

                    reader.onload = function() {
                        $('.js-image-upload').attr('src', reader.result).toggleClass('d-none');
                    };

                    return reader.readAsDataURL($photoInput[0].files[0]);
                } else {
                    $('.js-image-upload').addClass('d-none');
                }
            });

            $('#js-from-period, #js-to-period').change(function () {
                $('#form-crop-information').parsley().validate();
            })
        });

        Parsley.addValidator('filemaxmegabytes', {
            requirementType: 'string',
            validateString: function (value, requirement, parsleyInstance) {
                let file = parsleyInstance.$element[0].files;
                let maxBytes = requirement * 1024 * 1024;
                
                if (file.length == 0) {
                    return true;
                }
                
                return file.length === 1 && file[0].size <= maxBytes;
            },
            messages: {
                en: 'File is too big'
            }
        });

        Parsley.addValidator('fileextensions', {
            requirementType: 'string',
            validateString: function (value, requirement, parsleyInstance) {
                let file = parsleyInstance.$element[0].files;
                
                if (file.length == 0) {
                    return true;
                }
                
                let allowedExtensions = requirement.replace(/\s/g, "").split(',');
                return allowedExtensions.indexOf(file[0].name.toLowerCase().split('.').pop()) !== -1;
            },
            messages: {
                en: 'File extension not allowed'
            }
        });
    </script>
@endpush