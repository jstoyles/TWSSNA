<?php
if(!empty($_POST))
{
    $result = $db->execStoredProcedure('spTWSSALogin', array($_POST['u'], hash('sha512', $_POST['p'] . SALT_KEY)));

    if($result)
    {
        $_SESSION["user"] = ['id'=>$result[0]['id'], 'username'=>$result[0]['username'], 'dateCreated'=>$result[0]['dateCreated'], 'numComments'=>$result[0]['numComments'], 'guid'=>$result[0]['guid']];
        ob_end_clean();
        header('Location: /');
    }
    else
    {
?>
<section>
    <span class="bad-message">INVALID LOGIN</span>
</section>
<?php
    }
}
?>
<section>
    <h3>LOGIN</h3>
    <form action="/login/" method="post">
        <label>Username</label>
        <br />
        <input type="text" name="u" value="" />
        <br />
        <label>Password</label>
        <br />
        <input type="password" name="p" value="" />
        <br />
        <input type="submit" value="LOGIN" />
    </form>
</section>