import { cleanJson } from '../utils/utils'

export let computed = {
    computed: {
        /**
         * Returns current WizardStep reference
         * @returns {string}
         */
        currentRef () { return `head${this.currentStep}` },

        /**
         *
         * @returns {Array|rawServerErrors|{type, default}|string|*}
         */
        serverErrors () {
            if( typeof this.rawServerErrors == 'string' ) {
               return JSON.parse(this.rawServerErrors);
            }
            return this.rawServerErrors

        },

        formattedData () {
            return cleanJson(this.tempData)
        },
    }
}