<?php
namespace Entities;


/**
 * Medlemskap
 *
 * @Table(name="medlemskap")
 * @Entity
 */
class Medlemskap
{
    /**
     * @var integer $id
     *
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer $medlem
     *
     * @Column(name="medlem", type="integer", nullable=false)
     */
    private $medlem;

    /**
     * @var date $start
     *
     * @Column(name="start", type="date", nullable=false)
     */
    private $start;

    /**
     * @var date $slutt
     *
     * @Column(name="slutt", type="date", nullable=false)
     */
    private $slutt;

    /**
     * @var boolean $betalt
     *
     * @Column(name="betalt", type="boolean", nullable=false)
     */
    private $betalt;

}