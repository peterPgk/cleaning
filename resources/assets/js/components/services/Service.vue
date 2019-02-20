<template>
    <div class="row form">
        <div class="col-xs-12">
            <!--<div class="row" v-show="type=='client'">-->
                <!--<div class="col-xs-12 form-group">-->
                    <!--<div class="checkbox">-->
                        <!--<label>-->
                            <!--<vf-checkbox-->
                                    <!--:checked.sync="checked"-->
                            <!--&gt;</vf-checkbox>-->
                            <!--{{service.text}}-->
                        <!--</label>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->
            <div class="row">
                <div v-if="makeSelect">
                    <div class="col-xs-12">

                        <select v-model="userSelected" class="form-control" style="margin-bottom: 15px">
                            <option :value="null" selected="{{ !hasUserSelected }}">Please select</option>
                            <option v-for="(index, subcategory) in renderServices" value="{{ index }}">{{ subcategory.name }}</option>
                            <!--<option v-for="(index, subcategory) in renderServices" value="{{ index }}" selected="{{ index == 0 ? true : false }}">{{ subcategory.name }}</option>-->
                        </select>

                                    <!--:additional-taxes="additionalTaxes"-->
                        <div v-if="hasUserSelected">
                                    <!--:type="type"-->
                                    <!--:selected-data="selectedData[serviceType.id]"-->
                            <service-type
                                    v-for="serviceType in renderServices[userSelected].data"
                                    :service-data="serviceType"
                                    :selected-data="getSelected(serviceType.id)"
                                    :is-extra="isExtra"
                            ></service-type>
                        </div>
                    </div>
                </div>

                <div v-if="!makeSelect">
                    <!--<div class="col-xs-12 form-group" v-if="checked" v-for="serviceGroup in renderServices">-->
                    <div class="col-xs-12 form-group" v-for="serviceGroup in renderServices">
                        <h5>{{ serviceGroup.name }}</h5>
                                <!--:additional-taxes="additionalTaxes"-->
                                <!--:type="type"-->
                                <!--:selected-data="selectedData[serviceType.id]"-->
                        <service-type
                                v-for="serviceType in serviceGroup.data"
                                :service-data="serviceType"
                                :selected-data="getSelected(serviceType.id)"
                                :is-extra="isExtra"
                        ></service-type>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
    import ServiceType from './ServiceType'

    export default {

        components: {
            ServiceType
        },

        props: {
            /**
             * ['client', 'user']
             */
//            type: {
//                type: String
//            },
            /**
             * Service to show
             */
            service: Object,
            /**
             * If any services are already selected
             */
            selected: Object,
            /**
             * Additional taxes to calculate
             */
//            additionalTaxes: Object,

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
        data () {
            return {
                /**
                 * модела на селекта, ако услугите се показват като селект,
                 * т.е. ако makeSelect === true
                 */
                userSelected: null,
            }
        },
        computed: {

            hasUserSelected () {
                return !_.isNil(this.userSelected)
            },

            /**
             * * По новата логика имаме основни услуги и extra услуги,
             * които не зависят от основните (за разлика от старата логика)
             * Тук се филтрират услугите, които да се покажат на база на маркер
             * is_extra в самата услуга, и параметър isExtra който се подава на формата
             * в зависимост от това кой от двата типа услуги трябва да се показват
             *
             * @returns {Array}
             */
            renderServices () {
                return _.filter(this.service.additional, ((s) => {
                    return s.is_extra == this.isExtra;
                }))
            },

//            selectedData () {
//
//                let data = {};
//
//                _.each(this.servicesFlat, function (o) {
//                    /**
//                     * TODO: Търсим го като стринг или число - ДА СЕ ОПРАВИ
//                     */
//                    let f = _.find(this.selectedFlat, {'id': `${o.id}`});
//
//                    if (!f) {
//                        f = _.find(this.selectedFlat, {'id': o.id})
//                    }
//
//                    data[o.id] = (f) ? f : {};
//
//                }.bind(this))
//
//                return data;
//            },

//            selectedFlat () {
//                return _.flatMap(this.selected, (el) => { return _.values(el)});
//
////                Old way
////                ==============
////                let c = {}
////
////                _.each(this.selected, function (el) {
////                    _.assign(c, el)
////                })
////
////                console.log( c, 'sdd' );
////
////                return c;
//            },


            servicesFlat () {
                /**
                 * Вадим 'data' ключа от сървисите
                 */
                return _.flatMap(this.service.additional, 'data');
//                return _.flatten(_.values(this.service.additional));
            },

            /**
             * Дали да се показват всички подуслуги на един екран или
             * само една група, избираема чрез селект.
             * Валидно е само в потребителски интерфейс (ако формата се рендва
             * при потребител) и се контролира от сървъра чрез атрибут (is_select)
             * за конкретната услуга
             *
             * Добавено: Допълнителнните услуги (extra) също да не се пказват като селект
             *
             * @returns {boolean}
             */
            makeSelect () {
//                return !this.isExtra && this.type === 'user' && this.service.is_select == 1;
                return !this.isExtra && this.service.is_select == 1;
            }
        },

        methods: {
            getSelected (id) {
                return _.has(this.selected, id) ? this.selected[id]: null;
            },
        },

//        watch: {
//            userSelected () {
//
//            }
//        },

        ready () {
            if (this.makeSelect) {

                /**
                 * TODO: Да го оправя
                 * @type {null}
                 */
                let t = null;

                _.forEach(this.renderServices, function (service, key) {
                    _.forEach(service.data, function (d) {
                        if (_.has(this.selected, d.id)) {
                            t = key;
                        }
                    }.bind(this))
                }.bind(this));

                this.userSelected = t;
            }
        }

    }
</script>

<style scoped>
    h5 {
        border-bottom: 1px solid #cccccc;
    }
</style>