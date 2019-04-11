var d = {
    el: '#main',
    data: {
        create_user_name: '', 
        username: '', // 会员账号
        usernameStatus: 0, // 0=》未检测 -》检测通过
        name: '', // 会员名称
        pwd: '', // 密码
        confirm_pwd: '', // 确认密码
        levelValue: {},
        quick: '', // 设置创建人应持有的额度（从当前登录人所持有的额度里扣除）
        usable_quick: 1, // 当前登录人（代理）所持有的额度
        game_list: [],

        //
        accountList: {
            jlk3: {agent: 100, member: 0},
            ssc: {agent: 100, member: 0},
        }
    },

    methods: {
        // 验证
        verificationUsername: function(){
            var _this = this;
            utils.getAjax({
                url: '/api/Member/checkUserName',
                loading: true,
                data: {
                    user_name: this.username
                },
                type: 'POST',
                success: function(){
                    _this.usernameStatus = 1;
                }
            })
        },

        submit: function(){
            if(this.usernameStatus == 0){
                alert('请检测账号是否重复！');
                return;
            }
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
                accountList: this.accountList
            };

            utils.getAjax({

                url: '/api/member/addGeneralAgent',
                loading: true,
                type: 'POST',
                data: data,
                success: function(result){
                    history.back(1);
                },
                alert: true,
            })
        },


    },


    mounted: function(){
        var _this = this;

        this.create_user_name = ENV.userInfo.user_name;
        this.usable_quick = ENV.userInfo.quick_open_quote;

        var level = {};
        for(var i = 0; i < ENV.userInfo.dish.length; i++) {
            level[ENV.userInfo.dish[i]] = true;
        }
        this.levelValue = level;
        
        for(var i = 0; i < ENV.userInfo.game_list.length; i++){
            ENV.userInfo.game_list[i].select = true;
        }
        this.game_list = ENV.userInfo.game_list;

        $('body').fadeIn('fast');
    },

    watch: {
        'accountList.jlk3.agent': function(val){
            this.accountList.jlk3.member = 100 - val;
        },
        'accountList.jlk3.member': function(val){
            this.accountList.jlk3.agent = 100 - val;
        },
        'accountList.ssc.agent': function(val){
            this.accountList.ssc.member = 100 - val;
        },
        'accountList.ssc.member': function(val){
            this.accountList.ssc.agent = 100 - val;
        },
    }

};


utils.getAjax({
    url: '/api/Member/getProportion',
    type: 'GET',
    data: {type: 0},
    success: function(result){
        for(var k in result) {
            if(result.hasOwnProperty(k)){
                d.data.accountList[k].agent = result[k];
            }
        }
        window.app = new Vue(d);
    }
})