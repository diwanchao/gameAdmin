var app = new Vue({
    el: '#main',
    data: {
        /**  左侧  **/
        user_type: '总代理',
        user_name: 'han12',
        credit_limit: '0', // 信用额度
        already_limit: '0', // 以开额度
        fast_limit: '0', // 快开额度
        occpuy: {},

        /**  右侧  **/
        game_key: 'jlk3',
        data: []
    },
    methods: {
        init: function(){
            var _this = this;
            utils.getAjax({
                url: '/api/Information/list',
                type: 'GET',
                data: {game_key: this.game_key},
                success: function(result){
                    _this.data = result.data;
                }
            })
        },
    },
    mounted: function(){
        var _this = this;
        utils.getAjax({
            url: '/api/user/creditInfo',
            type: 'GET',
            success: function(result){
                _this.user_name = result.user_name;
                _this.credit_limit = result.credit_quota;
                _this.already_limit = result.use_quota;
                _this.fast_limit = result.quick_open_quote;
                // 缺少类型（是否总代理），占成格式不对
            }
        })
        this.init();
    },
    watch: {
        game_key: function(){
            this.init();
        }
    }
})