var app = new Vue({
    el: '#main',
    data: {
        old_pwd: '',
        new_pwd: '',
        repeat_pwd: '',
    },
    methods: {
        submit: function(){

            utils.getAjax({
                url: '/api/user/changePassword',
                type: 'POST',
                data: {
                    old_pwd: this.old_pwd,
                    new_pwd: this.new_pwd,
                    repeat_pwd: this.repeat_pwd
                },
                sucess: function(){
                    window.location = '/index/login';
                }

            });

        },

        reset: function(){
            this.old_pwd = '';
            this.new_pwd = '';
            this.repeat_pwd = '';
        }
    },

    mounted: function() {
        $('body').fadeIn('fast');
    },

})