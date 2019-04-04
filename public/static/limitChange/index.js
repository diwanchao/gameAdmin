var tablePage = new Page('#pageInfo', function(index){ app.search();});

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
        search: function(){
            var _this = this;
            var data = {
                index: tablePage.data.index,
                type: this.search.type,
                user_name: this.search.user_name
            };
            utils.getAjax({
                data: data,
                url: '/',
                type: 'GET',
                success: function(result){
                    _this.data = result
                }
            });
        }
    },

    mounted: function(){
        search();
    }

})