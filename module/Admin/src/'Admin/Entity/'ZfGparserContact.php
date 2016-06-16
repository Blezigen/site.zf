<?php

namespace 'Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 'ZfGparserContact
 *
 * @ORM\Table(name="zf_gparser_contact")
 * @ORM\Entity
 */
class 'ZfGparserContact
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_gparser_contact", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idGparserContact;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_gparser_firm", type="integer", nullable=false)
     */
    private $idGparserFirm;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_type", type="string", length=20, nullable=false)
     */
    private $contactType;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_value", type="string", length=255, nullable=false)
     */
    private $contactValue;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_data", type="string", length=255, nullable=false)
     */
    private $contactData;


}

