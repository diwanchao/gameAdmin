var app = new Vue({
    el: '#main',
    data: {
        old_pwd: '',
        new_pwd: '',
        repeat_pwd: '',
    },
    methods: {
        submit: function(){
            var data = utils.deepCopy(this.data);

            utils.getAjax({
                url: '/api/user/changePassword',
                type: 'POST',
                data: data,
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
    }

})