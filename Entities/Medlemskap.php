<?php
namespace Entities;


/**
 * Medlemskap
 *
 * @Table(name="medlemskap")
 * @Entity
 * @method \Entities\Medlemskap getRawValue()
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
     * @ManyToOne(
     *  targetEntity="User",
     *  inversedBy="membership"
     * )
     * @JoinColumn(name="medlem", nullable=false, referencedColumnName="id")
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
    public function isValid(\DateTime $time){
      return $time>$this->start && $time<$this->slutt && $this->betalt;
    }
}