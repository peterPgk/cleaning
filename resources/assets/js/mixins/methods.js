export let methods = {
    methods: {
        /**
         * Try to find number (represents step) at the end of url
         * @param {string} link - url
         * @returns {number}
         */
        getStep(link) {
            return +link.replace( /^\D+/g, '')
        },

        /**
         * Правим стъпките след настоящата невалидирани
         */
        changeValidation( iteratee, step ) {
            _.each(iteratee, (value, key) => {

                if ( this.getStep(key) > +step ) {
                    iteratee[key] = null;
                }
            })
        },

        /**
         * Отваря предна стъпка (бутон Back)
         */
        prevStep () {

            let step = this.currentStep > 1 ? this.currentStep - 1 : this.currentStep;

            this.changeValidation(this.valid, (step-1));
            this.setStep(step);

            this.$emit('openCurrentStep')

        },

        /**
         * Event polyfill заради IE
         * @param event
         * @param params
         * @returns {Event}
         */
        customEvent (event, params) {
            params = params || { bubbles: false, cancelable: false, detail: undefined };
            let evt = document.createEvent( 'CustomEvent' );
            evt.initCustomEvent( event, params.bubbles, params.cancelable, params.detail );
            return evt;
        },

        /**
         * Scroll to top
         * TODO: Да направя универсална ф-я която да се изплзва и за грешките в events.js
         */
        goTop () {
            setTimeout(() => {
                $('html, body').animate({
                    scrollTop: '0px'
                }, 400)
            }, 300)
        }
    }
};