var id = utils.getURL(location.search, 'id');
var name = utils.getURL(location.search, 'name');

if(!id){
    alert('请正确进入～');
    history.back(1);
}

var app = new Vue({
    el: '#main',
    data: {
        id: id,
        name: name, // 会员名称
        
        accountList: {
            jlk3: {agent: 0, member: 0},
            ssc: {agent: 0, member: 0},
        },
        jlk3Total: 0,
        sscTotal: 0
    },

    methods: {

        submit: function(){

            var data = {
                id: this.id,
                accountList: this.accountList,
            }

            utils.getAjax({
                url: '/api/Member/setAccount',
                loading: true,
                type: 'POST',
                data: data,
                success: function(result){
                    history.back(1);
                },
                alert: true,
            });
        }

    },

    mounted() {
        var _this = this;
        utils.getAjax({
            url: '/api/Member/getAccountList',
            type: 'GET',
            data: {id: this.id},
            success: function(result){
                _this.accountList = result;
                _this.jlk3Total = Number(result.jlk3.agent) + Number(result.jlk3.member);
                _this.sscTotal = Number(result.ssc.agent) + Number(result.ssc.member)
            }
        });
        $('body').fadeIn('fast');
    },

    watch: {
        'accountList.jlk3.agent': function(val){
            this.accountList.jlk3.member = this.jlk3Total - val;
        },
        'accountList.jlk3.member': function(val){
            this.accountList.jlk3.agent = this.jlk3Total - val;
        },
        'accountList.ssc.agent': function(val){
            this.accountList.ssc.member = this.sscTotal - val;
        },
        'accountList.ssc.member': function(val){
            this.accountList.ssc.agent = this.sscTotal - val;
        },
    }

});