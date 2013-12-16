<?php

use Doctrine\ORM\Mapping AS ORM;
use JMS\Serializer\Annotation AS Serializer;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *  name="blockedConnection"
 * )
 */
class sspmod_janus_Model_Connection_Revision_BlockedConnectionRelation
{
    /**
     * @var sspmod_janus_Model_Connection_Revision
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="sspmod_janus_Model_Connection_Revision", inversedBy="blockedConnectionRelations")
     * @ORM\JoinColumn(name="connectionRevisionId", referencedColumnName="id", onDelete="cascade")
     */
    protected $connectionRevision;

    /**
     * @var sspmod_janus_Model_Connection
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="sspmod_janus_Model_Connection")
     * @ORM\JoinColumn(name="remoteeid", referencedColumnName="id", onDelete="cascade")
     * @Serializer\Groups({"compare"})
     *
     */
    protected $remoteConnection;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created", type="janusDateTime")
     */
    protected $createdAtDate;

    /**
     * @var sspmod_janus_Model_Ip
     *
     * @ORM\Column(name="ip", type="janusIp")
     */
    protected $updatedFromIp;

    /**
     * @param sspmod_janus_Model_Connection_Revision $connectionRevision
     * @param sspmod_janus_Model_Connection $remoteConnection
     */
    public function __construct(
        sspmod_janus_Model_Connection_Revision $connectionRevision,
        sspmod_janus_Model_Connection $remoteConnection
    ) {
        $this->connectionRevision = $connectionRevision;
        $this->remoteConnection = $remoteConnection;
    }

    /**
     * @param \DateTime $createdAtDate
     * @return $this
     */
    public function setCreatedAtDate(DateTime $createdAtDate)
    {
        $this->createdAtDate = $createdAtDate;
        return $this;
    }

    /**
     * @param sspmod_janus_Model_Ip $updatedFromIp
     * @return $this
     */
    public function setUpdatedFromIp(sspmod_janus_Model_Ip $updatedFromIp)
    {
        $this->updatedFromIp = $updatedFromIp;
        return $this;
    }

    /**
     * @return \sspmod_janus_Model_Connection
     */
    public function getRemoteConnection()
    {
        return $this->remoteConnection;
    }
}