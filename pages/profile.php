<section>
    <h3>MY PROFILE</h3>
    <div id="profile">
        <p>
        <span>Username:</span>
        <?php
            echo $_SESSION['user']['username'];
        ?>
        </p>
        <p>
        <span>Date Created:</span>
        <?php
            echo $_SESSION['user']['dateCreated'];
        ?>
        </p>
        <p>
        <span>Number of Comments:</span>
        <?php
            echo $_SESSION['user']['numComments'];
        ?>
        </p>
    </div>
</section>