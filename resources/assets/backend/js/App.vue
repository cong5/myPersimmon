<template>
    <div id="app">
        <router-view></router-view>
    </div>
</template>
<style type="text/css">

</style>
<script type="text/ecmascript-6">
    export default{
        name: 'app',
        data(){
            return {
                //sessionID: this.$cookie.get('myPersimmon')
                user: JSON.parse(sessionStorage.getItem('myPersimmon'))
            }
        },
        watch: {
            '$route'(to, from) {//监听路由改变
                this.authLogin();
            }
        },
        methods: {
            authLogin: function () {
                let _this = this;
                let user = JSON.parse(sessionStorage.getItem('myPersimmon'));
                if (!user) {
                    _this.$router.push({path: 'login'});
                }
                _this.axios.post('/auth/check').then(function (response) {
                    if (response.data.auth == 'Unauthenticated') {
                        sessionStorage.removeItem('myPersimmon');
                        _this.$router.push({path: '/login'});
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            }
        },
        created: function () {
            this.authLogin();
        }
    }
</script>