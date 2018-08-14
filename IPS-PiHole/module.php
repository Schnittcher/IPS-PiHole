<?php

class IPS_PiHole extends IPSModule
{

    public function Create()
    {
        //Never delete this line!
        parent::Create();
        //Connect to Websocket Client
        $this->RegisterPropertyString('Host', '');
        $this->RegisterPropertyInteger('Port', 80);
        $this->RegisterPropertyString('PihToken', '');
        $this->RegisterPropertyInteger('UpdateTimerInterval', 20);

        $this->RegisterVariableBoolean('PihStatus', 'Status', '~Switch', 1);
        $this->RegisterVariableInteger('PihBlockedDomains', 'Blocked Domains', '', 2);
        $this->RegisterVariableInteger('PihDNSQueriesToday', 'DNS Queries Today', '', 3);
        $this->RegisterVariableInteger('PihAdsBlockedToday', 'Ads Blocked Today', '', 4);

        $this->RegisterTimer('Pih_updateStatus', 0, 'Pih_updateStatus($_IPS[\'TARGET\']);');
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();

        if ($this->ReadPropertyString("Host") != '') {
            $this->SetTimerInterval('Pih_updateStatus', $this->ReadPropertyInteger('UpdateTimerInterval') * 1000);
        } else {
            $this->SetTimerInterval('Pih_updateStatus', 0);
        }
        $this->EnableAction('PihStatus');

    }

    public function updateStatus()
    {
        $this->getStatus();
        $this->getSummaryRaw();
    }

    private function request($parm)
    {

        $url = "http://" . $this->ReadPropertyString("Host") . ":" . $this->ReadPropertyInteger("Port") . "/admin/api.php?" . $parm . "&auth=" . $this->ReadPropertyString("PihToken");
        $this->SendDebug(__FUNCTION__." URL",$url,0);
        $json = @file_get_contents($url);
        if ($json === FALSE) {
            $this->SendDebug(__FUNCTION__." Error",'Cannot access to API',0);
        } else {
            $this->SendDebug(__FUNCTION__." JSON",$json,0);
            $data = json_decode($json, TRUE);
            return $data;

        }
    }

    public function getStatus()
    {
        $data = $this->request("status");
        if ($data["status"] == "enabled") {
            SetValue($this->GetIDForIdent('PihStatus'), true);
        } else {
            SetValue($this->GetIDForIdent('PihStatus'), false);
        }
    }

    public function setStatus($value) {
        switch ($value) {
            case true:
                $data = $this->request("enable");
                $this->SendDebug(__FUNCTION__,"Pi-hole enabled",0);
                $this->SendDebug(__FUNCTION__." JSON Result",json_encode($data),0);
                break;
            case false:
                $data = $this->request("disable");
                $this->SendDebug(__FUNCTION__,"Pi-hole disabled",0);
                $this->SendDebug(__FUNCTION__." JSON Result",json_encode($data),0);
                break;
            default:
                $this->SendDebug(__FUNCTION__,"Wrong Parameter: $value",0);
                break;
        }
    }

    public function getSummaryRaw() {
        $data = $this->request('summaryRaw');
        SetValue($this->GetIDForIdent('PihBlockedDomains'), $data['domains_being_blocked']);
        SetValue($this->GetIDForIdent('PihDNSQueriesToday'), $data['dns_queries_today']);
        SetValue($this->GetIDForIdent('PihAdsBlockedToday'), $data['ads_blocked_today']);
    }

    public function RequestAction($Ident, $Value)
    {
        switch ($Ident) {
            case 'PihStatus':
                if ($Value) {
                        $this->SendDebug(__FUNCTION__.' Pi-hole Enable', $Value, 0);
                        $this->setStatus(true);
                        SetValue(IPS_GetObjectIDByIdent($Ident, $this->InstanceID), true);
                    } else {
                    $this->SendDebug(__FUNCTION__.' Pi-hole Disable', $Value, 0);
                    $this->setStatus(false);
                    SetValue(IPS_GetObjectIDByIdent($Ident, $this->InstanceID), false);
                    }
                    break;
        }
    }
}
