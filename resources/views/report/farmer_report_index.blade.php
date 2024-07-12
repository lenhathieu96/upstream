@extends('layouts.app')

@section('content')

    <!-- Loading -->
    <div class="wrap-loader">
      <div class="loader"></div>
    </div>

    <!-- Main content -->
    <div class="container-fluid">

      <form action="{{ route('farmer_report.index')}}" class="mb-4" id="form-search-farmer">
        {{-- @csrf --}}
        <div class="form-group row align-items-center">
            <div class="col">
                <label for="js-start-date">Start Date</label>
                <input id="js-start-date" name="start_date" type="text" class="form-control datatimepicker-enable" value="{{ $startDate }}" autocomplete="off" placeholder="Start Date">
            </div>
            <div class="col">
              <label for="js-end-date">End Date</label>
              <input id="js-end-date" name="end_date" type="text" class="form-control datatimepicker-enable" value="{{ $endDate }}" autocomplete="off" placeholder="End Date">
            </div>
            <div class="col">
                <div>
                    <label for="js-farmer-code">Farmer Code</label>
                    <input type="text" name="farmer_code" id="js-farmer-code" class="form-control" value="{{ $farmerCode }}">
                </div>
            </div>
            <div class="col">
                <label for="js-farmer-name">Farmer Name</label>
                <input id="js-farmer-name" name="farmer_name" type="text" class="form-control" value="{{ $farmerName }}" autocomplete="off" placeholder="From Period">
            </div>
            <div class="col">
              <label for="js-phone-number">Phone number</label>
              <input id="js-phone-number" name="phone_number" type="text" class="form-control" value="{{ $phoneNumber }}" autocomplete="off" placeholder="Phone Number">
            </div>
            <div class="col">
              <label for="js-province">Province</label>
              <select name="province_id" id="js-province" class="form-control js-select2">
                  <option value="">Select Province</option>
                  @foreach(\App\Models\Province::get()->pluck('province_name', 'id')->all() as $id => $provinceName)
                    <option value="{{ $id }}" {{ $provinceId == $id ? 'selected' : ''}}>{{ $provinceName }}</option>
                  @endforeach
              </select>
            </div>
            <div class="col">
              <label for="js-staff">Field Officer</label>
              <select name="staff_id" id="js-staff" class="form-control js-select2">
                  <option value="">Select Field Officer</option>
                  @foreach(\App\Models\Staff::get()->pluck('name', 'id')->all() as $id => $staffName)
                    <option value="{{ $id }}" {{ $staffId == $id ? 'selected' : ''}}>{{ $staffName }}</option>
                  @endforeach
              </select>
            </div>
            <div style="width: 260px;" class="mt-3">
                <button type="button" class="btn btn-primary js-btn-search" style="margin-right: 1rem;">Search</button>
                <button type="button" class="btn btn-secondary js-reset">Reset</button>
            </div>
        </div>
        <div class="form-group d-flex justify-content-end mt-5">
          <input type="hidden" name="export_excel" value="0" class="js-export-excel-type">
          <div>
            <button type="button" class="btn btn-info js-export-excel-btn" style="margin-right: 1rem;">Export Farmer</button>
          </div>
        </div>
      </form>

    <div class="table-responsive" style="font-size: 14px;">
      <table class="table table-bordered">
        <thead>
          <tr style="background-color: #2E7F25;">
            <th scope="col" style="color:white;">Enrollment Date</th>
            <th scope="col" style="color:white;">Farmer Code</th>
            <th scope="col" style="color:white;">Farmer Name</th>
            <th scope="col" style="color:white;">Phone Number</th>
            <th scope="col" style="color:white;">Field Officer</th>
            <th scope="col" style="color:white;">Gender</th>
            <th scope="col" style="color:white;">Province</th>
            <th scope="col" style="color:white;">District</th>
            <th scope="col" style="color:white;">Commune</th>
            <th scope="col" style="color:white;">Total No of Plots</th>
            <th scope="col" style="color:white;">Total land holding(HA)</th>
            <th scope="col" style="color:white;">Status</th>
          </tr>
        </thead>
        <tbody>
            @if($farmerDetails->count())
                @foreach ($farmerDetails as $farmerDetail)  
                    <tr>
                        <td>{{ $farmerDetail->enrollment_date}}</td>
                        <td><a href="{{ $farmerDetail?->farmer_url }}">{{ $farmerDetail->farmer_code }}</a></td>
                        <td>{{ $farmerDetail->full_name}}</td>
                        <td>{{ $farmerDetail->phone_number}}</td>
                        <td>{{ $farmerDetail->staff?->name}}</td>
                        <td>{{ $farmerDetail->gender}}</td>
                        <td>{{ $farmerDetail->provinceRelation?->province_name}}</td>
                        <td>{{ $farmerDetail->districtRelation?->district_name}}</td>
                        <td>{{ $farmerDetail->communeRelation?->commune_name}}</td>
                        <td>{{ $farmerDetail->farm_lands_count}}</td>
                        <td>{{ round($farmerDetail->sum_total_land_holding / 10000 , 2)}}</td>
                        <td>{{ ucwords($farmerDetail->status) }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
      </table>
    </div>

  <div class="position-relative mt-5" style="min-height: 30px">
      {{ $farmerDetails->links('shared.paginator') }}

      <div style="position: absolute;right: 19px; top:0"><span class="font-weight-bold">{{ $farmerDetails->total() }}</span> results found</div>
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

            $('.js-reset').click(function(){
                $('input[type="text"]').val('');
                $('select').val('');
                $('.js-select2').val('').trigger('change');
                document.querySelector('input[name="status"]:checked').checked = false;
            });

            $('.js-btn-search').click(function(){
              $('.js-export-excel-type').val(0);
              $('#form-search-farmer').submit();
            });

            $('.js-export-excel-btn').click(function() {
              $('.js-export-excel-type').val(1);
              $('#form-search-farmer').submit();
              $('.js-export-excel-type').val(0);
            });
        });
    </script>
@endpush
