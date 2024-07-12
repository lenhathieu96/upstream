@extends('layouts.app')

@section('content')
    <div class="container p-0">
        <div>
            <a href="{{ route('season-masters.create') }}" class="btn btn-info mb-4">Create Season Master</a>
            @include('shared.form-alerts')
        </div>
        <form action="{{ route('season-masters.index')}}" class="mb-5">
            {{-- @csrf --}}
            <div class="form-group row align-items-center">
                <div class="col">
                    <div>
                        <label for="js-season-name">Season</label>
                        <input type="text" name="season_name" id="js-season-name" class="form-control" value="{{ $seasonName }}">
                    </div>
                </div>
                <div class="col">
                    <label for="js-from-period">From period</label>
                    <input id="js-from-period" name="from_period" type="text" class="form-control datatimepicker-enable" value="{{ $fromPefiod }}" autocomplete="off" placeholder="From Period">
                </div>
                <div class="col">
                    <label for="js-to-period">To period</label>
                    <input id="js-to-period" name="to_period" type="text" class="form-control datatimepicker-enable" value="{{ $toPefiod }}" autocomplete="off" placeholder="To Period">
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
                <th scope="col" style="color:white;">Season</th>
                <th scope="col" style="color:white;">From period</th>
                <th scope="col" style="color:white;">To period</th>
                <th scope="col" style="color:white;">Status</th>
                <th scope="col" style="color:white;">Is Current Season</th>
                <th scope="col" style="color:white;">Action</th>
              </tr>
            </thead>
            <tbody>
                @if($seasonMasters->count())
                    @foreach ($seasonMasters as $seasonMaster)  
                        <tr>
                            <td>{{ $seasonMaster->season_name}}</td>
                            <td>{{ $seasonMaster->from_period}}</td>
                            <td>{{ $seasonMaster->to_period}}</td>
                            <td>{{ $seasonMaster->status == 'active' ? 'Active' : 'Inactive'}} </td>
                            <td>{{ $seasonMaster->is_current_season ? 'Current Season' : '-'}} </td>
                            <td style="width: 200px;">
                                <a class="rounded-circle btn-primary text-white p-2 avatar avatar-sm me-2" href="{{ route('season-masters.edit', ['season_master' => $seasonMaster]) }}" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <form method="POST" action="{{ route('season-masters.destroy', ['season_master' => $seasonMaster]) }}" class="d-inline">
                                    {{ method_field('DELETE') }}
                                    @csrf
                                    
                                    <a class="rounded-circle btn-danger text-white p-2 avatar avatar-sm js-delete-season" href="javascript:void(0)" data-delete-season-title="{{ $seasonMaster->season_name }}" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
          </table>
        

        <div class="position-relative" style="min-height: 30px">
            {{ $seasonMasters->links('shared.paginator') }}

            <div style="position: absolute;right: 19px; top:0"><span class="font-weight-bold">{{ $seasonMasters->total() }}</span> results found</div>
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

            $('.js-delete-season').click(function () {
                var message = 'Are you sure to delete "' + $(this).data('delete-season-title') + '"?';

                if (confirm(message)) {
                    $(this).closest('form').submit();
                }
            });

            $('.js-reset').click(function(){
                $('input[type="text"]').val('');
                $('select').val('');
                document.querySelector('input[name="status"]:checked').checked = false;
            });
        });
    </script>
@endpush