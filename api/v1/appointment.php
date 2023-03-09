<?php 
namespace v1;

class Appointment
{
    private $restObj;
    private $tableName = "appointments";
    private $ID;

    function __construct(\Rest $obj)
    {
        $this->restObj = $obj;
    }

    function list()
    {
        try{

            $list = $this->restObj->dbConn->selectSQL("SELECT * FROM {$this->tableName} ORDER BY id DESC",1);
            $this->restObj->returnResponse(SUCCESS_RESPONSE, json_encode($list));

        }catch(\Exception $e)
        {	
            $this->restObj->throwError(REQUEST_NOT_VALID, $e->getMessage());
        }
    }

    function create()
    {
        $patient = [
            'name' => '',
            'ic'   => '',
            'mrn'  => NULL,
            'mobileNo' => '', 
        ];

        $apptDateTime = $this->restObj->validateParameter('appointment_datetime', $this->restObj->param['appointment_datetime'], 'DATETIME');

        $patient['name'] = $this->restObj->validateParameter('patient_name', $this->restObj->param['patient_name'], 'STRING');
        $patient['ic']   = $this->restObj->validateParameter('patient_ic', $this->restObj->param['patient_ic'], 'INTEGER');
        $patient['mobileNo'] = $this->restObj->validateParameter('patient_mobileNo', $this->restObj->param['patient_mobileNo'], 'INTEGER');

        if(!empty($this->restObj->param['patient_mrn']))
        $patient['mrn'] = $this->restObj->validateParameter('patient_mrn', $this->restObj->param['patient_mrn'], 'STRING');

        try{
            
            $newApp = [
                'code' => strtoupper(random_string()),
                'appt_datetime' => $this->restObj->dbConn->escapeStr($apptDateTime),
                'patient' => $this->restObj->dbConn->escapeStr(json_encode($patient)),
                'status'  => 'pending',
                'created_at' => NOW
            ];

            $ID = $this->restObj->dbConn->insertSQL($this->tableName,$newApp);

            if(!empty($ID))
            {
                $this->restObj->returnResponse(SUCCESS_RESPONSE, ['message'=>'New appointment was successfully created!']);
            }

            $this->restObj->throwError(SERVER_BUSY, 'Server busy. Please retry.');

        }catch(\Exception $e)
        {	
            $this->restObj->throwError(REQUEST_NOT_VALID, $e->getMessage());
        }
    }

    public function schedule()
    {
        $apptDateTime = $this->restObj->validateParameter('appointment_datetime', $this->restObj->param['appointment_datetime'], 'DATETIME');
        $appt = $this->isIDExist();

        try{

            $newAppt = [
                'code' => strtoupper(random_string()),
                'appt_datetime' => $this->restObj->dbConn->escapeStr($apptDateTime),
                'patient' => $this->restObj->dbConn->escapeStr($appt['patient']),
                'status' => 'pending',
                'created_at' => NOW,
            ];

            $updateAppt = [
                'id'     => $appt['id'],
                'status' => 'rescheduled',
            ];

            $resp = $this->restObj->dbConn->insertSQL($this->tableName,$newAppt);

            if(!empty($resp))
            {
                $this->restObj->dbConn->updateSQL($this->tableName,$updateAppt);
                $this->restObj->returnResponse(SUCCESS_RESPONSE, ['message'=>'Appointment was successfully rescheduled.']);
            }

            $this->restObj->throwError(SERVER_BUSY, 'Server busy. Please retry.');

        }catch(\Exception $e)
        {
            $this->restObj->throwError(REQUEST_NOT_VALID, $e->getMessage());
        }
    }

    public function arrive()
    {
        $apptArrived = $this->restObj->validateParameter('appointment_arrive_at', $this->restObj->param['appointment_arrive_at'], 'DATETIME');
        $this->isIDExist();

        try{

            $updateAppt = [
                'id' => $this->ID,
                'status' => 'arrived',
                'arrived_at' => $this->restObj->dbConn->escapeStr($apptArrived),
            ];

            $resp = $this->restObj->dbConn->updateSQL($this->tableName,$updateAppt);

            if(!empty($resp))
            {
                $this->restObj->returnResponse(SUCCESS_RESPONSE, ['message'=>'Appointment was successfully updated to arrived.']);
            }

            $this->restObj->throwError(SERVER_BUSY, 'Server busy. Please retry.');

        }catch(\Exception $e)
        {
            $this->restObj->throwError(REQUEST_NOT_VALID, $e->getMessage());
        }

    }

    private function isIDExist()
    {
        $apptID= $this->restObj->validateParameter('appointment_id', $this->restObj->param['appointment_id'], 'INTEGER');

        try{
            $this->ID = $this->restObj->dbConn->escapeStr($apptID);
            $isExist = $this->restObj->dbConn->selectSQL("SELECT * FROM {$this->tableName} WHERE id = '{$this->ID}' LIMIT 1");

            if(empty($isExist)) $this->restObj->returnResponse(SERVICE_ID_NOT_EXISTED, ['message'=>'Appointment does not exist.']);
            return $isExist;
        }catch(\Exception $e)
        {
            $this->restObj->throwError(REQUEST_NOT_VALID, $e->getMessage());
        }
    }
}


?>