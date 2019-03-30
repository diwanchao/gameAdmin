var app = new Vue({
    el: '#main',
    data: {
        game_key: 'jlk3', // game_key
        level: [ 'A', 'B', 'C', 'D'],
        levelValue: 'A', // 分盘值
        count_down: '2019-11-11 00:00:00', // 服务器时间
        tab: 'szhs', // 0->三字和数 1->三字组合 2->二字组合 3->三连号通选 三同号通选 4->顺杂跨黑红码 5->三军
        refresh: '0', // 自动刷新
        periods: "20190324-11", // 当前期数
        timer: null, // 自动刷新时间time
        data: {
            heshu3: 10,
            heshu5: 20,
        }
    },
    methods: {
        // 更新
        refreshData: function() {
            window.clearInterval(this.timer);
            if(this.refresh != 0) {
                this.timer = window.setInterval(this.init, this.refresh)
            }
        },
        baseInit: function(){

        },
        init: function(){
            var data = {
                game_key: this.game_key,
                levelValue: this.levelValue,
                tab: this.tab,
                periods: '20190330006',
            }
            utils.getAjax({
                url: '/api/Monitoring/game',
                type: 'GET',
                data: data,
                success: function(result){
                    console.log(result);
                }
            });

            utils.getAjax({
                url: '/api/index/getNowExpect',
                type: 'GET',
                data: {
                    game_key: this.game_key,

                },
                success: function(result){
                    _this.periods = result.number;
                    
                }
    
            });
            console.log('请求加载：game_key=' + this.game_key + '; levelValue=' + this.levelValue + '; refresh=' + this.refresh + '; tab=' + this.tab)
        }
    },
    beforeCreate() {
        // ajax 赋值
        var time = new Date() * 1;
		var _this = this;
		this.count_down = utils.paseDate(time, 'yyyy-MM-dd HH:mm:ss');
		window.setInterval(function () {
			time = new Date(time) * 1 + 1000;
			_this.count_down = utils.paseDate(time, 'yyyy-MM-dd HH:mm:ss');
        }, 1000);
    },
    mounted: function(){
        this.init();
    },
    watch: {
        tab: function(){
            this.init();
        },
        levelValue: function(){
            this.init();
        },
        refresh: function(){
            this.refreshData();
        }
    },
});


