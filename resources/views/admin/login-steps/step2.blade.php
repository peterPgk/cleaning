<vf-form ajax
         action="/collect/step2"
         method="POST"
         :validation="validation"
         v-ref:step2
>
    <div class="row">
        <div class="col-xs-12">
            <h5>Your availability</h5>
            <p>
                You are on the <b>@{{ getPlanName }} subscription</b> which means {{ env('SITE_NAME') }} will aim to get you up to <b>@{{ getMaxBooking }}</b> bookings per month.
            </p>
            <p>
                To do this we need to know what days you are available to accept jobs and how many jobs you can accept per day.
            </p>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <label>Select postal districts you cover:</label>
            <vf-select
                    multiple
                    select2
                    err-msg="postal districts"
                    name="regions"
                    :html="true"
                    :value="_mapRegions"
                    v-ref:regions
                    :options="{width: '100%'}"
                    placeholder="None of the above"
                    :items="getPostcodes"
            >
            </vf-select>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <label>Select days are you able to service customers?</label>
            <vf-select
                    multiple
                    select2
                    err-msg="weekdays"
                    name="weekdays"
                    :html="true"
                    :value="_mapWeekdays"
                    v-ref:weekdays
                    :options="{width: '100%'}"
                    placeholder="None of the above"
                    :items="_weekdays"
            >
            </vf-select>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <label>Select maximum number of jobs you would like to service per day?</label>
            <vf-select
                    err-msg="jobs number"
                    name="jobs_number"
                    :value="mapData.jobs_number || ''"
                    v-ref:jobs_number
                    :options="{width: '100%'}"
                    :items="_daysNum"
            >
            </vf-select>

        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <label>Select which public holidays you are closed for:</label>
            <vf-select
                    multiple
                    select2
                    err-msg="holidays"
                    name="holidays"
                    :html="true"
                    :value="_mapHolidays"
                    v-ref:holidays
                    placeholder="None of the above"
                    :options="{width: '100%'}"
                    :items="getHolidays"
            >
            </vf-select>
        </div>
    </div>

    <div class="col-xs-12">
        <p>
            You can add additional dates in your administrator control panel at any time.
        </p>
    </div>


    <button-container>
        <vf-submit text="Next"></vf-submit>
    </button-container>

</vf-form>