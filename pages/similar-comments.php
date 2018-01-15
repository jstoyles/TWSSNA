<?php
if(!empty($_GET['s']) && !empty($_GET['gid']))
{
    $result = $db->execStoredProcedure('spTWSSASearchComment', array($_GET['s'], $_GET['gid']));
}
else
{
    $result = null;
}
?>
<section>
    <h3>Similar comments to the selected comment...</h3><br />
<?php
if($result)
{
    foreach ($result as $r)
    {
        $commentID = $r['id'];
        $userID = $r['userID'];
        $comment = $r['comment'];
        $dateAdded = $r['dateAdded'];
        $username = $r['username'];
?>
        <article>
            <p><?php echo $comment; ?></p>
            <details>
                <summary>By <?php echo $username; ?> (click for details)</summary>
                <p>Number of unicode characters: <?php echo strlen(bin2hex($comment)); ?></p>
                <p>Number of two-letter words: <?php echo two_letter_word_count($comment); ?></p>
                <p>Number of capital letters: <?php echo capital_letter_count($comment); ?></p>
            </details>
        </article>
<?php
    }
}
else
{
?>
    <p>No similar comments to the comment you selected were found!</p>
<?php
}
?>
</section>