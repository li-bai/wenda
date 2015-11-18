/* 
* @Author: Administrator
* @Date:   2015-01-14 16:52:44
* @Last Modified by:   Administrator
* @Last Modified time: 2015-01-14 18:24:11
*/
$(function(){
	$('tr[pid!=0]').hide();
	$('.showPlus').toggle(
		function(){
			// 显示符号
			var cid = $(this).parents('tr').attr('cid');
			$(this).removeClass().addClass('showMinus');
			$('tr[pid='+cid+']').show();
		},
		function(){
			$(this).removeClass().addClass('showPlus');
			var cid = $(this).parents('tr').attr('cid');
			$('tr[pid='+cid+']').hide();
		}
	);
})