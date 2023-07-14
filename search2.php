<?php
	if(ISSET($_POST['search'])){
		$keyword = $_POST['keyword'];
?>

<div>
	<hr style="border-top:1px groove;"/>
	<?php
		require 'conn.php';
		$query = mysqli_query($conn, "SELECT * FROM `blog1` WHERE `title` LIKE '%$keyword%' ORDER BY `title`") or die(mysqli_error());
		while($fetch = mysqli_fetch_array($query)){
	?>
	<div style="word-wrap:normal;">
		<p class="h1 font-weight-bold"<?php echo $fetch['blog_id']?>"><h4><?php echo $fetch['title']?></h4></p>
		<p class="font-italic"><?php echo substr($fetch['content'], 0, 100)?></p>
		<p class="h4 font-weight-light"><?php echo substr($fetch['address'], 0, 100)?></p>
		<p class="h4 font-weight-light"><?php echo substr($fetch['pluriel'], 0, 100)?></p>
	</div>
	<hr style="border-bottom:1px solid;"/>
	<?php
		}
	?>
</div>
<?php
	}
?>