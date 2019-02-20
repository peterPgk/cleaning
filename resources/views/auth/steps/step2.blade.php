{{-- ====== STEP 2 - REGISTRATION INFO ========= --}}
<div class="authstep2">
<vf-form ajax
         action="{{ $action }}"
         method="POST"
         :validation="validation"
         v-ref:step2
>

    <div class="row">

        <div class="col-xs-12">

            {{--Company name--}}
            <label>Your full name</label>&nbsp;&nbsp;
            <vf-text
                    err-msg="Your full name"
                    name="client_name"
                    :value="mapData.client_name"
                    v-ref:client_name
                    placeholder=""
            >
            </vf-text>

        </div>

        <div class="col-xs-12">

            {{--Company name--}}
            <label>Company name</label>&nbsp;&nbsp;
            <tooltip effect="scale" content="Enter your company name as you would like it to appear in our search results" placement="top" trigger="hover">
                <span class="tooltip-handler fa fa-question-circle" aria-hidden="true"></span>
            </tooltip>
            <vf-text
                    err-msg="Company name"
                    name="name"
                    :value="mapData.name"
                    v-ref:name
                    placeholder=""
            >
            </vf-text>

        </div>

        <div class="col-xs-12">

            {{--Company Email--}}
            <label>Company email</label>
            <tooltip effect="scale" content="Enter the email address you would like booking alerts to be sent to" placement="top" trigger="hover">
                <span class="tooltip-handler fa fa-question-circle" aria-hidden="true"></span>
            </tooltip>
            <vf-email
                    err-msg="Company email"
                    name="email"
                    v-ref:email
                    :value="mapData.email"
                    @if(Request::is('admin/*')) disabled @endif
                    @if(Request::is('master/*')) disabled @endif

                    placeholder=""
                    :rules="{email:true}"
        >
            </vf-email>
        </div>
        <div class="colxs-12">&nbsp;</div>

        <div class="col-xs-12">

            {{--Trading name--}}
            <label>Trading name (if different from above)</label>
            <tooltip effect="scale" content="Enter your trading name (if different from your company name)" placement="top" trigger="hover">
                <span class="tooltip-handler fa fa-question-circle" aria-hidden="true"></span>
            </tooltip>
            <vf-text
                    err-msg="Trading name"
                    name="trading_name"
                    :value="mapData.trading_name"
                    v-ref:trading_name
                    placeholder=""
            >
            </vf-text>
        </div>

        <div class="col-xs-12">

            {{-- Company number --}}
            <label>Company number</label>
            {{--<tooltip effect="scale" content="Enter your company number" placement="top" trigger="hover">--}}
                {{--<span class="tooltip-handler fa fa-question-circle" aria-hidden="true"></span>--}}
            {{--</tooltip>--}}
            <p class="small-text">To find your company number visit <a href="https://beta.companieshouse.gov.uk/" target="_blank">Companies House</a> </p>
            <vf-text
                    err-msg="Company number"
                    name="company_number"
                    :value="mapData.company_number"
                    v-ref:company_number
                    placeholder=""
            >
            </vf-text>
        </div>

        <div class="col-xs-12">&nbsp;</div>

        <div class="col-xs-12">
            {{-- VAT Number --}}
            <label>VAT Number (if applicable)</label>
            {{--<tooltip effect="scale" content="Enter your VAT number if you are registered for VAT" placement="top" trigger="hover">--}}
                {{--<span class="tooltip-handler fa fa-question-circle" aria-hidden="true"></span>--}}
            {{--</tooltip>--}}
            <vf-text
                    err-msg="VAT Number"
                    name="vat"
                    :value="mapData.vat"
                    v-ref:vat
                    placeholder=""
            >
            </vf-text>
        </div>
        <div class="col-xs-12">&nbsp;</div>

        <div class="col-xs-12">
            {{-- Company Website --}}
            <label>Company website</label>
            {{--<tooltip effect="scale" content="Enter your company website URL" placement="top" trigger="hover">--}}
                {{--<span class="tooltip-handler fa fa-question-circle" aria-hidden="true"></span>--}}
            {{--</tooltip>--}}
            <vf-text
                    err-msg="Company website"
                    name="website"
                    :value="mapData.website"
                    v-ref:website
                    type="url"
                    placeholder=""
            >
            </vf-text>

            {{-- Phone --}}
            {{-- TODO: Plugin for formating phone numbers --}}
            <label>Main phone number</label>
            {{--<tooltip effect="scale" content="Enter your company main contact number" placement="top" trigger="hover">--}}
                {{--<span class="tooltip-handler fa fa-question-circle" aria-hidden="true"></span>--}}
            {{--</tooltip>--}}
            <vf-text
                    err-msg="Main phone number"
                    name="phone"
                    :value="mapData.phone"
                    v-ref:phone
                    placeholder=""
            >
            </vf-text>

            {{-- Phone number 2 --}}
            <label>Second phone number (if applicable)</label>
            {{--<tooltip effect="scale" content="Enter your company second contact number" placement="top" trigger="hover">--}}
                {{--<span class="tooltip-handler fa fa-question-circle" aria-hidden="true"></span>--}}
            {{--</tooltip>--}}
            <vf-text
                    err-msg="Second phone number"
                    name="phone_2"
                    :value="mapData.phone_2"
                    v-ref:phone_2
                    placeholder=""
            >
            </vf-text>

        </div>

        <div class="col-xs-12">

            {{--Address--}}
            <label>Address Line 1</label>
            <vf-text
                    err-msg="Address Line 1"
                    name="address"
                    :value="mapData.address"
                    v-ref:address
                    type="text"
                    placeholder=""
            >
            </vf-text>

            {{--Address 2--}}
            <label>Address Line 2</label>
            <vf-text
                    err-msg="Address Line 2"
                    name="address_2"
                    :value="mapData.address_2"
                    v-ref:address_2
                    type="text"
                    placeholder=""
            >
            </vf-text>

            {{--Address 3 --}}
            <label>Address Line 3</label>
            <vf-text
                    err-msg="Address Line 3"
                    name="address_3"
                    :value="mapData.address_3"
                    v-ref:address_3
                    type="text"
                    placeholder=""
            >
            </vf-text>

            {{--City/Town--}}
            <label>City/Town</label>
            <vf-text
                    err-msg="City/Town"
                    name="city"
                    :value="mapData.city"
                    v-ref:city
                    placeholder=""
            >
            </vf-text>

            {{--PostCode--}}
            <label>Postcode </label>
            <vf-text
                    err-msg="Postcode"
                    name="postcode"
                    :value="mapData.postcode"
                    v-ref:postcode
                    placeholder=""
                    required
            >
            </vf-text>

            {{--YouTube--}}
            <label>YouTube video link (optional)</label>
            <tooltip effect="scale" content="If you would like to promote a company YouTube video please enter it here" placement="top" trigger="hover">
                <span class="tooltip-handler fa fa-question-circle" aria-hidden="true"></span>
            </tooltip>
            <vf-text
                    err-msg="YouTube video link"
                    name="youtube"
                    :value="mapData.youtube"
                    v-ref:youtube
                    type="url"
                    placeholder=""
            >
            </vf-text>

            <div class="col-xs-12">&nbsp;</div>

            {{--Logo--}}
            <div class="logo_upload">
                <label>Upload Company Logo</label>
                <vf-file
                         err-msg="Company Logo"
                         :name="shared.uuid"
                         v-ref:logo
                         :ajax="true"
                         :options="{
                                    url:'{{ $logo_path or '/company/signup/step2/logo'}}',
                                }"
                >
            </vf-file>
            </div>
        </div>

    </div>

    <button-container>
        <vf-submit text="Next"></vf-submit>
    </button-container>

</vf-form>

</div>
