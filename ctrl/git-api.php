<?php
$page_title = 'Update Repositories';
$gitHub = new GitData();
$gitUpdateResponse = $gitHub->import_git_api_data();

// Output body content
ob_start();
?>
    <div id="content-wrapper">
        <p><strong><?php echo $gitUpdateResponse['git_items_received']; ?></strong> total repositories retrieved.</p>
        <p><strong><?php echo $gitUpdateResponse['git_items_added']; ?></strong> row(s) inserted.</p>
        <p><strong><?php echo $gitUpdateResponse['git_items_updated']; ?></strong> row(s) updated.</p>
    </div>
<?php
$output['content'] = ob_get_contents();
ob_clean();