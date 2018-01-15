<?php
$searchTerm = '';
if(!empty($_POST['s']))
{
    $searchTerm = $_POST['s'];
    $result = $db->execStoredProcedure('spTWSSASearchComment', array($searchTerm, ''));
}
else
{
    //$m->delete('latest_comments_cache');
    if($m->get('latest_comments_cache'))
    {
        $result = $m->get('latest_comments_cache');
    }
    else
    {
        $result = $db->execStoredProcedure('spGetLatestTWSSNAComments', array());
        $m->add('latest_comments_cache', $result, 10);
    }
}
?>
<section>
    <form id="search-form" action="/" method="post">
        <input type="text" name="s" value="<?php echo $searchTerm; ?>" placeholder="Search Comments..." />
        <input type="submit" value="SEARCH" />
    </form>
<?php
if(isset($_SESSION['user']))
{
?>
<a class="add-comment" href="/add-comment/">ADD COMMENT</a><br /><br />
<?php
}
?>

<?php
if($result)
{
    foreach ($result as $r)
    {
        $commentID = $r['id'];
        $userID = $r['userID'];
        $comment = $r['comment'];
        $dateAdded = $r['dateAdded'];
        $guid = $r['guid'];
        $username = $r['username'];

        $unicodeCharacters = strlen(bin2hex($comment));
        $twoLetterWordCount = two_letter_word_count($comment);
        $capitalLetterCount = capital_letter_count($comment);
        $longestWord = longest_word($comment);
?>
        <article>
            <p>
                <?php echo $comment; ?>
                <?php
                if(isset($_SESSION['user']) && $userID == $_SESSION['user']['id'])
                {
?>
                (<a class="delete" href="/delete-comment/?gid=<?php echo $guid; ?>">delete</a>)
<?php
                }
?>
            </p>
            <details>
                <summary>By <?php echo $username; ?> (click for details)</summary>
                <p>Number of unicode characters: <?php echo $unicodeCharacters; ?></p>
                <p>Number of two-letter words: <?php echo $twoLetterWordCount; ?></p>
                <p>Number of capital letters: <?php echo $capitalLetterCount; ?></p>
                <p>Longest word: "<?php echo $longestWord; ?>" <a href="/similar-comments/?gid=<?php echo $guid; ?>&s=<?php echo htmlentities($longestWord); ?>">See similar comments</a></p>
            </details>
        </article>
<?php
    }
}
else
{
?>
    <h3>No comments found!</h3>
<?php
}
?>
</section>