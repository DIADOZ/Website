<?php
	include 'dbConnect.php';
	
	$id = $_POST['id'];
						
	$sql_overlay = 'SELECT * FROM posts WHERE Post_ID = '.$id.';';
		
	$result_overlay = mysqli_query($conn, $sql_overlay);
		
	if($result_overlay){
		while($row_overlay = mysqli_fetch_assoc($result_overlay)){ ?>
			
			<h1><?php echo $row_overlay['Post_Title']; echo $row_overlay['Post_Date']; ?></h1>
			<img src="images/<?php echo $row_overlay['Post_Image_Embed']; ?>" width="100%">
			<?php
			if (!$row_overlay['Post_Video_Embed'] == null){
				echo $row_overlay['Post_Video_Embed'];
			}
			if (!$row_overlay['Post_Music_Embed'] == null){
				echo $row_overlay['Post_Music_Embed'];
			}
			?>
		<?php echo $row_overlay['Post_Credit']; ?>
		<p><?php echo $row_overlay['Post_Content']; ?><?php echo $row_overlay['Post_Votes']; ?></p>
		<?php
		}
	}
?>