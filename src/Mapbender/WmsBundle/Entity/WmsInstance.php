<?php

namespace Mapbender\WmsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Mapbender\CoreBundle\Entity\SourceInstance;
use Mapbender\WmsBundle\Component\WmsMetadata;
use Mapbender\WmsBundle\Entity\WmsInstanceLayer;
use Mapbender\WmsBundle\Entity\WmsSource;

/**
 * WmsInstance class
 *
 * @author Paul Schmidt
 *
 * @ORM\Entity
 * @ORM\Table(name="mb_wms_wmsinstance")
 * ORM\DiscriminatorMap({"mb_wms_wmssourceinstance" = "WmsSourceInstance"})
 */
class WmsInstance extends SourceInstance
{

    /**
     * @var array $configuration The instance configuration
     * @ORM\Column(type="array", nullable=true)
     */
    protected $configuration;

    /**
     * @ORM\ManyToOne(targetEntity="WmsSource", inversedBy="instance", cascade={"refresh"})
     * @ORM\JoinColumn(name="wmssource", referencedColumnName="id")
     */
    protected $source;

    /**
     * @ORM\OneToMany(targetEntity="WmsInstanceLayer", mappedBy="sourceInstance", cascade={"refresh", "persist", "remove"})
     * @ORM\JoinColumn(name="layers", referencedColumnName="id")
     * @ORM\OrderBy({"priority" = "asc"})
     */
    protected $layers; //{ name: 1,   title: Webatlas,   visible: true }

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $srs;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $format;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $infoformat;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $exceptionformat = null;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $transparency = true;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $visible = true;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $opacity = 100;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $proxy = false;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $tiled = false;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    protected $dimensions;

    /**
     * @ORM\Column(type="integer", options={"default" = 0})
     */
    protected $buffer = 0;

    /**
     * @ORM\Column(type="decimal", scale=2, options={"default" = 1.25})
     */
    protected $ratio = 1.25;

    public function __construct()
    {
        $this->layers = new ArrayCollection();
        $this->dimensions = array();
    }

    /**
     * Set id
     * @param integer $id
     * @return WmsInstance
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
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

    /**
     * Returns dimensions
     * 
     * @return array of DimensionIst
     */
    public function getDimensions()
    {
        return $this->dimensions ? : array();
    }

    /**
     * Sets dimensions
     * 
     * @param array $dimensions array of DimensionIst
     * @return \Mapbender\WmsBundle\Entity\WmsInstance
     */
    public function setDimensions(array $dimensions)
    {
        $this->dimensions = $dimensions;
        return $this;
    }

    /**
     * Set configuration
     *
     * @param array $configuration
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * Get an Instance Configuration.
     *
     * @return array $configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Set layers
     *
     * @param array $layers
     * @return WmsInstance
     */
    public function setLayers($layers)
    {
        $this->layers = $layers;
        return $this;
    }

    /**
     * Get layers
     *
     * @return array
     */
    public function getLayers()
    {
        return $this->layers;
    }

    /**
     * Get root layer
     *
     * @return WmsInstanceLayer
     */
    public function getRootlayer()
    {
        foreach ($this->layers as $layer) {
            if ($layer->getParent() === null) {
                return $layer;
            }
        }
        return null;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return WmsInstance
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set srs
     *
     * @param array $srs
     * @return WmsInstance
     */
    public function setSrs($srs)
    {
        $this->srs = $srs;
        return $this;
    }

    /**
     * Get srs
     *
     * @return array
     */
    public function getSrs()
    {
        return $this->srs;
    }

    /**
     * Set format
     *
     * @param string $format
     * @return WmsInstance
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * Get format
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format !== null ? $this->format : 'image/png';
    }

    /**
     * Set infoformat
     *
     * @param string $infoformat
     * @return WmsInstance
     */
    public function setInfoformat($infoformat)
    {
        $this->infoformat = $infoformat;
        return $this;
    }

    /**
     * Get infoformat
     *
     * @return string
     */
    public function getInfoformat()
    {
        return $this->infoformat;
    }

    /**
     * Set exceptionformat
     *
     * @param string $exceptionformat
     * @return WmsInstance
     */
    public function setExceptionformat($exceptionformat)
    {
        $this->exceptionformat = $exceptionformat;
        return $this;
    }

    /**
     * Get exceptionformat
     *
     * @return string
     */
    public function getExceptionformat()
    {
        return $this->exceptionformat;
    }

    /**
     * Set transparency
     *
     * @param boolean $transparency
     * @return WmsInstance
     */
    public function setTransparency($transparency)
    {
        $this->transparency = $transparency;
        return $this;
    }

    /**
     * Get transparency
     *
     * @return boolean
     */
    public function getTransparency()
    {
        return $this->transparency;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     * @return WmsInstance
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set opacity
     *
     * @param integer $opacity
     * @return WmsInstance
     */
    public function setOpacity($opacity)
    {
        $this->opacity = $opacity;
        return $this;
    }

    /**
     * Get opacity
     *
     * @return integer
     */
    public function getOpacity()
    {
        return $this->opacity;
    }

    /**
     * Set proxy
     *
     * @param boolean $proxy
     * @return WmsInstance
     */
    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
        return $this;
    }

    /**
     * Get proxy
     *
     * @return boolean
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * Set tiled
     *
     * @param boolean $tiled
     * @return WmsInstance
     */
    public function setTiled($tiled)
    {
        $this->tiled = $tiled;
        return $this;
    }

    /**
     * Get tiled
     *
     * @return boolean
     */
    public function getTiled()
    {
        return $this->tiled;
    }

    /**
     * Set ratio
     *
     * @param boolean $ratio
     * @return WmsInstance
     */
    public function setRatio($ratio)
    {
        $this->ratio = $ratio;

        return $this;
    }

    /**
     * Get ratio
     *
     * @return boolean
     */
    public function getRatio()
    {
        return $this->ratio;
    }

    /**
     * Set buffer
     *
     * @param boolean $buffer
     * @return WmsInstance
     */
    public function setBuffer($buffer)
    {
        $this->buffer = $buffer;

        return $this;
    }

    /**
     * Get buffer
     *
     * @return boolean
     */
    public function getBuffer()
    {
        return $this->buffer;
    }

    /**
     * Set wmssource
     *
     * @param WmsSource $wmssource
     * @return WmsInstance
     */
    public function setSource($wmssource = null)
    {
        $this->source = $wmssource;
        return $this;
    }

    /**
     * Get wmssource
     *
     * @return WmsSource
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Add layers
     *
     * @param WmsInstanceLayer $layers
     * @return WmsInstance
     */
    public function addLayer(WmsInstanceLayer $layer)
    {
        $this->layers->add($layer);
        return $this;
    }

    /**
     * Remove layers
     *
     * @param WmsInstanceLayer $layers
     */
    public function removeLayer(WmsInstanceLayer $layers)
    {
        $this->layers->removeElement($layers);
    }

    public function __toString()
    {
        return (string) $this->getId();
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return "wms";
    }

    /**
     * @inheritdoc
     */
    public function getManagerType()
    {
        return "wms";
    }

    /**
     * @inheritdoc
     */
    static public function listAssets()
    {
        return array(
            'js' => array(
                '@MapbenderWmsBundle/Resources/public/mapbender.source.wms.js'),
            'css' => array(),
            'trans' => array('MapbenderWmsBundle::wmsbundle.json.twig'));
    }

    /**
     * @return WmsMetadata
     */
    public function getMetadata()
    {
        return new WmsMetadata($this);
    }

    /**
     * @inheritdoc
     */
    public function copy(EntityManager $em)
    {
        $inst = new WmsInstance();
        $inst->title = $this->title;
        $inst->weight = $this->weight;
        $inst->enabled = $this->enabled;
        $inst->configuration = $this->configuration; //???
        $inst->source = $this->source;
        $inst->srs = $this->srs;
        $inst->format = $this->format;
        $inst->infoformat = $this->infoformat;
        $inst->exceptionformat = $this->exceptionformat;
        $inst->transparency = $this->transparency;
        $inst->visible = $this->visible;
        $inst->opacity = $this->opacity;
        $inst->proxy = $this->proxy;
        $inst->tiled = $this->tiled;
        $inst->ratio = $this->ratio;
        $inst->buffer = $this->buffer;
        $this->copyLayerRecursive($em, $inst, $this->getRootlayer(), NULL);
        return $inst;
    }

    /**
     * Recursively copy a nested Layerstructure
     * @param EntityManager $em
     * @param WmsInstanceLayer $instLayer
     */
    private function copyLayerRecursive(EntityManager $em, WmsInstance $instCloned, WmsInstanceLayer $origin,
        WmsInstanceLayer $clonedParent = null)
    {
        $cloned = $origin->copy($em);
        $cloned->setWmsinstance($instCloned);
        $cloned->setSourceItem($origin->getSourceItem());
        if ($clonedParent !== null) {
            $cloned->setParent($clonedParent);
            $clonedParent->addSublayer($cloned);
        }
        $instCloned->addLayer($cloned);
        foreach ($origin->getSublayer() as $sublayer) {
            $this->copyLayerRecursive($em, $instCloned, $sublayer, $cloned);
        }
    }
}
