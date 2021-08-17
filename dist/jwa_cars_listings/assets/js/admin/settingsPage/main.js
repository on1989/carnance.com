jQuery(document).ready(function($) {
	function generateToken(length){
		//edit the token allowed characters
		let a = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890".split("");
		let b = [];
		for (let i=0; i<length; i++) {
			let j = (Math.random() * (a.length-1)).toFixed(0);
			b[i] = a[j];
		}
		return b.join("");
	}


	$('#token_generate').click(function (e){
		e.preventDefault();

		let token = generateToken(20);
		$('#token').val(token);
	})


});
