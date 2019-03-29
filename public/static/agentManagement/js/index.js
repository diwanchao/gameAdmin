var app = new Vue({
    el: '#main',
    data: {
        search: {
            total: 100, // 总共多少条
            index: 1, // 当前第几页
            state: 1, // 停用启用 0->停用 1->启用
            sort: 'createTime', // createTime->新增日期 account->名称 balance->信用额度 loginTime->最后登录
            user_name: '', // 用户姓名
        },
        

    },

    methods: {
        init: function(){
            console.log(this.search)
        }
    }
})