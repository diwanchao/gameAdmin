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
        levelValue: { // 分盘
            'A': 0,
            'B': 1,
            'C': 0,
            'D': 1
        },
        credit: 100,
        usable_credit: 101,
        quick: 1000,
        usable_quick: 2000,
        game_list: [
            {game_key: 'jlk3', name: '吉林快3', select: 1},
            {game_key: 'ssc', name: '重庆时时彩', select: 0}
        ],
    },
    methods: {
        // 验证
        verificationUsername: function(){
            this.usernameStatus = 1;
        }
    },
    mounted: function(){

        console.log(ENV)

        // utils.getAjax({
        //     url: '/api/user/getuserinfo',
        //     type: 'GET',
        //     success: function(result){

        //     }
        // })
        // if(!this.id){
        //     this.agentValue = Object.keys(this.agentList)[0];
        // }
    },


})