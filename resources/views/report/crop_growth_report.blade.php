@extends('layouts.app')
<link rel="stylesheet" type="text/css" href="{{ asset('gc-map/css/gc-map.css') }}">
<!-- init script for components -->
<script type="text/javascript" src="{{asset('gc-map/js/gc-map-init.js')}}"></script>
<link href="{{ asset('gc-chart/css/gc-chart.css') }}" rel="stylesheet">
<script id="gc-chart-init" type="text/javascript" src="{{ asset('gc-chart/js/gc-chart-init.js') }}"></script>
<link rel="stylesheet" href="{{ asset('custom\css\jquery.datetimepicker.min.css')}}">
<link rel="stylesheet" href="{{ asset('custom\css\style.css?v=')}}{{ now()->timestamp}}">
<script src="https://d3js.org/d3.v7.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/billboard.js/3.1.5/billboard.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/billboard.js/3.1.5/billboard.min.css">
@section('content')
<h3>Crop Growth</h3>

@if ($cultivation)
    <div class="mb-3 row">
        <div class="col"><label>Farmer:</label> {{$cultivation->farm_land->farmer_details->fullname ?? '' . ' - ' . $cultivation->farm_land->farmer_details->farmer_code}}</div>
        <div class="col"><label>Farmland:</label> {{$cultivation->farm_land->farm_name}}</div>
        <div class="col"></div>
    </div>
    <div class="mb-3 row">
        <div class="col"><h5><span>{{$cultivation->crop_variety}}</span></h5></div>
        <div class="col"><label>Sowing Date:</label> {{$cultivation->sowing_date}}</div>
        <div class="col"><label>Harvest Date:</label> {{$cultivation->expect_date}}</div>
    </div>
@endif
    <button class="tab-analysis btn btn-primary" data-name="map">
        <i class="fa fa-map mr-2 fs-15"></i> Map
    </button>
    <button class="tab-analysis btn btn-secondary" data-name="chart">
        <i class="fa fa-chart-bar mr-2 fs-15"></i> Chart
    </button>
<div class="gc-position"></div>



@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        var vueInstance; // Define the Vue instance outside the click handler

            var $position = $('.gc-position');
            var dataName = 'map';
            var parcelId = {{ $parcelId }}
            $('#gc-app').remove();
    $.ajax({
        url: '{{ route('report.getGcMapHtml') }}',
                type: 'POST',
                data: {
                    type: dataName,
                    parcel_id: parcelId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $position.after(response.html);

                    if (vueInstance) {
                        vueInstance.$destroy();
                    }

                    vueInstance = new Vue({
                        el: '#gc-app'
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching HTML:', error);
                }
            });
        $(document).on('click', '.tab-analysis', function() {
            var $this = $(this);
            var dataName = $this.attr('data-name');
            var parcelId = {{ $parcelId }};

            // Toggle button classes
            $('.tab-analysis').removeClass('btn-primary').addClass('btn-secondary');
            $this.removeClass('btn-secondary').addClass('btn-primary');

            $('#gc-app').remove();
            $.ajax({
                url: '{{ route('report.getGcMapHtml') }}',
                type: 'POST',
                data: {
                    type: dataName,
                    parcel_id: parcelId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('.gc-position').after(response.html);

                    if (vueInstance) {
                        vueInstance.$destroy();
                    }

                    vueInstance = new Vue({
                        el: '#gc-app'
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching HTML:', error);
                }
            });
        });
    });
</script>

