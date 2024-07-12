@extends('layouts.app')

@section('content')
    <div class="container p-0">
        <div>
            <a href="{{ route('cooperative.create') }}" class="btn btn-info mb-4">Create Cooperative</a>
            @include('shared.form-alerts')
        </div>
        <form action="{{ route('cooperative.index')}}" class="mb-5">
            {{-- @csrf --}}
            <div class="form-group row align-items-center">
                <div class="col">
                    <label for="js-cooperative-name">Cooperative Name</label>
                    <input id="js-cooperative-name" name="name" type="text" class="form-control " value="{{ $name }}" autocomplete="off" placeholder="Cooperative Name">
                </div>
                <div class="col">
                    <label for="js-formation-date">Formation Date</label>
                    <input id="js-formation-date" name="formation_date" type="text" class="form-control datatimepicker-enable" value="{{ $formationDate }}" autocomplete="off" placeholder="Formation Date">
                </div>
                <div class="col">
                    <label for="js-cooperative-code">Cooperative Code</label>
                    <input id="js-cooperative-code" name="cooperative_code" type="text" class="form-control" value="{{ $cooperativeCode }}" autocomplete="off" placeholder="Cooperative Code">
                </div>
                <div class="col">
                    <label for="js-to-period">Status</label>
                    <div class="d-flex">
                        <div class="form-check mt-2" style="margin-right: 1rem;">
                            <input class="form-check-input" type="radio" name="status" id="status-active" value="active" {{ $status == 'active' ? 'checked' : ''}}>
                            <label class="form-check-label" for="status-active">Active</label>
                        </div>
                        <div class="form-check  mt-2">
                            <input class="form-check-input" type="radio" name="status" id="status-inactive" value="inactive" {{ $status == 'inactive' ? 'checked' : ''}}>
                            <label class="form-check-label" for="status-inactive">Inactive</label>
                        </div>
                    </div>
                </div>
                <div style="width: 260px;">
                    <button type="submit" class="btn btn-primary" style="margin-right: 1rem;">Search</button>
                    <button type="button" class="btn btn-secondary js-reset">Reset</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
              <tr style="background-color: #2E7F25;">
                <th scope="col" style="color:white;">Cooperative Name</th>
                <th scope="col" style="color:white;">Formation Date</th>
                <th scope="col" style="color:white;">Cooperative Code</th>
                <th scope="col" style="color:white;">Email</th>
                <th scope="col" style="color:white;">Phone</th>
                <th scope="col" style="color:white;">Status</th>
                <th scope="col" style="color:white;">Action</th>
              </tr>
            </thead>
            <tbody>
                @if($cooperatives->count())
                    @foreach ($cooperatives as $cooperative)  
                        <tr>
                            <td>{{ $cooperative->name}}</td>
                            <td>{{ $cooperative->formation_date}}</td>
                            <td>{{ $cooperative->cooperative_code}}</td>
                            <td>{{ $cooperative->email ?? '-'}}</td>
                            <td>{{ $cooperative->phone_number ?? '-'}}</td>
                            <td>{{ $cooperative->status == 'active' ? 'Active' : 'Inactive'}} </td>
                            <td style="width: 200px;">
                                <a class="rounded-circle btn-primary text-white p-2 avatar avatar-sm me-2" href="{{ route('cooperative.edit', ['cooperative' => $cooperative]) }}" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
          </table>
        

        <div class="position-relative" style="min-height: 30px">
            {{ $cooperatives->links('shared.paginator') }}

            <div style="position: absolute;right: 19px; top:0"><span class="font-weight-bold">{{ $cooperatives->total() }}</span> results found</div>
        </div>
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
                document.querySelector('input[name="status"]:checked').checked = false;
            });
        });
    </script>
@endpush