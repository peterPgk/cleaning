<template>
    <div class="form">
    <vf-form ajax
             action="/admin/user/new"
             method="POST"
             v-ref:step1
    >

        <vf-text
                label="User name"
                name="username"
                v-ref:username
                placeholder="e.g. Johnsmith"
                required
                :rules="{min:4, username:true}"
        >
        </vf-text>

        <vf-email
                label="User email"
                name="user_email"
                v-ref:user_email
                placeholder="e.g. johnsmith@test.com"
                required
                :rules="{min:4, user_email:true}"
        >
        </vf-email>

        <vf-password
                label="User password"
                name="user_pass"
                v-ref:user_pass
                placeholder="New user password here"
                :rules="{min:4, user_pass:true}"
        >
        </vf-password>

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
</template>

<script>

    export default {
        data () {
            return {}
        }
    }
</script>

<style scoped>

</style>