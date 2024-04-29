<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Amazone Marketing Site</title>
	<link rel="icon" type="image/png" href="images/logo.png">
	<link rel="stylesheet" href="styles.css">
</head>
<body>
	<section class="products">
	<div class="container">
		<?php
			// Fetch product data from the database
			$db = mysqli_connect('localhost', 'dev', 'dev', 'marketing');
			$query = "SELECT * FROM product_data";
			$result = mysqli_query($db, $query);

			// Check if any rows were returned
			if(mysqli_num_rows($result) > 0) {
				// Loop through each row and generate HTML code for each product
				while($row = mysqli_fetch_assoc($result)) { ?>
				<div class="product">
					<a href="<?= $row['product_link'] ?>" class="product-link" target="_blank" onclick="getHrefAndIPAddress(event)">
						<img src="<?= $row['product_image'] ?>" alt="Product 1" >
						<h3 class="product-title"><?=  $row['product_name'] ?></h3>
					</a>
					<p><?= $row['product_description'] ?></p>
				</div>
				<?php
				}
			} else {
				// If no products are found in the database
				echo '<p>No products found.</p>';
			}
			?>
	</div>
	</section>
	<script>
		function getHrefAndIPAddress(event) {
			// Prevent default action of the event
			event.preventDefault();

			// Get the href attribute of the <a> tag
			var href = event.target.closest('a').getAttribute('href');
			
			// Get the IP address of the user
			var ipAddress = '<?php echo $_SERVER['REMOTE_ADDR']; ?>'; // Assuming PHP is enabled
			
			// Do something with the href and IP address
			console.log("Href: " + href);
			console.log("IP Address: " + ipAddress);
			var data = {
				ip: ipAddress,
				product_link: href
			};
			// Send an AJAX request to a server-side script (e.g., PHP) to handle the database operation
			var xhr = new XMLHttpRequest();
			xhr.open("POST", "insert_data.php", true);
			xhr.setRequestHeader("Content-Type", "application/json");
			xhr.onreadystatechange = function () {
				if (xhr.readyState === XMLHttpRequest.DONE) {
					if (xhr.status === 200) {
						console.log("Data inserted successfully.");
					} else {
						console.error("Error inserting data:", xhr.responseText);
					}
				}
			};
			xhr.send(JSON.stringify(data));
			window.open(href, '_blank');
		}
	</script>
</body>
</html>