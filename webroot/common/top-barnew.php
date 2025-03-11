<?php
error_reporting(1);

if ($teamList = $conn->query("SELECT tl.id,tl.team_name,ck.id as cid,ck.customer_name FROM users_teams uo  LEFT JOIN team tl ON tl.id=uo.team_id LEFT JOIN customer_detail ck ON uo.customer_id=ck.id WHERE  uo.user_id='" . $_SESSION['userid'] . "' AND tl.status=1 ORDER BY tl.team_name ASC")) {
    $teamData = $teamList->num_rows;
}

?>
<section class="topBar ncrGrey relative chat-box-center">
    <div class="navBrand ">
        <div class="brand-logo">
            <img src="img/ncr.png" class="img-responsive" alt=""> 
            <form action="" class="uid-bx" method="post" id="formcustom">
                <span class="udi-b">User : <?php echo $userDetail['user_id'] ?></span>
                <?php if ($teamData <= 1) { ?><a href="#" class="v-btn btn-sm"><?php echo $teamName['team_name'] ?></a><?php } else { ?>
               
                    
                <select name="teamcustom" id="" class="form-control"  onchange="getteamdata();">
                        <?php
                       
                        while ($teamData = $teamList->fetch_array(MYSQLI_ASSOC)) {
                             $selected = '';
                            if ($teamData['id'] == $_SESSION['teamid'] && $teamData['cid']==$_SESSION['customer_id'])
                                $selected = 'selected';
                            echo '<option value="' . $teamData['id'] . '-' . $teamData['cid'] . '" ' . $selected . '>' . $teamData['team_name'] . ' (' . $teamData['customer_name'] . ')</option>';
                        }
                        ?>

                    </select>
                
                <?php } ?>
            </form>           
        </div>
        <ul class="pull-right">
            <li><button class="v-btn v-btn-primary btn-sm cb-btn" data-toggle="modal" data-target="#myModal">Report Issue</button></li>
            
            <li><a href="login.php" class="v-btn v-btn-primary btn-sm cb-btn" onclick="" style="background-color:red;color:#fff" title="exit from chatbot">Logout</a><li>
        </ul>
        
<!--        <a href="#"><img src="img/ncr.png" class="img-responsive"><?php //echo $_SESSION['settings']['name']    ?></a>		

        <ul class="inline-view pull-right userid">
            <li>
                
                <?php if ($teamData <= 1) { ?><a href="#" class="v-btn btn-sm"><?php echo $teamName['team_name'] ?></a><?php } else { ?>
                <form method="post" id="formcustom">
                    <a href="#" class=""><b>User : </b> <?php echo $userDetail['user_id'] ?></a>
                <select name="teamcustom" id="" class="form-control" style="width:73px !important;height:27px;font-size:12px" onchange="getteamdata();">
                        <?php
                       
                        while ($teamData = $teamList->fetch_array(MYSQLI_ASSOC)) {
                             $selected = '';
                            if ($teamData['id'] == $_SESSION['teamid'])
                                $selected = 'selected';
                            echo '<option value="' . $teamData['id'] . '-' . $teamData['cid'] . '" ' . $selected . '>' . $teamData['team_name'] . ' (' . $teamData['customer_name'] . ')</option>';
                        }
                        ?>

                    </select>
                </form>
                <?php } ?>
            </li>
            <li></li>
            <li><button class="v-btn v-btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Report Issue</button></li>
            <li><a href="login.php" class="v-btn v-btn-primary btn-sm" onclick="" style="background-color:red;color:#fff" title="exit from chatbot">exit</a></li>
        </ul>	-->

    </div>
</section>

