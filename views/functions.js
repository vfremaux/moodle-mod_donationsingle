
	function validate(document){
		document.write("HelloWorld!");
		if(isNumericalValue(document.amount)) return true;
		else error();
	}
	
	function isNumericalValue(string){
		var i=0;
		while(i<string.length()){
			if(!isnumber(string.charAt(i))) return false;
			i++;
		}
		return true;
	}

	function isnumber(n){
		if(n<0 || n>9) return false;
		return true;
	}

	function error(){
		var text = <?php print_string('alert', 'donationsingle') ?>;
		document.write(text);
	}

