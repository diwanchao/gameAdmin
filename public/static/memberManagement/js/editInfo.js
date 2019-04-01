var id = utils.getURL(location.search, 'id');

if(!id){
    alert('请正确进入～');
    history.back(1);
}

// 需用修改人的ID
var app = new Vue({
    el: '#main',
    data: {
        id: id, // 有id 编辑 id->被操作人的id
        create_user_name: '', 
        username: '', // 会员账号
        usernameStatus: 0, // 0=》未检测 -》检测通过
        name: '', // 会员名称
        pwd: '', // 密码
        confirm_pwd: '', // 确认密码
        levelValue: {},
        quick: '',
        usable_quick: 1,
        game_list: [],
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
                agent_name: this.create_user_name,
                user_num: this.username,
                user_name: this.name,
                password: this.pwd,
                confirm_pwd: this.confirm_pwd,
                quick_open_quote: this.quick,
                part: this.levelValue,
                game: game,
                id: this.id
            };

            utils.getAjax({

                url: '/api/Member/addUser',
                type: 'POST',
                data: data,
                success: function(result){
                    history.back(1);
                },
                alert: true,
            })
        }
    },
    mounted: function(){
        var _this = this;
        var game_list = ENV.userInfo.game_list;
        var part_list = ENV.userInfo.dish;
        utils.getAjax({
            url: '/api/Member/editUser',
            type: 'GET',
            data: {id: this.id},
            success: function(result){
                _this.create_user_name = result.agent_name;
                _this.username = result.user_num;
                _this.name = result.user_name;
                _this.usable_quick = result.usable_quote;
                _this.quick = result.quick_open_quote;

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


})