<?php
namespace Metaregistrar\EPP;

class eppTransferRequest extends eppRequest {
    const OPERATION_QUERY = 'query';
    const OPERATION_REQUEST = 'request';
    const OPERATION_APPROVE = 'approve';
    const OPERATION_REJECT = 'reject';
    const OPERATION_CANCEL = 'cancel';

    function __construct($operation, $object) {
        parent::__construct();

        #
        # Sanity checks
        #
        switch ($operation) {
            case self::OPERATION_QUERY:
                if ($object instanceof eppDomain) {
                    if (!strlen($object->getDomainname())) {
                        throw new eppException('Domain object does not contain a valid domain name on eppTransferRequest');
                    }
                    $this->setDomainQuery($object);
                } elseif ($object instanceof eppContactHandle) {
                    $this->setContactQuery($object);
                }
                break;
            case self::OPERATION_REQUEST:
                if ($object instanceof eppDomain) {
                    if (!strlen($object->getDomainname())) {
                        throw new eppException('Domain object does not contain a valid domain name on eppTransferRequest');
                    }
                    $this->setDomainRequest($object);
                } elseif ($object instanceof eppContactHandle) {
                    $this->setContactRequest($object);
                }
                break;
            case self::OPERATION_CANCEL:
                if ($object instanceof eppDomain) {
                    if (!strlen($object->getDomainname())) {
                        throw new eppException('Domain object does not contain a valid domain name on eppTransferRequest');
                    }
                    $this->setDomainCancel($object);
                } elseif ($object instanceof eppContactHandle) {
                    throw new eppException('CANCEL operation not possible on contact transfer query');
                }
                break;
            case self::OPERATION_APPROVE:
                if ($object instanceof eppDomain) {
                    if (!strlen($object->getDomainname())) {
                        throw new eppException('Domain object does not contain a valid domain name on eppTransferRequest');
                    }
                    $this->setDomainApprove($object);
                } elseif ($object instanceof eppContactHandle) {
                    throw new eppException('APPROVE operation not possible on contact transfer query');
                }
                break;
            case self::OPERATION_REJECT:
                if ($object instanceof eppDomain) {
                    if (!strlen($object->getDomainname())) {
                        throw new eppException('Domain object does not contain a valid domain name on eppTransferRequest');
                    }
                    $this->setDomainReject($object);
                } elseif ($object instanceof eppContactHandle) {
                    throw new eppException('REJECT operation not possible on contact transfer query');
                }
                break;
            default:
                trigger_error('Operation parameter should be QUERY, REQUEST, CANCEL, APPROVE or REJECT on eppTransferRequest, ignore this if you are using a custom transfer operation', E_USER_NOTICE);
                break;
        }
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }


    public function setDomainQuery(eppDomain $domain) {
        #
        # Object create structure
        #
        $transfer = $this->createElement('transfer');
        $transfer->setAttribute('op', self::OPERATION_QUERY);
        $this->domainobject = $this->createElement('domain:transfer');
        $this->domainobject->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        if (strlen($domain->getAuthorisationCode())) {
            $authinfo = $this->createElement('domain:authInfo');
            $authinfo->appendChild($this->createElement('domain:pw', $domain->getAuthorisationCode()));
            $this->domainobject->appendChild($authinfo);
        }
        $transfer->appendChild($this->domainobject);
        $this->getCommand()->appendChild($transfer);
    }


    public function setDomainApprove(eppDomain $domain) {
        #
        # Object create structure
        #
        $transfer = $this->createElement('transfer');
        $transfer->setAttribute('op', self::OPERATION_APPROVE);
        $this->domainobject = $this->createElement('domain:transfer');
        $this->domainobject->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        if (strlen($domain->getAuthorisationCode())) {
            $authinfo = $this->createElement('domain:authInfo');
            $authinfo->appendChild($this->createElement('domain:pw', $domain->getAuthorisationCode()));
            $this->domainobject->appendChild($authinfo);
        }
        $transfer->appendChild($this->domainobject);
        $this->getCommand()->appendChild($transfer);
    }


    public function setDomainReject(eppDomain $domain) {
        #
        # Object create structure
        #
        $transfer = $this->createElement('transfer');
        $transfer->setAttribute('op', self::OPERATION_REJECT);
        $this->domainobject = $this->createElement('domain:transfer');
        $this->domainobject->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        if (strlen($domain->getAuthorisationCode())) {
            $authinfo = $this->createElement('domain:authInfo');
            $authinfo->appendChild($this->createElement('domain:pw', $domain->getAuthorisationCode()));
            $this->domainobject->appendChild($authinfo);
        }
        $transfer->appendChild($this->domainobject);
        $this->getCommand()->appendChild($transfer);
    }


    public function setDomainCancel(eppDomain $domain) {
        #
        # Object create structure
        #
        $transfer = $this->createElement('transfer');
        $transfer->setAttribute('op', self::OPERATION_CANCEL);
        $this->domainobject = $this->createElement('domain:transfer');
        $this->domainobject->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        if (strlen($domain->getAuthorisationCode())) {
            $authinfo = $this->createElement('domain:authInfo');
            $authinfo->appendChild($this->createElement('domain:pw', $domain->getAuthorisationCode()));
            $this->domainobject->appendChild($authinfo);
        }
        $transfer->appendChild($this->domainobject);
        $this->getCommand()->appendChild($transfer);
    }


    public function setContactQuery(eppContactHandle $contact) {
        #
        # Object create structure
        #
        $transfer = $this->createElement('transfer');
        $transfer->setAttribute('op', self::OPERATION_QUERY);
        $this->contactobject = $this->createElement('contact:transfer');
        $this->contactobject->appendChild($this->createElement('contact:id', $contact->getContactHandle()));
        $transfer->appendChild($this->contactobject);
        $this->getCommand()->appendChild($transfer);
    }


    public function setDomainRequest(eppDomain $domain) {
        #
        # Object create structure
        #
        $transfer = $this->createElement('transfer');
        $transfer->setAttribute('op', self::OPERATION_REQUEST);
        $this->domainobject = $this->createElement('domain:transfer');
        $this->domainobject->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        if ($domain->getPeriod()) {
            $domainperiod = $this->createElement('domain:period', $domain->getPeriod());
            $domainperiod->setAttribute('unit', eppDomain::DOMAIN_PERIOD_UNIT_Y);
            $this->domainobject->appendChild($domainperiod);
        }
        if (strlen($domain->getAuthorisationCode())) {
            $authinfo = $this->createElement('domain:authInfo');
            $pw = $authinfo->appendChild($this->createElement('domain:pw'));
            $pw->appendChild($this->createCDATASection($domain->getAuthorisationCode()));
            //$authinfo->appendChild($this->createElement('domain:pw', $domain->getAuthorisationCode()));
            $this->domainobject->appendChild($authinfo);
        }
        $transfer->appendChild($this->domainobject);
        $this->getCommand()->appendChild($transfer);
    }


    public function setContactRequest(eppContactHandle $contact) {
        #
        # Object create structure
        #
        $transfer = $this->createElement('transfer');
        $transfer->setAttribute('op', self::OPERATION_REQUEST);
        $this->contactobject = $this->createElement('contact:transfer');
        $this->contactobject->appendChild($this->createElement('contact:id', $contact->getContactHandle()));
	if (strlen($contact->getPassword())) {
	    $authinfo = $this->createElement('contact:authInfo');
	    $authinfo->appendChild($this->createElement('contact:pw', $contact->getPassword()));
	    $this->contactobject->appendChild($authinfo);
	}
        $transfer->appendChild($this->contactobject);
        $this->getCommand()->appendChild($transfer);
    }


}
