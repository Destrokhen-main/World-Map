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

	if(isset($_GET['name'])) {
		$name = $_GET['name'];
		
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
			<h3 style = "text-align:center"><?php echo get_name($_GET['name'])?></h3>
			<table>
				<?php
					$row = 1;
					if (($handle = fopen($name.".csv", "r")) !== FALSE) {
					    while (($data = fgetcsv($handle, 100000, ",")) !== FALSE) {
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
						    			if($data[$c] != "..")
							            	$cs .= "<td>".$data[$c] ."</td>";
							            if($data[$c] != "..")
							            	$help_line .= "<td class = 'exist'>".$data[$c]."</td>";
							            else
							            	$help_line .= "<td class = 'null'></td>";
							        }
							        if(strlen($cs) > 0)
							        	echo $t.$help_line."</tr>";
					    		}
					    	}
					    }
					    fclose($handle);
					}
				?>
			</table>
		</body>
	</html>
<?php
	} else {
		echo "Use tag_name<br>
		example http://higher-question.ru/HW/read.php?name=hlfemale<br>
		list:<br>
			1. <a href = 'http://higher-question.ru/HW/read.php?name=hlfemale'>hlfemale</a><br>
			2. <a href = 'http://higher-question.ru/HW/read.php?name=hlmale'>hlmale</a><br>
			3. <a href = 'http://higher-question.ru/HW/read.php?name=hufemale'>hufemale</a><br>
			4. <a href = 'http://higher-question.ru/HW/read.php?name=humale'>humale</a><br>";
	}
?>