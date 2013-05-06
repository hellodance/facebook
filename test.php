<?php 
require 'api/src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
            'appId' => '544594275570740',
            'secret' => 'deb6bd4b0bc2d229b03db2a66e3b75c6',
        ));

// Get User ID
//$user = $facebook->getUser();
// We may or may not have this data based on whether the user is logged in.
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.
/* if ($user) {
  try {
  // Proceed knowing you have a logged in user who's authenticated.
  $user_profile = $facebook->api('/me');
  var_dump($user);die;
  } catch (FacebookApiException $e) {
  error_log($e);
  $user = null;
  }
  }
 */?>


<?php
// Login or logout url will be needed depending on current user state.
if ($user) {
    $logoutUrl = $facebook->getLogoutUrl();
} else {
    $loginUrl = $facebook->getLoginUrl();
}
$ownerid= $_POST['owner'];
$lim = $_POST['limit'];
$qry = $_POST['qry'];
// This call will always work since we are fetching public data.

$user = $facebook->api('/marjss');
//print_r($user) ;
?>
<!doctype html>

<html>
    <head>
        <title>New FB Album Viewer -Author: Sudhanshu Saxena</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Bootstrap -->
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span2">

                    <?php if ($user): ?>
                        <?php //print_r($user); ?>
                        <a href="<?php echo $logoutUrl; ?>">Logout<i class="icon-user"></i></a>
                        <form method="post" action="<? $_SERVER['PHP_SELF']?>" >
                            <fieldset>
                                <legend>Legend</legend>
                                <label>Label name</label>
                                <input type="text" placeholder="Type something" name="qry"><i class="icon-search"></i>
                                <input type="text" placeholder="Limit" name="limit">
                                <input type="text" placeholder="Owner Id" name="owner">
                                <span class="help-block">Put here your search criteria.</span>
                                <button type="submit" class="btn">Submit</button>
                            </fieldset>
                        </form>
                        <?php 
//                        function PageOwner($owner,$limit){
//                    $facebook_page_owner = $owner;
                    $facebook_page_owner ='punjaban.mutiyara';
                    $string = file_get_contents('https://graph.facebook.com/' . $facebook_page_owner . '/albums?fields=id,name,cover_photo');
                    
                    $jdata = json_decode($string);
                    
                    $albumcount = count($jdata->data);

                    echo "There are " . $albumcount . " Albums in this Facebook page.<br /><br />";

                    echo "<form method=\"post\" action=\"" . $_SERVER['PHP_SELF'] . "\">";

                    echo "<select id=\"facebookalbumid\" name=\"facebookalbumid\">";
                    
                    $facebookalbumid = $_POST['facebookalbumid'];
                    $i = 0;
                    while ($i <= ($albumcount - 1)) {
                        $selected = ($jdata->data[$i]->id == $facebookalbumid) ? "selected='selected'" : "";
                        if ($facebookalbumid == "") {
                            $facebookalbumid = $jdata->data[$i]->id;
                            echo $facebookalbumid;
                        }?>

                        <option value="<?php echo $jdata->data[$i]->id ;?>"> <?php echo $jdata->data[$i]->name   ;?> </option>
                       <?php $i++; }
                       
                    echo "</select>";
                    $url= 'https://graph.facebook.com/'.$facebookalbumid.'/photos?fields=images&limit=100';
                    
                    $album = file_get_contents($url);
                    $jalbm = json_decode($album);
//                    print_r($jalbm);die;
                    echo " <input type=\"submit\" value=\"View Album\"></form><br />";
                    $string2 = file_get_contents('https://graph.facebook.com/fql?q=SELECT%20name,%20created%20FROM%20album%20WHERE%20owner=' . $facebook_page_owner . '%20and%20object_id=' . $facebookalbumid . '');
                    $adata = json_decode($string2);
                    $facebookalbumname = $adata->data[0]->name;
                    $albumdate = $adata->data[0]->created;
                    $facebookalbumdate = date("d-M-Y", $albumdate);
                    echo $facebookalbumname . "<br />";
                    echo $facebookalbumdate . "<br />";
//                    return $jalbm;
//                        }
//                    if(isset($_POST['owner'])){
//                    echo $facebookalbumname . "<br />";
//                    echo $facebookalbumdate . "<br />";
//                    $jalbm = PageOwner($ownerid,$lim);
//                    }
                    ?>
                    </div>
                    <div class="span10">
                        <!--Body content-->
                    <?php else: ?>
                        <div>
                            Login using OAuth 2.0 handled by the PHP SDK:
                            <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
                        </div>
                    <?php endif ?>
                    
<!--                         <ul class="thumbnails">-->
                    <?php 
                    
                    if($jalbm){
                    $j = 0;
                    foreach ($jalbm->data as $pic) {
                      
                           
                                $src=$pic->images['0']->source;
                                $height =$pic->images['0']->height;
                                $width =$pic->images['0']->width;
                               ?>
                               
                       
                                    <li class="span4">
                                        <a href="<?php  echo $src; ?>" class="thumbnail" rel="prettyPhoto[gallery1]">
                                            <img src="<?php  echo $src; ?>" alt="Ileana">
                                        </a>
                                    </li>
                                                
                               

                        <!--<img src="<?php // echo $pic->picture; ?>" >-->
                        <?php $j++;
                    }} ?>
                    <!--</ul>-->
                   

                    <?php if ($user): ?>
                        <?php

                        function GetData($qry) {
                            $search = file_get_contents("https://graph.facebook.com/search?q=" . $qry);
                            $data = json_decode($search);

                            return $data;
                        }
                        ?>
                        <?php
                        if ($qry!='') {
                           
                            $arr = GetData($qry);
                            $i = 0;
                            foreach ($arr->data as $data) {
                                ?>

                                From <?php echo $data->from->name; ?>
                                The User Id's are: <?php echo $data->id; ?><br>
                                <img src="<?php echo $data->picture; ?>" height="150" width="120">
            <?php $i++;
        }
    } ?>
                        <img src="<?php echo $data->picture->data->url; ?>" height="150" width="120">
                        <img src="<?php echo $data->cover->source; ?>">


<?php else: ?>
                        <strong><em>You are not Connected.</em></strong>
<?php endif ?>
                </div>
            </div>
        </div>



        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="bootstrap/js/bootstrap.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js" type="text/javascript"></script>
        <script src="pretty/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="pretty/css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
		
                
        <script type="text/javascript" charset="utf-8">
			$(document).ready(function(){
				$("a[rel^='prettyPhoto']").prettyPhoto();
				
//				$(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:3000, autoplay_slideshow: true});
//				$(".gallery:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'fast',slideshow:10000, hideflash: true});
//		
//				$("#custom_content a[rel^='prettyPhoto']:first").prettyPhoto({
//					custom_markup: '<div id="map_canvas" style="width:260px; height:265px"></div>',
//					changepicturecallback: function(){ initialize(); }
//				});
//
//				$("#custom_content a[rel^='prettyPhoto']:last").prettyPhoto({
//					custom_markup: '<div id="bsap_1259344" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div><div id="bsap_1237859" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6" style="height:260px"></div><div id="bsap_1251710" class="bsarocks bsap_d49a0984d0f377271ccbf01a33f2b6d6"></div>',
//					changepicturecallback: function(){ _bsap.exec(); }
//				});
			});
			
            </script>
    </body>
</html>
