<?php

use App\Http\Controllers\Admin\CropActivityController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CommuneController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\SeasonMasterController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\FarmersController;
use App\Http\Controllers\Admin\CropMasterController;
use App\Http\Controllers\Admin\CropStageController;
use App\Http\Controllers\CatalogueValueController;
use App\Http\Controllers\Admin\CropCalendarController;
use App\Http\Controllers\AjaxOptionsController;
use App\Http\Controllers\CooperativeController;
use App\Http\Controllers\CropVarietyController;
use App\Http\Controllers\FarmLandController;
use App\Http\Controllers\LogActivitiesController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\WarehouseController;
use App\Models\CatalogueValue;
use App\Models\CropVariety;
use App\Models\FarmLand;
use App\Models\LogActivities;
use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return redirect('login');
});

Route::group(["prefix"=> ""], function () {
    Route::get("/login", [LoginController::class, 'showLoginForm'])->name('show_login_form');
    Route::post('/login', [LoginController::class, 'login'])->name('login');
    Route::get("/logout", [LoginController::class, 'logout'])->name('logout');
});




Route::group(['middleware' => ['auth']], function () {
    Route::get('ajax-option-get-provinces', [AjaxOptionsController::class,'getProvinces'])->name('ajax_options.get-provinces');
    Route::get('ajax-option-get-districts', [AjaxOptionsController::class,'getDistricts'])->name('ajax_options.get-districts');
    Route::get('ajax-option-get-varieties', [AjaxOptionsController::class,'getVarieties'])->name('ajax_options.get-varieties');
    Route::get('ajax-is-email-exist/{cooperative?}', [AjaxOptionsController::class,'isEmailExist'])->name('ajax_options.is-email-exist');
    Route::get('ajax-is-phone-exist/{cooperative?}', [AjaxOptionsController::class,'isPhoneExist'])->name('ajax_options.is-phone-exist');

    Route::get("/dashboard", [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/get-staffs', [HomeController::class, 'ajaxGetStaff'])->name('ajax.get-staff');
    Route::get('/get-farmer-by-commune', [HomeController::class, 'ajaxGetFarmerByCommune'])->name('ajax.get_farmer_by_commune');
    Route::get('/get-commune-by-farm-area', [HomeController::class, 'ajaxGetCommuneByFarmArea'])->name('ajax.get_commune_by_farm_area');

    Route::resource('season-masters', SeasonMasterController::class)->names('season-masters');
    Route::resource('crop-informations', CropMasterController::class);
    Route::resource('catalogue-values', CatalogueValueController::class)->only('index');

    Route::resource('crop-stages', CropStageController::class);
    Route::post('update-crop-stage-status', [CropStageController::class, 'updateStatus'])->name('crop_stage.update_status');
    Route::resource('crop-activities', CropActivityController::class);
    Route::post('update-crop-activity-status', [CropActivityController::class, 'updateStatus'])->name('crop_activity.update_status');

    Route::resource('crop-calendars', CropCalendarController::class);
    Route::get('ajax-get-calendar-view', [CropCalendarController::class, 'ajaxGetCalendarView'])->name('ajax.get-calendar-view');
    Route::post('update-crop-calendar-status', [CropCalendarController::class, 'updateStatus'])->name('crop_calendar.update_status');

     // Farmer Details
    Route::get("/farmer", [FarmersController::class, 'index'])->name('farmer.index');
    Route::get("/farmer_location", [FarmersController::class, 'farmer_location'])->name('farmer_location.index');
    Route::get("/farmer/dtajax", [FarmersController::class, 'dtajax'])->name('farmer.dtajax');
    Route::get("/farmer/distribute_transation", [FarmersController::class, 'distribute_transation'])->name('farmer.distribute_transation');
    Route::get("/farmer/{id}", [FarmersController::class, 'show'])->name('farmer.show');

    Route::post("/import-csv", [FarmersController::class, 'importCSV'])->name('farmer.import_csv');
    Route::post("/import-csv-farmer-details", [FarmersController::class, 'importCSV_Farmer_Details'])->name('farmer-detail.import_csv');
    Route::post("/import-csv-area-audit", [FarmersController::class, 'importCSV_Area_Audit'])->name('area_audit.import_csv');
    Route::post("/import-csv-update-farmland-lat-long", [FarmersController::class, 'updateFarmlandLatLng'])->name('update-lat-long.import_csv');
    Route::get("/import-farmer", [FarmersController::class, 'importFarmer'])->name('farmer.import_csv');
    Route::post("/import-farmer", [FarmersController::class, 'importFarmer'])->name('farmer.import_csv');

    //Country
    Route::get("/country", [CountryController::class, 'index'])->name('country.index');
    Route::get("/country/dtajax", [CountryController::class, 'dtajax'])->name('country.dtajax');
    Route::get("/country/create", [CountryController::class, 'create'])->name('country.create');
    Route::post("/add_country", [CountryController::class, 'store'])->name('country.store');

    // Staff
    Route::resource('/staff', StaffController::class);

    // Warehouse
    Route::resource('/warehouse', WarehouseController::class);

    //Province
    Route::get("/province", [ProvinceController::class, 'index'])->name('province.index');
    Route::get("/province/dtajax", [ProvinceController::class, 'dtajax'])->name('province.dtajax');
    Route::get("/province/create", [ProvinceController::class, 'create'])->name('province.create');
    Route::post("/add_province", [ProvinceController::class, 'store'])->name('province.store');
    Route::get("/province_filter_by_country/{id}", [ProvinceController::class, 'filter_by_country'])->name('province.filter_by_country');

    //District
    Route::get("/district", [DistrictController::class, 'index'])->name('district.index');
    Route::get("/district/dtajax", [DistrictController::class, 'dtajax'])->name('district.dtajax');
    Route::get("/district/create", [DistrictController::class, 'create'])->name('district.create');
    Route::post("/add_district", [DistrictController::class, 'store'])->name('district.store');
    Route::get("/district_filter_by_province/{id}", [DistrictController::class, 'filter_by_province'])->name('district.filter_by_province');

    //Commune
    Route::get("/commune", [CommuneController::class, 'index'])->name('commune.index');
    Route::get("/commune/dtajax", [CommuneController::class, 'dtajax'])->name('commune.dtajax');
    Route::get("/commune/create", [CommuneController::class, 'create'])->name('commune.create');
    Route::post("/add_commune", [CommuneController::class, 'store'])->name('commune.store');
    Route::get("/commnue_filter_by_district/{id}", [CommuneController::class, 'filter_by_district'])->name('commnue.filter_by_district');

    //Log Activities
    Route::get("/staff_activities", [LogActivitiesController::class, 'index'])->name('log_activities.index');
    // Route::get("/commune/dtajax", [LogActivities::class, 'dtajax'])->name('commune.dtajax');
    // Route::get("/commune/create", [LogActivities::class, 'create'])->name('commune.create');
    // Route::post("/add_commune", [LogActivities::class, 'store'])->name('commune.store');
    // Route::get("/commnue_filter_by_district/{id}", [LogActivities::class, 'filter_by_district'])->name('commnue.filter_by_district');

    // Farm land
    Route::get("/farm_land", [FarmLandController::class, 'index'])->name('farm_land.index');
    Route::post("/farm_land/filter_farmland", [FarmLandController::class, 'filter_farmland'])->name('farm_land.filter_farmland');

    // Crop Variety
    Route::get("/crop_variety", [CropVarietyController::class, 'index'])->name('crop_variety.index');
    Route::get("/crop_variety/create", [CropVarietyController::class, 'create'])->name('crop_variety.create');
    Route::post("/crop_variety/store", [CropVarietyController::class, 'store'])->name('crop_variety.store');
    Route::get("/crop_variety/dtajax", [CropVarietyController::class, 'dtajax'])->name('crop_variety.dtajax');

    // Report
    Route::get("/farmer_report", [ReportController::class, 'farmer_report'])->name('farmer_report.index');
    Route::get("/farmer_report/farmer_report_ajax", [ReportController::class, 'farmer_report_ajax'])->name('farmer_report.farmer_report_ajax');
    Route::get("/farmland_report", [ReportController::class, 'farmland_report'])->name('farmland_report.index');
    Route::get("/farmland_report/farmland_report_ajax", [ReportController::class, 'farmland_report_ajax'])->name('farmer_report.farmland_report_ajax');
    Route::get("/farmland_report/singel_farmland_location/{id}", [ReportController::class, 'singel_farmland_location'])->name('farmer_report.singel_farmland_location');
    Route::get("/cultivation_report", [ReportController::class, 'cultivation_report'])->name('cultivation_report.index');
    Route::get("/cultivation_report/cultivation_report_ajax", [ReportController::class, 'cultivation_report_ajax'])->name('farmer_report.cultivation_report_ajax');
    Route::get('/distribution_report', [ReportController::class, 'distributionReport'])->name('distribution_report');
    Route::get('/report/farmer_balance', [ReportController::class, 'farmerBalanceReport'])->name('farmer_balance_report');
    Route::get('/crop_growth/{parcelId}', [ReportController::class, 'getCropGrowthReport'])->name('report.crop_growth');
    Route::post('/ajax/getGcHtml', [ReportController::class, 'getGcMapHtml'])->name('report.getGcMapHtml');


    // export csv about plotting
    Route::get("/export-plotting", [ReportController::class, 'exportPlotting'])->name('export_plotting');
    Route::post("/export-plotting", [ReportController::class, 'exportPlotting'])->name('export_plotting');

    Route::resource('cooperative', CooperativeController::class)->names('cooperative');

    // For testing only
    Route::get("/test-call-api", [TestController::class, 'testCallApi'])->name('test_call_api');
    Route::get("/test-phone-exist/{phone}", [TestController::class, 'testPhoneExist'])->name('test_phone_exist');
    Route::get("/test-email-exist/{email}", [TestController::class, 'testEmailExist'])->name('test_email_exist');
});

