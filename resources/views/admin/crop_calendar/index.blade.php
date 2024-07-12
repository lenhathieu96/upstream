@extends('layouts.app')

@section('content')
    <div class="container p-0">
        <div>
            <a href="{{ route('crop-calendars.create') }}" class="btn btn-info mb-4">Create Crop Calendar</a>
            @include('shared.form-alerts')
        </div>

        <table class="table table-bordered">
            <thead>
              <tr style="background-color: #2E7F25;">
                <th scope="col" style="color:white;">Name</th>
                <th scope="col" style="color:white;">Calendar Name</th>
                <th scope="col" style="color:white;">Country</th>
                <th scope="col" style="color:white;">Province</th>
                <th scope="col" style="color:white;">District</th>
                <th scope="col" style="color:white;">Status</th>
                <th scope="col" style="color:white;">Action</th>
              </tr>
            </thead>
            <tbody>
                @if ($cropCalendars->count())
                    @foreach($cropCalendars as $indexItem => $cropCalendar)
                        <tr>
                            <td>{{ $cropCalendar?->cropInformation?->name }}</td>
                            <td>{{ $cropCalendar->calendar_name }}</td>
                            <td>{{ $cropCalendar?->country?->country_name }}</td>
                            <td>{{ $cropCalendar?->province?->province_name }}</td>
                            <td>{{ $cropCalendar?->district?->district_name }}</td>
                            <td>
                                <label class="switch ms-3">
                                    <input type="checkbox" name="calendar_detail[{{ $cropCalendar->id }}][status]" class="switch-input js-switch-status" {{ $cropCalendar->status == 'inactive' ? '' : 'checked' }} data-id={{ $cropCalendar->id }}>
                                    <span class="switch-toggle-slider">
                                      <span class="switch-on"></span>
                                      <span class="switch-off"></span>
                                    </span>
                                    <span class="switch-label"></span>
                                </label>
                            </td>
                            <td>
                                <a class="rounded-circle btn-primary text-white p-2 avatar avatar-sm me-2" href="{{ route('crop-calendars.edit', ['crop_calendar' => $cropCalendar]) }}" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
          </table>
        

        <div class="position-relative" style="min-height: 30px">
            {{ $cropCalendars->links('shared.paginator') }}

            <div style="position: absolute;right: 19px; top:0"><span class="font-weight-bold">{{ $cropCalendars->total() }}</span> results found</div>
        </div>
    </div>
@endsection 

@section('style')

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            $('.js-switch-status').click(function() {
                var id = $(this).data('id');
                var status = $(this).is(':checked') ? 'active' : 'inactive';
                $.ajax({
                    type: 'post',
                    url:  "{{ route('crop_calendar.update_status') }}",
                    data: {
                        'id': id,
                        'status': status,
                    },
                    success: function(response) {

                    }
                });
            });

        });
    </script>
@endpush