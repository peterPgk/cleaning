export let data = {
    data () {
        return {


            /**
             * Понякога данните се чупят и идват като стринг вместо json
             * При event 'sent.ok' попълвам tempData с респонса и след това
             * с computedProperty се генерира formattedData
             */
            tempData: {},

            /**
             *
             */
            today: moment(),

            /**
             * To process server errors if they
             * NEW: 2.0 compatible - we use computed properties instead of coerce function
             */
            rawServerErrors: {
                type: [String, Object],
                default: {}
            },
            /**
             * Save server validation errors for easy
             * cleaning after
             */
            processedErrors: [],
            isLocked: false
        }
    }
}