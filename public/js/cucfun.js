function likeMessage(mid){
	event.preventDefault();
	link = '/ajax/likemessage';
	$.post(link, {mid:mid}, function(data){
		if(data.status) {
			alert ('成功赞!');
		} else {
			alert ('失败赞!');
		}
	}, 'json');
}


function lockUser(uid, time){
	event.preventDefault();
	link = '/ajax/lockUser';
	$.post(link, {uid:uid, time:time}, function(data){
		if(data.status) {
			alert ('已经屏蔽');
		} else {
			alert ('操作失败');
		}
	}, 'json');
}