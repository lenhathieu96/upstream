@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-primary h-100">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                      <div class="avatar me-2">
                        <span class="avatar-initial rounded bg-label-primary"><i class="mdi mdi-account mdi-24px" ></i></span>
                      </div>
                      <h4 class="ms-1 mb-0 display-6">{{ $staffs->count() }}</h4>
                    </div>
                    <p class="mb-0 text-heading">Total Field Officers</p>
                  </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-info h-100">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                      <div class="avatar me-2">
                        <span class="avatar-initial rounded bg-label-info"><i class="mdi mdi-account-cowboy-hat mdi-20px"></i></span>
                      </div>
                      <h4 class="ms-1 mb-0 display-6">{{ $farmerCount }}</h4>
                    </div>
                    <p class="mb-0 text-heading">Total Farmers</p>
                  </div>
                </div>
              </div>

              <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-primary h-100">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                      <div class="avatar me-2">
                        <span class="avatar-initial rounded bg-label-primary"><i class="mdi mdi-land-plots mdi-24px" ></i></span>
                      </div>
                      <h4 class="ms-1 mb-0 display-6">{{ number_format($totalLandHolding, 2) }}</h4>
                    </div>
                    <p class="mb-0 text-heading">Total Land Holding(HA)</p>
                  </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 mb-4">
                <div class="card card-border-shadow-info h-100">
                  <div class="card-body">
                    <div class="d-flex align-items-center mb-2 pb-1">
                      <div class="avatar me-2">
                        <span class="avatar-initial rounded bg-label-info"><i class="mdi mdi-land-fields mdi-20px"></i></span>
                      </div>
                      <h4 class="ms-1 mb-0 display-6">{{ $totalFarmlands }}</h4>
                    </div>
                    <p class="mb-0 text-heading">Total Farms</p>
                  </div>
                </div>
            </div>
        </div>

        <div class="mb-4" id="basic-chart">

        </div>

        <div class="mb-4" id="location-chart">
                
        </div>

        <div id="area-chart" class="mt-5 mb-4">

        </div>

        <div class="table-wrapper">

        </div>
    </div>
@endsection 

@push('scripts')
    {{-- <script src="https://code.highcharts.com/highcharts.js"></script> --}}
    <script src="https://code.highcharts.com/stock/highstock.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
          NProgress.start();

          var staffOptions = {
                chart: {
                    type: 'column',
                    height: 600,
                    //zoomType: 'x'
                },
                title: {
                    text: 'Farmers by Field Officer'
                },
                xAxis: {
                    type: 'category',
                    //categories: ['Farmer'],
                    min: 0,
                    max: 15,
                    scrollbar: {
                        enabled: true,
                        size: 17
                    },
                    tickLength: 0,
                },
                yAxis: {
                    title: {
                        text: 'Total Farmer'
                    },
                },
                plotOptions: {
                    bar: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                },
                // legend: {
                //     enabled: false
                // },
                // credits: {
                //     enabled: false
                // },
                series:[],
            }

            $.ajax({
                type: "get",
                url: "{{ route('ajax.get-staff') }}",
                dataType: 'json',
                success: function(data){
                  staffOptions.series = [{'name':'Farmer', 'data':data}];
                  Highcharts.chart('basic-chart', staffOptions);
                  NProgress.set(0.3);
                }
            });

            // ================== Location Chart ===============

            let locationOptions = {
                chart: {
                    type: 'column',
                    height: 600,
                },
                title: {
                    text: 'Farmers by Commune'
                },
                xAxis: {
                  type: 'category',
                    min: 0,
                    max: 20,
                    scrollbar: {
                        enabled: true,
                        size: 17
                    },
                    tickLength: 0,
                },
                yAxis: {
                    title: {
                        text: 'Total Farmer'
                    }
                },
                series:  [],
            };

            $.ajax({
                type: "get",
                url: "{{ route('ajax.get_farmer_by_commune') }}",
                dataType: 'json',
                success: function(data){
                  locationOptions.series = [{'name':'Commune', 'data':data}];
                  Highcharts.chart('location-chart', locationOptions);
                  NProgress.set(0.6);
                }
            });


            // ================== Area Chart ========================

            // example(change type=bar): https://jsfiddle.net/a24cLdon/
            let areaOptions = {
                // data: {
                //     table: 'datatable',
                // },
                chart: {
                    type: 'column',
                    height: 600,
                },
                title: {
                    text: 'Farm area by commune'
                },
                xAxis: {
                    type: 'category',
                    min: 0,
                    max: 20,
                    scrollbar: {
                        enabled: true,
                        size: 17
                    },
                },
                yAxis: {
                    title: {
                        text: 'Hectare'
                    }
                },
                legend: {
                    reversed: true
                },
                tooltip: {
                    valueSuffix: ' HA'
                },
                series:  [],
            };

            $.ajax({
                type: "get",
                url: "{{ route('ajax.get_commune_by_farm_area') }}",
                success: function(data){
                  NProgress.set(0.8);
                  //$('.table-wrapper').html(data);
                  areaOptions.xAxis.categories = data.communeName;
                  areaOptions.series = data.communeAreaResult;
                  Highcharts.chart('area-chart', areaOptions);
                  NProgress.done();
                }
            });
        });

    </script>
@endpush
