@extends('layouts.app')

@section('content')
    <div class="container p-0">
        <div>
            <a href="{{ route('crop-informations.create') }}" class="btn btn-info mb-4">Create Crop Master</a>
            @include('shared.form-alerts')
        </div>
        {{-- <form action="{{ route('crop-informations.index')}}" class="mb-5">
            
        </form> --}}

        
        <table class="table table-bordered">
            <thead>
              <tr style="background-color: #2E7F25;">
                <th scope="col" style="color:white;">Name</th>
                <th scope="col" style="color:white;">Category</th>
                <th scope="col" style="color:white;">Duration</th>
                <th scope="col" style="color:white;">Expected Expense</th>
                <th scope="col" style="color:white;">Expected Income</th>
                <th scope="col" style="color:white;">Expected Yield</th>
                <th scope="col" style="color:white;">Action</th>
              </tr>
            </thead>
            <tbody>
                @if($cropInformations->count())
                    @foreach ($cropInformations as $cropInformation)  
                        <tr>
                            <td>{{ $cropInformation->name}}</td>
                            <td>{{ $cropInformation->crop_category->name}}</td>
                            <td>{{ $cropInformation->duration . ' ' . $cropInformation->duration_type }}</td>
                            <td>{{ number_format($cropInformation->expected_expense) . ' VND'}}</td>
                            <td>{{ number_format($cropInformation->expected_income) . ' VND'}}</td>
                            <td>{{ $cropInformation->expected_yield . ' ' . $cropInformation->expected_yield_type }}</td>
                            <td style="width: 200px;">
                                <a class="rounded-circle btn-primary text-white p-2 avatar avatar-sm me-2" href="{{ route('crop-informations.edit', ['crop_information' => $cropInformation]) }}" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <form method="POST" action="{{ route('crop-informations.destroy', ['crop_information' => $cropInformation]) }}" class="d-inline">
                                    {{ method_field('DELETE') }}
                                    @csrf

                                    <a class="rounded-circle btn-danger text-white p-2 avatar avatar-sm js-delete-crop" href="javascript:void(0)" data-delete-crop-title="{{ $cropInformation->name }}" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>

                                    {{-- <button type="button" class="btn btn-sm btn-danger js-delete-crop" data-delete-crop-title="{{ $cropInformation->name }}"><i class="fa fa-trash"></i> Delete</button> --}}
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <div class="position-relative" style="min-height: 30px">
            {{ $cropInformations->links('shared.paginator') }}

            <div style="position: absolute;right: 19px; top:0"><span class="font-weight-bold">{{ $cropInformations->total() }}</span> results found</div>
        </div>
    </div>
@endsection 

@section('style')

@endsection

@push('scripts')
    <script src="{{ asset('custom/js/jquery.datetimepicker.full.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('.datatimepicker-enable').datetimepicker({
                format: 'Y-m-d',
        		datepicker: true,
                timepicker: false,
            });

            $('.js-delete-crop').click(function () {
                var message = 'Are you sure to delete "' + $(this).data('delete-crop-title') + '"?';

                if (confirm(message)) {
                    $(this).closest('form').submit();
                }
            });
        });
    </script>
@endpush