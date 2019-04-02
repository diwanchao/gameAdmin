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
        list: {"jlk3":{"open_count":1,"not_open_count":1,"bet_amount":0,"sum_loss":1,"up_proportion":1,"0":"self_proportion","down_proportion":2,"all_agent_break":1,"agent_break":20,"rebate":"返利","up_profit":100,"down_profit":1,"self_profit":9999},"ssc":{"open_count":1,"not_open_count":1,"bet_amount":0,"sum_loss":1,"up_proportion":1,"0":"self_proportion","down_proportion":2,"all_agent_break":1,"agent_break":20,"rebate":"返利","up_profit":100,"down_profit":1,"self_profit":9999},"down_total":{"open_count":1,"not_open_count":1,"bet_amount":0,"sum_loss":1,"up_proportion":1,"0":"self_proportion","down_proportion":2,"all_agent_break":1,"agent_break":20,"rebate":"返利","up_profit":100,"down_profit":1,"self_profit":9999}}
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