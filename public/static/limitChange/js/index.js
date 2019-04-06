var tablePage = new Page('#pageInfo', function(index){ app.query();});

var app = new Vue({
    el: '#main',
    data: {
        search: {
            type: 0,
            user_name: ''
        },
        data: [],
    },

    methods: {
        query: function(){
            var _this = this;
            var data = {
                index: tablePage.data.index,
                type: this.search.type,
                user_name: this.search.user_name
            };
            utils.getAjax({
                data: data,
                url: '/api/user/limitChange',
                type: 'GET',
                success: function(json){
                    tablePage.init({total: json.total});
                    _this.data = json.data;
                }
            });
        }
    },

    mounted: function(){
        this.query();
    },
})