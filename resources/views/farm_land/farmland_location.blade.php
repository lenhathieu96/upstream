@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <div class="container">
      <h2>All Farm Land</h2>
      <div class="card">
        <div class="card-body">
          <div class="row" style="margin-bottom: 20px">
            <div class="col-6">

              <div class="input-group input-group-md">
                <select class="form-control form-control-user" id="season"  name="season" value="" style="">
                  <option value="">Select Season</option>
                  @foreach($season_data as $sub_season_data)
                    <option value="{{$sub_season_data->id}}">{{$sub_season_data->season_name}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
        </div>
      </div>
      <div>
        <div class="ui dimmer" id="dimmer">
          <div class="ui massive text loader">
              <h3>Loading</h3>
          </div>
        </div>
        <div id="map" style="height: 700px;">
          
        </div>
      </div>
    </div>
@stop
<style>
  /* .loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('https://lkp.dispendik.surabaya.go.id/assets/loading.gif') 50% 50% no-repeat rgb(249,249,249);
  } */
  label
  {
    color:black;
    font-weight: 900;
    margin-bottom: 6px;
  }
  p
  {
    margin-bottom: unset !important;
  }
  .window_form_farmer
  {
    background-color: azure;
    max-width: 410px;
    width: 100%;
    height: auto;
    margin-top: 6px;
  }
  .backgorund 
  {
    background-image: url('https://hero.farm-angel.com/public/uploads/all/wAHHHSSx7s8Jvd95tLN7KUx299Pn5eAHsZj9zVw5.png');
    max-height: 80px;
    height: 80px;
  }
  .form_image_and_name
  {
    padding: 0 24px;
    position: relative;
    z-index: 1;
    top: -35px;
    display: flex;
    align-items: center;
  }
  .form_image_and_name .name
  {
    margin-left: 16px;
    font-size: 18px;
    word-wrap: break-word;
    display: block;
    max-width: 200px;
    color: black;
  }
  .form_image_and_name .image{
    max-width: 115px;
    max-height: 115px;
    width: 100%;
    height: 100%;
  }
  .avatar_farmer{
    width: 115px;
    height: 115px;
    border-radius: 50%;
  }
  .img_cultivation 
  {
    max-width: 64px;
    max-height: 64px;
    width: 100%;
    height: 100%;
  }
  .img_landholding
  {
    max-width: 64px;
    max-height: 64px;
    width: 100%;
    height: 100%;
  }
  .form_information_cultivation
  {
    display: flex;
  }
  .cultivation
  {
    width: 40%;
    display: flex;
    align-items: center;
    border-right: 1px solid black;
    border-bottom: 1px solid black;
  }
  .total_land_holding
  {
    width: 60%;
    display: flex;
    align-items: center;
    border-bottom: 1px solid black;
  }
  .text_for_details
  {
    margin-left: 8px;
  }
  .form_information_details_farmer
  {
    padding: 10px;
    display: flex;
    border-bottom: 1px solid black;
  }
  .form_information_details_farmer .form_left 
  {
    width: 50%;
  }
  .form_information_details_farmer .form_right 
  {
    width: 50%;
  }
  .mar-all
  {
    margin-top: 8px;
    margin-bottom: 20px;
  }
</style>
@push('scripts')
<script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
{{-- <script src="https://code.jquery.com/jquery-3.3.1.slim.js"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.js"></script>
<script type="text/javascript">
$('#dimmer').dimmer('show');
  

  $(document).ready(function()
  {
    
    $('#season').on('change', function() {
      var value = this.value;
      $.ajax
      ({
          type: "POST",
          url: "{{route('farm_land.filter_farmland')}}", 
          data:
          {
            season_id:this.value
          },
          success: function(result,value)
          {
            initMap(result,value);
          }
      });
    });
  });
  function initMap($data='',$key = 0) 
  {
    const myLatLng = { lat: 10.7719514, lng: 106.726354 };
    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 8,
      center: myLatLng,
      mapTypeId:'hybrid',
    });

    // alert($data + $key);
    if($data=='' && $key == 0)
    {
      // alert('aaa');
      var locations = {{ Js::from($farm_land_data) }};
    }
    else if($data=='' && $key != 0)
    {
      var locations = $data;
    }
    else
    {
      // alert('bbb');
      var locations = $data;
    }
    
  
    var infowindow = new google.maps.InfoWindow(
      {
        disableAutoPan: true,
      }
    );
    var marker, i;
    const labels = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    const markers = locations.map((position, i) => {

        const label = labels[i % labels.length];
        const pinGlyph = new google.maps.LatLng(position['lat'], position['lng']);
        
        const marker = new google.maps.Marker({
            position: new google.maps.LatLng(position['lat'], position['lng']),
            content: pinGlyph.element,
          });
        

        var myTrip = new Array();
        
        const flightPath = new google.maps.Polyline({
          path: myTrip,
          geodesic: true,
          strokeColor: "#FF0000",
          strokeOpacity: 1.0,
          strokeWeight: 2,
        });
        flightPath.setMap(map);
        console.log(position);
        const content = document.createElement("div");
            content.classList.add("window_form_farmer");
            content.innerHTML = `
            
                <div class="name">
                  <div>
                    <p>${position['farmer_details']['full_name']} - ${position['farmer_details']['farmer_code']}</p>
                  </div>
                </div>
            
              
              <div>
                <div class="mar-all mb-2" style=" text-align: end;">
                  <a href="farmer/${position['farmer_details']['id']}">
                      <button type="submit" name="button" value="publish" class="btn btn-primary waves-effect waves-light">View More</button>
                  </a>
              </div>
              </div>
        `;

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
          return function() {
            infowindow.setContent(content);
            infowindow.open(map, marker);
          }
        })(marker, i));
        
        return marker;
    });
    new markerClusterer.MarkerClusterer({ markers, map });
    google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
      $('#dimmer').dimmer('hide');
      // alert('aaaa');
    });
    // google.maps.event.addListenerOnce(map, 'tilesloaded', function(){
    //   
    // });
  }
  window.initMap = initMap;
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false&key={{env('GOOGLE_MAP_KEY')}}&callback=initMap&v=weekly">
</script>


@endpush