var tablePage = new Page('#pageInfo', function(index){ app.query();});
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
        breaks: 0, // 反水
        amount: 0, // 总计
        data: [],
    },

    methods: {
        query: function(){
            var _this = this;

            var data = {
                user_num: this.search.username,
                game_key: this.search.game_key,
                time: this.search.time,
                status: this.search.status,
                index: tablePage.data.index,
            }

            utils.getAjax({
                url: '/api/Bet/list',
                loading: true,
                data: data,
                type: 'GET',
                success: function(json){
                    tablePage.init({total: json.total});
                    _this.money = json.money;
                    _this.handsel = json.handsel;
                    _this.breaks = json.break;
                    _this.amount = json.amount;
                    _this.data = json.data;
                }
            });

        }
    },

    mounted: function(){
        this.query();
        $('body').fadeIn('fast');

    },

    directives: {
        'time': {
            inserted: function(el){
                bindTime(el.id, function(obj){
                    app.search.time = obj.val;
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