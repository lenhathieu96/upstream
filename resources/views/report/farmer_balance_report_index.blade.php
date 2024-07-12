@extends('layouts.app')

@section('content')

    <!-- Loading -->
    <div class="wrap-loader">
        <div class="loader"></div>
    </div>

    <!-- Main content -->
    <div class="container-fluid">
        <form action="{{ route('farmer_balance_report')}}" class="mb-4" id="form-search-farmer">
            {{-- @csrf --}}
            <div class="form-group row align-items-center">
                <div class="col">
                    <div>
                        <label for="js-farmer-code">Farmer Code</label>
                        <input type="text" name="farmer_code" id="js-farmer-code" class="form-control" value="{{ $farmerCode }}" placeholder="Enter Farmer Code">
                    </div>
                </div>
                <div class="col">
                    <div>
                        <label for="js-farmer-name">Farmer Name</label>
                        <input type="text" name="farmer_name" id="js-farmer-name" class="form-control" value="{{ $farmerCode }}" placeholder="Enter Farmer Name">
                    </div>
                </div>
                <div class="col">
                    <label for="js-acc-no">Account Number</label>
                    <input id="js-acc-no" name="acc_no" type="text" class="form-control" value="{{ $accNo }}" autocomplete="off" placeholder="Enter Account Number">
                </div>
                <div class="col">
                    <label for="js-province">Amount Type</label>
                    <select name="amount_type" id="js-province" class="form-control js-select2">
                        <option value="">Select Amount Type</option>
                        <option value="Debit Balance" {{ $amountType == "Debit Balance" ? 'selected' : ''}}>Debit Balance</option>
                        <option value="Credit Balance" {{ $amountType == "Credit Balance" ? 'selected' : ''}}>Credit Balance</option>
                    </select>
                </div>
                <div style="width: 260px;" class="mt-3">
                    <button type="button" class="btn btn-primary js-btn-search" style="margin-right: 1rem;">Search</button>
                    <button type="button" class="btn btn-secondary js-reset">Reset</button>
                </div>
            </div>
            <div class="form-group d-flex justify-content-end mt-5">
                <input type="hidden" name="export_type" value="farmer_balance_report" class="js-export-excel-type">
                <div>
                    <button type="button" class="btn btn-info js-export-excel-btn" style="margin-right: 1rem;">Export Farmer Balance Details</button>
                </div>
            </div>
        </form>

        <div class="table-responsive" style="font-size: 14px;">
            <table class="table table-bordered">
                <thead>
                <tr style="background-color: #2E7F25;">
                    <th scope="col" style="color:white;">Farmer Code</th>
                    <th scope="col" style="color:white;">Farmer Name</th>
                    <th scope="col" style="color:white;">Account Number</th>
                    <th scope="col" style="color:white;">Outstanding Amount</th>

                </tr>
                </thead>
                <tbody>
                @if($farmers->count())
                    @foreach ($farmers as $farmer)
                        <tr>
                            <td><a href="{{ $farmer?->farmer_url }}">{{ $farmer->farmer_code }}</a></td>
                            <td>{{ $farmer->full_name }}</td>
                            <td>{{ $farmer->faAccount->acc_no }}</td>
                            <td>{{ abs($farmer->faAccount->outstanding_amount) . ' - ' . ($farmer->faAccount->outstanding_amount > 0 ? 'CR' : 'DB') }}</td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>

        <div class="position-relative mt-5" style="min-height: 30px">
            {{ $farmers->links('shared.paginator') }}

            <div style="position: absolute;right: 19px; top:0"><span class="font-weight-bold">{{ $farmers->total() }}</span> results found</div>
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
                        $('.js-export-excel-type').val(null);
                        $('#form-search-farmer').submit();
                    });

                    $('.js-export-excel-btn').click(function() {
                        $('.js-export-excel-type').val('farmer_balance_report');
                        $('#form-search-farmer').submit();
                        $('.js-export-excel-type').val(null);
                    });
                });
            </script>
    @endpush
