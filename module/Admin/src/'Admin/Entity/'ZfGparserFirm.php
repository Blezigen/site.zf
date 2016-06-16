<?php

namespace 'Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 'ZfGparserFirm
 *
 * @ORM\Table(name="zf_gparser_firm")
 * @ORM\Entity
 */
class 'ZfGparserFirm
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_gparser_firm", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idGparserFirm;

    /**
     * @var string
     *
     * @ORM\Column(name="firm_alias", type="string", length=255, nullable=false)
     */
    private $firmAlias;

    /**
     * @var string
     *
     * @ORM\Column(name="firm_name", type="string", length=255, nullable=false)
     */
    private $firmName;

    /**
     * @var string
     *
     * @ORM\Column(name="firm_city", type="string", length=255, nullable=false)
     */
    private $firmCity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="firm_insert_date", type="datetime", nullable=false)
     */
    private $firmInsertDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="firm_external_id", type="bigint", nullable=false)
     */
    private $firmExternalId;


}

