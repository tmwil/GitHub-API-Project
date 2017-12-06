<?php
$gitHub = new GitData();

if (isset($url_segments[1]) && is_numeric($url_segments[1])) {
    $gitHub->load($url_segments[1]);
    $page_title = $gitHub->data['name'];
    $meta_desc = $gitHub->data['description'];
}
if (empty($gitHub->data)) {
    header('Location: /list');
    exit();
}

// Output body content
ob_start();
?>
    <div id="content-wrapper">
        <h2><?php echo $gitHub->data['name']; ?></h2>
        <p>
            <strong>Stars:</strong> <?php echo $gitHub->data['stars']; ?><br>
            <strong>Repository ID:</strong> <?php echo $gitHub->data['id']; ?><br>
            <strong>URL:</strong> <a href="<?php echo $gitHub->data['url']; ?>"
                                     target="_blank"><?php echo $gitHub->data['url']; ?></a><br>
            <strong>Date Created:</strong> <?php echo date('n/j/Y g:ia', strtotime($gitHub->data['date_created'])); ?>
            <br>
            <strong>Last Push Date:</strong> <?php echo date(
                'n/j/Y g:ia',
                strtotime($gitHub->data['date_last_push'])
            ); ?>
            <br>
        </p>
        <p>
            <strong>Description:</strong><br>
            <?php echo $gitHub->data['description']; ?>
        </p>
    </div>
<?php
$output['content'] = ob_get_contents();
ob_clean();