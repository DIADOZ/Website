<!DOCTYPE html>

<?php include 'dbConnect.php'; ?>

<html lang="en">
	<head>
		<title>DIADOZ</title>
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<meta charset="UTF-8">

		<!-- CSS in use -->
		<link rel="stylesheet" href="stylesheets/bootstrap.min.css">
		<link rel="stylesheet" href="stylesheets/stylesheet.css">

		<!-- JavaScript in use -->
		<script src="js/jquery-1.11.1.min.js"></script>
		<script src="js/jquery.mousewheel.min.js"></script>
		<script src="js/bootstrap.min.js"></script>

		<script>
			$(function() {
				// Horizontal Scrolling
				$("body").mousewheel(function(event, delta) {
					this.scrollLeft -= (delta * 20);
					event.preventDefault();
				});

				// Add posts depending on database size
				// Will need to change to dynamically load data as user scrolls
				function xHandler(){
					var wrap = document.getElementById('supremeBody');
					var contentWidth = wrap.offsetWidth;
					var xOffset = window.pageXOffset; 
					var x = xOffset + window.innerWidth;
					if(x >= contentWidth){
						// Ajax call to get more dynamic data goes here
						var loadNew = document.getElementById('loading');
						loadNew.innerHTML = "Loading Post";
					}
					var status = document.getElementById('status');
					status.innerHTML = contentWidth+" | "+x;
				}
				window.onscroll = xHandler;

				// Toggles the overlay by which post is clicked
				// Toggle works by checking CSS for overlay display setting and doing the opposite
				$(document).on('click', '.openOverlay, .closeOverlay', toggleOverlay);
				
				function toggleOverlay() {
					var postID = $(this).attr('name');
					var specialBox = document.getElementById('specialBox');
					var container = document.getElementById('container');
					
					if(container.style.display == "block"){
						document.body.style.overflowY = "hidden";
						container.style.display = "none";
						specialBox.style.display = "none";
						$("body").mousewheel(function(event, delta) {
							this.scrollLeft -= (delta * 20);
							event.preventDefault();
						});
						$('div#postOverlay').empty();
					} else {
						document.body.style.overflowX = "hidden";
						container.style.display = "block";
						specialBox.style.display = "block";
						$("body").unmousewheel();
						$.ajax({  
							type: "POST",  
							url: "overlayFill.php", 
							data: { 'id': postID },  
							success: function(data){  
								 $('div#postOverlay').append(data);
							}  
						});  
					}
					event.preventDefault();
				}
			});
		</script>
		<style>
			div#status{position:fixed; font-size:24px; color: white;}
		</style>
	</head>

	<body>
		<div id="status">0 | 0</div>

		<!-- Overlay Container -->
		<div id="container">
			<div id="specialBox">
				<a class="closeOverlay" name="" href="www.diadoz.com">Close</a>
				<div id="postOverlay"></div>
			</div>
		</div>

		<!-- Logo and Posts Table -->
		<div class="supremeBody" id="supremeBody">
			<table class="mainTable">
				<tr>
					<td>
						<img class="startLogo" src="images/rawLogo.png">
					</td>

					<!-- Posts -->
					<?php
						$sql = 'SELECT * FROM posts ORDER BY Post_ID DESC';
		
						$result = mysqli_query($conn, $sql);

						if($result){
							while($row = mysqli_fetch_assoc($result)){
					?>
					<td>
						<div class="<?php if($row['Post_Featured'] == 0) {echo 'visualPost';} else {echo 'featuredPost';} ?>" id="<?php echo $row['Post_ID']; ?>">
							<a class="openOverlay" name="<?php echo $row['Post_ID']; ?>" href="www.diadoz.com">
								<?php echo $row['Post_Title']; ?>
							</a>
							<img src="images/<?php echo $row['Post_Image_Embed']; ?>" width="100%">
							<p><?php echo $row['Post_Blurb']; ?> Votes:<?php echo $row['Post_Votes']; ?></p>
						</div>
					</td>
					<!-- Add 750 width for each post -->
					<script>
						$('.supremeBody').css("width", "+=750");
					</script>
					<?php
							}
						}
					?>
				</tr>
			</table>
		</div>
		<div id="loading"></div>
	</body>
</html>