<tr class="js-calendar-detail-wrapper">
    <td>
        <input type="text" name="calendar_detail[{{ $itemIndex }}][activity_title]" class="form-control" value="{{ $calendarDetail->activity_title }}" required>
    </td>
    <td>
        <select name="calendar_detail[{{ $itemIndex }}][crop_activity_id]" id="" class="form-control" required>
            <option value="">Select Crop Activity</option>
            @foreach(\App\Models\CropActivity::where('status', 'active')->get() as $cropActivity)
                <option value="{{ $cropActivity->id }}" {{ $calendarDetail->crop_activity_id == $cropActivity->id ? 'selected' : '' }}>{{ $cropActivity->name }}</option>
            @endforeach
        </select>
    </td>

    <td>
        <select name="calendar_detail[{{ $itemIndex }}][crop_stage_id]" id="" class="form-control" required>
            <option value="">Select Crop Stage</option>
            @foreach(\App\Models\CropStage::where('status', 'active')->get() as $cropStage)
                <option value="{{ $cropStage->id }}" {{ $calendarDetail->crop_stage_id == $cropStage->id ? 'selected' : '' }}>{{ $cropStage->name }}</option>
            @endforeach
        </select>
    </td>

    <td>
        <input type="number" name="calendar_detail[{{ $itemIndex }}][duration]" class="form-control" value="{{ $calendarDetail->duration }}" required>
    </td>
    <td>
        <select name="calendar_detail[{{ $itemIndex }}][duration_uom]" class="form-control" required>
            @foreach(\App\Models\CropCalendarDetail::DURATION_UOM as $code => $name)
                <option value="{{ $code }}" {{ $calendarDetail->duration_uom == $code ? 'selected' : ''}}>{{ $name }}</option>
            @endforeach
        </select>
    </td>
    <td>
        <textarea class="tinymce-enable" rows="3" name="calendar_detail[{{ $itemIndex }}][activity_description]" required data-parsley-errors-container="#js-description-error-{{ $itemIndex }}">{!! $calendarDetail->activity_description !!} </textarea>
        <div id="js-description-error-{{ $itemIndex }}"></div>
    </td>
    <td>
        <input type="number" name="calendar_detail[{{ $itemIndex }}][repetition]" class="form-control" value="{{ $calendarDetail->repetition }}" required>
    </td>
    <td>
        <input type="number" name="calendar_detail[{{ $itemIndex }}][lead_time]" class="form-control" value="{{ $calendarDetail->lead_time }}" required>
    </td>
    <td>
        <label class="switch ms-3">
            <input type="checkbox" name="calendar_detail[{{ $itemIndex }}][is_base_on_sowing_date]" class="switch-input js-switch-status" {{ $calendarDetail->is_base_on_sowing_date === 0 ? '' : 'checked' }}>
            <span class="switch-toggle-slider">
              <span class="switch-on"></span>
              <span class="switch-off"></span>
            </span>
            <span class="switch-label"></span>
          </label>
    </td>
    <td>
        <label class="switch ms-3">
            <input type="checkbox" name="calendar_detail[{{ $itemIndex }}][status]" class="switch-input js-switch-status" {{ $calendarDetail->status == 'inactive' ? '' : 'checked' }}>
            <span class="switch-toggle-slider">
              <span class="switch-on"></span>
              <span class="switch-off"></span>
            </span>
            <span class="switch-label"></span>
          </label>
    </td>
    <td>
        <a class="rounded-circle btn-danger text-white p-2 avatar avatar-sm js-delete-calendar-detail ms-3" href="javascript:void(0)" data-delete-calendar-detail-title="Do you want to deleted this calendar detail?" title="Delete">
            <i class="fa fa-trash"></i>
        </a>
    </td>
</td>

