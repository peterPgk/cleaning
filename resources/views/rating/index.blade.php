<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/css/app.css">
    <title>{{ $data['title'] }}</title>

</head>
<body>
<div class="container" id="general_app" v-cloak>



    @if ($data['isRated'])

        <div class="row">
            <div class="col-xs-12">

                <h2>You have already rated this order!</h2>

            </div>
        </div>

    @else

        <h2>PLEASE RATE YOUR ORDER</h2>

        <div class="alert alert-success" role="alert" v-if="sent && success">
            Thank you, your rating has been saved.
        </div>

        <div class="alert alert-danger" role="alert" v-if="sent && error">
            Error when saving your rating. Please try again later!
        </div>

    <div class="row">
        <div class="col-xs-12">
            <rating :items="items" legend="Rate your last order:" :value="value" v-on:change="update" kind="growRotate"></rating> ( @{{ value }} )

        </div>

        <vf-form ajax
                 action="/rating/store"
                 method="POST"
                 v-ref:ratingForm
        >

            <div class="row">
                <div class="col-xs-12 col-md-6">

                    <vf-textarea
                            label="Post your comment"
                            placeholder="Want to tell something about company?"
                            name="comment"
                            v-ref:comment
                            :disabled="sent"
                            :rules="{comment:true, min:4}"
                    >
                    </vf-textarea>

                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <vf-submit v-if="!sent"></vf-submit>
                </div>

            </div>

        </vf-form>
    </div>

    @endif

</div>

<script>
    var uuid = <?= json_encode($data['uuid']);?>
</script>
<script src="/js/rating.js"></script>
</body>
</html>
