var id = utils.getURL(location.search, 'id');
if(!id){
    alert('请正确进入～');
    history.back(1);
}
var app = new Vue({
    el: '#main',
    data: {
        id: id, // 有id 编辑 id->被操作人的id
        create_user_name: '', 
        username: '', // 会员账号
        name: '', // 会员名称
        pwd: '', // 密码
        confirm_pwd: '', // 确认密码
        // quick: '', // 设置创建人应持有的额度（从当前登录人所持有的额度里扣除）
        // usable_quick: 1, // 当前登录人（代理）所持有的额度
    },

    methods: {
        submit: function(){
            
            var data = {
                agent_name: this.create_user_name,
                user_num: this.username,
                user_name: this.name,
                password: this.pwd,
                confirm_pwd: this.confirm_pwd,
                // quick_open_quote: this.quick,
                id: this.id
            };

            utils.getAjax({

                url: '/api/Member/addAgent',
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
        utils.getAjax({
            url: '/api/Member/editAgent',
            type: 'GET',
            data: {id: this.id},
            success: function(result){
                _this.create_user_name = result.general_name;
                _this.username = result.user_number;
                _this.name = result.user_name;
                // _this.usable_quick = result.usable_quote;
                // _this.quick = result.quick_open_quote;
            }
        })
    },

})