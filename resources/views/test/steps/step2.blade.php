{{-- ====== STEP 2 - DATE AND POSITION ========= --}}
{{--<vf-form client--}}
<vf-form ajax
         action="/customers/step2"
         method="POST"
         :validation="validation"
         v-ref:step2
>

    {{--<div v-for="extra in _extraServices">--}}
        {{--@{{extra.id}}--}}
    {{--</div>--}}

    <div class="row">
        {{--<div class="col-xs-12 col-sm-8 col-sm-push-2 col-md-6 col-md-push-3 col-lg-4 col-lg-push-4">--}}
        <div class="col-xs-12">
            {{-- Date to search --}}
            <div class="">
            <vf-date
                    label="Choose a date"
                    name="service_date"
                    v-ref:service_date
                    :value="_chosenDate"
                    {{--:value="mapData.service_date"--}}
                    :rules="{service_date:true}"
                    :options="{minDate:tomorrow, locale:{firstDay:1}}"
                    required
            ></vf-date>
            </div>
        </div>
    </div>
    <div class="row">
        {{--<div class="col-xs-12 col-sm-8 col-sm-push-2 col-md-6 col-md-push-3 col-lg-4 col-lg-push-4">--}}
        <div class="col-xs-12">
            <div class="">

            <vf-timerange-select
                    label="Choose timeslot"
                    name="timeslots"
                    date-element-name="service_date"
                    :time-format="timeFormat"
                    :from="8"
                    :to="18"
                    :step="1"
                    {{--:value="_timeslotss"--}}
                    :value="mapData.timeslots"
                    {{--:value="shared.timeslots"--}}
                    v-ref:timeslots
                    placeholder="Select"
                    required
            >
            </vf-timerange-select>

            </div>

        </div>
    </div>

    <button-container-compare>
        <p class="text_before_next_button">Arrival times are estimates only. The cleaning company you book with will get in touch to confirm this.</p>
        <vf-submit text="CONTINUE" style="background-color:#EF5BA2" onclick="ga('send', 'event', 'Comparison', 'Step', 'Step 1');"></vf-submit>
    </button-container-compare>

</vf-form>