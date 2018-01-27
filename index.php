<?php
	require_once "getdb.php";
	$query='CREATE TABLE `students` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(50) NOT NULL,
			`estimation` float NOT NULL,
			`budget` tinyint(4) NOT NULL DEFAULT 0,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
			CREATE TABLE `university` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(50) NOT NULL,
			`estimation` float NOT NULL,
			`budget` tinyint(4) NOT NULL DEFAULT 0,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
	$result = $dbh->query($query);

	$show_tables='SHOW TABLES';
	//$sql = "SHOW TABLES";
	$statement = $dbh->prepare($show_tables);
	$statement->execute();
	//$row = $statement->fetchall(PDO::FETCH_ASSOC);
	//print_r($row);
	//while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
	//foreach($row as $key => $value) {
	//	echo $value . '<br>';
	//}}



	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$edit_name = strip_tags(trim($_POST['edit_name']));
		$table_name = strip_tags(trim($_POST['table_name']));
		$field_name = strip_tags(trim($_POST['field_name']));
		$field_type = strip_tags(trim($_POST['field_type']));
		$field_null = strip_tags(trim($_POST['field_null']));
		$field_key = strip_tags(trim($_POST['field_key']));
		$field_def = strip_tags(trim($_POST['field_def']));
		$field_extra = strip_tags(trim($_POST['field_extra']));
		$edit_type = strip_tags(trim($_POST['edit_type']));

		if ($field_null == 'NO') {
			$null = 'NOT NULL';
		} else {
			$null = 'NULL';
		}

		if ($field_def == '0') {
			$default = 'DEFAULT 0';
		} else {
			$default = '';
		}

		$command = array();
	
		if (isset($_POST['delete'])) {
			$query_del= "ALTER TABLE " . $table_name . " DROP COLUMN " . $field_name;
			$dbh->exec($query_del);
			// header("location: index.php");
			$command[]=(string)$query_del;
		}

		if (isset($_POST['edit_field'])) {
			$query_edit = 'ALTER TABLE ' . $table_name . ' CHANGE ' . $field_name . ' ' . $edit_name . ' ' . $field_type . ' ' . $null . ' ' . $default . ' ' . $field_extra;
			$dbh->exec($query_edit);
			// header("location: index.php");
			$command[]=(string)$query_edit;
		}

		if (isset($_POST['change_type'])) {
			$query_change = 'ALTER TABLE ' . $table_name . ' MODIFY ' . $field_name . ' ' . $edit_type . ' ' . $null . ' ' . $default . ' ' . $field_extra;
			$dbh->exec($query_change);
			// header("location: index.php");
			$command[]=(string)$query_change;
		}
	}

?>
<!DOCTYPE html>
<html lang="en" style="font-size: 14px;">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.3/css/bootstrap.min.css">
	<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
</head>
<body style ="background-color: #84b5dd">
	<div class="container">
		<img src="logo1.png" alt="adminer" class="rounded mx-auto d-block" style="margin: 40px auto; padding: 20px;background-color: rgba(0,0,0,0.2);">
		<?php while ($row = $statement->fetch(PDO::FETCH_ASSOC)): ?>
		<?php foreach ($row as $kay => $table): ?>
			<div class="block">
				<h3 class="alert alert-success extremum-click" style="max-width: 25%; margin: 30px auto;">ТАБЛИЦА: <?php echo $table; ?></h3>
				<div class="table-responsive extremum-slide" style="overflow: hidden; display: none;">
					<table class="table table-sm table-dark">
						<thead>
							<tr>
								<th>Имя</th>
								<th>Тип</th>
								<th>Null</th>
								<th>Ключ</th>
								<th>По умолчанию</th>
								<th>Дополнительно</th>
								<th>Операции</th>
							</tr>
						</thead>
						<tbody style="color: #000">
							<?php $show_fields='SHOW COLUMNS FROM ' . $table; ?>
							<?php foreach ($dbh->query($show_fields) as $desc): ?>
							<tr>
								<td class="table-light"><?= $desc['Field']; ?></td>
								<td class="table-light"><?= $desc['Type']; ?></td>
								<td class="table-light"><?= $desc['Null']; ?></td>
								<td class="table-light"><?= $desc['Key']; ?></td>
								<td class="table-light"><?= $desc['Default']; ?></td>
								<td class="table-light"><?= $desc['Extra']; ?></td>
								<td class="table-light">
									<form action="index.php" method="POST">
											<div class="input-group input-group-sm">
											<input type="hidden" name="table_name" value="<?= $table['Tables_in_bushenev']; ?>">
											<input type="hidden" name="field_name" value="<?= $desc['Field']; ?>">
											<input type="hidden" name="field_type" value="<?= $desc['Type']; ?>">
											<input type="hidden" name="field_null" value="<?= $desc['Null']; ?>">
											<input type="hidden" name="field_key" value="<?= $desc['Key']; ?>">
											<input type="hidden" name="field_def" value="<?= $desc['Default']; ?>">
											<input type="hidden" name="field_extra" value="<?= $desc['Extra']; ?>">
											<input type="submit" name="delete" value="Удалить" class="btn btn-outline-secondary">
											<input type="text" name="edit_name" class="form-control">
											<input type="submit" name="edit_field" value="Переименовать" class="btn btn-outline-secondary">
											<select name="edit_type" class="custom-select">
												<option value="INT">INT</option>
												<option value="VARCHAR(50)">VARCHAR(50)</option>
												<option value="FLOAT">FLOAT</option>
											</select>
											<input type="submit" name="change_type" value="Сменить тип" class="btn btn-outline-secondary">
										</div>
									</form>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endforeach; ?>
		<?php endwhile; ?>
		<?php if (isset($command)): ?>
		<h6 class="alert alert-primary" style="margin:30px 0;">Обработанна команда: <?=array_shift($command); ?></h6>
		<?php endif; ?>
	</div>
	<script>
	    $(".extremum-click").click(function () {
	      $(this).siblings(".extremum-slide").slideToggle("slow");
	    });
	</script>
</body>
</html>

