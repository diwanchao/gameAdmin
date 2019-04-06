
var modal = new H_modal('#modal');
var app = new Vue({
    el: '#main',
    data: {
        data: [],
        search: {
            total: 0,
            index: 1,
        }
        
    },
    methods: {
        add: function(){
            $('#modal h5').html('添加公告');
            modal.show();
        },
        query: function(){
            var _this = this;
            utils.getAjax({
                url: '/api/notice/list',
                type: 'GET',
                data: {index: this.index},
                success: function(result){
                    _this.search.total = result.total;
                    _this.data = result.data;
                }
            })
        },

        del: function(item){
            var _this = this;
            if(window.confirm('是否删除当前公告？')){
                utils.getAjax({
                    url: '/api/notice/delete',
                    type: 'GET',
                    data: {id: item.id},
                    success: function(){
                        _this.query();
                    }
                });

            }
        },

    }
});

modal.on('bs-beforeSubmit', function(){
    var data = {
        content: $('#modal [name=content]').val()
    };
    
    utils.getAjax({
        url: '/api/notice/add',
        type: 'GET',
        data: data,
        success: function(){
            app.query();
            modal.hide();
        },
        alert: true,
    })
});