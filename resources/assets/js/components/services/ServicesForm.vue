<template>
    <div class="services-form">
        <div v-for="service in _services">
                    <!--:type="_type"-->
                    <!--:additional-taxes="additionalTaxes"-->
                    <!--:selected="getSelectedService(service)"-->
            <service
                    :service="service"
                    :selected="_selected"
                    :is-extra="isExtra"
            ></service>
        </div>
    </div>
</template>

<script type="text/babel">

    import Service from './Service';

    export default {

        components: {
            Service
        },

        data() {
            return {
                /**
                 *  Формата може да се използва за събиране на информация от фирми
                 *  или от потребители.
                 *  В първия случай, за всяка услуга се показва цифрово поле за цената на
                 *  услугата, а във втория (user) слайдер за избор на броя услуги
                 */
//                types: ['client', 'user']
            }
        },

        props: {
            /**
             * ['client', 'user']
             */
//            type: {
//                type: String
//            },
            /**
             * Offered services
             * Услугите, които трябва да се покажат
             */
            services: {
                required: true,
                type: [Array, Object, String],
                'default': () => []
            },
            /**
             * If any services are already selected
             */
            selected: {
                type: Object,
                required: false,
                'default': () => {}
            },
            /**
             * Additional taxes to calculate
             */
//            additionalTaxes: {
//                type: Object,
//                required: false,
//                default: () => {}
//            },

            /**
             * Услугите могат да бъдат основни и допълнителни (extra)
             * Понеже се подават всички услуги наведнъж от сървъра,
             * от тук се задава кой тип услуги да се дисплейнат
             *
             * Всяка услуга има пропърти is_extra: {true|false}
             *
             * За сега го прилагаме само ако формата е потребителска (type == 'user')
             *
             */
            isExtra: {
                type: Boolean,
                'default': false
            }
        },

        computed: {
            /**
             * Reformat services
             */
            _services () {

                let services = this.services;

                if (_.isArray(this.services)) {
                    services = this.services;
                }

                if (_.isObjectLike(this.services) || _.isObject(this.services)) {
                    services = _.castArray(this.services)
                }

                if (_.isString(this.services)) {
                    services = JSON.parse(this.services)
                }


                return services;
            },

//            _type () {
//                return _.includes(this.types, this.type) ? this.type : 'client'
//            },


            /**
             * Това са вече избраните категории.
             * @private
             */
//            _selected () {
//                /**
//                 * Логиката е, че компанията ще избира услугите, които предлага по
//                 * време на регистрацията си.
//                 * След това при пръв логин ще трябва да попълни допълнителната информация
//                 * за избраните услуги (категории)
//                 * При първия логин ще се показват само вече избраните в регистрацията услуги,
//                 * затова, ако няма подаден обект (selected) приемаме, че трябва да селектираме
//                 * всички услуги - това означава чекбоксовете да са вкл. и подкатегориите и самите
//                 * услуги да се показват
//                 */
//                if (_.isEmpty(this.selected)) {
//
//                    return _.fromPairs(this.services.map((item) => [item.id, item]))
//                }
//
//                return this.selected;
//
//            }
            _selected () {

                return _.mapKeys(this.selected, function (val, key) {
                    return  _.split(key, '_')[1];
                });
            }
        },

//        methods: {
//            getSelectedService (service) {
////                console.log( _.some(this._selected, ['id', service.id]), '???' );
//
//                return _.has( this.selected, service.id ) ? this.selected[service.id] : {}
//            }
//        }
    }
</script>

<style lang="sass" scoped>
</style>