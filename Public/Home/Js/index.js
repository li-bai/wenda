$(function () {
	//左侧问题分类选项卡
	$( '.list-l1' ).hover( function () {
		$( this ).children( 'div:eq(0)' ).addClass( 'list-cur' ).next().show();
	}, function () {
		$( this ).children( 'div:eq(0)' ).removeClass( 'list-cur' ).next().hide();
	} );

	//中部轮换版
	$( '.imgs-wrap ul li:not(:first)' ).css( {opacity : 0, filter : 'Alpha(Opacity = 0)'} );
	$( '.ani-btn li' ).mouseover( function () {
		$( this ).addClass( 'ani-btn-cur' ).siblings().removeClass( 'ani-btn-cur' );

		var index = $( this ).index();
		var obj = $( '.imgs-wrap' );
		var ulObj = obj.find( 'ul' );
		var liObj = obj.find( 'li' );

		ulObj.stop().animate( {
			left : -558 * index + 'px'
		}, 500).find('li').stop().animate( {
			opacity : 0,
    		filter : 'Alpha(Opacity = 0)'
		}, 100);

		liObj.stop().eq( index ).animate( {
			opacity : 1,
    		filter : 'Alpha(Opacity = 100)'
		}, 600).siblings().animate( {
			opacity : 0,
    		filter : 'Alpha(Opacity = 0)'
		}, 600);
	} );

		// 用户注册验证
		$('form[name=register]').validate({
				// 验证字段
				username:{
					// 验证规则
					rule:{
						required:true,
						minlen:2,
						maxlen:14,
						ajax:APP+'?&m=Home&c=Register&a=user_ajax'
					},
					// 错误提示
					error:{
						required:'用户名不能为空',
						minlen: '最少2个字符',
						maxlen:'用户名不能超过14字符',
						ajax:'用户名已经存在'
					},
					success:'验证通过',
					message:' 2-14个字符：字母、数字或中文位'
				},
				passwd:{
					rule:{
						required: true,
						minlen: 6,
						maxlen: 20
					},
					error:{
						required: '密码不能为空',
						minlen: '密码最少6个字符',
						maxlen: '密码最多20字符'
					},
					success:'验证通过',
					message:'6-20个字符:字母、数字或下划线 _'
				},
				passwdc:{
					rule:{
						required:true,
						confirm:'passwd'
					},
					error:{
						required:'不能为空',
						confirm:'两次密码不一致'
					},
					message:'请确认密码',
					success:'验证通过'
				},
				verify:{
					rule:{
						required:true,
						ajax:APP+'?&m=Home&c=Register&a=code_ajax'
					},
					error:{
						required:'验证码不能为空',
						ajax:'验证码错误'
					},
					message:'请输入图中的字母或数字，不区分大小写',
					success:'验证通过'
				}
		});
		
		
		

});