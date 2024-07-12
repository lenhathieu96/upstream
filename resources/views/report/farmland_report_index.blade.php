@extends('layouts.app')

@section('content')

    <!-- Loading -->
    <div class="wrap-loader">
      <div class="loader"></div>
    </div>

    <!-- Main content -->
    <div class="container-fluid">

      <form action="{{ route('farmland_report.index')}}" class="mb-4" id="form-search-farmland">
        <div class="form-group row align-items-center">
            <div class="col">
                <div>
                    <label for="js-farmer-code">Farmer Code</label>
                    <input type="text" name="farmer_code" id="js-farmer-code" class="form-control" value="{{ $farmerCode }}" placeholder="Farmer Code">
                </div>
            </div>
            <div class="col">
                <label for="js-farmer-name">Farmer Name</label>
                <input id="js-farmer-name" name="farmer_name" type="text" class="form-control" value="{{ $farmerName }}" autocomplete="off" placeholder="Farmer Name">
            </div>
            <div class="col">
              <label for="js-phone-number">Phone number</label>
              <input id="js-phone-number" name="phone_number" type="text" class="form-control" value="{{ $phoneNumber }}" autocomplete="off" placeholder="Phone Number">
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
            <button type="button" class="btn btn-info js-export-excel-btn" style="margin-right: 1rem;">Export Farmland</button>
          </div>
        </div>
      </form>

    <div class="table-responsive" style="font-size: 14px;">
      <table class="table table-bordered">
        <thead>
          <tr style="background-color: #2E7F25;">
            <th scope="col" style="color:white;">Farmland Name</th>
            <th scope="col" style="color:white;">Farmer Code</th>
            <th scope="col" style="color:white;">Farmer Name</th>
            <th scope="col" style="color:white;">Farmer Phone</th>
            <th scope="col" style="color:white;">Field Officer</th>
            <th scope="col" style="color:white;">Land Ownership</th>
            <th scope="col" style="color:white;">Total land holding(HA)</th>
            <th scope="col" style="color:white;">Actual Area(HA)</th>
            <th scope="col" style="color:white;">Action</th>
          </tr>
        </thead>
        <tbody>
            @if($farmLands->count())
                @foreach ($farmLands as $farmLand)  
                    <tr>
                        <td>{{ $farmLand->farm_name}}</td>
                        <td><a href="{{ $farmLand->farmer_details?->farmer_url }}">{{ $farmLand->farmer_details?->farmer_code}}</a></td>
                        <td>{{ $farmLand->farmer_details?->full_name}}</td>
                        <td>{{ $farmLand->farmer_details?->phone_number}}</td>
                        <td>{{ $farmLand->farmer_details?->staff?->name}}</td>
                        <td>{{ $farmLand->land_ownership}}</td>
                        <td>{{ $farmLand->total_land_holding}}</td>
                        <td>{{ $farmLand->actual_area }}</td>
                        <td><a href="{{ route('farmer_report.singel_farmland_location', ['id' => $farmLand->id])}}" class="btn btn-primary btn-xs"><i class="fa fa-map-marker"></i></a></td>
                    </tr>
                @endforeach
            @endif
        </tbody>
      </table>
    </div>

  <div class="position-relative mt-5" style="min-height: 30px">
      {{ $farmLands->links('shared.paginator') }}

      <div style="position: absolute;right: 19px; top:0"><span class="font-weight-bold">{{ $farmLands->total() }}</span> results found</div>
  </div>
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@push('scripts')
    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.js-select2').select2();

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
