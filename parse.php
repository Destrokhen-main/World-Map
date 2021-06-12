<?php
//Возвращает округренный процент
//-> - обозначаю, что подаётся
//<- - обозначаю, что выдаёт

//функция считает процент
//-> одно из чисел и из сумма
//<- округленный процент
function per($a,$sum) {
	return round(($a/$sum)*100);
	
}

// $_GET['n1'] - это запрос к адресной строке, если посмотришь в выводе в адрессную строку увидешь параметры n1 n2 и т.д.
// isset() - функция которая проверяет определенна ли эта переменная
// Спрашиваю есть ли значения для 1 выборки (таблицы) и для 2, чтобы понимать, что есть с чем работать.
if(isset($_GET['n1']) && isset($_GET['n2'])) {
	function get_name($k) {
		switch($k){
			case "hlfemale":
				return "High level education female";
			case "hlmale":
				return "High level education male";
			case "humale":
				return "Undergraduate level education male";
			case "humale":
				return "Undergraduate level education male";
		}
	}
	?>
	<html>
		<head>
		</head>
		<body>
			<style>
				table td {
					border:1px solid black;
				}
				table th {
					border:1px solid black;
					background-color:#cccccc;
				}
				
				.null {
					background-color:#ffcccc;
				}
				
				.exist {
					background-color:#bafdb6;
				}
				
				.title {
					background-color:#c7c4f7;
				}
			</style>
			<h3 style = "text-align:center"><?php echo "Comparison ".get_name($_GET['n1'])." and ".get_name($_GET['n2']);?></h3>
			<table>
				<?php
					$h1 = fopen($_GET['n1'].".csv", "r"); // открываю файлы для чтения (файлы находятся на сервере)
					$h2 = fopen($_GET['n2'].".csv", "r"); // открываю файлы для чтения (файлы находятся на сервере)
				
					$row = 1;// простой ключ, чтобы выделить из таблицы данные от заголовков
					// пока идёт чтения файла
				    while ($data = fgetcsv($h1, 100000, ",")) {
				    	// читаю так же 2 фаил
				    	$data1 = fgetcsv($h2, 100000, ",");
				    	if($row == 1) {
				    		$t = "<tr>
				    			<th>Name country</th><th>Code</th>";
				    		for ($c=5; $c < count($data); $c++) {
				    			$d = explode(" ",$data[$c]);
					            $t.= "<th>".$d[0] ."</th>";
					        }
					        $t .= "</tr>";
					        echo $t;
					        $row++;
				    	} else {
				    		if ($data[5] != "") {
					    		$t = "<tr><td class = 'title'>".$data[2]."</td><td class = 'title'>".$data[3]."</td>";
					    		
					    		$help_line = "";
					    		
					    		$cs = "";
					    		for ($c=5; $c < count($data); $c++) {
					    			if($data[$c] != ".." && $data1[$c] != "..")
						            	$cs .= "<td>".$data[$c]."|".$data1[$c]."</td>";
						            if($data[$c] != ".." && $data1[$c] != "..") {
						            	$sum = (float)$data[$c]+ (float)$data1[$c];
						            	$help_line .= "<td class = 'exist'>F:".per($data[$c],$sum)."% M:".per($data1[$c],$sum)."%</td>";
						            }
						            else
						            	$help_line .= "<td class = 'null'></td>";
						        }
						        if(strlen($cs) > 0)
						        	echo $t.$help_line."</tr>";
				    		}
				    	}
				    }
				    fclose($h1);
				?>
			</table>
		</body>
	</html>
	<?php
} else {
	echo "Use tag_name<br>
		example http://higher-question.ru/HW/parse.php?n1=hlfemale&n2=hlmale<br>
		list:<br>
			1. <a href = 'http://higher-question.ru/HW/parse.php?n1=hlfemale&n2=hlmale'>hlfemale and hlmale</a><br>
			2. <a href = 'http://higher-question.ru/HW/parse.php?n1=hufemale&n2=humale'>humale and humale</a><br>";
}
?>