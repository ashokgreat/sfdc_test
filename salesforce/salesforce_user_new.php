<?php
header('Content-type: application/json');
require('../config.php');
require_once($CFG->dirroot . '/user/editlib.php');
session_start();
define("DBSERVER", "localhost");
define("DBUSER", "root");
define("DBPASS", "developer@456");
define("DBNAME", "webapp");

$conn_webapp = mysqli_connect(DBSERVER, DBUSER, DBPASS, DBNAME);
// Check connection
if (!$conn_webapp) {
    die("Connection failed: " . mysqli_connect_error());
}

function checktrueVal($val) {

    if ($val == "true" || is_numeric($val))
        $value = " " . $val;
    else
        $value = " '" . $val . "'";
    return $value;
}

/* salesforce data coming */
//if (isset($_POST)) {
/* Server Details */

/*define("DBSERVERNAME", "localhost");
define("DBUSERNAME", "master4898176838");
define("DBPASSWORD", "5809880140");
define("DBNAMEINS", "master4898176838");*/

define("DBSERVERNAME", $CFG->dbhost);
define("DBUSERNAME", $CFG->dbname);
define("DBPASSWORD", $CFG->dbpass);
define("DBNAMEINS", $CFG->dbuser);

  global $criteriasRuleId; 
  
/* Server Details Ends */
// Create connection
$conn = mysqli_connect(DBSERVERNAME, DBUSERNAME, DBPASSWORD, DBNAMEINS);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

/* Check External system GetBody json Data */
$json = file_get_contents('php://input');

//$json ='[{"attributes":{"type":"User","url":"/services/data/v35.0/sobjects/User/00528000001JqkAAAS"},"Id":"00528000001JqkAAAS","Username":"tes54@tech.com","LastName":"TechTesting","FirstName":"test","Name":"test TechTesting","Address":null,"Email":"te2s54@tech.com","EmailPreferencesAutoBcc":true,"EmailPreferencesAutoBccStayInTouch":false,"EmailPreferencesStayInTouchReminder":true,"Alias":"ttech","CommunityNickname":"tes54","IsActive":true,"TimeZoneSidKey":"America/Los_Angeles","LocaleSidKey":"en_US","ReceivesInfoEmails":true,"ReceivesAdminInfoEmails":true,"EmailEncodingKey":"ISO-8859-1","ProfileId":"00e28000000xatrAAA","UserType":"CsnOnly","LanguageLocaleKey":"en_US","LastPasswordChangeDate":"2015-10-22T12:41:54.000+0000","CreatedDate":"2015-10-22T12:41:54.000+0000","CreatedById":"00528000000z1FpAAI","LastModifiedDate":"2015-10-22T12:42:52.000+0000","LastModifiedById":"00528000000z1FpAAI","SystemModstamp":"2015-10-22T12:42:52.000+0000","UserPermissionsMarketingUser":false,"UserPermissionsOfflineUser":false,"UserPermissionsCallCenterAutoLogin":false,"UserPermissionsMobileUser":false,"UserPermissionsSFContentUser":false,"UserPermissionsKnowledgeUser":false,"UserPermissionsInteractionUser":false,"UserPermissionsSupportUser":false,"UserPermissionsJigsawProspectingUser":false,"UserPermissionsSiteforceContributorUser":false,"UserPermissionsSiteforcePublisherUser":false,"UserPermissionsChatterAnswersUser":false,"UserPermissionsWorkDotComUserFeature":false,"ForecastEnabled":false,"UserPreferencesActivityRemindersPopup":true,"UserPreferencesEventRemindersCheckboxDefault":true,"UserPreferencesTaskRemindersCheckboxDefault":true,"UserPreferencesReminderSoundOff":false,"UserPreferencesContentNoEmail":true,"UserPreferencesContentEmailAsAndWhen":true,"UserPreferencesApexPagesDeveloperMode":false,"UserPreferencesHideCSNGetChatterMobileTask":false,"UserPreferencesHideCSNDesktopTask":false,"UserPreferencesHideChatterOnboardingSplash":false,"UserPreferencesHideSecondChatterOnboardingSplash":false,"UserPreferencesJigsawListUser":false,"UserPreferencesShowTitleToExternalUsers":true,"UserPreferencesShowManagerToExternalUsers":false,"UserPreferencesShowEmailToExternalUsers":false,"UserPreferencesShowWorkPhoneToExternalUsers":false,"UserPreferencesShowMobilePhoneToExternalUsers":false,"UserPreferencesShowFaxToExternalUsers":false,"UserPreferencesShowStreetAddressToExternalUsers":false,"UserPreferencesShowCityToExternalUsers":false,"UserPreferencesShowStateToExternalUsers":false,"UserPreferencesShowPostalCodeToExternalUsers":false,"UserPreferencesShowCountryToExternalUsers":false,"UserPreferencesShowProfilePicToGuestUsers":false,"UserPreferencesShowTitleToGuestUsers":false,"UserPreferencesShowCityToGuestUsers":false,"UserPreferencesShowStateToGuestUsers":false,"UserPreferencesShowPostalCodeToGuestUsers":false,"UserPreferencesShowCountryToGuestUsers":false,"UserPreferencesDisableFeedbackEmail":false,"UserPreferencesDisableWorkEmail":false,"UserPreferencesHideS1BrowserUI":false,"UserPreferencesPathAssistantCollapsed":false,"UserPreferencesCacheDiagnostics":false,"UserPreferencesShowEmailToGuestUsers":false,"UserPreferencesShowManagerToGuestUsers":false,"UserPreferencesShowWorkPhoneToGuestUsers":false,"UserPreferencesShowMobilePhoneToGuestUsers":false,"UserPreferencesShowFaxToGuestUsers":false,"UserPreferencesShowStreetAddressToGuestUsers":false,"UserPreferencesLightningExperiencePreferred":false,"FullPhotoUrl":"https://c.ap2.content.force.com/profilephoto/005/F","SmallPhotoUrl":"https://c.ap2.content.force.com/profilephoto/005/T","JigsawImportLimitOverride":300,"LastViewedDate":"2015-10-22T12:42:52.000+0000","LastReferencedDate":"2015-10-22T12:42:52.000+0000","SYNC_FIELD__c":"SYNC3","SharingType":"e","CompanyName":null,"BadgeText":"Customer"}]';


$obj = json_decode($json, true);

/* Iterate Over The Loop */
$salesforceValue = array();
foreach ($obj as $fieldName => $item) {
    $salesforceValue[$fieldName] = $item;
}


$moodleInstanceQuery = "SELECT id FROM `moodle_instances` WHERE `db_name` = '$CFG->dbname' ORDER BY `id` ASC";
$resultInstanceMoodle = mysqli_query($conn_webapp, $moodleInstanceQuery);

 if (!$resultInstanceMoodle) {
            $message = 'Invalid query: ' . mysqli_error() . "\n";
            $message .= 'Whole query: ' . $moodleInstanceQuery;
            die($message);
  }
  
  while ($rowresultInstanceMoodle = mysqli_fetch_array($resultInstanceMoodle)) {
    $InstanceMoodle[] = $rowresultInstanceMoodle;
}
$InstanceMoodleId = $InstanceMoodle[0]['id'] ; 
  
$query1 = "SELECT * FROM `sfdc_rules_masters` WHERE `object` ='User' AND `moodle_instance_id` = '$InstanceMoodleId'";
$result = mysqli_query($conn_webapp, $query1);
 if (!$result) {
            $message = 'Invalid query: ' . mysqli_error() . "\n";
            $message .= 'Whole query: ' . $query2;
            die($message);
        }
        
while ($rowres = mysqli_fetch_array($result)) {
    $rowrules[] = $rowres;
}
$rules = $rowrules;

 

$recordmatch = array_filter($salesforceValue, 'filterRecord');

/*echo "<pre>";
print_r($recordmatch);*/

function filterRecord($rec) {
    //global $criterias;    
    $isValid = false;
    global $rules;
    $conn_webapp = mysqli_connect(DBSERVER, DBUSER, DBPASS, DBNAME);
    foreach ($rules as $rule) {
        $ruleid = $rule['id'];
        $query2 = "SELECT * FROM `sfdc_criterias` WHERE `rule_master_id` ='$ruleid' ";
        $isvalidCriteria = true;        
        $result = mysqli_query($conn_webapp, $query2);
        
        // Check result
        // This shows the actual query sent to MySQL, and the error. Useful for debugging.
        if (!$result) {
            $message = 'Invalid query: ' . mysqli_error() . "\n";
            $message .= 'Whole query: ' . $query2;
            die($message);
        }
        while ($criterias = mysqli_fetch_array($result)) {    
            
              echo "<pre>";
                print_r($criterias);
            
            $isvalidCriteria = isValidRecord(trim($rec[$criterias['field']]), $criterias['criteria'], trim($criterias['value']));
            if ($isvalidCriteria == false) {
                break;
            }
        }
        if ($isvalidCriteria) {
            $isValid = true;
			$criteriasRuleId[] = $ruleid;
			$_SESSION['criteriasRuleId'] = $criteriasRuleId;
            break;
        }
    }
    return $isValid;
}

function isValidRecord($recordfield, $phpcriteria, $phpvalue) {

    switch ($phpcriteria) {
        case "1":
            return $recordfield == $phpvalue;
            break;
        case "2":
            return $recordfield != $phpvalue;
            break;

        case "3":
            return $recordfield < $phpvalue;
            break;
        case "4":
            return $recordfield > $phpvalue;
            break;
        case "5":
            return $recordfield >= $phpvalue;
            break;
        case "6":
            return $recordfield <= $phpvalue;
            break;
    }
}

foreach ($recordmatch as $reckey => $recval)  {  
        /* Check If Email Is provide in Contact Object */
        /*if ($recval['Email'] != '') { */
            $ID = $recval['Id'];
            $firstname = $recval['FirstName'];
            $lastname = $recval['LastName'];
            $email = $recval['Email'];

			if($email=='' ){
			 $email = 'admin@T'.random_string(5).'321.com';
			}
			if($firstname=='' ){
			 $firstname = 'admin';
			}
			if($lastname=='' ){
			$lastname = 'admin';
			}		
			
            /* Generate The random String for the password */
            $pass = 'T' . random_string(5) . '@321';
            $password = md5($pass);
            $secret = random_string(15);
            $decription = $recval['AboutMe'];
            $mobilePhone = $recval['MobilePhone'];
            $phone = $recval['Phone'];
            $MailingStreet = $recval['Street'];
            $MailingCity = $recval['City'];
            $MailingCountry = $recval['Country'];            
            $Department = $recval['Department'];
            

            $sql = "SELECT `id`, `idnumber` ,`email` ,`username` from `mdl_user` where `email` = '$email' OR `idnumber` = '$ID'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $updaterecords = "Update `mdl_user` SET  `username` = ' $email' , `firstname` = '$firstname', `lastname` = '$lastname', `email` = '$email', `description` = '$decription', `phone1` ='$phone', `phone2` = '$mobilePhone',`department` = '$Department' ,`address` = '$MailingStreet', `city` = '$MailingCity' , `country` = '$MailingCountry'  WHERE `idnumber` = '$ID'  OR `email`='$email'";

                if (mysqli_query($conn, $updaterecords)) {
                    echo "New record Updated successfully";
                }
            } else {
                $insertrecords = "INSERT INTO `mdl_user` (`id`, `auth`, `confirmed`, `policyagreed`, `deleted`, `suspended`, `mnethostid`, `username`, `password`, `idnumber`, `firstname`, `lastname`, `email`, `emailstop`, `icq`, `skype`, `yahoo`, `aim`, `msn`, `phone1`, `phone2`, `institution`, `department`, `address`, `city`, `country`, `lang`, `calendartype`, `theme`, `timezone`, `firstaccess`, `lastaccess`, `lastlogin`, `currentlogin`, `lastip`, `secret`, `picture`, `url`, `description`, `descriptionformat`, `mailformat`, `maildigest`, `maildisplay`, `autosubscribe`, `trackforums`, `timecreated`, `timemodified`, `trustbitmask`, `imagealt`, `lastnamephonetic`, `firstnamephonetic`, `middlename`, `alternatename`, `salesforce_account_name`) VALUES (NULL, 'email', '1', '0', '0', '0', '0', '$email', '$password', '$ID', '$firstname', '$lastname', '$email', '0', '', '', '', '', '', '$phone', '$mobilePhone', '', '', '$MailingStreet', '$MailingCity', '$MailingCountry', 'en', 'gregorian', '', '99', '0', '0', '0', '0', '', '$secret', '0', '', '$decription', '1', '1', '0', '2', '1', '0', '0', '0', '0', NULL, NULL, NULL, NULL, NULL, 'NULL')";
            }
            if (mysqli_query($conn, $insertrecords)) {
                echo "New record created successfully";

                
		   // $selectquery =  "SELECT * FROM `sfdc_criterias` WHERE `value` ='$email' ";
		   foreach($_SESSION['criteriasRuleId'] as $key=>$val){
			 
			$selectquery =  "SELECT * FROM `sfdc_criterias` WHERE `rule_master_id`='$val' AND `moodle_instance_id` = '$InstanceMoodleId' group by `cohort_name`";			 
              
			$resultcheck = mysqli_query($conn_webapp, $selectquery);
			// Check result
					
			// This shows the actual query sent to MySQL, and the error. Useful for debugging.
			if (!$resultcheck) {
				$message = 'Invalid query: ' . mysqli_error() . "\n";
				$message .= 'Whole query: ' . $selectquery;
				die($message);
			}
			
			while ($rowcriteria = mysqli_fetch_array($resultcheck)) {
				$rowcriterias[] = $rowcriteria;
			}
			$phpValuecriteria = $rowcriterias;

            foreach ($phpValuecriteria as $cri) {
                $cohortName = $cri['cohort_name'];
				$moodle_instance_id = $cri['moodle_instance_id'];
				
                $cohortavailable = explode(',', $cohortName);
                //print_r($cohortavailable); 
                $userid = mysqli_insert_id($conn);
                foreach ($cohortavailable as $cohortvalue) {
                    $InsertCohort = "INSERT INTO `mdl_cohort_members` (`id`, `cohortid`, `userid`, `timeadded`) VALUES (NULL, '$cohortvalue', '$userid', '0');";

                    if (mysqli_query($conn, $InsertCohort)) {
                        echo "New record inserted in cohart successfully";
                    }
                }
            }
		   }			
			
			$date = date('Y-m-d H:i:s');			
			$insertIntoSalesforceSync = "INSERT INTO `salesforce_syncs` (`id`, `moodle_instance_id`, `status`, `type`, `created`, `modified`) VALUES (NULL, '$moodle_instance_id', '1', '3', '$date', '$date')";			
			$resultSalesforceSync = mysqli_query($conn_webapp, $insertIntoSalesforceSync);
			// Check result					
			// This shows the actual query sent to MySQL, and the error. Useful for debugging.
			if (!$resultSalesforceSync) {
				$message = 'Invalid query: ' . mysqli_error() . "\n";
				$message .= 'Whole query: ' . $insertIntoSalesforceSync;
				die($message);
			}else{
				echo "New record inserted in SalesforceSync successfully";
			}		
                
                
                $fromUser = "mohd.sayeed@webenturetech.com";
                $msg = "Hi " . $firstname . $lastname . ", <br/>You have registered in moodle your access details is 
						following:
						<br/>Login Url:" . get_login_url() . "
						<br/>
						user name :" . $email . "<br/>password:" . $pass . " <br/> Thanks ";

                $to = $email;
                $subject = 'Moodle Access Details';
                $msg = "Hi " . $firstname . " " . $lastname . ", <br/>You have registered in moodle your access details is 
						following:
						<br/>Login Url:" . get_login_url() . "
						<br/>
						<strong>User name : </strong> " . $email . "<br/><strong>Password : </strong> " . $pass . " <br/> Thanks ";
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                $headers .= 'From: mohd.sayeed@webenturetech.com' . "\r\n" .
                        'Reply-To: mohd.sayeed@webenturetech.com' . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();

                mail($to, $subject, $msg, $headers);
            } else {
                echo "Error: " . $insertrecords . "<br>" . mysqli_error($conn);
            }
        /*} */        
    }
?>