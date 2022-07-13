<?php

declare(strict_types=1);

class PiHole extends IPSModule
{
    public function Create()
    {
        //Never delete this line!
        parent::Create();
        $this->RegisterPropertyString('Host', '');
        $this->RegisterPropertyInteger('Port', 80);
        $this->RegisterPropertyBoolean('SSL', false);
        $this->RegisterPropertyString('PihToken', '');
        $this->RegisterPropertyInteger('UpdateTimerInterval', 20);

        $this->RegisterVariableBoolean('PihStatus', $this->Translate('Status'), '~Switch', 1);
        $this->EnableAction('PihStatus');
        $this->RegisterVariableInteger('PihDisableTime', $this->Translate('Time to disable'), '', 2);
        $this->EnableAction('PihDisableTime');
        $this->RegisterVariableInteger('PihBlockedDomains', $this->Translate('Blocked Domains'), '', 3);
        $this->RegisterVariableInteger('PihDNSQueriesToday', $this->Translate('DNS Queries Today'), '', 4);
        $this->RegisterVariableInteger('PihAdsBlockedToday', $this->Translate('Ads Blocked Today'), '', 5);
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
    }

    public function updateStatus()
    {
        $this->getSummaryRaw();
    }

    public function setActive(bool $value)
    {
        $data = $this->request(($value ? 'enable' : 'disable=' . $this->GetValue('PihDisableTime')));
        if ($data != null) {
            switch ($data['status']) {
                case 'enabled':
                    $this->SetValue('PihStatus', true);
                    break;
                case 'disabled':
                    $this->SetValue('PihStatus', false);
                    break;
            }
        }
    }

    public function getSummaryRaw()
    {
        $data = $this->request('summaryRaw');
        if ($data != null) {
            $this->SetValue('PihBlockedDomains', $data['domains_being_blocked']);
            $this->SetValue('PihDNSQueriesToday', $data['dns_queries_today']);
            $this->SetValue('PihAdsBlockedToday', $data['ads_blocked_today']);
            $this->SetValue('PihAdsPrecentageToday', $data['ads_percentage_today']);
            $this->SetValue('PihQueriesCached', $data['queries_cached']);
            $this->SetValue('PihDNSQueriesAllTypes', $data['dns_queries_all_types']);
            $this->SetValue('PihGravityLastUpdated', $data['gravity_last_updated']['absolute']);
            if ($data['status'] == 'enabled') {
                $this->SetValue('PihStatus', true);
            } else {
                $this->SetValue('PihStatus', false);
            }
        }
    }

    public function RequestAction($Ident, $Value)
    {
        switch ($Ident) {
            case 'PihStatus':
                $this->setActive($Value);
                break;
            case 'PihDisableTime':
                $this->SetValue($Ident, $Value);
                break;
        }
    }

    private function request(string $parm)
    {
        $url = ($this->ReadPropertyBoolean('SSL') ? 'https://' : 'http://') . $this->ReadPropertyString('Host') . ':' . $this->ReadPropertyInteger('Port') . '/admin/api.php?' . $parm . '&auth=' . $this->ReadPropertyString('PihToken');
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
}
