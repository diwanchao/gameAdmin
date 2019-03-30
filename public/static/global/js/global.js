var reg = {
    username: /^[a-zA-z]\w{3,15}$/,
    phone: /^1[345678]\d{9}$/,
    pwd: /^(\w|\.){8,16}$/,
    email: /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/
}
var utils = {
	/**
	 * 获取cookie
	 * @param {String} cookie名
	 * @returns {String/null} 返回取到的cooke
	 */
	getCookie: function (name) {
		var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");

		if (arr = document.cookie.match(reg))

			return unescape(arr[2]);
		else
			return null;
	},

	/**
	 * 删除cookie
	 * @param {String} cookie名
	 */
	delCookie: function (name) {
		var exp = new Date();
		exp.setTime(exp.getTime() - 1);
		var cval = utils.getCookie(name);
		if (cval != null)
			document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
	},

	/**
	 * 设置cookie
	 * @param {String} cookie名
	 * @param {String} cookie内容
	 */
	setCookie: function (name, value) {
		var Days = 1;
		var exp = new Date();
		exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
		document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString() + 'path=/';
	},

	/**
	 * 解析时间
	 * @param {Date/String} paseStr 需要解析的时间串
	 * @param {String} format 返回格式
	 * @return {String} 返回解析后的时间格式
	 */
	paseDate: function (paseStr, format) {

		if (paseStr === '0001-01-01T00:00:00') {
			return '-';
		}

		format = format || 'yyyy-MM-dd';
		var flag = /\d{2}T\d{2}/.test(paseStr); // 是否为UTC格式的时间
		var timeOffset = new Date().getTimezoneOffset() * 60000; // 获取本地时间与格林威治时间差（毫秒）;
		var date;

		try {
			if (flag) {
				// 如果UTC时间 没有Z后缀 加上Z后缀
				!/Z$/.test(paseStr) ? paseStr += 'Z' : null;
				date = new Date(new Date(paseStr) * 1 + timeOffset);
			} else {
				date = paseStr ? new Date(paseStr) : new Date();
			}

			var dict = {
				"yyyy": date.getFullYear(),
				"M": date.getMonth() + 1,
				"d": date.getDate(),
				"H": date.getHours(),
				"m": date.getMinutes(),
				"s": date.getSeconds(),
				"l": date.getMilliseconds(),
				"MM": ("" + (date.getMonth() + 101)).substr(1),
				"dd": ("" + (date.getDate() + 100)).substr(1),
				"HH": ("" + (date.getHours() + 100)).substr(1),
				"mm": ("" + (date.getMinutes() + 100)).substr(1),
				"ss": ("" + (date.getSeconds() + 100)).substr(1),
				"ll": ("" + (date.getMilliseconds() + 1000)).substr(1),
			};
			return format.replace(/(yyyy|MM?|dd?|HH?|ss?|mm?|ll?)/g, function () {
				return dict[arguments[0]];
			});
		} catch (e) {
			alert(true, '需要正确的时间格式,当前时间格式为' + paseStr, '解析时间失败');
		}
	},

	/**
	 * 倒计时 根据毫秒算出倒计时
	 * @param {Number/String} 毫秒数
	 * @returns {String} 返回剩余时间
	 */
	remainingTime: function (num) {
		num = num || 0;
		num = Math.round(num);
		var h,
			m,
			s,
			count;
		h = parseInt(num / (60 * 60));
		m = parseInt(num / 60) - (h * 60);
		s = parseInt(num) - (h * 60 * 60) - (m * 60);
		return (h.toString().length == 1 ? '0' + h : h) + ':' + (m.toString().length == 1 ? '0' + m : m) + ':' + (s.toString().length == 1 ? '0' + s : s);
	},


	/**
	 * 获取URL参数
	 * @param {String} search URL地址
	 * @param {any} name 参数名
	 * @return {String} 查询到的值
	 */
	getURL: function (search, name) {
		var reg = new RegExp("[?&]" + name + "=([^&]*)(&|$)", "i");
		var r = search.match(reg);
		if (r != null) return unescape(r[1]);
		return null;
	},

	concatGameKey: function (url) {
		return (ENV.game_key ? url + '?game_key=' + ENV.game_key : url);
	},

	getAjax: function (opt) {
		var userInfo = ENV.userInfo;
		if (!userInfo) {
			// alert('登陆超时……');
			// window.location.href = '/index/login';
			// return;
		}

		$.ajax({
			type: opt.type || 'POST',
			dataType: opt.dataType || 'JSON',
			url: opt.url || '/',
			data: opt.data || {},
			success: function (result) {
				if (result.code == 304) {
					// alert('登陆超时……');
					// window.location.href = '/index/login';
					// return;
				} else if (result.code == 200) {
					typeof opt.success == 'function' ? opt.success(result.data) : null;
					if (opt.alert) {
						alert(result.msg);
					}
					return;
				} else {
					alert(result.msg);
					return;
				}
			},
			error: function (err) {
				typeof opt.error == 'function' ? opt.error(err) : null;
				// alert('服务器错误');
			}
		})
	},

	/**
	 * 检测值是否是预期数据类型
	 * @param {All} val 值
	 * @param {string} type 预期类型
	 */
	isTypeOf(val, type) {
		switch (type) {
			case 'noEmpty':
				{ // 不为空
					return val !== '';
				}
			case 'isPhone':
				{
					return reg.phone.test(val);
				}
			case 'isPwd':
				{
					return val && reg.pwd.test(val);
				}
			case 'isEmail':
				{
					return val && reg.email.test(val);
				}
			case 'isUsername':
				{
					return val && reg.username.test(val);
				}
			default:
				{
					return Object.prototype.toString.call(val).toLowerCase() === `[object ${type.toLowerCase()}]`;
				}
		}
	},



};

var ENV = {
	// userInfo: JSON.parse(utils.getCookie('userInfo')),
	userInfo: {
		user_name: 'han123',
		jurisdiction: 1 // 0->代理 1->总代理
	}
};


(function () {

	/**
	 * 获取总共多少页
	 * @param {Number} total 总共多少条
	 * @param {Number} page_total 每页显示多少条
	 */
	var getPage = function (total, page_total) {
		var p = Math.ceil(total / page_total);
		if (!p) {
			p = 1;
		}
		return p
	}

	/**
	 * 分页
	 * @param {jQuery} wrapper 外层元素
	 * @param {Function} callback 页数变更回调用函数
	 * @param {Object} opt 初始化参数
	 */
	var Page = function (wrapper, callback, opt) {

		this.$wrapper = $(wrapper);
		this.callback = callback;
		if (!this.$wrapper.length) {
			throw Error('Pagination must have outermost support……');
		}
		// 总共多少条
		this.$total = this.$wrapper.find('.page-total');
		// 当前第几页(input/select)
		this.$index = this.$wrapper.find('.page-index');
		// 总共多少页
		this.$page = this.$wrapper.find('.page-page');
		// 首页
		this.$first = this.$wrapper.find('.page-first');
		// 末页
		this.$last = this.$wrapper.find('.page-last');
		// 下一页
		this.$next = this.$wrapper.find('.page-next');
		// 上一页
		this.$prev = this.$wrapper.find('.page-prev');

		this.data = {
			index: 1, // 当前第几页
			total: 0, // 总共多少条
			//page: 1, // 一共多少页
			page_total: 10, // 每页显示多少条
		}

		this.data = $.extend({}, this.data, opt);

		this.bind();
		this.init();
	}

	var _proto = Page.prototype;

	/**
	 * 分页初始化
	 * @param {Object} opt 初始化参数
	 */
	_proto.init = function (opt) {
		if (opt) {
			this.data = $.extend({}, this.data, opt);
		}
		this.data.page = getPage(this.data.total, this.data.page_total);

		this.render();
	}

	/**
	 * 绑定分页事件
	 */
	_proto.bind = function () {
		var _this = this;

		// 跳页
		if (this.$index.length) {
			if (this.$index[0].tagName === 'SELECT') {
				this.$index.bind('change', function () {
					_this.data.index = Number(this.value);
					_this.emit();
				});
			} else if (this.$index[0].tagName === 'INPUT') {
				this.$index.bind('blur', function () {
					if (isNaN(this.value)) {
						this.value = _this.data.page;
						return;
					}
					_this.data.index = Number(this.value);
					_this.emit();
				});
			}
		}

		// 首页
		if (this.$first.length) {
			this.$first.bind('click', function () {
				if (_this.data.index == 1) {
					return;
				}
				_this.data.index = 1;
				_this.emit();
			});
		}

		// 末页
		if (this.$first.length) {
			this.$last.bind('click', function () {
				if (_this.data.index == _this.data.page) {
					return;
				}
				_this.data.index = _this.data.page;
				_this.emit();
			});
		}

		// 下一页
		if (this.$next.length) {
			this.$next.bind('click', function () {
				_this.data.index++;
				if (_this.data.index > _this.data.page) {
					_this.data.index = _this.data.page;
				}
				_this.emit();
			});
		}

		// 上一页
		if (this.$prev.length) {
			this.$prev.bind('click', function () {
				_this.data.index--;
				if (_this.data.index < 1) {
					_this.data.index = 1;
				}
				_this.emit();
			});
		}
	}

	/**
	 * 渲染分页
	 */
	_proto.render = function () {
		this.$total.html(this.data.total);
		this.$page.html(this.data.page);

		// select
		if (this.$index.length) {
			if (this.$index[0].tagName === 'SELECT') {
				var html = '';
				for (var i = 1; i <= this.data.page; i++) {
					html += '<option value="' + i + '">' + i + '</option>';
				}
				this.$index.empty().append(html).val(this.data.index);
			} else {
				this.$index.val(this.data.index);
			}
		}


	}

	/**
	 * 触发分页回调
	 */
	_proto.emit = function () {
		typeof this.callback == 'function' ? this.callback.call(this, this.data.index) : null;
	}

	window.Page = Page;

})();


(function () {

	var InitHeader = function () {

		var $header = $('#header');
		this.$userType = $header.find('.userType');
		this.$username = $header.find('.username');
		this.$marqueeid = $header.find('.marqueeid');
		this.$logout = $header.find('.logout');
		this.$headerGameList = $header.find('.headerGameList');
		this.$jurisdiction = $header.find('.jurisdiction');
		this.$dropdown = $header.find('.dropdown');

		// this.userInfo = ENV.userInfo;


		this.data = {
			user_type_id: 0, // 0->总代理 1->代理
			user_type: '总代理',
			user_name: 'han123',
			notice: '这是假的一条公告',
			game_list: [
				{name: '吉林快3', url: '/'},
				{name: '重庆时时彩', url: '/'}
			],
		}
	
		this.init();
	}

	/**
	 * header初始化
	 */
	InitHeader.prototype.init = function () {
		var _this = this;
		utils.getAjax({
			url: '/api/index/firstNotice',
			type: 'GET',
			success: function(result){
				_this.data.notice = result.content;
				_this.noticeInit();
			}
		});
		utils.getAjax({
			url: '/api/user/getuserinfo',
			type: 'GET',
			success: function(result){
				_this.data.user_type_id = result.role_type;
				_this.data.user_type = result.role_name;
				_this.data.user_name = result.user_name;
				_this.data.game_list = result.game_list;
				_this.baseInit();
				_this.gameInit();
				_this.logoutInit();
			}
		});
	}

	/**
	 * 初始化最新公告
	 */
	InitHeader.prototype.noticeInit = function () {
		this.$marqueeid.text(this.data.notice);
	}

	/**
	 * 初始化最新公告
	 */
	InitHeader.prototype.baseInit = function () {
		if(this.data.user_type_id == 1){
			this.$jurisdiction.hide();
		}
		this.$userType.text(this.data.user_type);
		this.$username.text(this.data.user_name);
	}

	/**
	 * 游戏列表
	 */
	InitHeader.prototype.gameInit = function () {
		var _this = this;

		// 绑定游戏列表展开收起
		this.$dropdown.hover(function () {
			$(this).find('.dropdownList').slideDown('fast')
		}, function () {
			$(this).find('.dropdownList').slideUp('fast')
		})

		// 渲染游戏列表

		if(this.data.game_list && $.isArray(this.data.game_list) && this.data.game_list.length){

			for(var i = 0; i < this.data.game_list.length; i++) {
				var cur = this.data.game_list[i];
				this.$headerGameList.append('<tr><td><span class="mark_div">&nbsp;&nbsp;</span><span class="heng">&nbsp;&nbsp;</span><A href="'+ cur.url +'"><span class="titlehref">'+ cur.name +'</span></A></td></tr>')
			}
		}
	}

	/**
	 * 登出
	 */
	InitHeader.prototype.logoutInit = function () {
		this.$logout.bind('click', function () {
			utils.getAjax({
				url: '/api/user/logout',
				type: 'POST',
				success: function(){
					utils.delCookie('userInfo');
					window.location = '/index/login';
				}
			});
		});
	}

	window.InfoAll = {
		InitHeader: new InitHeader(),
	}

})();