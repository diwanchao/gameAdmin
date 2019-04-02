var startTime = utils.getURL(location.search, 'time').split(',')[0];
var endTime = utils.getURL(location.search, 'time').split(',')[1];
var gameList = utils.getURL(location.search, 'game_list');

var app = new Vue({
    el: '#main',
    data: {
        user_type: ENV.userInfo.role_type, // 0->代理 1->总代理
        startTime: startTime,
        endTime: endTime,
        gameList: gameList,
        sum: {},
        list: {}
    },

    mounted: function(){
        var _this = this;
        utils.getAjax({
            url: '/api/Report/list',
            type: 'GET',
            data: {
                startTime: this.startTime,
                endTime: this.endTime,
                gameList: this.gameList
            },
            success: function(result){
                _this.sum = result.down_total;
                delete result.down_total;
                _this.list = result;
            }
        })
    }

})