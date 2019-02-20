{{-- ====== STEP 5 - CHOOSE COMPANY ========= --}}

<vf-form ajax
         action="/customers/step5"
         method="POST"
         :validation="validation"
         v-ref:step5
>

    <div class="row">

        <div class="col-xs-12">
            <div class="form-inline" v-for="extra in _extraServices">
                <extra-service
                    :service="extra"
                    :selected="_selectedServices"
                ></extra-service>
            </div>

            <vf-textarea
                    placeholder="Special instructions e.g. for entry or access"
                    name="instructions"
                    v-ref:instructions
                    :value="mapData.instructions"
                    :rules="{instructions:true}"
            ></vf-textarea>

            <vf-text
                    {{--label="Your first names"--}}
                    placeholder="Your first name"
                    name="firstname"
                    v-ref:firstname
                    :value="mapData.firstname"
                    :rules="{firstname:true}"
            ></vf-text>

            <vf-text
                    {{--label="Your last names"--}}
                    placeholder="Your last names"
                    name="lastname"
                    v-ref:lastname
                    :value="mapData.lastname"
                    :rules="{lastname:true}"
            ></vf-text>

            {{-- Address --}}
            <vf-text
                    {{--label="Enter your email address"--}}
                    placeholder="Your email address"
                    name="email"
                    v-ref:email
                    :value="mapData.email"
                    :rules="{email:true}"
            ></vf-text>

        </div>

        <div class="col-xs-12">

            <vf-select
                    {{--label="How did you hear about us?"--}}
                    placeholder="How did you hear about us?"
                    name="survey"
                    :html="true"
                    :value="mapData.survey"
                    v-ref:survey
                    :options="{width: '100%'}"
                    :items="stored.how_did_hear_about_us"
            >
            </vf-select>
        </div>

    </div>

    <button-container-compare>
        <vf-submit text="COMPARE" style="background-color:#EF5BA2" onclick="ga('send', 'event', 'Comparison', 'Step', 'Step 4');"></vf-submit>
    </button-container-compare>

</vf-form>