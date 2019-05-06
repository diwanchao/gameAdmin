var modal = new H_modal('#modal');
var search = utils.getURL(location.search, 'search');
var general_search = utils.getURL(location.search, 'general_search');
var general_name = utils.getURL(location.search, 'general_name');
var general_id = utils.getURL(location.search, 'general_id');

var app = new Vue({
    el: '#main',
    data: {
        search: {
            total: 100, // 总共多少条
            index: 1, // 当前第几页
            status: 1, // 停用启用 0->停用 1->启用
            sort: 'create_time', // createTime->新增日期 account->名称 balance->信用额度 loginTime->最后登录
            user_name: search || '', // 用户姓名
            general_search: general_search || '',
        },
        data:[],
    },

    methods: {
        init: function(){
            var _this = this;
            var data = {
                index: this.search.index,
                status: this.search.state,
                sort: this.search.sort,
                user_name: this.search.user_name,
                general_name: this.search.general_search,
            }

            utils.getAjax({
                url: '/api/Member/userList',
                loading: true,
                type: 'POST',
                data: data,
                success: function(result){
                    _this.search.total = result.total;
                    _this.data = result.data;
                    console.log(result);
                }
            })
            console.log(data)
        },

        quickLimit: function(data, status){

            if(status == 1) {
                $('#modal h5').html('提取 -- ' + data.user_name);
                $('#modal [type=button]').val('提取');
            }
            else {
                $('#modal h5').html('存入 -- ' + data.user_name);
                $('#modal [type=button]').val('存入');
            }

            utils.getAjax({
                url: '/api/Member/getQuick',
                loading: true,
                data: {type: status, id: data.id},
                success: function(result){
                    $('#modal .use_money').text(result.number);
                    $('#modal .id').val(data.id);
                    $('#modal .type').val(status);
                    $('#modal .money').val('');
                    bindValue($('#modal .money'), result.number);
                    modal.show();
                }
            })
            
        },
        changeMember: function(id, status){
            var _this = this;
            status = status == 1 ? 0 : 1;
            H_confirm('是否确认<b style="color: red">' + (status == 0 ? '封存' : '启用') + '</b>当前账号',function(){
                utils.getAjax({
                    url: '/api/Member/changeMemberStatus',
                    loading: true,
                    data: {type: status, id: id},
                    success: function(result){
                        _this.init();
                    },
                    alert: true,
                })
            });
        },
        changeBet: function(id, status){
            var _this = this;
            status = status == 1 ? 0 : 1;
            H_confirm('是否确认<b style="color: red">' + (status == 0 ? '封存' : '解封') + '</b>当前账号',function(){
                utils.getAjax({
                    url: '/api/Member/changeBet',
                    loading: true,
                    data: {type: status, id: id},
                    success: function(result){
                        _this.init();
                    },
                    alert: true,
                })
            });
        }
    },
    mounted: function(){
        this.init();
        $('body').fadeIn('fast');
    }
});


function bindValue(ele, value){
    ele.unbind();

    ele.bind('blur', function(){
        if(isNaN(this.value) || !this.value){
            alert('请输入纯数字');
            this.value = '';
        }
        else if(Number(this.value) > Number(value)){
            alert('输入值不能超过最大限额');
            this.value = '';
        }
    });

    ele.bind('click', function(e){
        e.stopPropagation();
    });
}

modal.on('bs-beforeSubmit', function(){
    var data = {
        type: $('#modal .type').val(),
        number: $('#modal .money').val(),
        id: $('#modal .id').val(),
    };
    
    utils.getAjax({
        url: '/api/Member/setQuick',
        loading: true,
        type: 'POST',
        data: data,
        alert: true,
        success: function(){
            app.init();
            modal.hide();
        },
        alert: true,
    })
});