var jlk3 = [
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 2.15, Bmax: 0.5, Cmax: 1.0, Dmax: 0.5},
    {select: false, Amax: 2.15, Bmax: 1.5, Cmax: 1.0, Dmax: 0.5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 1.0, Bmax: 1.0, Cmax: 1.0, Dmax: 1.0},
    {select: false, Amax: 1.0, Bmax: 1.0, Cmax: 1.0, Dmax: 1.0},
    {select: false, Amax: 1.0, Bmax: 1.0, Cmax: 1.0, Dmax: 1.0},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 4, Bmax: 4, Cmax: 4, Dmax: 4},
    {select: false, Amax: 4, Bmax: 4, Cmax: 4, Dmax: 4},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 6, Bmax: 6, Cmax: 0.5, Dmax: 0.5},
];


var ssc = [];

var id = utils.getURL(location.search, 'id');

if(!id){
    alert('请正确进入～');
    history.back(1);
}

var app = new Vue({
    el: '#main',
    data: {
        userInfo: ENV.userInfo,
        id: id,
        memberList: {},
        memberValue: '',
        game_key: 'jlk3',
        setting: {
            jlk3: jlk3,
            ssc: ssc,
        },
        data: [],

        // 快速调控
        quick: {
            show: false,
        }
    },
    methods:{
        init: function(){
            var ary = this.setting[this.game_key];
            var _this = this;
            utils.getAjax({
                url: '/api/Information/list',
                data: {game_key: this.game_key, id: this.memberValue},
                success: function(result){
                    var data = result.data;
                    for(var i = 0; i < data.length; i++){
                        data[i] = Object.assign(data[i],ary[i]);
                    }
                    _this.data = data;
                }
            })
            // var data = [
            //     {methods: '二同号复选', A: '7', B: '7', C: '7', D: '2', limit: '1', max: '1000', min: '10'},
            //     {methods: '三同号复选', A: '6', B: '7', C: '7', D: '2', limit: '1', max: '1000', min: '10'}
            // ];
        },
        computed: function(num){
            var ary = num.toString().split('.');
            if(ary.length == 1){
                num = num + '.0';
            }
            return num;
        },


        // 快速调控

        // 全选
        selectAll: function(){
            for(var i = 0; i < this.data.length; i++) {
                this.data[i].select = true;
            }
        },
        selectUnAll: function(){
            for(var i = 0; i < this.data.length; i++) {
                this.data[i].select = false;
            }
        },
        // 反选
        selectOver: function(){
            for(var i = 0; i < this.data.length; i++) {
                this.data[i].select = !this.data[i].select;
            }
        },

        selectNumber: function(arr){
            for(var i = 0 ; i < this.data.length; i++) {
                if($.inArray(i, arr) == -1) {
                    this.data.select = false;
                }
                else{
                    this.data.select = true;
                }
            }
        },

        // 不定位
        selectN1: function(){
            selectNumber([0,1,2,18])
        },

        // 定位
        selectN2: function(){
            this.selectNumber([3,4,5,6,7,8,9,19,20]);
        },
        // 双面
        selectN3: function(){
            this.selectNumber([10]);
        },
        // 和数
        selectN4: function(){
            this.selectNumber([11]);
        },
        // 尾数
        selectN5: function(){
            this.selectNumber([12]);
        },
        // 组合
        selectN6: function(){
            this.selectNumber([13,15,16,17]);
        },
        // 跨度
        selectN7: function(){
            this.selectNumber([14]);
        },

    },
    mounted: function(){
        var _this = this;
        utils.getAjax({
            url: '/api/member/memberList',
            type: 'GET',
            success: function(result){
                delete result[_this.id];
                _this.memberList = result;
            }
        });

        
        this.init();
    }
})