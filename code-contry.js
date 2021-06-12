function get_code(str){
	return code_contry.get(str);
}

var code_contry = new Map();

// использовал ajax для чтения локального файла
$.ajax({
		// это названия файла, можешь посомтреть как там всё описано 
      url: 'file.txt',
      success: function(data) {
      		// прохожусь по строкам делю их по разделителю \n - это тип нажатие ENTER в текстовом документе
	      	var path = data.split('\n');
	      	for(let i = 0;i != path.length;++i){
	      		// беру строку и делю её ещё раз по " "
	      		let str = path[i].split(' ');
	      		
	      		// Создаю запись в словарь как для a - b так и для  b - a
	      		code_contry.set(str[0], str[1]);
	      		code_contry.set(str[1], str[0]);
	      	}
    	}
});
