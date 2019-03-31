// 需用修改人的ID
var app = new Vue({
    el: '#main',
    data: {
        // id: null, // 有id 编辑 id->被操作人的id
        // agentList: {
        //     '1213': 'han213',
        //     '3425': 'han433'
        // },
        // agentValue: '',
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
        // 验证
        verificationUsername: function(){
            utils.getAjax({
                url: '/api/Member/checkUserName',
                data: {
                    user_name: this.user_name
                },
                type: 'POST',
                success: function(){
                    this.usernameStatus = 1;
                }
            })
        },

        submit: function(){
            if(usernameStatus == 0){
                alert('请检测账号是否重复！');
                return;
            }
            var game = {};
            for(var i  =0 ; i < this.game_list.length; i++) {
                if(this.game_list[i].select){
                    game[this.game_list[i].game_key] = this.game_list[i];
                }
            }
            var data = {
                agent_name: this.create_user_name,
                user_num: this.username,
                user_name: this.user_name,
                password: this.pwd,
                confirm_pwd: this.confirm_pwd,
                quick_open_quote: this.quick,
                part: this.levelValue,
                game: game
            };

            utils.getAjax({

                url: '/Member/addUser',
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
        this.create_user_name = ENV.userInfo.user_name;
        this.usable_quick = ENV.userInfo.quick_open_quote;

        var level = {};
        for(var i = 0; i < ENV.userInfo.dish.length; i++) {
            level[ENV.userInfo.dish[i]] = 0
        }
        this.levelValue = level;
        
        this.game_list = ENV.userInfo.game_list;
    },


})