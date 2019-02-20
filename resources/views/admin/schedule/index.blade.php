@extends('layouts.admin.admin')

@section('content')

    <section class="content-header">
        <h1>
            Workdays
            <small>Edit your working days</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Workdays</a></li>
            <li class="active">Change workdays</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div id="app">

            <notification :show="alertData.show" placement="top" duration="3000" :msg="alertData.msg"
                          :type="alertData.type" width="400px" dismissable>
            </notification>

            <vf-form ajax
                     action="/admin/schedule/update"
                     method="POST"
                     v-ref:schedule
            >
                <h3>Choose workdays</h3>
                <label>Select days are you able to service customers?</label>

                <div class="form-inline">
                    <div class="row">
                        <div class="col-xs-12">
                            <vf-select
                                    multiple
                                    select2
                                    err-msg="workdays"
                                    name="workdays"
                                    :html="true"
                                    :value="selected"
                                    v-ref:weekdays
                                    :options="{width: '100%'}"
                                    :items="_workdays"
                            >
                            </vf-select>
                        </div>
                    </div>
                </div>

                <hr>

                <label>Select maximum number of jobs you would like to service per day?</label>

                <div class="form-inline">
                    <div class="row">
                        <div class="col-xs-12">
                            <vf-select
                                    err-msg="jobs number"
                                    name="max_jobs"
                                    :value="jobs"
                                    v-ref:jobs_number
                                    :options="{width: '100%'}"
                                    :items="_daysNum"
                            >
                            </vf-select>

                        </div>

                    </div>
                </div>

                <hr>

                <label>Select which public holidays you are closed for:</label>

                <div class="form-inline">
                    <div class="row">
                        <div class="col-xs-12">
                            <vf-select
                                    multiple
                                    select2
                                    err-msg="holidays"
                                    name="holidays"
                                    v-ref:holidays
                                    :value="restdays"
                                    :options="{width: '100%'}"
                                    :items="holidays"
                            >
                            </vf-select>

                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-xs-12">
                        <button-container>
                            <vf-submit text="Update"></vf-submit>
                        </button-container>
                    </div>
                </div>
            </vf-form>

            <div class="spinner" v-if="loading">
                <loader :loading="loading" :color="color" :size="loaderSize" class="text-center"></loader>
            </div>

        </div>

    </section>

@endsection

@section('js')
    <script>

        let app = new Vue({

            el: '#app',

            data: {
                //избраните работни дни
                selected: <?php echo json_encode( $weekdays )?>,
                //Максималния брой услуги за ден
                jobs: <?php echo $max_jobs ?>,
                //Всички празници
                holidays: <?php echo $holidays ?>,
                //Моите празници
                restdays: <?php echo $restdays ?>,
                //Jobs limit
                jobs_limit: <?php echo $jobs_limit ?>
            },

            computed: {

                /**
                 * Генерираме дните от седмицата
                 *
                 * @returns {Array}
                 * @private
                */
                _workdays: function () {
                    /**
                     * Имаме ново изискване дните да започват от понеделник, не от неделя
                     * Има такъв формат ISO-8601, но за да не се налага и промяна на сървъра
                     * правя тази врътка
                     */
                    let d = [];
                    for (let i = 1; i <= 6; i++) {
                        let day = moment(i, 'd');
                        d.push({
                            id: i + "",
                            text: day.format('dddd'),
                        });
                    }

                    d.push({id: '0', text: moment(0, 'd').format('dddd')});

                    return d;
                },

                /**
                 * Генерираме брой работи за ден
                 * @private
                */
                _daysNum: function () {
                    let n = [];
                    for (let i = 1; i <= +this.jobs_limit; i++) {
                        n.push({
                            id: i + "",
                            text: i + " jobs",
                        });
                    }

                    return n;
                }
            },

            events: {
                'vue-formular.sent': function () {
                    this.loading = false;
                    this.showMessage('success', 'Your record has been edited successfully');
                },
            },

        });

    </script>
@endsection