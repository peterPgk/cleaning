@extends('layouts.admin.admin')

@section('content')

    <section class="content-header">
        <h1>
            Edit user
            <small>You can edit user here</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Users</a></li>
            <li class="active">Edit user</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <div id="app">

            <vf-form
                 :action="url"
                 method="POST"
                 v-ref:step1
            >
                {{ csrf_field() }}
                {{-- User email --}}
                <vf-email
                        label="User email"
                        name="email"
                        :value="user.email"
                        v-ref:email
                        placeholder="e.g. johnsmith@test.com"
                        required
                        disabled
                        :rules="{min:4, email:true}"
                >
                </vf-email>

                {{-- User password --}}
                <vf-password
                        label="User password"
                        name="user_pass"
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

        </div>

    </section>

@endsection

@section('js')
    <script>

        let app = new Vue({

            el: '#app',

            data: {
                url: <?php echo json_encode( reset($url) ) ?>,
                user: <?php echo json_encode( $user ) ?>
            },
        });

    </script>
@endsection