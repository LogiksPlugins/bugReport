<?php
if(!defined("ROOT")) exit("Direct Access To This Script Not Allowed");

$cfg=loadFeature("bugReport");

$debugPage=explode("/",$_REQUEST['page']);
$debugPage=$debugPage[0];
if(strlen($cfg['show_on_pages'])<=0) {
	$debugPages=array();
} elseif($cfg['show_on_pages']=="*") {
	$debugPages=array($debugPage);
} else {
	$debugPages=explode(",",$cfg['show_on_pages']);
}
if(in_array($debugPage,$debugPages)) {
	$token=$cfg['public_token'];
	$pass=$cfg['private_token'];
	$submitLink=$cfg['submitlink'];
	if($submitLink=="##") {
		$submitLink=_service("bugReport");
	}
	_css("bugReport");
	
	$debug=getServerSpecs();
	if($cfg['params_for_server']=="*") {
		$cfg['params_for_server']=array_keys($debug);
	} else {
		$cfg['params_for_server']=explode(",",$cfg['params_for_server']);
	}
    $lc=new LogiksEncryption($pass);
	foreach($debug as $key => $value) {
		if(!in_array($key, $cfg['params_for_server'])) {
			unset($debug[$key]);
		} else {
			$debug[$key]=$lc->encode($value);
		}
	}
	$webpath=getWebPath(__FILE__);
?>
<script type="text/javascript" src="<?=$webpath?>html2canvas/script.js"></script>
<div id='bugReporter'>
	<a class="feedback">Make <?=APPS_NAME?> better!</a>
	<a class="debug right">Report Bug</a>
	<div class="debug-form innerform">
       <?php 
        include "plugins/debug.php";
       ?>
    </div>
    <div class="feedback-form innerform">
    	<?php 
        include "plugins/feedback.php";
       ?>
    </div>
    <div class='debugFooter'>
        Powered By Logiks
    </div>
    <iframe name="debugTargetFrame1" id="debugTargetFrame" style='display:none'></iframe>
</div>
<script>
$(function() {
	$('#bugReporter').delegate("a.feedback","click",function() {
		openDebugFrame($('#bugReporter .feedback-form'),this);
	});
	$('#bugReporter').delegate("a.debug","click",function() {
		openDebugFrame($('#bugReporter .debug-form'),this);
	});
	$('#bugReporter').delegate(".closeBtn","click",function() {
		$('#bugReporter').css("height","20px").removeClass("open");
		$('#bugReporter>a').removeClass("active");
		$('#bugReporter>.innerform').hide();
	});
	$("#screenshotsLoader").click(function() {
		loadScreenshot();
	});
	$("#screenshotsCount").click(function() {
		if($("div.screenshots.attachment textarea").length<=0) {
			return false;
		}
		html="<style>img {width:70%;padding: 10px;margin-left: 15%;border-bottom:2px dashed #777;}</style>";
		$("div.screenshots.attachment textarea").each(function() {
			data=$(this).val();
			html+="<img src='"+data+"' />";
		});
		window.open(null,"Screenshots").document.write(html);
	});
    if($.browser.safari==true) {
        $('#bugReporter').disableSelection();
    }
    //$("#bugReporter form").attr("target","_blank");
});
function openDebugFrame(framePane,btn) {
	if(framePane.is(":visible")) {
		framePane.hide();
        $("#bugReporter .debugFooter").hide();
		$('#bugReporter').css("height","20px").removeClass("open");
		$(btn).removeClass("active");
	} else {
		$('#bugReporter .innerform').hide();
		$('#bugReporter>a').removeClass("active");
		$('#bugReporter').css("height","340px").addClass("open");	
		framePane.show();
        $("#bugReporter .debugFooter").show();
		$(btn).addClass("active");
	}
}
function loadScreenshot() {
	$('#bugReporter').css("display","none");
	$("div.screenshots.attachment").html("");
	html2canvas(document.body, {
	    onrendered: function(canvas) {
	    	// data is the Base64-encoded image
		    var data = canvas.toDataURL();
		    html=$("<textarea name='screenshots[]'></textarea>");
		    html.val(data);
		    $("div.screenshots.attachment").append(html);
		    $("#screenshotsCount").html("Show ("+$("div.screenshots.attachment textarea").length+")");

		    $('#bugReporter').css("display","");
	    }
	});
	$("iframe:visible").each(function() {
		frm=$(this).contents().find("body").get(0);
		html2canvas(frm, {
		    onrendered: function(canvas) {
			    var data = canvas.toDataURL();
			    html=$("<textarea name='screenshots[]'></textarea>");
			    html.val(data);
			    $("div.screenshots.attachment").append(html);
			    $("#screenshotsCount").html("Show ("+$("div.screenshots.attachment textarea").length+")");
		    }
		});
	});
}
function validateBugForm(form,type) {
	ans=true;
	$("input[name].required,textarea[name].required,select[name].required",form).each(function() {
		if(ans && ($(this).val()==null || $(this).val().length<=0 || $(this).val()==$(this).attr('value'))) {
			ans=false;
		}
	});
	if(!ans) {
		showMessage("All required fields must be filled.","error");
		return false;
	}
	$("input[type=email]",form).each(function() {
		if(ans && !validateEmail($(this).val())) {
			ans=false;
		}
	});
	if(!ans) {
		showMessage("A valid emailid is required to proceed.<br/>\nWe will not bother you on your emails or phone.","error");
		return false;
	}
	return true;
}
function showMessage(msg,type) {
	if(type==null) type="plain";
	lgksAlert(msg);
}
function validateEmail(email) {
	if(email==null || email.length<=0) return true;
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}
</script>
<?php
}
function getServerSpecs() {
    $debug=array("server"=>"","session"=>"","php"=>"","userid"=>"guest","privilegeid"=>"guest");
    if(isset($_SESSION['SESS_PRIVILEGE_ID'])) {
        $debug['privilegeid']=$_SESSION['SESS_PRIVILEGE_ID'];
    }
    if(isset($_SESSION['SESS_USER_ID'])) {
        $debug['userid']=$_SESSION['SESS_USER_ID'];
    }
    ob_start();
    phpinfo();
    $debug['php']=ob_get_contents();
    ob_clean();

    $dataSess=$_SESSION;
    unset($dataSess['DataBus']);unset($dataSess['DASHBOARD']);
    ob_start();
    printArray($dataSess);
    $debug['session']=ob_get_contents();
    ob_clean();

    $dataSess=$_SERVER;
    ob_start();
    printArray($dataSess);
    $debug['server']=ob_get_contents();
    ob_clean();

    return $debug;
}
?>
