//操作提示
function dialog(msg,status,url){
	if(typeof msg == 'object'){
		var res = msg
		msg = res.msg
		status = res.status
		url = res.url
	}
	if(status==1){
		$.toast(msg);
	}else if(status!=-2){
		$.toast(msg,'text');
	}
	if(status==-1){
		window.location.href = location.href+'/needlogin/1';
		return;
	}
	if(status==-2){
		_showbindteldialog(msg);
		return;
	}
	$('.weui-mask_transparent').hide();
	if (typeof(url) != "undefined" && url != "") {
		setTimeout(function(){
			if(url === true){
				reload();
			}else{
				if(status==-1){ //需要登录
					//window.location.href = url + '?fromurl='+encodeURIComponent(location.href);
					window.location.href = location.href+'/needlogin/1';
				}else{
					window.location.href = url;
				}
			}
		},1000);
	}
}

//时间戳转换成时间格式 php的date函数
function date(format,timestamp){if(timestamp==null || timestamp=='') return '';var a,jsdate=((timestamp)?new Date(timestamp*1000):new Date());var pad=function(n,c){if((n=n+"").length<c){return new Array(++c-n.length).join("0")+n}else{return n}};var txt_weekdays=["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];var txt_ordin={1:"st",2:"nd",3:"rd",21:"st",22:"nd",23:"rd",31:"st"};var txt_months=["","January","February","March","April","May","June","July","August","September","October","November","December"];var f={d:function(){return pad(f.j(),2)},D:function(){t=f.l();return t.substr(0,3)},j:function(){return jsdate.getDate()},l:function(){return txt_weekdays[f.w()]},N:function(){return f.w()+1},S:function(){return txt_ordin[f.j()]?txt_ordin[f.j()]:'th'},w:function(){return jsdate.getDay()},z:function(){return(jsdate-new Date(jsdate.getFullYear()+"/1/1"))/864e5>>0},W:function(){var a=f.z(),b=364+f.L()-a;var nd2,nd=(new Date(jsdate.getFullYear()+"/1/1").getDay()||7)-1;if(b<=2&&((jsdate.getDay()||7)-1)<=2-b){return 1}else{if(a<=2&&nd>=4&&a>=(6-nd)){nd2=new Date(jsdate.getFullYear()-1+"/12/31");return date("W",Math.round(nd2.getTime()/1000))}else{return(1+(nd<=3?((a+nd)/7):(a-(7-nd))/7)>>0)}}},F:function(){return txt_months[f.n()]},m:function(){return pad(f.n(),2)},M:function(){t=f.F();return t.substr(0,3)},n:function(){return jsdate.getMonth()+1},t:function(){var n;if((n=jsdate.getMonth()+1)==2){return 28+f.L()}else{if(n&1&&n<8||!(n&1)&&n>7){return 31}else{return 30}}},L:function(){var y=f.Y();return(!(y&3)&&(y%1e2||!(y%4e2)))?1:0},Y:function(){return jsdate.getFullYear()},y:function(){return(jsdate.getFullYear()+"").slice(2)},a:function(){return jsdate.getHours()>11?"pm":"am"},A:function(){return f.a().toUpperCase()},B:function(){var off=(jsdate.getTimezoneOffset()+60)*60;var theSeconds=(jsdate.getHours()*3600)+(jsdate.getMinutes()*60)+jsdate.getSeconds()+off;var beat=Math.floor(theSeconds/86.4);if(beat>1000)beat-=1000;if(beat<0)beat+=1000;if((String(beat)).length==1)beat="00"+beat;if((String(beat)).length==2)beat="0"+beat;return beat},g:function(){return jsdate.getHours()%12||12},G:function(){return jsdate.getHours()},h:function(){return pad(f.g(),2)},H:function(){return pad(jsdate.getHours(),2)},i:function(){return pad(jsdate.getMinutes(),2)},s:function(){return pad(jsdate.getSeconds(),2)},O:function(){var t=pad(Math.abs(jsdate.getTimezoneOffset()/60*100),4);if(jsdate.getTimezoneOffset()>0)t="-"+t;else t="+"+t;return t},P:function(){var O=f.O();return(O.substr(0,3)+":"+O.substr(3,2))},c:function(){return f.Y()+"-"+f.m()+"-"+f.d()+"T"+f.h()+":"+f.i()+":"+f.s()+f.P()},U:function(){return Math.round(jsdate.getTime()/1000)}};return format.replace(/[\\]?([a-zA-Z])/g,function(t,s){if(t!=s){ret=s}else if(f[s]){ret=f[s]()}else{ret=s}return ret})}

//数组对象转换成url参数
function urlEncode(param, key, encode) {
	if (param==null) return '';
	var paramStr = '';
	var t = typeof (param);
	if (t == 'string' || t == 'number' || t == 'boolean') {
		paramStr += '/' + key + '/'  + ((encode==null||encode) ? encodeURIComponent(param) : param); 
	} else {
		for (var i in param) {
			var k = key == null ? i : key + (param instanceof Array ? '[' + i + ']' : '.' + i)
			paramStr += urlEncode(param[i], k, encode)
		}
	}
	return paramStr;
}

var _loading = false;
var _pagenum = 1;
//obj:html对象 callback:回调 postdata:参数 scrollobj:滚动对象
function getmore(obj,callback,postdata,scrollobj,url){
	if(undefined == scrollobj || '' == scrollobj) scrollobj = document.body;
	if(undefined == url) url = '';
  $(scrollobj).infinite().on("infinite", function(){
    if(_loading) return;
    _loading = true;
    $(obj).append('<div class="weui-loadmore" id="weui_loadding"><i class="weui-loading"></i><span class="weui-loadmore__tips">正在加载</span></div>');
    _pagenum++;
		if(undefined == postdata || '' == postdata) postdata = {};
		postdata.op = 'getmore';
		postdata.pagenum = _pagenum;
    $.post(url,postdata,function(res){
			$('#weui_loadding').remove();
			var _data =res.data
			if(_data.length > 0){
				callback(res.data);
				_loading = false;
			}else{
				$(obj).append('<div class="weui-loadmore weui-loadmore_line"><span class="weui-loadmore__tips">没有更多记录了</span></div>');
			}
    });
  });
}


//预览图片接口
function previewImage(obj){
	var temps= new Array();	//当前页面图片集合
	var cureentpath="";		//当前点击的图片链接
	cureentpath=$(obj).attr('src');
	cureentpath = cureentpath.split('!')[0];
	console.log(cureentpath);
	wx.previewImage({
	    current: cureentpath, // 当前显示的图片链接
	    urls: [cureentpath] // 需要预览的图片链接列表
	});
}


//预览图片接口
function previewImageUrl(url,urls){
	if(undefined == urls || '' == urls){
		urls = url;
	}
	urls = urls.split(',');
	wx.previewImage({
	    current: url, // 当前显示的图片链接
	    urls: urls // 需要预览的图片链接列表
	});
}

//是否微信环境
function isweixin(){
	var ua = window.navigator.userAgent.toLowerCase();
	if(ua.match(/MicroMessenger/i) == 'micromessenger'){
		return true;
	}else{
		return false;
	}
}
//上传图片
function uploadimg(obj,callback) {
	var picPath = $(obj).val();
	//如果上传图片的后缀为空，不过滤，直接上传
	if(picPath.lastIndexOf('.')>-1){
		var type = picPath.substring(picPath.lastIndexOf('.') + 1, picPath.length).toLowerCase();
		if (type != undefined && type != "" && type != 'jpg' && type != 'png' && type != 'jpeg') {
			$.hideLoading();
			dialog("图片支持jpg/png/jpeg格式，请上传正确的图片格式！");
			return false;
		}
	}
	if (obj.files && obj.files[0]) {
		var file = obj.files[0];
		var URL = window.URL || window.webkitURL;
		var blob = URL.createObjectURL(file);
		var img = new Image();
		img.src = blob;
		img.onload = function () {
			var that = this;
			var quality=1;
			var maxWidth=1000;
			var base64 = "";
			//生成比例
			var w = that.width,h = that.height,scale = w / maxWidth;
			//如果图片大于最大宽度
			if(w> maxWidth){
				quality = 0.8;//压缩图片质量0-1，值越大质量越好
				w = maxWidth;
				h = h / scale;
				//生成canvas
				var canvas = document.createElement('canvas');
				var ctx = canvas.getContext('2d');
				$(canvas).attr({width : w, height : h});
				ctx.drawImage(that, 0, 0, w, h);
				var useragent = navigator.userAgent;
				// 修复IOS
				if( useragent.match(/iphone/i) || useragent.match(/ipod/i) || useragent.match(/ipad/i)) {
						var mpImg = new MegaPixImage(img);
						mpImg.render(canvas, { maxWidth: w, maxHeight: h, quality: quality});
						base64 = canvas.toDataURL('image/jpeg', quality );
				}else if( useragent.match(/Android/i) ) {
					// 修复android
						var encoder = new JPEGEncoder();
						base64 = encoder.encode(ctx.getImageData(0,0,w,h), quality * 100 );
				}else{
					//非ios和Android系统
					base64 = canvas.toDataURL('image/jpeg', quality );
				}
			}else{
				//生成canvas
				var canvas = document.createElement('canvas');
				var ctx = canvas.getContext('2d');
				$(canvas).attr({width : w, height : h});
				ctx.drawImage(that, 0, 0, w, h);
				base64 = canvas.toDataURL('image/jpeg', quality );
			}
			$.post('/m.php?s=/imageupload/uploadbase64/aid/'+_getaid(),{imgFileBase:base64},function(res){
				$.hideLoading();
				
				if(res.status==1) {
					var url = res.url;
					callback(url);
				}else{
					dialog(res.msg);
				}
			});
			//alert('成功'+base64);
			// 执行后函数
			//obj.success(result);
		}
	} else {
		$.hideLoading();
	}
}
//获取x参数
function _getaid(){
	var href = window.location.href;
	href = href.split('/aid/')[1];
	href = href.split('/')[0];
	href = href.split('?')[0];
	href = href.split('&')[0];
	return href;
}
function reload(){
	var href = window.location.href;
  href = href.replace(/&?t_reload=(\d+)/g,'')
  window.location.href = href+(href.indexOf('?') > -1?'&':'?')+"t_reload="+new Date().getTime()
}

$(function(){
	//图片预览
	$('.previewImgContent img').click(function(){
		var url = $(this).attr('src');
		//url = url.split('!')[0];
		//url = url+'!w';
		var urls = [];
		$(this).parents('.previewImgContent').find('img').each(function(){
			var turl = $(this).attr('src');
			//turl = turl.split('!')[0];
			//turl = turl+'!w';
			urls.push(turl);
		});
		wx.previewImage({
				current: url, // 当前显示的图片链接
				urls: urls // 需要预览的图片链接列表
		});
	});
	//登录
	var href = window.location.href;
	if(href.indexOf('_showloginsuccess=1') > -1){
			dialog('登录成功',1);
	}
	if(href.indexOf('_needbindtel=1') > -1){
		_showbindteldialog();
	}
})
function _showbindteldialog(){
	$('#_bindteldialog').remove();
	var _loginhtml = '';
	_loginhtml += '<div class="auth-modal-box" id="_bindteldialog">';
	_loginhtml += '	<div class="auth-modal">';
	_loginhtml += '		<div style="position:relative">';
	_loginhtml += '			<div class="auth-modal-close" onclick="_closebindtel()" style="display:none">×</div>';
	_loginhtml += '			<div class="auth-title">注册/登录</div>';
	_loginhtml += '			<div class="auth-body">';
	_loginhtml += '				<div class="auth-tel flex-y-center">';
	_loginhtml += '					<input class="auth-input flex1" type="text" placeholder="请输入您的手机号" name="_bindtel_tel" value=""/>';
	_loginhtml += '				</div>';
	_loginhtml += '				<div class="auth-pwd flex-y-center">';
	_loginhtml += '					<input class="auth-input flex1" type="text" placeholder="请输入验证码" name="_bindtel_code" value=""/>';
	_loginhtml += '					<div style="height:0.3rem;line-height:0.3rem;margin-left:0.1rem;width:1.5rem" onclick="_sendsmscode(this)">获取验证码</div>';
	_loginhtml += '				</div>';
	_loginhtml += '				<button style="background:#FBA045" class="auth-button" type="button" onclick="_bindtelsubmit()">确 定</button>';
	_loginhtml += '				<div class="auth-footer">';
	_loginhtml += '					<span>我们承诺您的个人信息在<br/>《腾讯隐私协议》下会受到安全保障</span>';
	_loginhtml += '				</div>';
	_loginhtml += '			</div>';
	_loginhtml += '		</div>';
	_loginhtml += '	</div>';
	_loginhtml += '</div>';
	$('body').append(_loginhtml);
}
var _smscodehqing = 0;
function _bindtelsubmit(){
	var tel = $('input[name=_bindtel_tel]').val();
	var code = $('input[name=_bindtel_code]').val();
	if(!(/^1[3456789]\d{9}$/.test(tel))){
		dialog('请输入正确的手机号');return;
	}
	if(!code){
		dialog('请输入短信验证码');
	}
	$.showLoading('正在绑定');
	var fr="";
	var frid="";
	var fromurl = ""
	$.post('/m.php?s=pub/login/aid/'+_getaid(),{tel:tel,code:code},function(res){
		$.hideLoading();
		if(res.status==1){
			res.url = (location.href).replace('?_needbindtel=1','');
			res.url = (res.url).replace('&_needbindtel=1','');
		}
		dialog(res);
	});
}
function _sendsmscode(obj){
	if(_smscodehqing) return;
	_smscodehqing = 1;
	var tel = $('input[name=_bindtel_tel]').val();
	if(tel==''){
		dialog('请输入手机号码');
		_smscodehqing = 0;
		return false;
	}
	if(!(/^1[3456789]\d{9}$/.test(tel))){
		dialog("手机号码格式错误");
		_smscodehqing = 0;
		return false;
	}
	$.post("/m.php?s=pub/sendsms/aid/"+_getaid(),{tel:tel},function(data){
		if(data.status!=1){
			dialog(data.msg);
		}
	});
	var time = 120;
	var t = setInterval(function() {
		time--;
		if (time < 0) {
			$(obj).html("重新获取");
			_smscodehqing = 0;
			clearInterval(t);
		} else if (time >= 0) {
			$(obj).html(time + "秒");
		}
	}, 1000);
}
function _closebindtel(){
	$('#_bindteldialog').remove();
}

//领取优惠券
function getcoupon(id,score,price){
	if(price > 0){
		location.href="/m.php?s=wxpay/buycoupon/aid/"+_getaid()+"/id/"+id
	}else if(score > 0){
		$.confirm('确定要消耗'+score+'积分兑换吗?',function(){
			$.showLoading('兑换中');
			$.post("/m.php?s=coupon/getcoupon/aid/"+_getaid(),{id:id},function(res){
				$.hideLoading();
				dialog(res);
			});
		})
	}else{
		$.showLoading('领取中');
		$.post("/m.php?s=coupon/getcoupon/aid/"+_getaid(),{id:id},function(res){
			$.hideLoading();
			dialog(res);
		});
	}
}

//表单提交
function _editorFormSubmit(obj,formid,tourl){
	console.log(obj)
	var formdata = $(obj).serializeArray();
	var newformdata = {};
	for(var i in formdata){
		newformdata[formdata[i].name] = formdata[i].value;
	}
	console.log(formdata)
	console.log(newformdata)
	//return false;
	$.showLoading('提交中');
	$.post('/m.php?s=form/formsubmit/aid/'+_getaid(), {formid:formid,formdata:newformdata,price:newformdata.price},function(res){
		$.hideLoading();
		console.log(res)
		if(res.status == 0){
			//that.showsuccess(res.data.msg);
			dialog(res);
		}else if(res.status == 2){ //需要付款
			dialog('正在跳转到支付...',1,'/m.php?s=wxpay/formpay/aid/'+_getaid()+'/orderid/'+res.orderid+'&tourl='+encodeURIComponent(tourl));
		}else{
			dialog(res.msg,1,tourl);
		}
	});
	return false;
}
//加入购物车
function _tocart(proid,menuindex,isrefresh){
	if(typeof(isrefresh) == "undefined") var isrefresh = false
	var guigelist;
	var ks;
	var ggselected = [];
	$.showLoading();
	$.post('/m.php?s=shop/getproductdetail/aid/'+_getaid(),{id:proid},function(res){
		$.hideLoading();
		var product = res.product
		guigelist = res.guigelist
		if(guigelist.length <= 1 && isrefresh){
			var ggid = guigelist[0].id;
			$.post('/m.php?s=shop/addcart/aid/'+_getaid(),{proid:proid,ggid:ggid,num:1}, function (res) {
				if(isrefresh){
					res.url = location.href;
				}
				dialog(res);
				$('#_buydialog').remove();
			});
			return ;
		}
		var guigedata = JSON.parse(product.guigedata);
		ggselected = []
		for (var i = 0; i < guigedata.length; i++) {
			ggselected.push(0);
		}
		ks = ggselected.join(',');
		var html = '';
		html+='<div id="_buydialog">';
		html+='	<div class="_buydialog-mask '+(menuindex>-1?'tabbarbot':'')+'" onclick="$(\'#_buydialog\').remove()"></div>';
		html+='	<div class="_buydialog '+(menuindex>-1?'tabbarbot':'')+'">';
		html+='			<div class="close" onclick="$(\'#_buydialog\').remove()">';
		html+='				<img src="/static/m/images/close.png" class="buydialog-canimg">';
		html+='			</div>';
		html+='			<div>';

		html+='				<div class="title">';
		html+='					<img src="'+guigelist[ks].pic+'" onerror="this.src=\''+product.pic+'\'" class="img">';
		html+='					<div class="price"><span class="t1">￥</span>'+guigelist[ks].sell_price+' <span class="t2">￥'+guigelist[ks].market_price+'</span></div>';
		html+='					<div class="stock">剩余'+guigelist[ks].stock+'件</div>';
		html+='					<div class="choosename">已选规格: '+guigelist[ks].name+'</div>';
		html+='				</div>';
		html+='		<div style="max-height:50vh;overflow:scroll">';
		for(var i=0;i<guigedata.length;i++){
			var item = guigedata[i];
			html+='			<div class="guigelist flex-col">';
			html+='				<div class="name">'+item.title+'</div>';
			html+='				<div class="item flex flex-y-center">';
			var items = item['items'];
			for(var j=0;j<items.length;j++){
				var item2 = items[j];
				html+='				<div class="item2 '+(j==0?'on':'')+'" idx="'+item.k+'" itemk="'+item2.k+'">'+item2.title+'</div>';
			}
			html+='				</div>';
			html+='			</div>';
		}
		html+='			</div>';
		html+='			</div>';
		html+='			<div class="buynum flex flex-y-center">';
		html+='				<div class="flex1">购买数量：</div>';
		html+='				<div class="f2 flex flex-y-center">';
		html+='					<span class="minus flex-x-center">-</span>';
		html+='					<input class="flex-x-center" type="number" value="1" id="_ggnum"/>';
		html+='					<span class="plus flex-x-center">+</span>';
		html+='				</div>';
		html+='			</div>';
		html+='			<div class="op">';
		html+='				<button class="addcart">加入购物车</button>';
		html+='				<button class="tobuy">立刻购买</button>';
		html+='			</div>';
		html+='	</div>';
		html+='</div>';
		$('#_ggnum').val('1')
		$('#_buydialog').remove()
		$('body').append(html);
		//减
		$('#_buydialog .minus').click(function(){
			var num = parseInt($('#_ggnum').val());
			num--;
			if(num<1) num = 1;
			$('#_ggnum').val(num);
		});
		$('#_buydialog .plus').click(function(){
			var stock = parseInt(guigelist[ks].stock);
			var num = parseInt($('#_ggnum').val());
			num++;
			if(num > stock) num = stock;
			$('#_ggnum').val(num);
		})
		$('#_ggnum').bind('input change propertychange',function(){
			var num = parseInt($('#_ggnum').val());
			var stock = parseInt(guigelist[ks].stock);
			if(num < 1) $('#_ggnum').val(1);
			if(num > stock){
				$('#_ggnum').val(stock);
			}
		})
		$('#_buydialog .item2').click(function(){
			$(this).siblings().removeClass('on');
			$(this).addClass('on');
			var idx = $(this).attr('idx')
			var itemk = $(this).attr('itemk')
			ggselected.splice(idx,1,itemk);
			//ggselected[idx] = itemk
			//console.log(ggselected);
			ks = ggselected.join(',');
			$('#_buydialog .img').attr('src',guigelist[ks].pic)
			$('#_buydialog .price').html('<span class="t1">￥</span>'+guigelist[ks].sell_price+(guigelist[ks].market_price>guigelist[ks].sell_price?' <span class="t2">￥'+guigelist[ks].market_price+'</span>':''));
			$('#_buydialog .choosename').html('已选规格: '+guigelist[ks].name);
			$('#_buydialog .stock').html('剩余'+guigelist[ks].stock+'件');
		})
		//加入购物车
		$('#_buydialog .addcart').click(function(){
			var num = parseInt($('#_ggnum').val());
			var ggid = guigelist[ks].id;
			$.showLoading();
			$.post('/m.php?s=shop/addcart/aid/'+_getaid(),{proid:proid,ggid:ggid,num:num}, function (res) {
				$.hideLoading(false);
				if(isrefresh){
					res.url = location.href;
				}
				dialog(res);
				$('#_buydialog').remove();
			});
		});
		//购买
		$('#_buydialog .tobuy').click(function(){
			var ggid = guigelist[ks].id;
			var num = parseInt($('#_ggnum').val());
			var prodata = proid + ',' + ggid + ',' + num;
			location.href = '/m.php?s=shop/buy/aid/'+_getaid()+'/prodata/' + prodata;
		});
	})
	event.stopPropagation();
	return false;
}

function getDistance(lat1, lng1, lat2, lng2) {
	if(!lat1 || !lng1 || !lat2 || !lng2) return '';
	var rad1 = lat1 * Math.PI / 180.0;
	var rad2 = lat2 * Math.PI / 180.0;
	var a = rad1 - rad2;
	var b = lng1 * Math.PI / 180.0 - lng2 * Math.PI / 180.0;
	var r = 6378137;
	var juli = r * 2 * Math.asin(Math.sqrt(Math.pow(Math.sin(a / 2), 2) + Math.cos(rad1) * Math.cos(rad2) * Math.pow(Math.sin(b / 2), 2)));
	juli = juli/1000
	juli = juli.toFixed(2);
	return juli;
}