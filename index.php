<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>World Map</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script type="text/javascript" src="http://api-maps.yandex.ru/2.1/?lang=en-US"></script>
</head>
<body>
<style>
    .map {
        width: 100%;
        height: 100%;
    }

    table td {
        width: 50%;
    }
    
    .selected-button {
    	background-color: grey;
    	border: none;
    }
</style>
<a href = 'read.php'>View selections</a><br>
<a href = 'parse.php'>Compare samples</a><br>
<?php

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
			echo "<h3 style='text-align:center'>Comparison ".get_name($_GET['n1'])." and ".get_name($_GET['n2'])."</h3>";
			echo "Select year<br>"; // вывожу на экран текст
			$array = []; // тут будут храниться значения с выборки (если не понял, смотри дальше)
			$h1 = fopen($_GET['n1'].".csv", "r"); // открываю файлы для чтения (файлы находятся на сервере)
			$h2 = fopen($_GET['n2'].".csv", "r"); // так же открываю фаил для чтения (файлы находятся на сервере)
			
			$row = 1; // простой ключ, чтобы выделить из таблицы данные от заголовков
			// пока идёт чтения файла
		    while (($data = fgetcsv($h1, 100000, ",")) !== FALSE) {
		    	// читаю так же 2 фаил
		    	$data1 = fgetcsv($h2, 100000, ",");
		    	
		    	// если это 1 проход, значит сейчас будут браться заголовки с таблицы (что в 1 что во второй они одинаковые)
		    	// так что не важно с какой таблицы эти данные брать
		    	if($row == 1) {
		    		// элементы от 0 до 5 это надписи (Названия страны, код и т.д, они мне не нужны, так что просто пропускаю)
			    	for ($c=5; $c < count($data); $c++) {
			    		// беру по " " делю данные, чтобы на выходе получить массив из значений.
		    			$d = explode(" ",$data[$c]);
		    			$param = ""; // это нужно для интерфейса
		    			
		    			// если в адресной строке есть выбранный год, тогда кнопку с этим годом нужно выделить
		    			if(isset($_GET['year']) && (int)$d[0] == (int)$_GET['year']) {
		    				$param = " selected-button "; // добавляю в параметры класс, что я её выбрал
		    			}
		    			
		    			// вывожу кнопку в которой есть 
		    			// n1 - название 1 выборки
		    			// n2 - название 2 выборки
		    			// col - чтобы облегчить чтение, можно сразу использовать это значение. Это тип укаазатель какой столбец берём
		    			// year - тут пишем выбранный год
			            echo "<button class ='".$param." but' n1 = '".$_GET['n1']."' col = '".$c."' n2 = '".$_GET['n2']."' year = '".$d[0]."'>".$d[0]."</button>";
			        }
			        // Если был выбран год, тогда можно начинать обрабатывать данные на карте, если нет, ничего не делаем 
			        if(isset($_GET['year']))
			        	$row += 1;
			        else
			        	break;
		    	} else {
		    		// Получаю как раз тот столбец для работы 
		    		$col = $_GET['col'];
		    		
		    		// в таблице, если нет значений для страны указывается '..' делаю проверку, есть ли в 1 выборке и во второй эти значения
		    		if($data[$col] != ".." && $data1[$col] != '..') {
		    			// Во время теста были некоторые пустые значения, для них поставил вот такую заглушку
		    			if($data[3] != '') {
		    				// считаю сумму дву значений мужчин и женщин
		    				// нужно для получения в итоге процента
			    			$sum = (float)$data[$col] + (float)$data1[$col];
			    			
			    			// считаю процент для 1 
			    			$sum1 = round(($data[$col]/$sum)*100);
			    			// считаю процент для 2 
			    			$sum2 = round(($data1[$col]/$sum)*100);
			    			
			    			// Создаю объект, в который записываю, код страны, и проценты мужчин и женщин
			    			$t = [];
			    			$t['code']		= $data[3];
			    			$t['male']		= $sum2;
			    			$t['female']	= $sum1;
			    			
			    			// добавляю в итоговый массив array 
			    			array_push($array,$t);
		    			}
		    		}
		    	}
		    }
			//echo var_dump($array);
	}
	// Это просто вижуально показывает какие цвета (просто для красоты, можно убрать и ничего не поменяется)
	if(isset($_GET['n1']) && isset($_GET['n2']))
		echo "<br><l style = 'background-color:#7e82ff;font-size:50px'>__</l> <l> More Male </l> <l style = 'background-color:#ff7ef5;font-size:50px'>__</l> <l> More female </l> <l style = 'background-color:#9bfd89;font-size:50px'>__</l> <l> Equal </l>";//Equal
	// man #7e82ff
	// woman #ff7ef5 #ac50ff
// Если в адресной строке не было найдено значения 2 выборок, тогда вместо ошибки будет показываться подсказка
if(!isset($_GET['n1']) && !isset($_GET['n2'])) {
	echo "
	You need to select a sample.<br>
	example http://higher-question.ru/HW/index.php?n1=hlfemale&n2=hlmale<br>
		list:<br>
		\t1. <a href = 'http://higher-question.ru/HW/index.php?n1=hlfemale&n2=hlmale'>hlfemale and hlmale</a><br>
		\t2. <a href = 'http://higher-question.ru/HW/index.php?n1=hufemale&n2=humale'>humale and humale</a><br>";
}

// так же проверка, чтобы не отрисовывать карту кучу раз просто так
if(isset($_GET['n1']) && isset($_GET['n2']))
{
?>
	<table width=100% height=800>
	    <tr valign=top>
	        <td>
	            <div id="map1" class="map"></div>
	        </td>
	    </tr>
	</table>

<script src="../assets/js/libs/jquery-3.1.1.min.js"></script>
<script src="code-contry.js"></script>
<script type="text/javascript">

	// ТУТ НЕ ЧИСТЫЙ JS использую библиотеку Jquery

	// для того, чтобы успело прочесть все файлы, поставил задержу 
	// setTimeout - функция позволяющя постаивть задержку в выполнение кода
	setTimeout(function(){
		// для дальнейшей работы, нужно перевести код с php в код понятный js
		// для этого я создал масив в котором продублировал теже данные (код, процент мужчин, процент женщин)
		const array = [<?php 
			for($i = 0 ; $i != count($array);$i++) {
				if($i == 0)
					echo "{ code:'".$array[$i]['code']."',male:".$array[$i]['male'].",female:".$array[$i]['female']."}";
				else
					echo ",{ code:'".$array[$i]['code']."',male:".$array[$i]['male'].",female:".$array[$i]['female']."}";
			}
		?>];
		
		
		// поставил событие на кнопку (Чтобы при нажатие на года сверху что-то происходило)
		$('.but').bind('click',function(){
			const n1	= $(this).attr('n1');
			const n2	= $(this).attr('n2');
			const year	= $(this).attr('year');
			const col	= $(this).attr('col');
			
			location.href = "index.php?n1="+n1+"&n2="+n2+"&year="+year+"&col="+col;
		})
		
		// функция выводит рандомный цвет, использовал для тестов на карте, сейчас не используется в программе
		function color(){
			col = Math.round(255.0*Math.random());
			r = col.toString(16);
			col = Math.round(255.0*Math.random());
			g=col.toString(16);
			col = Math.round(255.0*Math.random());
			d=col.toString(16);
			col="#"+r+g+d;
			if(col.length < 7)
				color()
			return col;
		}
		
		
		// функция которая возвращает итоговый цвет страны
		// если мужчин больше то синий если женщин розовый
		function return_color(code){
			// -> код страны в альфа 2 (тоесть код из 2 букв) Так как в таблицах был код в альфа 3 мне нужно его как-то переделать
			// тут использую словарь, код для него нахоиться в code-contry.js
			let cur_code = get_code(code);
			// <- Получаю код в альфа 3 
			// Прохожусь по всему массиву из данных которые получил через php
			for(let i = 0;i != array.length; ++i) {
				// Тут обычная проверка на совпадение кода 
				if(array[i].code == cur_code) {
					// Если male больше female выводим один цвет
					// если наоборон то другой
					// если поровну, тогда 3 цвет выводим
					if(array[i].male > array[i].female)
						return "#7e82ff";
					else if (array[i].male < array[i].female)
						return "#ff7ef5";
					else
						return "#9bfd89";
					
					// man #7e82ff
					// woman #ff7ef5
				}
			}
			// если в массиве array не нашёлся элемент который мы ищём, то возвращаем просто белый цвет
			return "#ffffff";
		}
		
		// это уже функция яндекс для отрисовки карты я тут не сильно шарю, тип что знаю отпишу 
	    ymaps.ready(function () {
	        var geoMap1 = new ymaps.Map('map1', {
	            center: [0, 0],
	            type: "yandex#map",
	            zoom: 2 // Ну тут понятно, какой зум будет 
	        });
			
			// для отрисовки границ, нужно было использовать другую утилиту, вот она, тут выводиться геометки с границами
			ymaps.borders.load('001').then(function (geojson) {
			  var objectManager = new ymaps.ObjectManager();
			  var features = geojson.features.map(function (feature) {
			        feature.id = feature.properties.iso3166; // это код страны в альфа 2
			        const color = return_color(feature.properties.iso3166); // вот тут отправляю в ту функцию этот альфа 2 код чтобы в итоге получить цвет
			        feature.options = {
	                            strokeColor: '#000000', // это цвет текста 
	                            title:"ss",
	                            fillColor: color, // сюда пишу итоговый цвет
	                            fillOpacity: 0.5 // степень прозрачности
	                        };
			        return feature;
			      }); 
			  objectManager.add(features);
			  geoMap1.geoObjects.add(objectManager);
			}, function (e) {
			   console.log(e);
			});
			
	        
	    });
	},2000)
</script>
<?php
}
?>
</body>
</html>

<!--<html>
	<head>
		<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
  integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
  crossorigin=""/>
		<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
  integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
  crossorigin=""></script>
	</head>
	<body>
		<div id = "mapid" style = "width: 100%; height: 100%"></div>
		<script>
		
			var map = L.map('mapid');

			map.createPane('labels');
		
			// This pane is above markers but below popups
			map.getPane('labels').style.zIndex = 650;
		
			// Layers in this pane are non-interactive and do not obscure mouse/touch events
			map.getPane('labels').style.pointerEvents = 'none';
		
			var cartodbAttribution = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, &copy; <a href="https://carto.com/attribution">CARTO</a>';
		
			var positron = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_nolabels/{z}/{x}/{y}.png', {
				attribution: cartodbAttribution
			}).addTo(map);
		
			var positronLabels = L.tileLayer('http://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}.png', {
				attribution: cartodbAttribution,
				pane: 'labels'
			}).addTo(map);
		
			map.setView({ lat: 47.040182144806664, lng: 9.667968750000002 }, 4);
		</script>
	</body>
</html>
-->