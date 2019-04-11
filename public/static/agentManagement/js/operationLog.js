var id = utils.getURL(location.search, 'id');

if(!id){
    alert('请正确进入～');
    history.back(1);
}

var app = new Vue({
    el: '#main',
    data: {
        data: [],
        id: id,
    },

    mounted: function(){
        var _this = this;
        utils.getAjax( {
            url: '/api/Member/getOperationLog',
            type: 'GET',
            data: {id: this.id},
            success: function(result){
                _this.data = result;
            }
        });

        $('body').fadeIn('fast');
    }
})