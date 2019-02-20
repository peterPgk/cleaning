<vf-form ajax
         action="/collect/step3"
         method="POST"
         v-ref:step3
>

    <div class="row">
        <div class="col-xs-12">
            <h5>Your prices</h5>
            <div>
                <p>In the next step we well ask you to enter the prices for each ot the types of services you selected when registering.</p>
                <p>Remember to enter your prices as they appear on your website. Failure to do so may result in your company not being approved.</p>
                <p>The prices you enter here will be subject to a 25% commision deduction</p>
            </div>
        </div>
    </div>

    <button-container>
        <vf-submit text="Start entering prices"></vf-submit>
    </button-container>

</vf-form>