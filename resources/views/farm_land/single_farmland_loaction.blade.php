@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <div class="container">
      <h2>Report Farm Land Location</h2>
      <div id="map" style="height: 500px;"></div>
    </div>
@stop
<style>
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

<script type="text/javascript">
  $(".link").on("click", function(event) {

  if (event.ctrlKey || event.shiftKey || event.metaKey || event.which == 2) {
    
  }
  // ... load only necessary things for normal clicks
  });


  function initMap() {
    const myLatLng = { lat: 10.7719514, lng: 106.726354 };
    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 15,
      center: myLatLng,
      mapTypeId: 'satellite',
    });

    var locations = {{ Js::from($farm_land_data) }};
  
            var infowindow = new google.maps.InfoWindow();
  
            var marker, i;
              
                  marker = new google.maps.Marker({
                    
                    position: new google.maps.LatLng(locations['lat'], locations['lng']),
                    map: map
                  });
                    
                  // const flightPlanCoordinates = locations[i]['plot_data'];

                  // const flightPath = new google.maps.Polyline({
                  //   path: flightPlanCoordinates,
                  //   geodesic: true,
                  //   strokeColor: "#FF0000",
                  //   strokeOpacity: 1.0,
                  //   strokeWeight: 2,
                  // });
                  var myTrip = new Array();
                  if((locations['plot_data']).length > 0)
                  {
                    for (j = 0; j < locations['plot_data'].length; j++) { 
                      console.log(locations['plot_data'][j]['lat']);
                      myTrip.push(new google.maps.LatLng(locations['plot_data'][j]['lat'], locations['plot_data'][j]['lng']));
                    }
                  } 
        
                  const flightPath = new google.maps.Polyline({
                    path: myTrip,
                    geodesic: true,
                    strokeColor: "#FF0000",
                    strokeOpacity: 1.0,
                    strokeWeight: 2,
                    });
                  flightPath.setMap(map);
                  
                  const content = document.createElement("div");
                  content.classList.add("window_form_farmer");
                  content.innerHTML = `
                  <div class="backgorund"></div>
                    <div class="form_image_and_name">
                      <div class="image">
                        <img class="avatar_farmer" src="${locations['farmer_photo']}" alt="">
                      </div>
                      <div class="name">
                        <div>
                          <p>${locations['farmer_name']}</p>
                        </div>
                        <div>
                          <p>${locations['farmer_code']}</p>
                        </div>
                      </div>
                    </div>
                    <div class="form_information_cultivation">
                        <div class="cultivation">
                          <div>
                            <img class="img_cultivation" src="https://hero.farm-angel.com/public/uploads/all/T0yt5PpBElTPbNHPStLyiJjZN8XCj9L2G1Oa0pEr.png" alt="">
                          </div>
                          <div class="details_cultivation">
                            <div class="text_for_details">
                              <label for="">Crop</label>
                              <p>
                                ${locations['crop_name']}
                              </p>
                            </div>
                            
                          </div>
                        </div>
                        <div class="total_land_holding">
                          <div>
                            <img class="img_landholding" src="https://hero.farm-angel.com/public/uploads/all/68bJ8FPMziQNnYRZ1Ay51GcmqdU8lrsQYMomt0CU.png" alt="">
                          </div>
                          <div>
                            <div class="text_for_details">
                              <label for="" >Total Land Holding</label>
                              <p>${locations['actual_area']} km</p>
                            </div>
                          </div>
                        </div>
                    </div>
                    <div class="form_information_details_farmer">
                      <div class="form_left">
                        <div>
                          <label for="">Farm Name</label>
                        </div>
                        <div>
                          ${locations['farm_name']}
                        </div>
                      </div>
                      <div class="form_right">
                        <div>
                          <label for="">Organization</label>
                        </div>
                        <div>
                          
                        </div>
                      </div>
                    </div>
                    <div class="form_information_details_farmer">
                      <div class="form_left">
                        <div>
                          <label for="">Village</label>
                        </div>
                        <div>
                          
                        </div>
                      </div>
                      <div class="form_right">
                        <div>
                          <label for="">Estiamte Harvest Date</label>
                        </div>
                        <div>
                          ${locations['harvest_date']}
                        </div>
                      </div>
                    </div>
                    <div class="form_information_details_farmer">
                      <div class="form_left">
                        <div>
                          <label for="">Season</label>
                        </div>
                        <div style="display: block;word-wrap: break-word;max-width: 100px;">
                          ${locations['season_period_from']} to ${locations['season_period_to']}
                        </div>
                      </div>
                      <div class="form_right">
                        <div>
                          <label for="">Yield-(Kgs)</label>
                        </div>
                        <div>
                          ${locations['est_yeild']}
                        </div>
                      </div>
                    </div>
                    <div>
                      <div class="mar-all mb-2" style=" text-align: end;">
                        <a href="farmer/${locations['farmer_id']}">
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
        }
        window.initMap = initMap;
</script>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false&key={{env('GOOGLE_MAP_KEY')}}&callback=initMap">
</script>

@endpush