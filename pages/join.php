<?php
$error = '';
if(!empty($_POST))
{
    if(empty($_POST['u']) || empty($_POST['p']))
    {
        $error = 'All fields required';
    }
    else if($_POST['p'] != $_POST['p2'])
    {
        $error = 'Your passwords do not match';
    }
    else
    {
        $result = $db->execStoredProcedure('spTWSSACreateAccount', array($_POST['u'], hash('sha512', $_POST['p'] . SALT_KEY)));
        if($result[0]['error']=='1')
        {
            $error = $result[0]['message'];
        }
        else
        {
            $_SESSION["user"] = ['id'=>$result[0]['id'], 'username'=>$result[0]['username'], 'dateCreated'=>$result[0]['dateCreated'], 'numComments'=>$result[0]['numComments'], 'guid'=>$result[0]['guid']];
            ob_end_clean();
            header('Location: /profile/');
        }
    }
}
?>
<section>
    <?php
    if(!empty($error))
    {
    ?>
    <p class="bad-message"><?php echo 'Error - ' . $error; ?></p>
    <?php
    }
    ?>
    <h3>JOIN</h3>
    <form action="/join/" method="post">
        <label>Username</label>
        <br />
        <input type="text" name="u" value="" />
        <br />
        <label>Password</label>
        <br />
        <input type="password" name="p" value="" />
        <br />
        <label>Repeat Password</label>
        <br />
        <input type="password" name="p2" value="" />
        <br />
        <input type="submit" value="JOIN" />
    </form>
</section>