@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <div class="container p-0">
      <div>
          <a href="{{ route('staff.create') }}" class="btn btn-info mb-4">Create Staff</a>
          @include('shared.form-alerts')
      </div>
      <table class="table table-bordered">
        <thead>
          <tr style="background-color: #B5661E;">
              <th scope="col" style="color:white;">Staff Name</th>
              <th scope="col" style="color:white;">Staff Type</th>
              <th scope="col" style="color:white;">Contact Number</th>
              <th scope="col" style="color:white;">Email</th>
              <th scope="col" style="color:white;">Gender</th>
              <th scope="col" style="color:white;">Status</th>
              @if ($isEditableByCurrentUser)
                <th scope="col" style="color:white;">Action</th>
              @endif
          </tr>
        </thead>
        <tbody>
          @if($staffs->count())
            @foreach ($staffs as $staff)  
                <tr>
                    <td>{{ $staff->name}}</td>
                    <td>{{ Str::headline($staff->user_type) }}</td>
                    <td>{{ $staff->phone_number}}</td>
                    <td>{{ $staff->email}}</td>
                    <td>{{ $staff->gender}}</td>
                    <td>{{ ucwords($staff->status) }} </td>

                    @if ($isEditableByCurrentUser)
                      <td style="width: 200px;">
                          <a class="rounded-circle btn-primary text-white p-2 avatar avatar-sm me-2" href="{{ route('staff.edit', ['staff' => $staff]) }}" title="Edit">
                              <i class="fa fa-edit"></i>
                          </a>
                      </td>
                    @endif
                </tr>
            @endforeach
         @endif
        </tbody>
    </table>
    <div class="position-relative" style="min-height: 30px">
      {{ $staffs->links('shared.paginator') }}

      <div style="position: absolute;right: 19px; top:0"><span class="font-weight-bold">{{ $staffs->total() }}</span> results found</div>
    </div>
  </div>

@endsection