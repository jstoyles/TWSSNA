<?php
if(!empty($_POST))
{
    $result = $db->execStoredProcedure('spTWSSAInsertComment', array($_SESSION['user']['id'], $_POST['c']));
    ob_end_clean();
    header('Location: /');
}
?>
<section>
    <h3>ADD A NEW COMMENT</h3>
    <br />
    <form id="search-form" action="/add-comment/" method="post">
        <textarea name="c"></textarea><br />
        <input type="submit" value="ADD COMMENT" />
        <br />
        <span class="red">Results are updated every 10 seconds.</span>
    </form>
</section>