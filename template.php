<?php
$site_name = 'VICTR: GitHub Assessment';
$html_title = (!empty($page_title) ? $page_title.' | ' : '').$site_name;
$meta_desc = (isset($meta_desc) && !empty($meta_desc)) ? $meta_desc : 'GitHub API project for The Vanderbilt Institute for Clinical and Translational Research.';
$meta_keys = (isset($meta_keys) && !empty($meta_keys)) ? $meta_keys : 'VICTR,GitHub,PHP';
?>
<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $html_title; ?></title>
    <meta name="keywords" content="<?php echo $meta_keys; ?>">
    <meta name="description" content="<?php echo $meta_desc; ?>"/>

    <!-- OG sharing properties -->
    <meta property="og:site_name" content="<?php echo $site_name; ?>">
    <meta property="og:title" content="<?php echo $html_title; ?>">
    <meta property="og:description" content="<?php echo $meta_desc; ?>">
    <meta property="og:type" content="website">
    <link href="https://fonts.googleapis.com/css?family=Droid+Serif:700,700i|Work+Sans:400,700" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <script>
        window.onload = function () {
            document.getElementById("git-update-btn").addEventListener('click', function (event) {
                event.preventDefault();
                document.body.classList.add('loading-git');

                var choice = confirm('Are you sure you want to import GitHub data? This may take a moment.');

                if (choice) {
                    window.location.href = this.getAttribute('href');
                } else {
                    document.body.classList.remove('loading-git');
                }
            });
        }
    </script>
</head>

<body>
<div id="loading"></div>
<h1 id="site-title">VICTR GitHub API Project</h1>
<div id="site-container">
    <nav>
        <ul id="main-nav">
            <li><a href="/git-api" id="git-update-btn" class="button">Update Repositories</a></li>
            <li><a href="/list" class="button">List Repositories</a></li>
        </ul>
    </nav>
    <main>
        <hr>
        <?php echo $output['content']; ?>
    </main>
</div>

</body>
</html>
