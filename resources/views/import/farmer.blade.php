@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="card mb-3">
            <div class="card-header row gutters-5">
                <div class="col">
                    <h5 class="mb-md-0 h6">Import Farmer Details</h5>
                </div>
            </div>
            <div class="card-body">
                @include('shared.form-alerts')
                <div class="row">
                    <div class="col-6">
                        <form method="post" action="{{ route('farmer.import_csv') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <div class="col-3 text-info fw-bold">
                                    Import Farmer
                                </div>
                                <div class="col-6">
                                    <input type="file" name="csvFile" class="form-control">
                                </div>
                                <div class="col-3">
                                    <div class="js-spinner spinner-border text-success d-none" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3 offset-3">
                                    <button type="submit" class="btn btn-primary btn-submit">Import</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.btn-submit').click(function() {
                $('.js-spinner').removeClass('d-none');
            });
        });
    </script>
@endpush
