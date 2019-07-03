<?php

if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') == 0){
	//Request hash
	$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
	if(strcasecmp($contentType, 'application/json') == 0){
		$data = json_decode(file_get_contents('php://input'));
		$hash=hash('sha512', $data->key.'|'.$data->txnid.'|'.$data->amount.'|'.$data->pinfo.'|'.$data->fname.'|'.$data->email.'|||||'.$data->udf5.'||||||'.$data->salt);
		$json=array();
		$json['success'] = $hash;
    	echo json_encode($json);

	}
	exit(0);
}

function getCallbackUrl()
{
	$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . 'response.php';
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style >
html, body{
margin: 0 auto;
}
</style>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Donation Page</title>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<!-- this meta viewport is required for BOLT //-->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" >
<!-- BOLT Sandbox/test //-->
<script id="bolt" src="https://sboxcheckout-static.citruspay.com/bolt/run/bolt.min.js" bolt-
color="e34524" bolt-logo="http://boltiswatching.com/wp-content/uploads/2015/09/Bolt-Logo-e14421724859591.png"></script>
<!-- BOLT Production/Live //-->
<!--// script id="bolt" src="https://checkout-static.citruspay.com/bolt/run/bolt.min.js" bolt-color="e34524" bolt-logo="http://boltiswatching.com/wp-content/uploads/2015/09/Bolt-Logo-e14421724859591.png"></script //-->


</head>
<style type="text/css">

	.main {
		
		font-family:Verdana, Geneva, sans-serif, serif;
	}
	.text {
		float: left;
		width:300px;
	}
	.dv {
		margin-bottom:8px;
	}
	
	
	.topleft {
  
  top: 8px;
  left: 16px;
  font-size: 30px;
  color:#FFF;
  
}

</style>
<body>
	
	<div class="main" style="background-image: url(images/back1.png);
	background-size: 100%;
	background-repeat: no-repeat;
	width: 1213px;
	color:white;
	padding: 25px;">

    <div>
	<img src="images/logo1.png" align="left" height="160" width="170"><p><br><h1><b><u>Helping Hands Organisation</u></b></h1></div></p>
	
	<form action="#" id="payment_form">
    <input type="hidden" id="udf5" name="udf5" value="BOLT_KIT_PHP7" />
    <input type="hidden" id="surl" name="surl" value="<?php echo getCallbackUrl(); ?>" />
    <div class="dv">
    <span class="text"><label></label></span>
    <span><input type="hidden" id="key" name="key" placeholder="Merchant Key" value="bMKmWzvq" /></span>
    </div>

    <div class="dv">
    <span class="text"><label></label></span>
    <span><input type="hidden" id="salt" name="salt" placeholder="Merchant Salt" value="1ie9JhPsCV" /></span>
    </div>

    <div class="dv">
    <span class="text"><label></label></span>
    <span><input type="hidden" id="txnid" name="txnid" value="<?php echo  "Txn" . rand(10000,99999999)?>" /></span>
    </div>
		<br><br>
	  <h5>Please fill in the details below to donate</h5>
    <div class="dv">
    <span class="text"><label>Amount:</label></span>
    <span><input type="text" id="amount" name="amount" value="" /></span>
    </div>

    <div class="dv">
    <span class="text"><label></label></span>
    <span><input type="hidden" id="pinfo" name="pinfo"  value="P01,P02" /></span>
    </div>

    <div class="dv">
    <span class="text"><label>Name:</label></span>
    <span><input type="text" id="fname" name="fname"  value="" /></span>
    </div>

  	<div class="dv">
    <span class="text"><label>Email ID:</label></span>
    <span><input type="text" id="email" name="email"  value="" /></span>
		</div>

    <div class="dv">
    <span class="text"><label>Mobile Number:</label></span>
    <span><input type="text" id="mobile" name="mobile"  value="" /></span>
    </div> </b>

    <div class="dv">
    <span class="text"><label></label></span>
    <span><input type="hidden" id="hash" name="hash"  value="" /></span>
    </div>
 <br>
   <input type="submit" value="DONATE" onclick="launchBOLT(); return false;" />
	</form>


<h4> ABOUT US</h4>
<marquee> 
Our Vision is to work as a catalyst in bringing sustainable change in the lives of the underprivileged children and build an approach for development. Helping Hands Organisation was founded on the principle that true education requires head, heart and hand, and that less-privileged children should receive the same exposure and facilities available at the country’s best schools.
</marquee>

<h4> CONTACT US</h4>
<marquee>
GENERAL QUERIES: donorcare@helpinghands.org, xyz@helpinghands.org //CONTACT NUMBER: +918144, +9188002 //ADDRESS: Plot No.4, XYZ Road, Mumbai
</marquee>


<script type="text/javascript"><!--
$('#payment_form').bind('keyup blur', function(){
	$.ajax({
          url: 'index.php',
          type: 'post',
          data: JSON.stringify({
            key: $('#key').val(),
			salt: $('#salt').val(),
			txnid: $('#txnid').val(),
			amount: $('#amount').val(),
		    pinfo: $('#pinfo').val(),
            fname: $('#fname').val(),
			email: $('#email').val(),
			mobile: $('#mobile').val(),
			udf5: $('#udf5').val()
          }),
		  contentType: "application/json",
          dataType: 'json',
          success: function(json) {
            if (json['error']) {
			 $('#alertinfo').html('<i class="fa fa-info-circle"></i>'+json['error']);
            }
			else if (json['success']) {
				$('#hash').val(json['success']);
            }
          }
        });
});
//-->
</script>
<script type="text/javascript"><!--
function launchBOLT()
{
	bolt.launch({
	key: $('#key').val(),
	txnid: $('#txnid').val(),
	hash: $('#hash').val(),
	amount: $('#amount').val(),
	firstname: $('#fname').val(),
	email: $('#email').val(),
	phone: $('#mobile').val(),
	productinfo: $('#pinfo').val(),
	udf5: $('#udf5').val(),
	surl : $('#surl').val(),
	furl: $('#surl').val(),
	mode: 'dropout'
},{ responseHandler: function(BOLT){
	console.log( BOLT.response.txnStatus );
	if(BOLT.response.txnStatus != 'CANCEL')
	{
		//Salt is passd here for demo purpose only. For practical use keep salt at server side only.
		var fr = '<form action=\"'+$('#surl').val()+'\" method=\"post\">' +
		'<input type=\"hidden\" name=\"key\" value=\"'+BOLT.response.key+'\" />' +
		'<input type=\"hidden\" name=\"salt\" value=\"'+$('#salt').val()+'\" />' +
		'<input type=\"hidden\" name=\"txnid\" value=\"'+BOLT.response.txnid+'\" />' +
		'<input type=\"hidden\" name=\"amount\" value=\"'+BOLT.response.amount+'\" />' +
		'<input type=\"hidden\" name=\"productinfo\" value=\"'+BOLT.response.productinfo+'\" />' +
		'<input type=\"hidden\" name=\"firstname\" value=\"'+BOLT.response.firstname+'\" />' +
		'<input type=\"hidden\" name=\"email\" value=\"'+BOLT.response.email+'\" />' +
		'<input type=\"hidden\" name=\"udf5\" value=\"'+BOLT.response.udf5+'\" />' +
		'<input type=\"hidden\" name=\"mihpayid\" value=\"'+BOLT.response.mihpayid+'\" />' +
		'<input type=\"hidden\" name=\"status\" value=\"'+BOLT.response.status+'\" />' +
		'<input type=\"hidden\" name=\"hash\" value=\"'+BOLT.response.hash+'\" />' +
		'</form>';
		var form = jQuery(fr);
		jQuery('body').append(form);
		form.submit();
	}
},
	catchException: function(BOLT){
 		alert( BOLT.message );
	}
});
}
//--
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight){
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}
</script>
</body>
</html>
