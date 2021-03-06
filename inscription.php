<?php
/*
 * home.php
 *
 * Author : Flavien Knuchel
 * Date : 24.06.2013
 *
 * Description : whole gestion of the inscription process
 *
 *
*/
include 'header.php';
include 'menu_frontend.php';

/*------------------------------ Where did the user come from? (return button) ------------------------------*/
if(isset($_SERVER['HTTP_REFERER'])){
    //disassemble the url
    $cameFrom=explode("/",$_SERVER['HTTP_REFERER']);
//check the interesting part (file name)
    $cameFrom=explode('?',$cameFrom[5]);
//if it is event_detail.php
    if($cameFrom[0]=="event_detail.php"){
        /* make the "back" link go to the good event_detail pag
         *  the explode is to avoid repeating the get infos when going back and forth
         *  from event_detail page to inscription page
        */
        $userCameFrom=explode("&",$_SERVER['HTTP_REFERER']);
        //selection the good part of the explode
        $userCameFrom=$userCameFrom[0];
        $smarty->assign('userCameFrom',$userCameFrom);
    }//if
    else{
        //do nothing
    }//else
}//if
else{
    //do nothing
}

/*------------------------------ Check the event which the registration is for ------------------------------*/
//if the event number is not set in the session
if(!isset($_SESSION['eventNo'])){
    //if the event Number is specified in get
    if(isset($_GET['eventNo'])){
            //stock it in session
            $_SESSION['eventNo']=$_GET['eventNo'];
    }//if
    else{
        //if the number is not specified, there's a problem
        header("Location: 404.php");
    }//else
}//if
//else it is in the session already


/*------------------------------ MAIN: formular processing, and registration  ------------------------------*/
//set the registration success to false
$registrationSuccess=false;
//initializing the error variable
$error='';
//if the post datas are set (if motivation is sent, even empty, it means the user is submitting the formular)
if(isset($_POST['motivation'])){
    //if the user is logged
    if ($tedx_manager->isLogged()) {
        //check if the status has been entered
        if(!is_null(checkStatus())){
            //is is a save or a send?
            // if it is a send
            if(checkStatus()=="send"){
                //if the motivation is filled
                if(checkMotivation()){
                    //send the registration
                    $messageRegister=Register(checkStatus());
                    if(empty($messageRegister)){
                        $registrationSuccess=true;
                    }//if
                    else{
                        $error=$messageRegister;
                    }//else
                }//if
                else{
                    //set the error for motivation filling
                    $error="Please, fill in your motivation if you want to send your inscription";
                }//else
            }//if
            //if it is a save
            else{
                $messageRegister=Register(checkStatus());
                if(empty($messageRegister)){
                    $registrationSuccess=true;
                }//if
                else{
                    $error=$messageRegister;
                }//else
            }//else
        }//if
        else{
            //set the error for status
            $error="Status Error - ???";
        } //else

    }//if

    else{
        //test if all the fields of the form are filled
        //test if the password id correct
        if(confirmPassword()){
            if(validateDateOfBirth()){
                //create an array with the variables
                $visitor=createVisitorArray();
                //try to create the visitor
                $messageRegisterVisitor=$tedx_manager->registerVisitor($visitor);
                //if the registration of the visitor went well
                if($messageRegisterVisitor->getStatus()){
                    //login the user
                    $messageLogin = $tedx_manager->login($_POST['username'], $_POST['password']);
                    //if logged in
                    if($messageLogin->getStatus()){
                        //assign true tu the smarty value (for the userbar display
                        $smarty->assign('loggedin', true);
                        //test the user level
                        setUserLevel();
                    }
                    //check if the status has been entered
                    if(!is_null(checkStatus())){
                        //is is a save or a send?
                        // if it is a send
                        if(checkStatus()=="send"){
                            //if the motivation is filled
                            if(checkMotivation()){
                                //send the registration
                                $messageRegister=Register(checkStatus());
                                if(empty($messageRegister)){
                                    $registrationSuccess=true;
                                }//if
                                else{
                                    $error=$messageRegister;
                                }//else
                            }
                            else{
                                //set the error for motivation filling
                                $error="Please, fill in your motivation if you want to send your inscription";
                            }//else
                        }//if
                        //if it is a save
                        else{
                            $messageRegister=Register(checkStatus());
                            if(empty($messageRegister)){
                                $registrationSuccess=true;
                            }//if
                            else{
                                $error=$messageRegister;
                            }//else
                        }//else
                    }//if
                    else{
                        //set the error for status
                        $error="status";
                    }//else
                }//if
                else{
                    //if the registration didn't go well
                    $error=$messageRegisterVisitor->getMessage();
                }//else

            }//if
            else{
                $error="Please enter a correct Birthdate";
            }//else

        }//if
        else{
            //set the error for password
            $error="Please Check your password again!";
        }//else
    }//else
}
else{
    //don't do anything but display the formular
}
//if everything went well, the go to the homepage and display a message
if(isset($registrationSuccess)&&$registrationSuccess&&empty($error)){
    header("Location: events.php?registrationSuccess=true");
}
else{
    $smarty->assign('error', $error);
}

//stock the error message in smarty
$smarty->assign('error', $error);

//send the filled datas, so we can collect them, and in case of error, no need to retype them
sendFilledDatas();
/*---------------------------- normal inscription display  -----------------------------*/
$smarty->display('events_registerToEvent.tpl');
include 'userbar.php';

/*---------------------------- functions -----------------------------*/
//test if the password and passwordConfirm are the same
function confirmPassword(){
    if(isset($_POST['password'])){
        if($_POST['password']==$_POST['confirmPassword']){
            return true;
        }//if
        else{
            return false;
        }//else
    }//if
}//function

//create an array with the visitor infos
function createVisitorArray(){
    $visitor= array(
        'name'        => $_POST['firstname'],
        'firstname'   => $_POST['lastname'],
        'dateOfBirth' => $_POST['date'],
        'address'     => $_POST['address'],
        'city'        => $_POST['city'],
        'country'     => $_POST['country'],
        'phoneNumber' => $_POST['phone'],
        'email'       => $_POST['email'],
        'description' => "",
        'idmember'    => $_POST['username'],
        'password'    => $_POST['password']
    );
    return $visitor;
}//function

//check if the motivation is filled
function checkMotivation(){
    if(isset($_POST['motivation'])&&!empty($_POST['motivation'])){
        return true;
    }//if
    else{
        return false;
    }//else
}//function


//check if the status is send or save
function checkStatus(){
    //if the user clicked save
    if(isset($_POST['save'])){
        return "save";
    }//if
    //if the user clicked send
    elseif(isset($_POST['send'])){
        return "send";
    }//if
}//function


//create and array with the keywords
function arrayKeywords(){
    $keywords=array();
    if(isset($_POST['keyword1']))array_push( $keywords,$_POST['keyword1']);
    if(isset($_POST['keyword2']))array_push( $keywords,$_POST['keyword2']);
    if(isset($_POST['keyword3']))array_push( $keywords,$_POST['keyword3']);
    return $keywords;
}//function

//send the filled datatas via post to display them in the fields if there's an error
function sendFilledDatas(){
    global $smarty;
    //create an array with the filled infos
    $registration=array();
    if(isset($_POST['firstname']))$registration['firstname'] = $_POST['firstname'];
    if(isset($_POST['lastname']))$registration['lastname']= $_POST['lastname'];
    if(isset($_POST['username']))$registration['username']= $_POST['username'];
    if(isset($_POST['date']))$registration['date']=$_POST['date'];
    if(isset($_POST['address']))$registration['address']= $_POST['address'];
    if(isset($_POST['city']))$registration['city']= $_POST['city'];
    if(isset($_POST['country']))$registration['country']= $_POST['country'];
    if(isset($_POST['phone']))$registration['phone']=$_POST['phone'];
    if(isset($_POST['email']))$registration['email']= $_POST['email'];
    if(isset($_POST['username']))$registration['username']= $_POST['username'];
    if(isset($_POST['type']))$registration['type']= $_POST['type'];
    if(isset($_POST['typeDescription']))$registration['typeDescription']= $_POST['typeDescription'];
    if(isset($_POST['motivation']))$registration['motivation']= $_POST['motivation'];
    if(isset($_POST['keyword1']))$registration['keyword1']= $_POST['keyword1'];
    if(isset($_POST['keyword2']))$registration['keyword2']= $_POST['keyword2'];
    if(isset($_POST['keyword3']))$registration['keyword3']= $_POST['keyword3'];

        //assign the array to smarty
        $smarty->assign('filledDatas', $registration);
}//function

//register the logged user to the selected event
function Register($registrationStatus){
    //create an empty error variable
    $error='';
    global $tedx_manager;
    global $smarty;
    $person=$tedx_manager->getLoggedPerson()->getContent();
    $eventNo=$_SESSION['eventNo'];
    $eventMessage=$tedx_manager->getEvent($eventNo);
    var_dump($_SESSION);
    if($eventMessage->getStatus()){
        $event=$eventMessage->getContent();
    }
    else{
        $error=$eventMessage->getMessage();
    }
    $slots=$tedx_manager->getSlotsFromEvent($event)->getcontent();
    $type=$_POST['type'];
    $typeDescription=$_POST['typeDescription'];

    $registration = array(
        'person' => $person, // object Person
        'event' => $event, // object Event
        'slots' => $slots, // List of objects Slot
        'type' => $type, // String
        'typeDescription' => $typeDescription // String
    );

    $messageRegistration=$tedx_manager->registerToAnEvent($registration);
    if(!$messageRegistration->getStatus()){
        $error=$messageRegistration->getMessage();
    }

    if(isset($_POST['creatingAccount'])){
        //login the user
        $messageLogin = $tedx_manager->login($_POST['username'], $_POST['password']);
        //if logged in
        if($messageLogin->getStatus()){
            //assign true tu the smarty value (for the userbar display
            $smarty->assign('loggedin', true);
            //test the user level
            setUserLevel();
        }
    }

    $arrayKeywords=arrayKeywords();
    $keywordsArray = array(
    'listOfValues' => $arrayKeywords,
    'person' => $person,
    'event' => $event );

    $messageKeywords=$tedx_manager->addKeywordsToAnEvent($keywordsArray);
    foreach($messageKeywords as $msg){
        if(!$msg->getStatus()){
            $error=$msg->getMessage();
        }
    }
    var_dump($event);
    $personNo=$person->getNo();
    $messageParticipant=$tedx_manager->getParticipant($personNo);
    if($messageParticipant->getStatus()){
        $participant=$messageParticipant->getContent();

        $aMotivation = array(
            'text' => $_POST['motivation'],
            'event' => $event,
            'participant' => $participant);
        $messageMotivation =$tedx_manager->addMotivationToAnEvent($aMotivation);
        if(!$messageMotivation->getStatus()){
            $error="addMotivationToAnEvent --- ".$messageMotivation->getMessage();
        }
    }
    else{
        $error="getParticipant --- ".$messageParticipant->getMessage();
    }


    if($registrationStatus!="send" && $registrationStatus!="save"){
        header('Location:404.php');
    }
    else{
       if($registrationStatus=="send"){
           $participant=$tedx_manager->getParticipant($person->getNo());
           if($participant->getStatus()){
               $arrayWaiting=array('status' =>'Pending','event' => $event,'participant' => $participant->getContent());
               $waitingRegistration = $tedx_manager->getRegistration($arrayWaiting)->getContent();
               $tedx_manager->sendRegistration($waitingRegistration);
           }

       }
    }

    return $error;
}//function

//defines the level of the user (for footer display)
function setUserLevel(){
    global $smarty;
    $smarty->assign('userLevel', 'participant');
}//function

//validate the birthdate format (year between 1800 and current year)
function validateDateOfBirth(){
    if(isset($_POST['date'])){
        $date= $_POST['date'];
        $date=explode("-",$date);
            if(sizeof($date)==3){
                $year=$date[0];
                $month=$date[1];
                $day=$date[2];

                if($year>=1800 && $year <=date('Y') && $month<13 && $month>0 && $day>=0 && $day<=31){
                    return true;
                }//if
                else{
                    return false;
                }//else
            }//if
        else{
            return false;
        }//else
    }//if
    else{
        return false;
    }//else
}//function



?>