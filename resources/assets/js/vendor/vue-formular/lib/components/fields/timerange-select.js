var merge = require('merge');
var Field = require('./field');

module.exports = function() {
    return merge.recursive(Field(), {

        props: {
            date: {
                type: [String],
                default: () => moment().startOf('hour')
            },
            /**
             * name атрибута на datetime елемента,
             * от който ще вземем датата за слотовете
             */
            dateElementName: {
                type: String,
                default: null
            },

            timeFormat: {
                type: String,
                default: 'DD/MM/YYYY'
            },

            from: {
                type: [String, Number],
                default: 6

            },

            step: {
                type: Number,
                default: 1
            },

            to: {
                type: [String, Number],
                default: 22
            },

            placeholder: {
                type: String,
                required: false,
                default: 'Select'
            }
        },

        data:function() {
            return {
                fieldType:'timerangeSelect',
            }
        },
        ready: function () {

            if ( ! _.isNull( this.dateElementName )) {

                let initialDate = moment($(`[name=${this.dateElementName}]`).val(), this.timeFormat);

                if( initialDate.isValid() ) {

                    this.date = initialDate;
                }

                this.$root.$on(`vue-formular.change::${this.dateElementName}`, ({value}) => {

                    /**
                     * Ако имаме някаква предварително избрана ст-т (или такава, дошла от ресторе)
                     * я запазваме, иначе нулираме
                     *
                     * @type {string}
                     */
                    this.value = this.value !== "" ? this.value : "";
                    this.date = value;

                });
            }

        },

        watch: {
            value () {
                this.date = moment(_.split(this.value, '|')[0]);
            }
        },

        computed: {
            _date () {
                return moment(this.date).isValid() ? moment(this.date, this.timeFormat).startOf('hour') : moment().startOf('hour')
            },

            _from () {
                return this._date.clone().hours(this.from)
            },

            _to () {
                return this._date.clone().hours(this.to)
            },

            slots () {
                let range = moment.range(this._from, this._to);
                let slots = [];

                let iteratee = moment.range(this._date.clone(), this._date.clone().add(this.step, 'hours'));

                range.by(iteratee, (moment) => {
                    let to = moment.clone().add(this.step, 'hours');

                    slots.push({
                        interval: `${moment.format()}|${to.format()}`,
                        text: `${moment.format('HH:mm')} - ${to.format('HH:mm')}`
                    })


                }, true);

                return slots;

            }
        },

    });

}