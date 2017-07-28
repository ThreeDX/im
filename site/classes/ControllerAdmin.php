<?php

if (!defined('THREED')) {
    die ("Access denied!");
}

class ControllerAdmin extends Controller {
    public function __construct()
    {
        parent::__construct();
    }

    public function can_process() {
        if ($this->ses_helper->get_session_status()==SesHelper::SESSION_INITED)
            if ($this->ses_helper->is_granted())
                return Controller::CONTROLLER_CAN_PROCESS;
            else {
                header("Location: index.php");
                return Controller::CONTROLLER_REDIRECTED;
            }
        else
            return Controller::CONTROLLER_SHOULD_LOGIN;
    }
}