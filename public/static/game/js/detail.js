var game_key = utils.getURL(location.search, 'game_key');
var level = utils.getURL(location.search, 'level');
var game_type = utils.getURL(location.search, 'gameType');

var app = new Vue({

    el: '#main',
    data: {
        data: [],
        total: 0,
        money: 0,
        break: 0,
        result: 0,
    },

    methods: {
        query: function(){
            var _this = this;
            var data = {
                game_key: game_key,
                part: level,
                game_type: game_type
            };

            utils.getAjax({
                url: '/api/game/detail',
                type: 'GET',
                data: data,
                success: function(result){
                    for(var i = 0; i < result.length; i++) {
                        _this.money += Number(result[i].money);
                        _this.break += Number(result[i].break);
                        _this.result += Number(result[i].result);
                    }
                    _this.total = result.length;
                    _this.data = result;
                }
            })
        }
    },
    mounted: function(){
        this.query();
    }
})