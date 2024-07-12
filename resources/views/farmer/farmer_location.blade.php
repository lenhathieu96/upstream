@extends('layouts.app')

@section('content')
    <!-- Main content -->
    <div class="container mt-5">
      <h2>Laravel Google Maps Multiple Markers Example - ItSolutionStuff.com</h2>
      <div id="map" style="height: 500px;"></div>
    </div>


    <div class="window_form_farmer">
      <div class="backgorund"></div>
      <div class="form_image_and_name">
        <div class="image">
          <img class="avatar_farmer" src="{{uploaded_asset($farmers_data[0]->farmer_photo)}}" alt="">
        </div>
        <div class="name">
          <div>
            <p>{{$farmers_data[0]->full_name}}</p>
          </div>
          <div>
            <p>{{$farmers_data[0]->farmer_code}}</p>
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
                  Avocado
                </p>
              </div>
              
            </div>
          </div>
          <div class="total_land_holding">
            <div>
              <img class="img_landholding" src="	https://hero.farm-angel.com/public/uploads/all/68bJ8FPMziQNnYRZ1Ay51GcmqdU8lrsQYMomt0CU.png" alt="">
            </div>
            <div>
              <div class="text_for_details">
                <label for="">Total Land Holding</label>
                <p>10.00 ha</p>
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
            Test
          </div>
        </div>
        <div class="form_right">
          <div>
            <label for="">Organization</label>
          </div>
          <div>
            Test
          </div>
        </div>
      </div>
      <div class="form_information_details_farmer">
        <div class="form_left">
          <div>
            <label for="">Farm Name</label>
          </div>
          <div>
            Test
          </div>
        </div>
        <div class="form_right">
          <div>
            <label for="">Organization</label>
          </div>
          <div>
            Test
          </div>
        </div>
      </div>
      <div class="form_information_details_farmer">
        <div class="form_left">
          <div>
            <label for="">Farm Name</label>
          </div>
          <div>
            Test
          </div>
        </div>
        <div class="form_right">
          <div>
            <label for="">Organization</label>
          </div>
          <div>
            Test
          </div>
        </div>
      </div>
      <div class="form_information_details_farmer">
        <div class="form_left">
          <div>
            <label for="">Farm Name</label>
          </div>
          <div>
            Test
          </div>
        </div>
        <div class="form_right">
          <div>
            <label for="">Organization</label>
          </div>
          <div>
            Test
          </div>
        </div>
      </div>
      <div>
        <div class="mar-all mb-2" style=" text-align: end;">
          <a href="">
              <button type="submit" name="button" value="publish" class="btn btn-primary waves-effect waves-light">View More</button>
          </a>
      </div>
      </div>
    </div>
@stop
<style>
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
  }
  .backgorund 
  {
    background-image: url('https://hero.farm-angel.com/public/uploads/all/wAHHHSSx7s8Jvd95tLN7KUx299Pn5eAHsZj9zVw5.png');
    max-height: 80px;
    height: 100%;
  }
  .form_image_and_name
  {
    padding: 0 24px;
    position: relative;
    z-index: 1;
    top: -30px;
    display: flex;
    align-items: center;
  }
  .form_image_and_name .name
  {
    margin-left: 8px;
  }
  .form_image_and_name .image{
    max-width: 115px;
    max-height: 115px;
    width: 100%;
    height: 100%;
  }
  .avatar_farmer{
    width: 100%;
    height: 100%;
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
  function initMap() {
    const myLatLng = { lat: 10.7719514, lng: 106.726354 };
    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 15,
      center: myLatLng,
    });

    var locations = {{ Js::from($farmers_data) }};
  
            var infowindow = new google.maps.InfoWindow();
  
            var marker, i;
              
            for (i = 0; i < locations.length; i++) {  
              console.log( locations[i]['lat'] + "," +locations[i]['lng'])
                  marker = new google.maps.Marker({
                    
                    position: new google.maps.LatLng(locations[i]['lat'], locations[i]['lng']),
                    map: map
                  });
                    
                  google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                      infowindow.setContent(
                        ''
                        );
                      infowindow.open(map, marker);
                    }
                  })(marker, i));
  
            }
        }
        window.initMap = initMap;
</script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?libraries=geometry&sensor=false&key=AIzaSyAfLu--o47wFfFifI1F2gnK0T8l7oje08Q&callback=initMap">
</script>

@endpush