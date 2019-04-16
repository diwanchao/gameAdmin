var startTime = utils.getURL(location.search, 'time').split(',')[0];
var endTime = utils.getURL(location.search, 'time').split(',')[1];
var gameList = utils.getURL(location.search, 'game_list').split(',');
var id = utils.getURL(location.search, 'id');
var user_type = utils.getURL(location.search, 'user_type')

var app = new Vue({
    el: '#main',
    data: {
        id: id,
        user_type: user_type || ENV.userInfo.role_type, // 0->代理 1->总代理
        startTime: startTime,
        endTime: endTime,
        game_key: gameList,
        // 头部信息
        head_list: [],
        sum: {},
        list: {}
    },

    mounted: function(){
        var _this = this;
        // utils.getAjax({
        //     url: '/api/Report/list',
        //     type: 'GET',
        //     data: {
        //         startTime: this.startTime,
        //         endTime: this.endTime,
        //         gameList: this.gameList
        //     },
        //     success: function(result){
        //         _this.sum = result.down_total;
        //         delete result.down_total;
        //         _this.list = result;
        //     }
        // });
        utils.getAjax({
            url: '/api/Report/head',
            type: 'POST',
            data: {
                id: this.id,
                start_time: this.startTime,
                end_time: this.endTime,
                game_key: this.game_key
            },
            success: function(result){
                _this.head_list = result;
            }
        })
        $('body').fadeIn('fast');
    }

})