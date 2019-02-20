<vf-form ajax
         action="/collect/step1"
         method="POST"
         v-ref:step1
>

    <div class="row">
        <div class="col-xs-12">
            <h5>Welcome to {{ env('SITE_NAME') }}</h5>
            <div>
                <p>To complete your profile and start being seen on our website you will need to complete a couple more steps.</p>
                <p>We should get you live in no time</p>
                <p>If you have any questions during this process, please call {{ env('SITE_PHONE') }}</p>
            </div>
        </div>
    </div>

    <button-container>
        <vf-submit text="Next"></vf-submit>
    </button-container>

</vf-form>