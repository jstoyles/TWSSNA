<?php
if(!empty($_POST))
{
    $result = $db->execStoredProcedure('spTWSSADeleteComment', array($_POST['gid'], $_SESSION['user']['guid']));
    $_SESSION["user"]["numComments"] = $result[0]['numComments'];
    ob_end_clean();
    header('Location: /');
}
?>
<section>
    <h3>CONFIRM DELETE COMMENT</h3>
    <br />
    Are you sure you want to delete the selected comment?<br />
    NOTE: This action will be permanent!<br />
    <form action="/delete-comment/" method="post">
        <input type="hidden" name="gid" value="<?php echo $_GET['gid']; ?>" />
        <input type="submit" value="DELETE" />
        <br />
        <span class="red">Results are updated every 10 seconds.</span>
    </form>
</section>