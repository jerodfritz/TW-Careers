					<div class="search">
						<form action="<?php echo $baseUrl; ?>search/" method="GET">
							<input type="text" class="search-keywords" name="search" value="<?php if (!empty($_GET['search'])) echo htmlentities($_GET['search']); ?>" size="45" />
							<input type="submit" class="search" value="Submit" />
						</form>
					</div>