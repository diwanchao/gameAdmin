var app = new Vue({

    el: '#main',
    data: {
        selectBtn: 'select_all', //select_un_all
        selectGameList: [],
        startTime: '',
        endTime: '',
    },

    methods: {
        query: function(){
            if(!startTIme || !this.endTime){
                alert('请选择时间区间!');
                return;
            }
            else if(!selectGameList.length){
                alert('请选择游戏！');
                return;
            }

        }
    }
})