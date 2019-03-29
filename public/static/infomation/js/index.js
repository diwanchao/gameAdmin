var app = new Vue({
    el: '#main',
    data: {
        /**  左侧  **/
        user_type: '总代理',
        user_name: 'han12',
        credit_limit: '0', // 信用额度
        already_limit: '0', // 以开额度
        fast_limit: '0', // 快开额度
        occpuy: [ // 占成
            {
                name: '吉林快3',
                rate: '0'
            },
            {
                name: '重庆时时彩',
                rate: '87'
            },
        ],

        /**  右侧  **/
        game_key: 'jlk3',
        data: [
            {
                name: '二同号复选',
                A: '10',
                B: '10',
                C: '10',
                D: '10',
                single: '300000',
                single_note: '600000',
            },
            {
                name: '二不同号',
                A: '10',
                B: '10',
                C: '10',
                D: '10',
                single: '300000',
                single_note: '600000',
            }
        ]
    }
})