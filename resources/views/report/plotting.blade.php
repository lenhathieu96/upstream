@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <div class="container-fluid">

      <form action="{{ route('export_plotting')}}" class="mb-4" id="form-search-farmland" method="POST">
        @csrf
        <div class="form-group d-flex justify-content-end mt-5">
          <input type="hidden" name="export_excel" value="0" class="js-export-excel-type">
          <div>
            <button type="submit" class="btn btn-info js-export-excel-btn" style="margin-right: 1rem;">Export Plotting</button>
          </div>
        </div>
      </form>
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@push('scripts')
    
@endpush
