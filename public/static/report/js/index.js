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
            if(!this.startTime || !this.endTime){
                alert('请选择时间区间!');
                return;
            }
            var game = [];
            for(var i = 0; i < this.game.length; i++) {
                if(this.game[i].select){
                    game.push(this.game[i].game_key);
                }
            }
            if(!game.length){
                alert('请选择游戏！');
                return;
            }
            window.location = '/index/report/detail?time=' + this.startTime + ',' + this.endTime + '&game_list=' + game.toString();
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

        getFirstDayOfWeek: function () {
            var date = new Date();
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
        'stime':{
            inserted: function(el, binding){
                bindTime(el.id, function(obj){
                    app.startTime = obj.val;
                });
            }
        },
        'etime':{
            inserted: function(el, binding){
                bindTime(el.id, function(obj){
                    app.endTime = obj.val;
                });
            }
        },
    },

    watch: {
        selectBtn: function(val){
            if(val == 'select_all'){
                this.selectAll();
            }
            else {
                this.selectUnAll();
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