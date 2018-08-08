<?
		require_once 'includes/paypal.callerservice.php';
		###########################################################################
		###	PAYPAL PRO ManageRecurringPaymentsProfileStatus 
		###########################################################################
		//PROCESS PAYMENT BY WEBSITE PAYMENTS PRO
					
		$profileID = urlencode($profileID);
		$action = urlencode($action);
		$desc = urlencode($description);
					
		$nvpStr="&PROFILEID={$profileID}&ACTION={$action}&NOTE={$desc}";
					
					
		//print $nvpStr."<br><br>" ;
		$httpParsedResponseAr = PPHttpPost('ManageRecurringPaymentsProfileStatus', $nvpStr);
		
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
			//exit('CreateRecurringPaymentsProfile Completed Successfully: '.print_r($httpParsedResponseAr, true));
			$my_status="<br/><div>Profile Updated Successful!<br/>";
			$my_status .= "Profile {$profileID} was {$action}<br/><br/>";
			$error=0;
			$mess = '<div class="ui-widget"><div class="ui-state-highlight ui-corner-all" style="padding: 0 .7em;">'.$my_status.'</div></div><br />';
		}else{
			$my_status="<br/><div>Error Updated Successful!<br/>";
			$my_status .= "Profile {$profileID} was {$action}<br/>";
			$my_status .="Error Message: ". urldecode($httpParsedResponseAr['L_LONGMESSAGE0'])."<br/><br/></div>";
			$error=1;
			$mess = '<div class="ui-widget"><div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">'.$my_status.'</div></div><br />';
		}
?>