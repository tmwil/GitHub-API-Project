<?php
$page_title = '404 error';

// Output body content
ob_start();
?>
    <div id="content-wrapper">
        <h2>404: Content not found!</h2>
		  <p>We were unable to find the page you requested. Please check the url and try again.</p>
    </div>
<?php
$output['content'] = ob_get_contents();
ob_clean();