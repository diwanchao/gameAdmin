var tablePage = new Page('#pageInfo', function(index){ app.query();});

var app = new Vue({
    el: '#main',
    data:{
        game_key: 'jlk3',
        data: [],
    },

    methods: {
        query: function(){
            var _this = this;
            utils.getAjax({
                url: '/api/game/resultList',
                type: 'GET',
                data: {
                    index: tablePage.data.index,
                    game_key: this.game_key,
                },
                success: function(json){
                    tablePage.init({total: json.total});
                    _this.data = json.data;
                }
            })
        }
    },

    mounted: function(){
        this.query();
        $('body').fadeIn('fast');
    }
    ,
    watch: {
        game_key: function(){
            this.query();
        }
    }
})