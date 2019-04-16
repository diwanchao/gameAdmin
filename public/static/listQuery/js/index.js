// 会从报表传来开始时间 结束时间 如果有 搜索报表的时间区间
var timeStart = utils.getURL(location.search, 'time_start');
var timeEnd = utils.getURL(location.search, 'time_end');
var gameKey = utils.getURL(location.search, 'game_key');
var username = utils.getURL(location.search, 'user_name');
var status = utils.getURL(location.search, 'status');
var tablePage = new Page('#pageInfo', function(index){ app.query();});
var app = new Vue({
    el: '#main',
    data: {
        search: {
            username: username || '',
            game_key: gameKey || '',
            time_start: timeStart || utils.paseDate(),
            time_end: timeEnd || utils.paseDate(),
            status: status || '',
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

            if(!this.search.time_start){
                return alert('请选择开始时间！');
            }
            else if(!this.search.time_end){
                this.search.time_end = utils.paseDate();
            }

            var data = {
                user_num: this.search.username,
                game_key: this.search.game_key,
                time_start: this.search.time_start + ' 00:00:00',
                time_end: this.search.time_end + ' 23:59:59',
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
        'timestart': {
            inserted: function(el){
                bindTime(el.id, function(obj){
                    app.search.time_start = obj.val;
                });
            }
        },
        'timeend': {
            inserted: function(el){
                bindTime(el.id, function(obj){
                    app.search.time_end = obj.val;
                });
            }
        },
    }

});

function bindTime(id, callback) {
    jeDate('#' + id,{
        format: 'YYYY-MM-DD',
        donefun: callback 
    })
}