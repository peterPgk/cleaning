{{-- ====== STEP 3 - ADDITIONAL INFO ========= --}}
<div class="authstep3">
<vf-form ajax
         action="{{ $action }}"
         method="POST"
         :validation="validation"
         v-ref:step3
>

    <div class="row services">
        <div class="col-xs-12">
            <label>Which of the following services do you offer</label>
            <p class="small-text">Please select each of the services you offer from the drop down below.</p>
            <vf-select
                    multiple
                    select2
                    err-msg="Services"
                    name="services"
                    :html="true"
                    :value="mapData.services"
                    v-ref:services
                    :options="{width: '100%'}"
                    {{--:items="stored.service_categories"--}}
                    :items="getServiceCategories"
            >
            </vf-select>

        </div>

    </div>

    <div class="row">

        <div class="col-xs-12">

            {{-- Members of --}}
            <label>Are you a member of any of these trade associations?</label>
            <vf-select
                    multiple
                    select2
                    name="members_of"
                    err-msg="Address Line 1"
                    :value="mapData.members_of"
                    :html="true"
                    v-ref:members_of
                    :options="{width: '100%'}"
                    :items="getMembers"
            >
            </vf-select>
        </div>

        <div class="col-xs-12">

            {{--Date established--}}
            <label>Year company established</label>
            <vf-text
                    err-msg="Year company established"
                    name="date_established"
                    :value="mapData.date_established"
                    v-ref:date_established
                    placeholder="Year"
                    required
            >
            </vf-text>

        </div>

        <div class="col-xs-12">

            {{-- Cleaning guarantee --}}
            <label>Cleaning guarantee</label>
            <p class="small-text">Enter your complaints procedure here, include any cleaning guarantee you may have. Please note the customer will be able to see this when
                viewing your company profile on {{ env('SITE_NAME') }}</p>

            <vf-select
                    name="complaints"
                    err-msg=" Cleaning guarantee"
                    v-ref:complaints
                    :value="mapData.complaints"
                    :items="getQuarantee"
                    required
            ></vf-select>
        </div>

        {{-- Insurance value --}}
        <div class="col-xs-12 form-inline">
            <label>Does your company have public liability insurance</label>
            <vf-checkbox
                    err-msg="Liability Insurance"
                    name="liability"
                    :checked="mapData.liability"
                    v-ref:liability
                    class="hidden"
            >
            </vf-checkbox>

            {{--<div class="row">--}}
                {{--<div class="col-xs-3 text-right" style="width: 55px; line-height: 30px;">--}}
                    {{--<span>Yes</span>--}}
                {{--</div>--}}
                {{--<div class="col-xs-6" style="width: 110px;">--}}
                    {{--<div class="onoffswitch">--}}
                        {{--<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" v-model="mapData.liability">--}}
                        {{--<label class="onoffswitch-label" for="myonoffswitch">--}}
                            {{--<span class="onoffswitch-inner"></span>--}}
                            {{--<span class="onoffswitch-switch"></span>--}}
                        {{--</label>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-xs-3 text-left" style="line-height: 30px;">--}}
                    {{--<span>No</span>--}}
                {{--</div>--}}
            {{--</div>--}}


            <div class="VF VF-Field form-group VF-Field--Select" id="liability">

                <div class="VF-Field__wrapper">

                    <select class="form-control" name="liability" v-model="mapData.liability">
                        <option v-for="option in liabilityOpts" v-bind:value="option.value" >@{{ option.text }}</option>
                    </select>

                </div>
            </div>

        </div>

        <div class="col-xs-12">&nbsp;</div>

        <div class="col-xs-12 col-md-6" v-show="mapData.liability">
            <vf-select
                    label="Public Liability cover amount"
                    name="liability_amount"
                    v-ref:liability_amount
                    :value="mapData.liability_amount"
                    :items="getAmount"
            >
            </vf-select>

        </div>

        <div class="col-xs-12 col-md-6" v-show="mapData.liability">
            <label>When does your insurance expire?</label>
            <vf-date
                    err-msg="The When does your liability insurance expire"
                    name="liability_expires"
                    :value="mapData.liability_expires"
                    v-ref:liability_expires
                    no-input
                    :options="{showDropdowns: true, minDate:today}"
            >
            </vf-date>
        </div>

        <div class="col-xs-12 certificate-image" v-show="mapData.liability">
            <label>Upload your public liability insurance [PDF]</label>
            <tooltip effect="scale" content="Please upload a copy of your public liability insurance" placement="top" trigger="hover">
                <span class="tooltip-handler fa fa-question-circle" aria-hidden="true"></span>
            </tooltip>
            <vf-file
                    :ajax="true"
                    name="liability_certificate"
                    :name="shared.uuid"
                    v-ref:liability_certificate
                    :options="{ url: '{{ $liability_path or '/company/signup/step3/liability-image'}}' }"
            >
            </vf-file>

        </div>
    </div>

    <button-container>
        <vf-submit text="Next"></vf-submit>
    </button-container>

</vf-form>
</div>