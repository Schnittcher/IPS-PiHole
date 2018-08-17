<?php

class IPS_PiHole extends IPSModule
{
    public function Create()
    {
        //Never delete this line!
        parent::Create();
        $this->RegisterPropertyString('Host', '');
        $this->RegisterPropertyInteger('Port', 80);
        $this->RegisterPropertyString('PihToken', '');
        $this->RegisterPropertyInteger('UpdateTimerInterval', 20);

        $this->RegisterVariableBoolean('PihStatus', $this->Translate('Status'), '~Switch', 1);
        $this->RegisterVariableInteger('PihBlockedDomains', $this->Translate('Blocked Domains'), '', 2);
        $this->RegisterVariableInteger('PihDNSQueriesToday', $this->Translate('DNS Queries Today'), '', 3);
        $this->RegisterVariableInteger('PihAdsBlockedToday', $this->Translate('Ads Blocked Today'), '', 4);
        $this->RegisterVariableInteger('PihQueriesCached', $this->Translate('Queries Cached'), '', 6);
        $this->RegisterVariableInteger('PihDNSQueriesAllTypes', $this->Translate('DNS Queries All Types'), '', 7);
        $this->RegisterVariableInteger('PihGravityLastUpdated', $this->Translate('Gravity Last Updated'), '~UnixTimestamp', 8);

        if (!IPS_VariableProfileExists('PiHole.Percent')) {
            IPS_CreateVariableProfile('PiHole.Percent', 1);
            IPS_SetVariableProfileText('PiHole.Percent', '', ' %');
        }
        $this->RegisterVariableInteger('PihAdsPrecentageToday', $this->Translate('Ads Percentage Today'), 'PiHole.Percent', 5);

        $this->RegisterTimer('Pih_updateStatus', 0, 'Pih_updateStatus($_IPS[\'TARGET\']);');
    }

    public function ApplyChanges()
    {
        //Never delete this line!
        parent::ApplyChanges();

        if ($this->ReadPropertyString('Host') != '') {
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

    private function request(string $parm)
    {
        $url = 'http://' . $this->ReadPropertyString('Host') . ':' . $this->ReadPropertyInteger('Port') . '/admin/api.php?' . $parm . '&auth=' . $this->ReadPropertyString('PihToken');
        $this->SendDebug(__FUNCTION__ . ' URL', $url, 0);
        $json = @file_get_contents($url);
        if ($json === false) {
            echo 'Cannot access to API / Pi-hole offline?';
        } else {
            $this->SendDebug(__FUNCTION__ . ' JSON', $json, 0);
            $data = json_decode($json, true);

            return $data;
        }
    }

    public function getStatus()
    {
        $data = $this->request('status');
        if ($data != null) {
            if ($data['status'] == 'enabled') {
                SetValue($this->GetIDForIdent('PihStatus'), true);
            } else {
                SetValue($this->GetIDForIdent('PihStatus'), false);
            }
        }
    }

    public function setActive(bool $value)
    {
        $data = $this->request(($value ? 'enable' : 'disable'));
        if ($data != null) {
            switch ($data['status']) {
                case 'enabled':
                    SetValue(IPS_GetObjectIDByIdent('PihStatus', $this->InstanceID), true);
                    break;
                case 'disabled':
                    SetValue(IPS_GetObjectIDByIdent('PihStatus', $this->InstanceID), false);
                    break;
            }
        }
    }

    public function getSummaryRaw()
    {
        $data = $this->request('summaryRaw');
        if ($data != null) {
            SetValue($this->GetIDForIdent('PihBlockedDomains'), $data['domains_being_blocked']);
            SetValue($this->GetIDForIdent('PihDNSQueriesToday'), $data['dns_queries_today']);
            SetValue($this->GetIDForIdent('PihAdsBlockedToday'), $data['ads_blocked_today']);
            SetValue($this->GetIDForIdent('PihAdsPrecentageToday'), $data['ads_percentage_today']);
            SetValue($this->GetIDForIdent('PihQueriesCached'), $data['queries_cached']);
            SetValue($this->GetIDForIdent('PihDNSQueriesAllTypes'), $data['dns_queries_all_types']);
            SetValue($this->GetIDForIdent('PihGravityLastUpdated'), $data['gravity_last_updated']['absolute']);
        }
    }

    public function RequestAction($Ident, $Value)
    {
        switch ($Ident) {
            case 'PihStatus':
                $this->setActive($Value);
                break;
        }
    }
}
