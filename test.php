<?php
require 'api/src/facebook.php';
echo 'test';
// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '432757580141005',
  'secret' => 'a9815d8a32c12abfea412280ab6baa44',
));

// Get User ID
//$user = $facebook->getUser();

// We may or may not have this data based on whether the user is logged in.
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.
/*if ($user) {
  try {
    // Proceed knowing you have a logged in user who's authenticated.
    $user_profile = $facebook->api('/me');
    var_dump($user);die;
  } catch (FacebookApiException $e) {
    error_log($e);
    $user = null;
  }
}
*/
// Login or logout url will be needed depending on current user state.
if ($user) {
  $logoutUrl = $facebook->getLogoutUrl();
} else {
  $loginUrl = $facebook->getLoginUrl();
}
$qry= $_GET['qry'];
// This call will always work since we are fetching public data.

$user= $facebook->api('/marjss');
//print_r($user) ;
?>
<!doctype html>

<html>
<head>
<title>Bootstrap 101 Template</title>
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
           <form>
    <fieldset>
    <legend>Legend</legend>
    <label>Label name</label>
    <input type="text" placeholder="Type something" name="qry"><i class="icon-search"></i>
    <span class="help-block">Put here your search criteria.</span>
    <button type="submit" class="btn">Submit</button>
    </fieldset>
    </form>
    </div>
    <div class="span10">
    <!--Body content-->
    <?php else: ?>
      <div>
        Login using OAuth 2.0 handled by the PHP SDK:
        <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
      </div>
    <?php endif ?>

   
    <?php if ($user): ?>
      <h3>You</h3>
      <?php function GetData($qry){
      $search =file_get_contents("https://graph.facebook.com/search?q=".$qry);
                        $data=  json_decode($search);
                        return $data;
                       } ?>
      <?php 
      
      if(isset($qry)){
      $arr = GetData($qry);
      $i=0;
      foreach($arr->data as $data){?>
      
         From <?php echo $data->from->name; ?>
      The User Id's are: <?php echo $data->id;?><br>
       <?php $i++;} } ?>
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
</body>
</html>
