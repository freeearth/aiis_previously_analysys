<?php

namespace PlotsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
/**
 * @ORM\Table(name="rshu_new")
 * @ORM\Entity(repositoryClass="PlotsBundle\Entity\RshuRepository")
 */
class RshuNew extends Controller
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="T", type="float", precision=10, scale=0, nullable=true)
     */
    private $t;

    /**
     * @var float
     *
     * @ORM\Column(name="P", type="float", precision=10, scale=0, nullable=true)
     */
    private $p;

    /**
     * @var float
     *
     * @ORM\Column(name="F", type="float", precision=10, scale=0, nullable=true)
     */
    private $f;

    /**
     * @var float
     *
     * @ORM\Column(name="Pr", type="float", precision=10, scale=0, nullable=true)
     */
    private $pr;

    /**
     * @var float
     *
     * @ORM\Column(name="R", type="float", precision=10, scale=0, nullable=true)
     */
    private $r;

    /**
     * @var float
     *
     * @ORM\Column(name="Vx", type="float", precision=10, scale=0, nullable=true)
     */
    private $vx;

    /**
     * @var float
     *
     * @ORM\Column(name="Vy", type="float", precision=10, scale=0, nullable=true)
     */
    private $vy;

    /**
     * @var float
     *
     * @ORM\Column(name="d", type="float", precision=10, scale=0, nullable=true)
     */
    private $d;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datetime", type="datetime", nullable=true)
     */
    private $datetime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="edited", type="datetime", nullable=false)
     */
    private $edited = 'CURRENT_TIMESTAMP';



    /**
     * Set t
     *
     * @param float $t
     * @return RshuNew
     */
    public function setT($t)
    {
        $this->t = $t;

        return $this;
    }

    /**
     * Get t
     *
     * @return float 
     */
    public function getT()
    {
        return $this->t;
    }

    /**
     * Set p
     *
     * @param float $p
     * @return RshuNew
     */
    public function setP($p)
    {
        $this->p = $p;

        return $this;
    }

    /**
     * Get p
     *
     * @return float 
     */
    public function getP()
    {
        return $this->p;
    }

    /**
     * Set f
     *
     * @param float $f
     * @return RshuNew
     */
    public function setF($f)
    {
        $this->f = $f;

        return $this;
    }

    /**
     * Get f
     *
     * @return float 
     */
    public function getF()
    {
        return $this->f;
    }

    /**
     * Set pr
     *
     * @param float $pr
     * @return RshuNew
     */
    public function setPr($pr)
    {
        $this->pr = $pr;

        return $this;
    }

    /**
     * Get pr
     *
     * @return float 
     */
    public function getPr()
    {
        return $this->pr;
    }

    /**
     * Set r
     *
     * @param float $r
     * @return RshuNew
     */
    public function setR($r)
    {
        $this->r = $r;

        return $this;
    }

    /**
     * Get r
     *
     * @return float 
     */
    public function getR()
    {
        return $this->r;
    }

    /**
     * Set vx
     *
     * @param float $vx
     * @return RshuNew
     */
    public function setVx($vx)
    {
        $this->vx = $vx;

        return $this;
    }

    /**
     * Get vx
     *
     * @return float 
     */
    public function getVx()
    {
        return $this->vx;
    }

    /**
     * Set vy
     *
     * @param float $vy
     * @return RshuNew
     */
    public function setVy($vy)
    {
        $this->vy = $vy;

        return $this;
    }

    /**
     * Get vy
     *
     * @return float 
     */
    public function getVy()
    {
        return $this->vy;
    }

    /**
     * Set d
     *
     * @param float $d
     * @return RshuNew
     */
    public function setD($d)
    {
        $this->d = $d;

        return $this;
    }

    /**
     * Get d
     *
     * @return float 
     */
    public function getD()
    {
        return $this->d;
    }

    /**
     * Set datetime
     *
     * @param \DateTime $datetime
     * @return RshuNew
     */
    public function setDatetime($datetime)
    {
        $this->datetime = $datetime;

        return $this;
    }

    /**
     * Get datetime
     *
     * @return \DateTime 
     */
    public function getDatetime()
    {
        return $this->datetime;
    }

    /**
     * Set edited
     *
     * @param \DateTime $edited
     * @return RshuNew
     */
    public function setEdited($edited)
    {
        $this->edited = $edited;

        return $this;
    }

    /**
     * Get edited
     *
     * @return \DateTime 
     */
    public function getEdited()
    {
        return $this->edited;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
