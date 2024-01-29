<?php
namespace Metaregistrar\EPP;

class eppCheckResponse extends eppResponse {
    function __construct() {
        parent::__construct();
    }

    function __destruct() {
        parent::__destruct();
    }

    /**
     *
     * @return array of checked domains with status true/false
     */
    public function getCheckedDomains() {
        $result = null;
        if ($this->getResultCode() == self::RESULT_SUCCESS) {
            $result = array();
            $xpath = $this->xPath();
            $domains = $xpath->query('/epp:epp/epp:response/epp:resData/domain:chkData/domain:cd');
            foreach ($domains as $domain) {
                $childs = $domain->childNodes;
                $checkeddomain = array('domainname' => null, 'available' => false, 'reason' => null);
                foreach ($childs as $child) {
                    if ($child instanceof \DOMElement) {
                        if (strpos($child->tagName, ':name')) {
                            $available = $child->getAttribute('avail');
                            switch ($available) {
                                case '0':
                                case 'false':
                                    $checkeddomain['available'] = false;
                                    break;
                                case '1':
                                case 'true':
                                    $checkeddomain['available'] = true;
                                    break;
                            }
                            $checkeddomain['domainname'] = $child->nodeValue;
                        }
                        if (strpos($child->tagName, ':reason')) {
                            $checkeddomain['reason'] = $child->nodeValue;
                        }
                    }
                }
                $result[] = $checkeddomain;
            }
        }
        return ($result);
    }

    /**
     *
     * @return array of checked hosts with status true/false
     */
    public function getCheckedHosts() {
        $result = null;
        if ($this->getResultCode() == self::RESULT_SUCCESS) {
            $xpath = $this->xPath();
            $hosts = $xpath->query('/epp:epp/epp:response/epp:resData/host:chkData/host:cd/host:name');
            $checks = $xpath->query('/epp:epp/epp:response/epp:resData/host:chkData/host:cd/host:name/@avail');
            //$reasons = $xpath->query('/epp:epp/epp:response/epp:resData/host:chkData/host:cd/host:reason');
            $avail = false;
            foreach ($hosts as $idx => $host) {
                $available = $checks->item($idx)->nodeValue;
                switch ($available) {
                    case '0':
                    case 'false':
                        $avail = false;
                        break;
                    case '1':
                    case 'true':
                        $avail = true;
                        break;
                }
                $result[$host->nodeValue] = $avail;
            }
        }
        return ($result);
    }

    /**
     *
     * @return array of checked hosts with status true/false
     */
    public function getCheckedHostsExtended() {
        $result = null;
        if ($this->getResultCode() == self::RESULT_SUCCESS) {
            $xpath = $this->xPath();
            $hosts = $xpath->query('/epp:epp/epp:response/epp:resData/host:chkData/host:cd/host:name');
            $checks = $xpath->query('/epp:epp/epp:response/epp:resData/host:chkData/host:cd/host:name/@avail');
            $reasons = $xpath->query('/epp:epp/epp:response/epp:resData/host:chkData/host:cd/host:reason');
            $avail = false;
            foreach ($hosts as $idx => $host) {
                $available = $checks->item($idx)->nodeValue;
                switch ($available) {
                    case '0':
                    case 'false':
                        $avail = false;
                        break;
                    case '1':
                    case 'true':
                        $avail = true;
                        break;
                }
                $result[] = ['hostname'=>$host->nodeValue, 'available'=>$avail,'reason'=>$reasons->item($idx)->nodeValue];
            }
        }
        return ($result);
    }

    /**
     *
     * @return array of checked contacts with status true/false
     */
    public function getCheckedContacts() {
        $result = null;
        if ($this->getResultCode() == self::RESULT_SUCCESS) {
            $xpath = $this->xPath();
            $contacts = $xpath->query('/epp:epp/epp:response/epp:resData/contact:chkData/contact:cd/contact:id');
            $checks = $xpath->query('/epp:epp/epp:response/epp:resData/contact:chkData/contact:cd/contact:id/@avail');
            foreach ($contacts as $idx => $contact) {
                $avail = false;
                $available = $checks->item($idx)->nodeValue;
                switch ($available) {
                    case '0':
                    case 'false':
                        $avail = false;
                        break;
                    case '1':
                    case 'true':
                        $avail = true;
                        break;
                }
                $result[$contact->nodeValue] = $avail;
            }
        }
        return ($result);
    }
}

