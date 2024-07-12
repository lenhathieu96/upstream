@extends('layouts.app')

@section('content')

    <!-- Loading -->
    <div class="wrap-loader">
        <div class="loader"></div>
    </div>

    <!-- Main content -->
    <div class="container-fluid">
        <form action="{{ route('distribution_report')}}" class="mb-4" id="form-search-farmer">
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
                        <label for="js-receipt-no">Receipt No</label>
                        <input type="text" name="receipt_no" id="js-receipt-no" class="form-control" value="{{ $receiptNo }}">
                    </div>
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
                    <label for="js-province">Cooperative</label>
                    <select name="province_id" id="js-province" class="form-control js-select2">
                        <option value="">Select Cooperative</option>
                        @foreach(\App\Models\Cooperative::get()->pluck('name', 'id')->all() as $id => $cooperativeName)
                            <option value="{{ $id }}" {{ $cooperativeId == $id ? 'selected' : ''}}>{{ $cooperativeName }}</option>
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
                <input type="hidden" name="export_type" value="distribution_details" class="js-export-excel-type">
                <div>
                    <button type="button" class="btn btn-info js-export-excel-btn" style="margin-right: 1rem;">Export Distribution Details</button>
                </div>
            </div>
        </form>

        <div class="table-responsive" style="font-size: 14px;">
            <table class="table table-bordered">
                <thead>
                <tr style="background-color: #2E7F25;">
                    <th scope="col" style="color:white;">Distribution Date</th>
                    <th scope="col" style="color:white;">Receipt No.</th>
                    <th scope="col" style="color:white;">Farmer Code</th>
                    <th scope="col" style="color:white;">Farmer Name</th>
                    <th scope="col" style="color:white;">Field Officer</th>
                    <th scope="col" style="color:white;">Cooperative</th>
                    <th scope="col" style="color:white;">Total Amount</th>
                </tr>
                </thead>
                <tbody>
                @if($distributions->count())
                    @foreach ($distributions as $distribution)
                        <tr>
                            <td>{{ (new DateTime($distribution->distribution_date))->format('Y-m-d') }}</td>
                            <td>
                                <a href="#" class="js-toggle-details" data-receipt-no="{{ $distribution->receipt_no }}">
                                    {{ $distribution->receipt_no }}
                                </a>
                            </td>                            <td><a href="{{ $distribution->farmer?->farmer_url }}">{{ $distribution->farmer->farmer_code }}</a></td>
                            <td>{{ $distribution->farmer->full_name}}</td>
                            <td>{{ $distribution->staff->name}}</td>
                            <td>{{ $distribution->farmer?->cooperative?->name}}</td>
                            <td>{{ $distribution->total_amount}}</td>
                        </tr>
                        <div class="modal fade" id="distributionDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Distribution Details</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body" id="distributionDetailsContent">
{{--                                        <p>{{$distribution-}}</p>--}}
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @push('scripts')
                        <script>
                            $(document).ready(function() {
                                $('.js-show-distribution-details').click(function(e) {
                                    e.preventDefault();
                                    const receiptNo = $(this).data('receipt-no');
                                    // Here, you can make an AJAX call to fetch distribution details or update content directly
                                    // For simplicity, let's update the modal content with a dummy message
                                    $('#distributionDetailsContent').html('Distribution details for Receipt No: ' + receiptNo);
                                    $('#distributionDetailsModal').modal('show');
                                });
                            });
                        </script>
                    @endpush
                @endif
                </tbody>
            </table>
        </div>

        <div class="position-relative mt-5" style="min-height: 30px">
            {{ $distributions->links('shared.paginator') }}

            <div style="position: absolute;right: 19px; top:0"><span class="font-weight-bold">{{ $distributions->total() }}</span> results found</div>
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
                        $('.js-export-excel-type').val('distribution_details');
                        $('#form-search-farmer').submit();
                        $('.js-export-excel-type').val(null);
                    });
                });
            </script>
    @endpush
