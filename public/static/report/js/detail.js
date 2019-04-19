var startTime = utils.getURL(location.search, 'time').split(',')[0];
var endTime = utils.getURL(location.search, 'time').split(',')[1];
var gameList = utils.getURL(location.search, 'game_list').split(',');
var id = utils.getURL(location.search, 'id');
var user_type = utils.getURL(location.search, 'user_type') < 0 ? '0' : utils.getURL(location.search, 'user_type');

var getName = function(k){
    switch (k) {
        case 'jlk3': {
            return '吉林快3';
        }
        case 'ssc': {
            return '重庆时时彩';
        }
    }
}

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
    methods: {
        changeTwoDecimal_f: function(x){
            var f_x = parseFloat(x);
            if (isNaN(f_x))
            {
               alert('function:changeTwoDecimal->parameter error');
               return false;
            }
            f_x = Math.round(f_x*100)/100;
            var s_x = f_x.toString();
            var pos_decimal = s_x.indexOf('.');
            if (pos_decimal < 0)
            {
               pos_decimal = s_x.length;
               s_x += '.';
            }
            while (s_x.length <= pos_decimal + 2)
            {
               s_x += '0';
            }
            return s_x;
         }
    },

    mounted: function(){
        var _this = this;
        utils.getAjax({
            url: '/api/Report/list',
            type: 'GET',
            data: {
                id: this.id,
                start_time: this.startTime + ' 00:00:00',
                end_time: this.endTime + ' 23:59:59',
                game_key: this.game_key
            },
            success: function(result){
                var ary = [];
                for(var k in result) {
                    var total = {
                        open_count: 0,
                        not_open_count: 0,
                        bet_amount: 0,
                        sum_loss: 0,
                        up_proportion: 0,
                        self_proportion: 0,
                        down_proportion: 0,
                        self_back: 0,
                        down_back: 0,
                        rebate: 0,
                        up_profit: 0,
                        down_profit: 0,
                        self_profit: 0
                    }
                    for(var j in result[k]){
                        total.open_count += Number(result[k][j].open_count);
                        total.not_open_count += Number(result[k][j].not_open_count);
                        total.bet_amount += Number(result[k][j].bet_amount);
                        total.sum_loss += Number(result[k][j].sum_loss);
                        total.up_proportion += Number(result[k][j].up_proportion);
                        total.self_proportion += Number(result[k][j].self_proportion);
                        total.down_proportion += Number(result[k][j].down_proportion);
                        total.self_back += Number(result[k][j].self_back);
                        total.down_back += Number(result[k][j].down_back);
                        total.rebate += Number(result[k][j].rebate);
                        total.up_profit += Number(result[k][j].up_profit);
                        total.down_profit += Number(result[k][j].down_profit);
                        total.self_profit += Number(result[k][j].self_profit);
                    }
                    ary.push({
                        game_key: k,
                        game_name: getName(k),
                        list: result[k],
                        total: total
                    })
                }
                _this.list = ary;
            }
        });
        utils.getAjax({
            url: '/api/Report/head',
            type: 'POST',
            data: {
                id: this.id,
                start_time: this.startTime + ' 00:00:00',
                end_time: this.endTime + ' 23:59:59',
                game_key: this.game_key
            },
            success: function(result){
                _this.head_list = result;
            }
        })
        $('body').fadeIn('fast');
    }

})