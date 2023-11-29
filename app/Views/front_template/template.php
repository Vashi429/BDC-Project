<?php $uri = service('uri'); 
?>
		<?php try {
			echo view($view);
		} catch (Exception $e) {
			echo "<pre><code>$e</code></pre>";
		} ?>
