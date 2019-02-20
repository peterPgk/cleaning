<template>
    <div class="row">
        <div class="col-xs-12 col-md-12">
            <div class="btn-container">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
    export default {
        data() {
            return {}
        },
    }
</script>

<style lang="scss">
    .btn-container {
        padding: 0;
        width: 100%;
        max-width: 100%;
        margin: 0 auto;

        button,
        [class^=btn],
        a {
            min-height: 60px;
            font-weight: 700;
            font-size: 20px;
            padding: 12px 2em;
            min-width: 112px;
            margin: 0;
            width: 100%;
            background-color: #e50914;
            border-color: transparent;
            border-radius: 0;
            color: #ffffff;


            &:focus,
            &.focus,
            &:hover,
            &:active,
            &.active,
            &:active:hover,
            &:active:focus,
            &:active.focus,
            &.active:hover,
            &.active:focus,
            &.active.focus{
                color: #fff;
                border-color: transparent;
                background: #f40612 none;
                box-shadow: 0 1px 0 rgba(0, 0, 0, .45);
                outline: none;
                text-decoration: none;
            }

            &.disabled:hover,
            &.disabled:focus,
            &.disabled.focus,
            &[disabled]:hover,
            &[disabled]:focus,
            &[disabled].focus {
                background-color: #f40612;
                border-color: transparent;
                opacity: .6;
                text-decoration: underline;
            }


        }
    }
    .field-container {
        padding: 20px;
        max-width: 440px;
        margin: 0 auto;
        margin-top:-45px;
    }
</style>