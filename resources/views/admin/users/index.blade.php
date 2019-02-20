@extends('layouts.admin.admin')

@section('content')

    <section class="content-header">
        <h1>
            Create new user
            <small>You can create new user here</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Users</a></li>
            <li class="active">New user</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div id="app">

            <vf-form ajax
                     action="/admin/user/new"
                     method="POST"
                     v-ref:users
            >

                {{-- User email --}}
                <vf-email
                        label="User email"
                        name="user_email"
                        v-ref:user_email
                        {{--:value="user.user_email"--}}
                        placeholder="e.g. johnsmith@test.com"
                        required
                        :rules="{min:4, user_email:true}"
                >
                </vf-email>

                {{-- User password --}}
                <vf-password
                        label="User password"
                        name="user_pass"
                        {{--:value="user.user_pass"--}}
                        v-ref:user_pass
                        placeholder="New user password here"
                        :rules="{min:4, user_pass:true}"
                >
                </vf-password>

                {{-- Repeat password --}}
                <vf-password
                        label="Repeat user password"
                        name="repeat_pass"
                        v-ref:repeat_pass
                        placeholder="Repeat user password here"
                        :rules="{min:4, requiredIf:'user_pass', equivalent:'user_pass', repeat_pass:true}"
                >
                </vf-password>

                <vf-submit></vf-submit>

            </vf-form>

            <div class="table">
                <v-client-table :data="_users" :columns="headers" :options="options"></v-client-table>
            </div>

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
                headers: ['email', 'date_created'],
                users: <?= json_encode( $users ) ?>,
                options: {
                    dateColumns: ['date_created'],
                    headings: {
                        edit: 'Edit',
                        del: 'Delete'
                    },
                    templates: {
                        edit: function(row) {
                            //return `<a href='/admin/user/${row.id}/edit'><i class='glyphicon glyphicon-edit'></i></a>`
                            return "<a href='/admin/user/" + row.id + "/edit'><i class='glyphicon glyphicon-edit'></i></a>"
                        },

                        del: function (row) {
                            //return `<a href='javascript:void(0);' @click='$parent.deleteUser(${row.id})'><i class='glyphicon glyphicon-erase'></i></a>`
                            return "<a href='javascript:void(0);' @click='$parent.deleteUser(${row.id})'><i class='glyphicon glyphicon-erase'></i></a>";
                        }
                    }
                }
            },

            computed: {
                _users: function () {
                    if( _.isString(this.users) ) {
                        try {
                            return JSON.parse(this.users)
                        }
                        catch(e) {
                            return this.users
                        }
                    }

                    return this.users;
                }
            },

            events: {

                'vue-formular.sent': function (response) {
                    this.users = response.data;
                    this.loading = false;
                    this.resetForm('users')
                },
            },

            methods: {

                deleteUser: function (id) {
                    this.loading = true;
                    //this.$http.delete(`/admin/user/${id}/delete`, [])
                    this.$http.delete("/admin/user/" + id + "/delete", [])
                            .then(
                                function (response) {

                                    this.loading = false;
                                    this.users = response.data;

                                }.bind(this),

                                function (error) {
                                    this.loading = false;
                                    console.log(error, 'ERR');
                                }.bind(this)
                            );
                },
            },
        });

    </script>
@endsection