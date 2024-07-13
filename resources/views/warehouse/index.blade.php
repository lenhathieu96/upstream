@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <div class="container p-0">
      <div>
          <a href="{{ route('warehouse.create') }}" class="btn btn-info mb-4">Create Warehouse</a>
          @include('shared.form-alerts')
      </div>
      <table class="table table-bordered">
        <thead>
          <tr style="background-color: #B5661E;">
              <th scope="col" style="color:white;">Code</th>
              <th scope="col" style="color:white;">WH Operator</th>
              <th scope="col" style="color:white;">Name</th>
              <th scope="col" style="color:white;">Capacity(MT)</th>
              <th scope="col" style="color:white;">Type</th>
              <th scope="col" style="color:white;">Address</th>
              <th scope="col" style="color:white;">Status</th>
              <th scope="col" style="color:white;">Action</th>
          </tr>
        </thead>
        <tbody>
          @if($warehouses->count())
            @foreach ($warehouses as $warehouse)  
                <tr>
                    <td>{{ $warehouse->code}}</td>
                    <td>{{ $warehouse?->staff?->name ?? '-'}}</td>
                    <td>{{ $warehouse->name}}</td>
                    <td>{{ str_contains($warehouse->capacity, '.')? number_format($warehouse->capacity,2) : number_format($warehouse->capacity) }}</td>
                    <td>{{ Str::headline($warehouse->type) }}</td>
                    <td>{{ \Str::limit($warehouse->address, 20, ' ...')}}</td>
                    <td>{{ ucwords($warehouse->status) }}</td>
                    <td style="width: 200px;">
                        <a class="rounded-circle btn-primary text-white p-2 avatar avatar-sm me-2" href="{{ route('warehouse.edit', ['warehouse' => $warehouse]) }}" title="Edit">
                            <i class="fa fa-edit"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
         @endif
        </tbody>
    </table>
    <div class="position-relative" style="min-height: 30px">
      {{ $warehouses->links('shared.paginator') }}

      <div style="position: absolute;right: 19px; top:0"><span class="font-weight-bold">{{ $warehouses->total() }}</span> results found</div>
    </div>
  </div>

@endsection