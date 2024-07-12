@extends('layouts.app')

@section('content')
    <div class="container p-0">
        <div>
            <a href="{{ route('crop-activities.create') }}" class="btn btn-info mb-4">Create Crop Activity</a>
        </div>
        
        <table class="table table-bordered w-50">
            <thead>
              <tr style="background-color: #2E7F25;">
                <th scope="col" style="color:white;">Crop Activity</th>
                <th scope="col" style="color:white;">Action</th>
              </tr>
            </thead>
            <tbody>
                @if($cropActivities->count())
                    @foreach ($cropActivities as $cropActivity)  
                        <tr>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" class="switch-input js-switch-status" {{ $cropActivity->status == 'active' ? 'checked' : ''}} data-id={{ $cropActivity->id }}>
                                    <span class="switch-toggle-slider">
                                      <span class="switch-on"></span>
                                      <span class="switch-off"></span>
                                    </span>
                                    <span class="switch-label"></span>
                                  </label>

                                {{ $cropActivity->name}}
                            </td>
                            <td style="width: 200px;">
                                <a class="rounded-circle btn-primary text-white p-2 avatar avatar-sm me-2" href="{{ route('crop-activities.edit', ['crop_activity' => $cropActivity]) }}" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
@endsection 

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.js-switch-status').click(function() {
                var id = $(this).data('id');
                var status = $(this).is(':checked') ? 'active' : 'inactive';
                $.ajax({
                    type: 'post',
                    url:  "{{ route('crop_activity.update_status') }}",
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