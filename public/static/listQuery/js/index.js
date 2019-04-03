var app = new Vue({
    el: '#main',
    data: {
        search: {
            username: '',
            game_key: '',
            time: utils.paseDate(),
            status: '',
        },
        money: 0, //金额
        handsel: 0, // 派彩
        _break: 0, // 反水
        amount: 0, // 总计
        data: [],
    },

    methods: {
        query: function(){
            var _this = this;

            var data = {
                user_num: this.username,
                game_key: this.game_key,
                time: this.time,
                status: this.status
            }

            utils.getAjax({
                url: '/api/Bet/list',
                data: data,
                type: 'GET',
                success: function(result){
                    for(var i = 0; i < result.length; i++) {
                        _this.money += Number(result[i].money);
                        _this.handsel += Number(result[i].handsel);
                        _this._break += Number(result[i].break);
                        _this.amount += Number(result[i].amount);
                    }
                    _this.data = result;
                }

            })

        }
    },

    mounted: function(){
        this.query();

    },

    directives: {
        'time': {
            inserted: function(el){
                bindTime(el.id, function(obj){
                    app.time = obj.val;
                });
            }
        }
    }

});

function bindTime(id, callback) {
    jeDate('#' + id,{
        format: 'YYYY-MM-DD',
        donefun: callback 
    })
}