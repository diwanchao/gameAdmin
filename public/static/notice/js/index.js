
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
            $('#modal [name=id]').val('');
            modal.show();
        },
        edit: function(item){
            $('#modal h5').html('编辑公告');
            $('#modal [name=id]').val(item.id);
            modal.show();
        },
        query: function(){
            var _this = this;
            utils.getAjax({
                url: '/api/notice/list',
                type: 'GET',
                data: {index: this.search.index},
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

    },
    mounted: function(){
        this.query();
    }
});

modal.on('bs-beforeSubmit', function(){
    var content = $('#modal [name=content]').val();
    if(!content.replace(/^ +| +$/, "")){
        alert('请填写公告内容，公告内容不许为空！');
        return;
    }
    
    var data = {
        content: $('#modal [name=content]').val(),
        id: $('#modal [name=id]').val(),
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