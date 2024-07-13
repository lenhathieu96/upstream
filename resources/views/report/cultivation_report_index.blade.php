@extends('layouts.app')

@section('content')

    <!-- Loading -->
    <div class="wrap-loader">
      <div class="loader"></div>
    </div>

    <!-- Main content -->
    <div class="container-fluid">

      <form action="{{ route('cultivation_report.index')}}" class="mb-4" id="form-search-farmland">
        <div class="form-group row align-items-center">
            <div class="col">
                <label for="js-start-date"> Start Date</label>
                <input id="js-start-date" name="start_date" type="text" class="form-control datatimepicker-enable" value="{{ $startDate }}" autocomplete="off" placeholder="Start Date">
            </div>
            <div class="col">
              <label for="js-end-date">End Date</label>
              <input id="js-end-date" name="end_date" type="text" class="form-control datatimepicker-enable" value="{{ $endDate }}" autocomplete="off" placeholder="End Date">
            </div>
            
            <div class="col">
              <label for="js-crop">Crop </label>
              <select name="crop_id" id="js-crop" class="form-control js-select2"
                    data-fetch-child="true"
                    data-fetch-target="#js-crop-variety"
                    data-fetch-url="{{ route('ajax_options.get-varieties') }}"
                    data-fetch-param-name="crop_information_id">
                  <option value="">Select Crop</option>
                  @foreach(\App\Models\CropInformation::get()->pluck('name', 'id')->all() as $id => $cropName)
                    <option value="{{ $id }}" {{ $cropId == $id ? 'selected' : ''}}>{{ $cropName }}</option>
                  @endforeach
              </select>
            </div>

            <div class="col">
              <label for="js-crop-variety">Crop Variety</label>
              <select name="crop_variety" id="js-crop-variety" class="form-control js-select2">
                  <option value="">Select Crop Variety</option>
                  @foreach($varieties as $key => $variety)
                    <option value="{{ $key }}" {{ $cropVariety == $key ? 'selected' : ''}}>{{ $variety }}</option>
                  @endforeach
              </select>
            </div>

            <div class="col">
              <label for="js-season">Harvest Season</label>
              <select name="season_id" id="js-season" class="form-control js-select2">
                  <option value="">Select Season</option>
                  @foreach(\App\Models\SeasonMaster::get()->pluck('season_name', 'id')->all() as $id => $seasonName)
                    <option value="{{ $id }}" {{ $seasonId == $id ? 'selected' : ''}}>{{ $seasonName }}</option>
                  @endforeach
              </select>
            </div>

            <div class="col">
              <label for="js-staff">Field Officer</label>
              <select name="staff_id" id="js-staff" class="form-control js-select2">
                  <option value="">Select FO</option>
                  @foreach(\App\Models\Staff::get()->pluck('name', 'id')->all() as $id => $staffName)
                    <option value="{{ $id }}" {{ $staffId == $id ? 'selected' : ''}}>{{ $staffName }}</option>
                  @endforeach
              </select>
            </div>
            <div class="col">
                <label for="js-farmer-code">Farmer Code</label>
                <input type="text" name="farmer_code" id="js-farmer-code" class="form-control" value="{{ $farmerCode }}" placeholder="Farmer Code">
            </div>
            <div class="col">
                <label for="js-farmer-name">Farmer Name</label>
                <input id="js-farmer-name" name="farmer_name" type="text" class="form-control" value="{{ $farmerName }}" autocomplete="off" placeholder="Farmer Name">
            </div>
            <div style="width: 260px;" class="mt-3">
                <button type="button" class="btn btn-primary js-btn-search" style="margin-right: 1rem;">Search</button>
                <button type="button" class="btn btn-secondary js-reset">Reset</button>
            </div>
        </div>
        <div class="form-group d-flex justify-content-end mt-5">
          <input type="hidden" name="export_excel" value="0" class="js-export-excel-type">
          <div>
            <button type="button" class="btn btn-info js-export-excel-btn" style="margin-right: 1rem;">Export cultivation</button>
          </div>
        </div>
      </form>

    <div class="table-responsive" style="font-size: 14px;">
      <table class="table table-bordered">
        <thead>
          <tr style="background-color: #B5661E;">
            <th scope="col" style="color:white;">Sowing Date</th>
            <th scope="col" style="color:white;">Crop</th>
            <th scope="col" style="color:white;">Variety</th>
            <th scope="col" style="color:white;">Harvest Season</th>
            <th scope="col" style="color:white;">Field Officer</th>
            <th scope="col" style="color:white;">Farmer Code</th>
            <th scope="col" style="color:white;">Farmer Name</th>
            <th scope="col" style="color:white;">EST YIELD(KG)</th>
          </tr>
        </thead>
        <tbody>
            @if($cultivations->count())
                @foreach ($cultivations as $cultivation)  
                    <tr>
                        <td>{{ $cultivation->sowing_date}}</td>
                        <td>{{ $cultivation->crops_master?->name}}</td>
                        <td>{{ $cultivation->crop_variety }}</td>
                        <td>{{ $cultivation->season->season_name}}</td>
                        <td>{{ $cultivation->farm_land?->farmer_details?->staff?->name}}</td>
                        <td>{{ $cultivation->farm_land?->farmer_details?->farmer_code}}</td>
                        <td>{{ $cultivation->farm_land?->farmer_details?->full_name}}</td>
                        <td>{{ $cultivation->est_yield}}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
      </table>
    </div>

  <div class="position-relative mt-5" style="min-height: 30px">
      {{ $cultivations->links('shared.paginator') }}

      <div style="position: absolute;right: 19px; top:0"><span class="font-weight-bold">{{ $cultivations->total() }}</span> results found</div>
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
                format: 'd/m/Y',
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
              $('#form-search-farmland').submit();
            });

            $('.js-export-excel-btn').click(function() {
              $('.js-export-excel-type').val(1);
              $('#form-search-farmland').submit();
              $('.js-export-excel-type').val(0);
            });
        });
    </script>
@endpush
