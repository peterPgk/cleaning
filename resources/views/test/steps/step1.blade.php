{{-- ====== STEP 1 - SERVICES ========= --}}
<vf-form ajax
         action="/customers/step1"
         method="POST"
         :validation="validation"
         :messages="{required:'You must choose a service'}"
         v-ref:step1
>

    <div class="row">
        <div class="col-xs-12">
            <div class="">

            {{--<vf-num-placeholder--}}
                    {{--:hide-label="true"--}}
                    {{--:min="0" :max="10" :value="5" label="bathrooms"></vf-num-placeholder>--}}

            <vf-buttons-list
                    name="services"
                    label=""
                    v-ref:services
                    :value="mapData.services"
                    {{--:value="shared.services"--}}
                    :items="stored.service_categories"
                    :rules="{services:true}"
                    required
            >
            </vf-buttons-list>
            </div>
        </div>
    </div>

    {{--<div class="row buttons-group">--}}
        {{--<div class="col-xs-12">--}}
            {{--<vf-submit text="Next"></vf-submit>--}}
        {{--</div>--}}
    {{--</div>--}}

</vf-form>