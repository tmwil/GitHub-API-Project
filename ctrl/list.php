<?php
$gitHub = new GitData();

$repo_page = (isset($url_segments[1]) && is_numeric($url_segments[1]) ? $url_segments[1] : 1);
$repos = $gitHub->get_items($repo_page);

$page_title = 'List Repositories (Page '.$repo_page.')';

// Output body content
ob_start();
?>
<?php if (empty($repos)): ?>
    <div id="content-wrapper">
        <p class="list-error">No repositories currently loaded. Please click <strong>"Update Repositories"</strong> above to retrieve the most starred repositories from GitHub.</p>
    </div>
<?php else: ?>
    <ul class="list-pages">
        <?php for ($x = 1; $x <= $gitHub->total_pages; $x++): ?>
            <li>
                <?php if ($x == $repo_page): ?>
                    <strong class="faux-button"><?php echo $x; ?></strong>
                <?php else: ?>
                    <a href="/list/<?php echo $x; ?>" class="button" title="Go to page <?php echo $x; ?>"><?php echo $x; ?></a>
                <?php endif; ?>
            </li>
        <?php endfor; ?>
    </ul>
    <hr>
    <div id="content-wrapper">
        <ol start="<?php echo ($gitHub->return_per_page * ($repo_page - 1)) + 1; ?>" id="git-list-ol">
            <?php foreach ($repos AS $repo): ?>
                <li>
                    <a href="/view/<?php echo $repo['id']; ?>" title="View details for GitHub repository <?php echo $repo['name']; ?>"><?php echo $repo['name']; ?></a> :: &#x2605;<?php echo $repo['stars']; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>
    <hr>
    <ul class="list-pages">
        <?php for ($x = 1; $x <= $gitHub->total_pages; $x++): ?>
            <li>
                <?php if ($x == $repo_page): ?>
                    <strong class="faux-button"><?php echo $x; ?></strong>
                <?php else: ?>
                    <a href="/list/<?php echo $x; ?>" class="button" title="Go to page <?php echo $x; ?>"><?php echo $x; ?></a>
                <?php endif; ?>
            </li>
        <?php endfor; ?>
    </ul>
<?php endif; ?>
<?php
$output['content'] = ob_get_contents();
ob_clean();