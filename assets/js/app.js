//HTMLがすべて読み込まれたら実行される
$(function(){
	//DOM.on(イベント,クラスorID,処理)
	//documentはHTML全体を指す

	//クラス指定の場合は.が必要 例('.js-like')の.
	//ID指定の場合は#が必要
	$(document).on('click','.js-like', function(){
		console.log('ボタンが押されました')

		//誰がいいねしたかを所得
		let user_id = $('.signin-user').text()

		// どの投稿がいいねされたかを所得
		//thisは押されたボタン自身
		//sinlingsは兄妹
		//.はメソッド
		//DOM.siblings(クラス名)
		//指定されたクラス名を持っている要素を所得する
		let feed_id = $(this).siblings('.feed-id').text()

		//ボタンが押されたタイミングでいいね数を増やす
		let like_btn = $(this)
		let like_count = $(this).siblings('.like-count').text()

		//非同期通信(Ajax)
		//$.ajax(送信先や送信するデータ)
		//.done(成功時の処理)
		//.fail(失敗時の処理)
		$.ajax({
			url: 'like.php',
			type: 'POST',
			datatype: 'json',
			data: {
				'feed_id': feed_id,
				'user_id': user_id
			}
		}).done(function(data){

			// 成功時の処理
			// dataはサーバーからのレスポンス
			if (data) {
				like_count++
				like_btn.siblings('.like-count').text(like_count)

				like_btn.removeClass('js-like')
					.addClass('js-unlike')
					.children('span').text('いいねを取り消す')
			}

			console.log(data)
		}).fail(function(e){
			//失敗時の処理
			//eはサーバーから返されたエラー
			console.log(e)
		})
	})
	$(document).on('click','.js-unlike',function(){
		console.log('取り消すが押された')
	    let feed_id = $(this).siblings('.feed-id').text()
	    let user_id = $('.signin-user').text()
	    console.log(feed_id)
	    console.log(user_id)

	    let like_btn = $(this)
	    let like_count = $(this).siblings('.like-count').text()

	    $.ajax({
	    	url: 'like.php',
	    	type:'POST',
	    	datatyape: 'json',
	    	data: {
	    		'feed_id': feed_id,
	    		'user_id': user_id,
	    		'is_unlike':true
	        //'feed_id'と'user_id'がキーになっている
	    	}
	    }).done(function(data){
	    	if (data) {
				// 取り消されたら数字を減らす
				like_count--;
				like_btn.siblings('.like-count').text(like_count)
				// 取り消されたらボタンを切り替える
				like_btn.removeClass('js-unlike')
				like_btn.addClass('js-like')
				like_btn.children('span').text('いいね!')
			}
	    }).fail(function(e){
	    	console,log(e)
		})
	})
})