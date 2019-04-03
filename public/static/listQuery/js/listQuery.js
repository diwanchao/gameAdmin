var app = new Vue({
    el: '#main',
    data: {
        search: {
            username: '',
            game_key: '',
            time: utils.paseDate(),
            status: '',
        },

        

        data: [],
    },

    methods: {
        query: function(){
            var _this = this;

            var data = {
                user_num: this.username,
                game_key: this.game_key,
                time: this.time,
                status: this.status
            }

            utils.getAjax({
                url: '/api/Bet/list',
                data: data,
                type: 'GET',
                success: function(result){
                    _this.data = result;
                }

            })

        }
    },

    directives: {
        'time': {
            inserted: function(el){
                bindTime(el.id, function(obj){
                    app.time = obj.val;
                });
            }
        }
    }

});

function bindTime(id, callback) {
    jeDate('#' + id,{
        format: 'YYYY-MM-DD',
        donefun: callback 
    })
}