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
var app = new Vue({
    el: '#main',
    data: {
        game_key: 'jlk3',
        setting: {
            jlk3: jlk3,
            ssc: ssc,
        },
        data: []
    },
    methods:{
        init: function(){
            var data = [
                {methods: '二同号复选', A: '7', B: '7', C: '7', D: '2', limit: '1', max: '1000', min: '10'},
                {methods: '三同号复选', A: '6', B: '7', C: '7', D: '2', limit: '1', max: '1000', min: '10'}
            ];
            var ary = this.setting[this.game_key];

            for(var i = 0; i < data.length; i++){
                data[i] = Object.assign(data[i],ary[i]);
            }
            this.data = data;
        },
        computed: function(num){
            var ary = num.toString().split('.');
            if(ary.length == 1){
                num = num + '.0';
            }
            return num;
        },
    },
    mounted: function(){
        this.init();
    }
})