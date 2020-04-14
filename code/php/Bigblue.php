<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once './vendor/autoload.php';

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\DeleteRecordingsParameters;

class Bigblue extends MX_Controller {

    private $securitySalt = 'security-salt-of-bbb';
    private $serverBaseUrl = 'https://your-domain.com/bigbluebutton/';

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->form_validation->CI = & $this;
        $this->config->load('form_validation');
        $this->load->model('meeting_model');
        putenv("BBB_SECURITY_SALT=$this->securitySalt");
        putenv("BBB_SERVER_BASE_URL=$this->serverBaseUrl");
    }

    public function create($id) {
        $meeting = $this->meeting_model->get_meeting_data($id);
        $isRecordingTrue = TRUE;
        $duration = 40;
        $participant = 51;
        try {
            $bbb = new BigBlueButton();

            $createMeetingParams = new CreateMeetingParameters($meeting->mid, $meeting->name);
            $createMeetingParams->setAttendeePassword('password');
            $createMeetingParams->setModeratorPassword($meeting->moderator);
            $createMeetingParams->setLogoutUrl(base_url());
            $createMeetingParams->setDuration($duration);
            $createMeetingParams->setMaxParticipants($participant);
            if ($isRecordingTrue) {
                $createMeetingParams->setRecord(true);
                $createMeetingParams->setAllowStartStopRecording(true);
                $createMeetingParams->setAutoStartRecording(true);
            }

            $response = $bbb->createMeeting($createMeetingParams);
            if ($response->getReturnCode() == 'FAILED') {
                redirect('/live/meeting/');
            } else {
                $meetingData['sessions'] = $meeting->sessions + 1;
                $this->db->where('id', $meeting->id);
                $this->db->update('meetings', $meetingData);
                $joinUrl = $this->getJoinUrl($meeting->mid, $meeting->moderator);
                redirect($joinUrl);
            }
        } catch (\Exception $e) {
            redirect('/live/meeting/');
        }
    }

    public function join($id) {
        $meeting = $this->meeting_model->get_meeting_data($id);
        $joinUrl = $this->getJoinUrl($meeting->mid, 'password');
        redirect($joinUrl);
    }

    public function meeting_info($id) {
        $bbb = new BigBlueButton();
        $meeting = $this->meeting_model->get_meeting_data($id);
        $getMeetingInfoParams = new GetMeetingInfoParameters($meeting->mid, '', $meeting->moderator);
        $response = $bbb->getMeetingInfo($getMeetingInfoParams);
        if ($response->getReturnCode() == 'FAILED') {
            $liveMeeting = FALSE;
        } else {
            $liveMeeting = $this->meetingDataFilter($response->getRawXml());
        }
        echo json_encode($liveMeeting);
    }

    public function meetings() {
        $bbb = new BigBlueButton();
        $response = $bbb->getMeetings();
        $meetings = array();
        if ($response->getReturnCode() == 'SUCCESS') {
            $simpleXML = $response->getRawXml()->meetings->meeting;
            foreach ($simpleXML as $value) {
                $meetings[] = $this->meetingDataFilter($value);
            }
        }
        echo json_encode($meetings);
    }

    public function recordings($mid = 0) {
        $recordingParams = new GetRecordingsParameters();
        if ($mid) {
            $recordingParams->setMeetingID($mid);
        }
        $bbb = new BigBlueButton();
        $response = $bbb->getRecordings($recordingParams);
        $recordings = array();
        if ($response->getReturnCode() == 'SUCCESS') {
            foreach ($response->getRawXml()->recordings->recording as $recording) {
                $recordings[] = $recording;
            }
        }
        echo json_encode($recordings);
    }

    public function delete_recording($id) {
        $meeting = $this->meeting_model->get_meeting_data($id);
        if ($meeting) {
            $bbb = new BigBlueButton();
            $deleteRecordingsParams = new DeleteRecordingsParameters($meeting->mid);
            $response = $bbb->deleteRecordings($deleteRecordingsParams);
            if ($response->getReturnCode() == 'SUCCESS') {
                echo 'success';
            } else {
                echo 'failed';
            }
        } else {
            echo 'NotFound';
        }
    }

    private function getJoinUrl($meetingID, $password) {
        $bbb = new BigBlueButton();
        $userData = $this->session->userdata("user");
        $joinMeetingParams = new JoinMeetingParameters($meetingID, $userData->fullname, $password);
        $joinMeetingParams->setRedirect(true);
        $joinMeetingParams->setUserId($userData->id);
        $joinMeetingParams->setJoinViaHtml5(true);
        $url = $bbb->getJoinMeetingURL($joinMeetingParams);
        return $url;
    }

    private function meetingDataFilter($value) {
        $info['mid'] = (string) $value->meetingID;
        $info['name'] = (string) $value->meetingName;
        $info['running'] = (string) $value->running;
        $info['createTime'] = (string) $value->createTime;
        $info['startTime'] = (string) $value->startTime;
        $info['endTime'] = (string) $value->endTime;
        $info['date'] = (string) $value->createDate;
        $info['duration'] = (string) $value->duration;
        $info['userJoined'] = (string) $value->hasUserJoined;
        $info['recording'] = (string) $value->recording;
        $info['maxUsers'] = (string) $value->maxUsers;
        $info['moderator'] = (string) $value->moderatorCount;
        $info['participant'] = (string) $value->participantCount;
        $info['listener'] = (string) $value->listenerCount;
        $info['speaker'] = (string) $value->voiceParticipantCount;
        $info['video'] = (string) $value->videoCount;
        $info['attendees'] = $value->attendees;

        return $info;
    }

}
