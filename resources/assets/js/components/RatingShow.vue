<template>

    <div>
        <rating v-for="rating in ratings" :rating="rating"></rating>

    </div>

</template>

<script type="text/babel">
    export default {

        components: {
            'rating': {
                template:
                `
                        <div class="panel panel-white post panel-shadow">
                            <div class="post-heading clearfix">
                                <div class="pull-left meta">
                                    <div class="title h5">
                                        <b>{{ rating.email }}</b> rated {{{ stars }}}
                                    </div>
                                    <h6 class="text-muted time">{{ rating.date_created }}</h6>
                                </div>
                            </div>
                            <div class="post-description">
                                <p>{{ rating.comment }}</p>
                                <hr>
                                <p>{{ rating.answer }}</p>
                            </div>
                        </div>

                `,
                props: {
                    rating: {
                        type: Object,
                        required: true,
                        'default': () => {}
                    }
                },

                computed: {
                    stars () {
                        return `<span>${_.repeat('<i class="fa fa-star" aria-hidden="true"></i>', this.rating.rating)}</span>`;
                    }
                }
            }
        },

        data() {
            return {}
        },

        props: {

            company: {
                type: Object,
                required: true,
                'default': () => {}
            }
        },

        computed: {
            ratings () {
                return this.company.ratings;
            }
        }
    }
</script>

<style scoped>
    .panel {
        box-shadow: 0 0 5px;
        padding: 10px;
    }
    /*.post .post-heading .meta .title a {*/
        /*color: black;*/
    /*}*/
    /*.post .post-heading .meta .title a:hover {*/
        /*color: #aaaaaa;*/
    /*}*/
    .panel .time {
        margin-top: 8px;
        color: #999;
    }
    /*.post .post-image .image {*/
        /*width: 100%;*/
        /*height: auto;*/
    /*}*/
    /*.post .post-description {*/
        /*padding: 15px;*/
    /*}*/
    /*.post .post-description p {*/
        /*font-size: 14px;*/
    /*}*/
    /*.post .post-description .stats {*/
        /*margin-top: 20px;*/
    /*}*/
    /*.post .post-description .stats .stat-item {*/
        /*display: inline-block;*/
        /*margin-right: 15px;*/
    /*}*/
    /*.post .post-description .stats .stat-item .icon {*/
        /*margin-right: 8px;*/
    /*}*/
    /*.post .post-footer {*/
        /*border-top: 1px solid #ddd;*/
        /*padding: 15px;*/
    /*}*/
    /*.post .post-footer .input-group-addon a {*/
        /*color: #454545;*/
    /*}*/
    /*.post .post-footer .comments-list {*/
        /*padding: 0;*/
        /*margin-top: 20px;*/
        /*list-style-type: none;*/
    /*}*/
    /*.post .post-footer .comments-list .comment {*/
        /*display: block;*/
        /*width: 100%;*/
        /*margin: 20px 0;*/
    /*}*/
    /*.post .post-footer .comments-list .comment .avatar {*/
        /*width: 35px;*/
        /*height: 35px;*/
    /*}*/
    /*.post .post-footer .comments-list .comment .comment-heading {*/
        /*display: block;*/
        /*width: 100%;*/
    /*}*/
    /*.post .post-footer .comments-list .comment .comment-heading .user {*/
        /*font-size: 14px;*/
        /*font-weight: bold;*/
        /*display: inline;*/
        /*margin-top: 0;*/
        /*margin-right: 10px;*/
    /*}*/
    /*.post .post-footer .comments-list .comment .comment-heading .time {*/
        /*font-size: 12px;*/
        /*color: #aaa;*/
        /*margin-top: 0;*/
        /*display: inline;*/
    /*}*/
    /*.post .post-footer .comments-list .comment .comment-body {*/
        /*margin-left: 50px;*/
    /*}*/
    /*.post .post-footer .comments-list .comment > .comments-list {*/
        /*margin-left: 50px;*/
    /*}*/
</style>