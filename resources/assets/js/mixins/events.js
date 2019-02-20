export let events = {

    events: {

        'wizard::toggle' () {
            this.goTop();
        },


        /**
         * We will use vue-formular into entire application,
         */
        'vue-formular.sending': function () {
            this.$emit('sending')
        },

        'vue-formular.sent': function (response) {
            this.$emit('sent.ok', response)
        },

        'vue-formular.invalid.server': function (response) {
            this.$emit('sent.error', response)
        },

        /**
         * Изчистване на маркирани с грешки полета
         */
        'sent-errors.clear': function () {
            //За да се поставят автоматично върнатите от сървъра грешки
            //на правилните полета, в :rules трябва да се постави правило
            //с името на фирмата {companyAddress:true}
            //TODO:Да го направя автоматично преди изпращане на формата
            _.forEach( this.processedErrors, function (elementName ) {
                if ( _.has( this.$refs, elementName) ) {

                    let field = this.$refs[elementName];

                    field.errors = [];
                    field.hadErrors = false;
                }
            }.bind(this));

            this.processedErrors = [];
        },

        /**
         * General form sending events
         */
        'sending': function () {
            this.sending(true);

            /**
             * We must send back to the server some data, because there have not session yet
             * This is the only way to do this in vue-formular (for now)
             */
            if( _.has(this.$refs, `step${this.currentStep}`) ) {

                let currentForm = this.$refs[`step${this.currentStep}`];

                currentForm.options.additionalPayload = this.shared;
            }

            this.$emit('sent-errors.clear');
        },

        'sent.ok': function (response) {
            let {url, status, data} = response;

            this.sending(false);

            /**
             * Правим тези гимнастики, защото понякога върнатите
             * данни са парснати, а понякога са стринг.
             * С computed property formatData ги оправяме
             */
            this.tempData = data;

            if( status == 200 ) {

                let formIsOnStep = this.getStep(url);

                /**
                 * Have we some data to share between steps
                 */
                let {share, store} = this.formattedData;
                if( share !== undefined ) {
                    this.share(share);
                }

                if( store !== undefined ) {
                    this.storeIn(store)
                }

                this.valid[`step${formIsOnStep}`] = true;

                /**
                 * Може да сме се върнали на предишна стъпка и да изпращаме форма от там,
                 * затова проверяваме
                 */
                if( formIsOnStep == +this.currentStep ) {
                    this.nextStep();
                }

                if(  _.isFinite(formIsOnStep) && _.has( this.$refs, this.currentRef ) ) {
                    // setTimeout(() => this.$refs[this.currentRef].toggle(), 100)
                    this.$refs[this.currentRef].toggle()
                }
            }
        },

        'sent.finish': function () {
            this.sending(false);
         },

        'sent.error': function (response) {
            let {data:error, url, ok, status} = response;

            this.sending(false);

            /**
             * TODO: Sometimes error is html (404 ...). Handle this
             */
            this.rawServerErrors = error;

            /**
             *  body : "[{"name":"website","message":"The website format is invalid."}]"
             data : "[{"name":"website","message":"The website format is invalid."}]"
             headers : Object
             ok : false
             status : 422
             statusText : "Unprocessable Entity"
             url : "/company/signup/step1"
             */

            let step =`step${this.getStep(url)}`,
                form = this.$refs[step],
                messages = {};

            if( ! ok && status != 200 ) {
                this.valid[step] = false;
            }

            /**
             * Понеже използвам този евент и за стъпки, в които не
             * използвам vue-formular
             */
            if ( _.has(form, 'options') ) {

                _.forOwn(this.serverErrors, function (error) {
                    messages[error.name] = error.message;
                });

                form.options.messages = _.assign(form.options.messages, messages);

                _.forOwn( this.serverErrors, function ( error) {

                    /**
                     * Понякога грешките се връщат като обект с ключ 'error'
                     */
                    // let error = _.has(error, 'error') ? error.error : error;

                    let lowerName = error.name.toLowerCase();
                    if ( _.has( this.$refs, lowerName) ) {
                        let field = this.$refs[lowerName];

                        //save error
                        // this.errors.push(lowerName);
                        this.processedErrors.push(lowerName);

                        field.errors = [error.name];
                        field.hadErrors = true;

                    }
                }.bind(this) );

                /**
                 * Глупост, но за сега върши работа
                 */
                setTimeout(() => {
                    let $err = $('.has-error').first();
                    $('html, body').animate({
                        scrollTop: ($err.offset().top - 300)
                    }, 600, function () {
                        // $el.focus()
                    })
                }, 100)
            }
        },

        openCurrentStep () {

            if ( _.has( this.$refs, this.currentRef ) ) {

                // setTimeout(() => this.$refs[this.currentRef].toggle(), 100)
                this.$refs[this.currentRef].toggle()
            }
        }
    }

};