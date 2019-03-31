var app = new Vue({
    el: '#main',
    data: {
        search: {
            total: 100, // 总共多少条
            index: 1, // 当前第几页
            status: 1, // 停用启用 0->停用 1->启用
            sort: 'create_time', // createTime->新增日期 account->名称 balance->信用额度 loginTime->最后登录
            user_name: '', // 用户姓名
        },

        data:[],
        

    },

    methods: {
        init: function(){
            var _this = this;
            var data = {
                index: this.search.index,
                status: this.search.status,
                sort: this.search.sort,
                user_name: this.search.user_name
            }

            utils.getAjax({
                url: '/api/Member/userList',
                type: 'POST',
                data: data,
                success: function(result){
                    _this.search.total = result.total;
                    _this.data = result.data;
                    console.log(result);
                }
            })
            console.log(data)
        }
    },
    mounted: function(){
        this.init();
    }
})