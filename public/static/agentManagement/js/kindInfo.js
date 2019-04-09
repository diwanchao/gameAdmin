var id = utils.getURL(location.search, 'id');
var name = utils.getURL(location.search, 'name');
var general_name = utils.getURL(location.search, 'general_name');

if(!id){
    alert('请正确进入～');
    history.back(1);
}

var app = new Vue({
    el: '#main',
    data: {
        id: id,
        create_user_name: general_name,
        name: name, // 会员名称
        game_list: [],
        levelValue: {},
    },

    methods: {

        submit: function(){
            var game = {};
            for(var i  =0 ; i < this.game_list.length; i++) {
                if(this.game_list[i].select){
                    game[this.game_list[i].game_key] = true;
                }
            }
            var data = {
                part: this.levelValue,
                game: game,
                id: this.id
            };

            utils.getAjax({

                url: '/api/Member/setAgentGameInfo',
                type: 'POST',
                data: data,
                success: function(result){
                    history.back(1);
                },
                alert: true,
            })
        }

    },

    mounted() {
        var _this = this;
        var game_list = ENV.userInfo.game_list;
        var part_list = ENV.userInfo.dish;
        // this.create_user_name = ENV.userInfo.user_name;
        utils.getAjax({
            url: '/api/Member/addAgentGameInfo',
            type: 'GET',
            data: {id: this.id},
            success: function(result){
                for(var i = 0 ; i < game_list.length; i++) {
                    var key = game_list[i].game_key;
                    if(result.game[key]){
                        game_list[i].select = true;
                    }
                }
                _this.game_list = game_list;

                var level = {};
                for(var i = 0 ; i < part_list.length; i++) {
                    level[part_list[i]] = result.part[part_list[i]];
                }

                _this.levelValue = level;
            }
        })
    },
});