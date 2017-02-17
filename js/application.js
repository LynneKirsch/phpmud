$(function() {
	var conn = new WebSocket('ws://localhost:9000');
	conn.onopen = function(e) {
		console.log("Connection established!");
	};

	conn.onmessage = function(e) {
		console.log();
		$('#console').append(e.data);
	};
	
	$('#input input').on('keypress', function (e) {
         if(e.which === 13){
			conn.send($('#input input').val());
			$('#input input').select()
			
         }
   });
});
