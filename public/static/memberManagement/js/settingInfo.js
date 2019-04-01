// 吉林快3 基础验证
var jlk3 = [
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 10, Bmax: 10, Cmax: 10, Dmax: 5},
    {select: false, Amax: 2.15, Bmax: 1.5, Cmax: 1.0, Dmax: 0.5},
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

// 当前被操作人的id
var id = utils.getURL(location.search, 'id');

if(!id){
    alert('请正确进入～');
    history.back(1);
}

var app = new Vue({
    el: '#main',
    data: {
        userInfo: ENV.userInfo, // 登录人用户信息
        id: id, // 被操作人id
        username: '', // 被操作人用户名
        memberList: {}, // 当前登录人（代理）下管控的会员（不包括被操作人）
        memberValue: '', // 被选中管控的会员（用来引用他的数据）
        game_key: 'jlk3', // 游戏切换
        // jlk3 时时彩的基础验证信息
        setting: {
            jlk3: jlk3,
            ssc: ssc,
        },
        // 表格数据
        data: [],

        // 快速调控
        quick: {
            show: true, // 收起隐藏快速调控
            progress: 0.05, // 调高低进度
            level: 'ABCD', // 分盘
            money: '' // 金额
        }
    },
    methods:{
        init: function(){
            var ary = this.setting[this.game_key];
            var _this = this;
            utils.getAjax({
                url: '/api/Information/list',
                data: {game_key: this.game_key, me: this.memberValue},
                success: function(result){
                    
                    var data = result.data;
                    for(var i = 0; i < data.length; i++){
                        data[i] = Object.assign(data[i],ary[i] || {});
                    }
                    _this.data = data;
                }
            })
        },
        computed: function(num){
            var ary = num.toString().split('.');
            if(ary.length == 1){
                num = num + '.0';
            }
            return num;
        },

        reset: function(){
            this.memberValue = '';
            this.init();
        },


        // 两数运算 处理浮点
        

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
                    this.data[i].select = false;
                }
                else{
                    this.data[i].select = true;
                }
            }
        },

        // 不定位
        selectN1: function(){
            this.selectNumber([0,1,2,18])
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

        // 调高
        heigher: function(){
            for(var i = 0; i < this.data.length; i++) {
                if(this.data[i].select) {
                    var cur = this.data[i];
                    for(var s = 0; s < this.quick.level.length; s++) {
                        var str = this.quick.level[s];
                        cur[str] = (cur[str] * 1000 + this.quick.progress * 1000) / 1000;
                        if(cur[str] >= cur[str + 'max']){
                            cur[str] = cur[str + 'max'];
                        }
                    }
                }
            }
        },

        // 调低
        lower: function(){
            for(var i = 0; i < this.data.length; i++) {
                if(this.data[i].select) {
                    var cur = this.data[i];
                    for(var s = 0; s < this.quick.level.length; s++) {
                        var str = this.quick.level[s];
                        cur[str] = (cur[str] * 1000 - this.quick.progress * 1000) / 1000;
                        if(cur[str] <= 0){
                            cur[str] = 0;
                        }
                    }
                }
            }
        },

        // 最大
        max: function(){
            for(var i = 0; i < this.data.length; i++) {
                if(this.data[i].select) {
                    var cur = this.data[i];
                    for(var s = 0; s < this.quick.level.length; s++) {
                        var str = this.quick.level[s];
                        cur[str] = cur[str + 'max'];
                    }
                }
            }
        },

        // 最小
        min: function(){
            for(var i = 0; i < this.data.length; i++) {
                if(this.data[i].select) {
                    var cur = this.data[i];
                    for(var s = 0; s < this.quick.level.length; s++) {
                        var str = this.quick.level[s];
                        cur[str] = 0;
                    }
                }
            }
        },

        // 单号限额
        setLimit: function(){
            if(this.quick.money > 30000) {
                return alert('不可高于30000限额');
            }
            else if(this.quick.money < 2){
                return alert('不可低于2限额');
            }
            for(var i = 0; i < this.data.length; i++) {
                if(this.data[i].select) {
                    var cur = this.data[i];
                    cur.limit = this.quick.money;
                }
            }
        },

        // 单注限额
        setMax: function(){
            if(this.quick.money > 6000) {
                return alert('不可高于6000限额');
            }
            else if(this.quick.money < 2){
                return alert('不可低于2限额');
            }
            for(var i = 0; i < this.data.length; i++) {
                if(this.data[i].select) {
                    var cur = this.data[i];
                    cur.max = this.quick.money;
                }
            }
        },

        // 单注低限额
        setMin: function(){
            if(this.quick.money > 6000) {
                return alert('不可高于6000限额');
            }
            else if(this.quick.money < 2){
                return alert('不可低于2限额');
            }
            for(var i = 0; i < this.data.length; i++) {
                if(this.data[i].select) {
                    var cur = this.data[i];
                    cur.min = this.quick.money;
                }
            }
        },

        vailInput: function(item, type) {
            var max = 0;
            var min = 2;
            switch (type) {
                case 'limit': {
                    max = 30000;
                    break;
                }
                case 'max':
                case 'min': {
                    max = 6000;
                    break;
                }
            }

            if(item[type] > max){
                item[type] = max;
                alert('不可高于' + max + '限额');
                return;
            }
            else if(item[type] < min) {
                item[type] = min;
                alert('不可低于' + min + '限额');
                return;
            }
            return true;
        },

        submit: function(){
            var _this = this;

            if(confirm('是否确认修改？')) {

                var data = utils.deepCopy(_this.data);
                for(var i = 0; i < data.length; i++) {
                    var cur = data[i];
                    delete cur.Amax;
                    delete cur.Bmax;
                    delete cur.Cmax;
                    delete cur.Dmax;
                    delete cur.select;
                }

                utils.getAjax({
                    url: '/api/member/setMemberMethod',
                    type: 'POST',
                    data: {
                        id: _this.id,
                        game_key: _this.game_key,
                        list: data
                    },
                    success: function(){
                        history.back(1);
                    },
                    alert: true
                })
            }
        }

    },
    mounted: function(){
        var _this = this;
        utils.getAjax({
            url: '/api/member/memberList',
            type: 'GET',
            success: function(result){
                _this.username = result[_this.id] || '';
                delete result[_this.id];
                _this.memberList = result;
            }
        });

        
        this.init();
    },
    watch:{
        game_key: function(){
            this.init();
        }
    }
})