var game_key = utils.getURL(location.search, 'game_key');
var level = utils.getURL(location.search, 'level');
var game_type = utils.getURL(location.search, 'gameType');

var app = new Vue({

    el: '#main',
    data: {
        data: [],
        total: 0,
        money: 0,
        break2: 0,
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
                    _this.total = result.amount;
                    _this.money = result.money;
                    _this.break2 = result.break;
                    _this.result = result.result;
                    _this.data = result.data || [];
                }
            })
        }
    },
    mounted: function(){
        this.query();
    }
})