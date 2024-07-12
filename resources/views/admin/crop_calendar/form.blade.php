@extends('layouts.app')

@section('content')
    <div class="container p-0">
        @include('shared.form-alerts')

        <form action="{{ !empty($cropCalendar->id) ? route('crop-calendars.update', ['crop_calendar' => $cropCalendar]) : route('crop-calendars.store') }}" method="POST" class="mb-5" data-parsley-validate>
            {{ $cropCalendar->id ? method_field('PUT') : method_field('POST') }}
            @csrf

            <div class="card">
                <div class="card-header bg-primary text-white" style="background-color: #2E7F25 !important">
                    {{ $cropCalendar->id ? 'Update Crop Calendar' : 'Create Crop Calendar' }}
                </div>
                <div class="card-body mt-4">
                    <div class="form-group row">
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="col-12 col-md-3">
                                <label for="js-crop-infor-id">Crop</label>
                            </div>
                            <div class="col-12 col-md-6">
                                <select name="crop_info_id" id="js-crop-infor-id" class="form-control" required>
                                    <option value="">Select Crop</option>
                                    @foreach($cropInformations as $cropId => $cropName)
                                        <option value="{{ $cropId }}" {{ $cropCalendar->crop_info_id == $cropId ? 'selected' : ''}}>{{ $cropName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <div class="col-12 col-md-3">
                                <label for="js-crop-infor-id">Calendar Name</label>
                            </div>
                            <div class="col-12 col-md-6">
                                <input name="calendar_name" class="form-control" value="{{ $cropCalendar->calendar_name }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group row mt-3">
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="col-12 col-md-3">
                                <label for="js-from-period">Country</label>
                            </div>
                            <div class="col-12 col-md-6">
                                <select name="country_id" id="js-country-id" class="form-control" required
                                    data-fetch-child="true"
                                    data-fetch-target="#js-provinces"
                                    data-fetch-url="{{ route('ajax_options.get-provinces') }}"
                                    data-fetch-param-name="country_id">
                                    <option value="">Select Country</option>
                                    @foreach(\App\Models\Country::get() as $country)
                                            <option value="{{ $country->id }}" {{ $cropCalendar->country_id == $country->id ? 'selected' : ''}}>{{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <div class="col-12 col-md-3">
                                <label for="js-crop-infor-id">Province</label>
                            </div>
                            <div class="col-12 col-md-6">
                                <select name="province_id" id="js-provinces" class="form-control" required
                                    data-fetch-child="true"
                                    data-fetch-target="#js-district"
                                    data-fetch-url="{{ route('ajax_options.get-districts') }}"
                                    data-fetch-param-name="province_id">
                                    <option value="">Select Province</option>
                                    @if (!empty($cropCalendar->country_id))
                                        @foreach(\App\Models\Province::where('country_id', $cropCalendar->country_id)->get() as $province)
                                            <option value="{{ $province->id }}" {{ $cropCalendar->province_id == $province->id ? 'selected' : '' }}>{{ $province->province_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        
                    </div>

                    <div class="form-group row mt-3">
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="col-12 col-md-3">
                                <label for="js-crop-infor-id">District</label>
                            </div>
                            <div class="col-12 col-md-6">
                                <select name="district_id" id="js-district" class="form-control" required>
                                    <option value="">Select District</option>
                                    @if (!empty($cropCalendar->province_id))
                                        @foreach(\App\Models\District::where('province_id', $cropCalendar->province_id)->get() as $district)
                                            <option value="{{ $district->id }}" {{ $cropCalendar->district_id == $district->id ? 'selected' : '' }}>{{ $district->district_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 d-flex align-items-center">
                            <div class="col-12 col-md-3">
                                <label for="js-crop-infor-id">Status</label>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="switch">
                                    <input type="checkbox" name="status" class="switch-input js-switch-status" {{ $cropCalendar->status == 'inactive' ? '' : 'checked' }}>
                                    <span class="switch-toggle-slider">
                                      <span class="switch-on"></span>
                                      <span class="switch-off"></span>
                                    </span>
                                    <span class="switch-label"></span>
                                  </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <h3 class="mt-5">Crop Activities</h3>
            <p>The list of activities that needs to be carried out as part of above crop is entered here</p>

            <div style="overflow-x: scroll;">
                <table class="table table-bordered" style="width:2500px;">
                    <thead>
                    <tr style="background-color: #2E7F25;">
                        <th style="color:white; width: 250px;">Activity Title</th>
                        <th style="color:white; width: 250px;">Activity</th>
                        <th style="color:white; width: 250px;">Stage</th>
                        <th style="color:white; width: 250px;">Duration</th>
                        <th style="color:white; width: 250px;">Duration UOM</th>
                        <th style="color:white; width: 500px;">Activity Description</th>
                        <th style="color:white; width: 250px;">Repitition</th>
                        <th style="color:white; width: 250px;">Lead Time</th>
                        <th style="color:white; width: 250px;">Is based on sowing date</th>
                        <th style="color:white; width: 250px;">Is active calendar</th>
                        <th style="color:white; width: 250px;">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if ($cropCalendar->cropCalendarDetails()->count())
                            @foreach($cropCalendar->cropCalendarDetails as $itemIndex => $calendarDetail)
                                @include('admin.crop_calendar.calendar_details', ['calendarDetail' => $calendarDetail, 'itemIndex' => $itemIndex])
                            @endforeach
                        @endif
                        <tr class="js-calendar-detail-button">
                            <td colspan="12">
                                <button type="button" class="btn btn-info js-btn-add-calendar-detail"><span class="mdi mdi-plus"></span> Add</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <div class="form-group row mt-5">
                <div class="col d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection 

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.5.1/skins/content/default/content.min.css" integrity="sha512-KYlPDsJE6wqDev6smrRzaH8VwjoFV9Xj4VzyoUok3vzkVZe0g32WFiVawEiAD77EI2tSoruKNJCedUSCrk5E/Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.5.1/tinymce.min.js" integrity="sha512-rCSG4Ab3y6N79xYzoaCqt9gMHR0T9US5O5iBuB25LtIQ1Hsv3jKjREwEMeud8q7KRgPtxhmJesa1c9pl6upZvg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.5.1/plugins/lists/plugin.min.js" integrity="sha512-guy/v8wevrJz0DR4nCqBOEcFjvNtSfKQbDvN3sprAtIEfH7VMK6henRRm1spWo5POJxKLQn1LIb/lTfNmIojZw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.5.1/plugins/link/plugin.min.js" integrity="sha512-obmRleBnuneGV+XoUL8Rk9MH1HzasoVVzbiWflzppL5NalyWfQ5Ul5IwhuNuRs6wVAV7DtWdpKeGjaKuu5rPcw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            let $body = $('body');

            $body.removeClass('preload'); // To prevent CSS transition on page load

            $body.on('change', 'select[data-fetch-child=true]', (e) => {
                let $parentOption = $(e.currentTarget);
                let targetSelector = $parentOption.attr('data-fetch-target');
                let fetchUrl = $parentOption.attr('data-fetch-url');

                let paramName = $parentOption.attr('data-fetch-param-name');

                if (paramName === undefined) {
                    paramName = $parentOption.attr('name');
                }

                let $targetElement = $(targetSelector);
                let firstOptionHtml = $targetElement.find('option:first-child')[0].outerHTML;

                let submitData = {};
                submitData[paramName] = $parentOption.val();

                $.get(fetchUrl, submitData)
                    .done(function(data) {
                        let htmlOptions = Object.keys(data).map(function(key) {
                            return `<option value="${key}">${data[key]}</option>`
                        });
                        $targetElement.html(firstOptionHtml + htmlOptions);
                        $targetElement.trigger('change');
                    });
            });

            tinymce.init({
                selector:'textarea.tinymce-enable',
                toolbar_mode: 'wrap',
                min_height: 250,
                menubar: false,
                browser_spellcheck: true,
                entity_encoding: 'raw', // Without this the editor change Vietnamese chars to HTML entities so we can't use LIKE queries
                plugins: [
                    'advlist autolink lists link charmap print preview anchor',
                    'searchreplace visualblocks fullscreen  textpattern',
                    'insertdatetime media help wordcount textcolor'
                ],
                toolbar: 'undo redo | formatselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat link',
                content_css: false,
                setup: function (editor) {
                    editor.on('change', function () {
                        tinymce.triggerSave();
                    });
                }
            });

            $('.js-btn-add-calendar-detail').click(function () {
                console.log('btn click');
                $.ajax({
                    type: 'get',
                    url:  "{{ route('ajax.get-calendar-view') }}",
                    success: function(response) {
                        $(response).insertBefore('.js-calendar-detail-button');
                        console.log('ajax success');
                        console.log('response', response)

                        tinymce.init({
                            selector:'textarea.tinymce-enable',
                            toolbar_mode: 'wrap',
                            min_height: 250,
                            menubar: false,
                            browser_spellcheck: true,
                            entity_encoding: 'raw', // Without this the editor change Vietnamese chars to HTML entities so we can't use LIKE queries
                            plugins: [
                                'advlist autolink lists link charmap print preview anchor',
                                'searchreplace visualblocks fullscreen  textpattern',
                                'insertdatetime media help wordcount textcolor'
                            ],
                            toolbar: 'undo redo | formatselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat link',
                            content_css: false,
                            setup: function (editor) {
                                editor.on('change', function () {
                                    tinymce.triggerSave();
                                });
                            }
                        });
                    }
                });
            });

            $('body').on('click', '.js-delete-calendar-detail', function() {
                var message = $(this).attr('data-delete-calendar-detail-title');
                if (confirm(message)) {
                    $(this).closest('.js-calendar-detail-wrapper').remove();
                }
            });
        });
    </script>
@endpush