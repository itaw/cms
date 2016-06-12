<?php

namespace DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Page.
 *
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="DataBundle\Repository\PageRepository")
 */
class Page
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_title", type="string", length=255)
     */
    private $metaTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="text", nullable=true)
     */
    private $metaDescription;

    /**
     * @var int
     *
     * @ORM\Column(name="ordering", type="integer")
     */
    private $ordering;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var bool
     *
     * @ORM\Column(name="hidden", type="boolean")
     */
    private $hidden;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_blog", type="boolean")
     */
    private $isBlog = false;

    /**
     * @ORM\ManyToOne(targetEntity="Blog")
     * @ORM\JoinColumn(name="blog_id", referencedColumnName="id")
     */
    private $blog;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_blog_tag", type="boolean")
     */
    private $isBlogTag = false;

    /**
     * @ORM\ManyToOne(targetEntity="Tag")
     * @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     */
    private $tag;

    /**
     * @var bool
     *
     * @ORM\Column(name="enable_twig", type="boolean")
     */
    private $enableTwig;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_index", type="boolean")
     */
    private $isIndex = false;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return Page
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Page
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Page
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set metaTitle.
     *
     * @param string $metaTitle
     *
     * @return Page
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Get metaTitle.
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Set metaDescription.
     *
     * @param string $metaDescription
     *
     * @return Page
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription.
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set ordering.
     *
     * @param int $ordering
     *
     * @return Page
     */
    public function setOrdering($ordering)
    {
        $this->ordering = $ordering;

        return $this;
    }

    /**
     * Get ordering.
     *
     * @return int
     */
    public function getOrdering()
    {
        return $this->ordering;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Page
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set hidden.
     *
     * @param bool $hidden
     *
     * @return Page
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Get hidden.
     *
     * @return bool
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Set isBlog.
     *
     * @param bool $isBlog
     *
     * @return Page
     */
    public function setIsBlog($isBlog)
    {
        $this->isBlog = $isBlog;

        return $this;
    }

    /**
     * Get isBlog.
     *
     * @return bool
     */
    public function getIsBlog()
    {
        return $this->isBlog;
    }

    /**
     * Set isBlogTag.
     *
     * @param bool $isBlogTag
     *
     * @return Page
     */
    public function setIsBlogTag($isBlogTag)
    {
        $this->isBlogTag = $isBlogTag;

        return $this;
    }

    /**
     * Get isBlogTag.
     *
     * @return bool
     */
    public function getIsBlogTag()
    {
        return $this->isBlogTag;
    }

    /**
     * Set enableTwig.
     *
     * @param bool $enableTwig
     *
     * @return Page
     */
    public function setEnableTwig($enableTwig)
    {
        $this->enableTwig = $enableTwig;

        return $this;
    }

    /**
     * Get enableTwig.
     *
     * @return bool
     */
    public function getEnableTwig()
    {
        return $this->enableTwig;
    }

    /**
     * Set blog.
     *
     * @param \DataBundle\Entity\Blog $blog
     *
     * @return Page
     */
    public function setBlog(\DataBundle\Entity\Blog $blog = null)
    {
        $this->blog = $blog;

        return $this;
    }

    /**
     * Get blog.
     *
     * @return \DataBundle\Entity\Blog
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * Set tag.
     *
     * @param \DataBundle\Entity\Tag $tag
     *
     * @return Page
     */
    public function setTag(\DataBundle\Entity\Tag $tag = null)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag.
     *
     * @return \DataBundle\Entity\Tag
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Add child.
     *
     * @param \DataBundle\Entity\Page $child
     *
     * @return Page
     */
    public function addChild(\DataBundle\Entity\Page $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child.
     *
     * @param \DataBundle\Entity\Page $child
     */
    public function removeChild(\DataBundle\Entity\Page $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent.
     *
     * @param \DataBundle\Entity\Page $parent
     *
     * @return Page
     */
    public function setParent(\DataBundle\Entity\Page $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent.
     *
     * @return \DataBundle\Entity\Page
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set isIndex.
     *
     * @param bool $isIndex
     *
     * @return Page
     */
    public function setIsIndex($isIndex)
    {
        $this->isIndex = $isIndex;

        return $this;
    }

    /**
     * Get isIndex.
     *
     * @return bool
     */
    public function getIsIndex()
    {
        return $this->isIndex;
    }
}
