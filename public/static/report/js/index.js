var app = new Vue({

    el: '#main',
    data: {
        selectBtn: 'select_all', //select_un_all
        startTime: '',
        endTime: '',
        game: [
            {game_name: '各省快3', game_key: 'jlk3', select: true},
            {game_name: '各省时时彩', game_key: 'ssc', select: true}
        ]
    },

    methods: {
        query: function(){
            if(!startTime || !this.endTime){
                alert('请选择时间区间!');
                return;
            }
            else if(!selectGameList.length){
                alert('请选择游戏！');
                return;
            }

            var game = [];
            for(var i = 0; i < this.game.length; i++) {
                if(this.game[i].select){
                    game.push(this.game[i].game_key);
                }
            }
            window.location = '/index/report/detail?tiem=' + this.startTime + '-' + this.endTime + '&game_list=' + game.toString();
        },

        selectAll: function(){
            for(var i = 0; i < this.game.length; i++) {
                this.game[i].select = true;
            }
        },

        selectUnAll: function(){
            for(var i = 0; i < this.game.length; i++) {
                this.game[i].select = false;
            }
        },

        // 今日
        today: function(){
            this.startTime = utils.paseDate();
            this.endTime = utils.paseDate();
            this.query();
        },
        // 昨日
        yesterday: function(){
            this.startTime = this.getYesterday();
            this.endTime = this.getYesterday();
            this.query();
        },
        // 本周
        week: function(){
            this.startTime = this.getFirstDayOfWeek();
            this.endTime = utils.paseDate();
            this.query();
        },

        getFirstDayOfWeek: function (date) {
            var weekday = date.getDay()||7; 
            date.setDate(date.getDate()-weekday+1);
            return utils.paseDate(date);
        },

        getYesterday: function(){
            var day1 = new Date();
            day1.setDate(day1.getDate() - 1);
            return utils.paseDate(day1)
        }
    },

    directives:{
        'time':{
            inserted: function(el, binding){
                console.log(this)
                bindTime(el.id, function(obj){
                    console.log(obj.val);
                })
                console.log(binding)
                
            }
        },
    },
});

function bindTime(id, callback) {
    jeDate('#' + id,{
        format: 'YYYY-MM-DD',
        donefun: callback 
    })
}