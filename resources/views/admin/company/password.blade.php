@extends('layouts.admin.admin')

@section( 'content' )

    <section class="content-header">
        <h1>
            Change password
            <small>You can change your password here</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Company</a></li>
            <li class="active">Password</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div id="app">

            <notification :show="alertData.show" placement="top" duration="3000" :msg="alertData.msg" :type="alertData.type" width="400px" dismissable>
            </notification>

            <vf-form ajax
                     action="/admin/company/update-password"
                     method="POST"
                     v-ref:password_edit
            >
                <h3>Change password</h3>

                {{-- Old password --}}
                <vf-password
                        label="Old password"
                        name="old_pass"
                        v-ref:old_pass
                        placeholder="Your old password here"
                        :rules="{min:4, old_pass:true}"
                >
                </vf-password>

                {{-- New password --}}
                <vf-password
                        label="New password"
                        name="new_pass"
                        v-ref:new_pass
                        placeholder="Your new password here"
                        :rules="{min:4, new_pass:true, requiredIf:'old_pass'}"
                >
                </vf-password>

                <vf-password
                        label="Repeat new password"
                        name="repeat_pass"
                        v-ref:repeat_pass
                        placeholder="Repeat new password here"
                        :rules="{min:4, requiredIf:'new_pass', equivalent:'new_pass', repeat_pass:true}"
                >
                </vf-password>


                <vf-submit></vf-submit>

            </vf-form>

            <div class="spinner" v-if="loading">
                <loader :loading="loading" :color="color" :size="loaderSize" class="text-center"></loader>
            </div>

        </div>

    </section>
    <!-- /.content -->

@endsection

@section('js')
    <script>

        let app = new Vue({
            el: 'body',

            events: {

                'vue-formular.sent': function () {

                    this.resetForm('password_edit');
                    this.loading = false;
                    this.showMessage('success', 'Your data has been updated successfully');
                },

            }
        });

    </script>
@endsection