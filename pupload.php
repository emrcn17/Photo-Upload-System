<!DOCTYPE html>
<html lang="tr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Fotoğraf Yükleme Sistemi</title>

    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    
	<p><br/></p>
	<div class="container">
		
		<?php
		$db = new PDO('mysql:host=localhost;dbname=alupload', 'root', '');
		if(isset($_POST['upload'])){
			$file = $_FILES['foto']['name'];
			$size = $_FILES["foto"]["size"];
			$type = $_FILES["foto"]["type"];
			$tmp = $_FILES['foto']['tmp_name'];
			$target = "images/".$file;
			if (!file_exists($target)) {
				if ($size <= 50000000) {
					if($type == "image/jpg" || $type == "image/png" || $type == "image/jpeg" || $type == "image/gif" ) {
						if (move_uploaded_file($tmp, $target)) {
							$stmt = $db->prepare("INSERT INTO foto (foto) VALUES (?)");
							$stmt->bindParam(1, $file);
							if($stmt->execute()){
								?>
								<div class="alert alert-success alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								  Fotoğrafınız Dizine ve Veritabanına Yüklendi (<?php echo $file ?>);
								</div>
								<?php
							} else{
								?>
								<div class="alert alert-warning alert-dismissible" role="alert">
								  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								  Fotoğrafınız Veritabanına Yüklenemedi
								</div>
								<?php
							}
						} else {
							?>
							<div class="alert alert-danger alert-dismissible" role="alert">
							  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							  Malesef, Fotoğrafınızı Yüklerken Bir Hata Oluştu.
							</div>
							<?php
						}
					} else{
						?>
						<div class="alert alert-danger alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							Üzgünüz Sadece JPG, JPEG, PNG & GIF Dosyalara İzin Verir.
						</div>
						<?php
					}
				} else{
					?>
					<div class="alert alert-danger alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						Malesef, Dosyanız Çok Büyük.
					</div>
					<?php
				}
			} else{
				?>
				<div class="alert alert-danger alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					Malesef, Bu Dosya Zaten Var.
				</div>
				<?php
			}
		}
		if(isset($_GET['id'])){
			$id = $_GET['id'];
			$foto = $_GET['foto'];
			if(unlink('images/'.$foto)){
				$stmt3 = $db->prepare("DELETE FROM foto where id = ?");
				if ($stmt3->execute(array($id))) {
					?>
					<script>location.href='pupload.php'</script>
					<?php
					//header('location: pupload.php');
				}
			}
		}
		?>
		<form method="post" enctype="multipart/form-data">
		  <div class="form-group">
			<label for="foto">Fotoğraf Yükleme Sistemi</label>
			<input type="file" id="foto" name="foto">
			<p class="help-block">Sadece JPG, PNG, GIF</p>
		  </div>
		  <button type="submit" name="upload" class="btn btn-primary">Yükle</button>
		</form>
		
		<p></p>
		<div class="row">
		
		<?php
		$stmt2 = $db->prepare("SELECT * FROM foto");
		if ($stmt2->execute()) {
		  while ($row = $stmt2->fetch()) {
		?>
		  <div class="col-sm-6 col-md-3">
			<div class="thumbnail">
			  <img src="images/<?php echo $row['foto'] ?>" alt="<?php echo $row['foto'] ?>" style="height:120px;">
			  <div class="caption">
				<p><a href="#" data-toggle="modal" data-target="#myModal-<?php echo $row['id'] ?>" class="btn btn-primary" role="button">Detaylı Gör</a> <a href="?id=<?php echo $row['id'] ?>&foto=<?php echo $row['foto'] ?>" onclick="return confirm('Emin Misiniz ?')" class="btn btn-danger" role="button">Sil</a></p>
				<!-- Modal -->
				<div class="modal fade" id="myModal-<?php echo $row['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-<?php echo $row['id'] ?>">
				  <div class="modal-dialog" role="document">
					<div class="modal-content">
					  <div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel-<?php echo $row['id'] ?>"><?php echo $row['id'] ?> <?php echo $row['foto'] ?></h4>
					  </div>
					  <div class="modal-body">
						<img src="images/<?php echo $row['foto'] ?>" style="width:100%"/>
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Kapat</button>
					  </div>
					</div>
				  </div>
				</div>
				
			  </div>
			</div>
		  </div>
		<?php
		  }
		}
		?>
		</div>
		
	</div>

    
 </body>
</html>