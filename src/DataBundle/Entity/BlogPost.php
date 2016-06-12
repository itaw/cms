<?php

namespace DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * BlogPost.
 *
 * @ORM\Table(name="blog_post")
 * @ORM\Entity(repositoryClass="DataBundle\Repository\BlogPostRepository")
 */
class BlogPost
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="excerpt", type="text", nullable=true)
     */
    private $excerpt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publish_date", type="datetime")
     */
    private $publishDate;

    /**
     * @var string
     *
     * @ORM\Column(name="main_image_url", type="string", length=255, nullable=true)
     */
    private $mainImageUrl;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_markdown", type="boolean")
     */
    private $isMarkdown;

    /**
     * @ORM\ManyToOne(targetEntity="Blog", inversedBy="posts")
     * @ORM\JoinColumn(name="blog_id", referencedColumnName="id")
     */
    private $blog;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="posts")
     * @ORM\JoinTable(name="posts_tags")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="blogPosts")
     * @ORM\JoinColumn(name="author_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_published", type="boolean")
     */
    private $published;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
     * Set title.
     *
     * @param string $title
     *
     * @return BlogPost
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
     * Set slug.
     *
     * @param string $slug
     *
     * @return BlogPost
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
     * Set content.
     *
     * @param string $content
     *
     * @return BlogPost
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
     * Set blog.
     *
     * @param \DataBundle\Entity\Blog $blog
     *
     * @return BlogPost
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
     * Add tag.
     *
     * @param \DataBundle\Entity\Tag $tag
     *
     * @return BlogPost
     */
    public function addTag(\DataBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag.
     *
     * @param \DataBundle\Entity\Tag $tag
     */
    public function removeTag(\DataBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function removeTags()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * Set author.
     *
     * @param \DataBundle\Entity\User $author
     *
     * @return BlogPost
     */
    public function setAuthor(\DataBundle\Entity\User $author = null)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author.
     *
     * @return \DataBundle\Entity\User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set excerpt.
     *
     * @param string $excerpt
     *
     * @return BlogPost
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    /**
     * Get excerpt.
     *
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * Set publishDate.
     *
     * @param \DateTime $publishDate
     *
     * @return BlogPost
     */
    public function setPublishDate($publishDate)
    {
        $this->publishDate = $publishDate;

        return $this;
    }

    /**
     * Get publishDate.
     *
     * @return \DateTime
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

    /**
     * Set mainImageUrl.
     *
     * @param string $mainImageUrl
     *
     * @return BlogPost
     */
    public function setMainImageUrl($mainImageUrl)
    {
        $this->mainImageUrl = $mainImageUrl;

        return $this;
    }

    /**
     * Get mainImageUrl.
     *
     * @return string
     */
    public function getMainImageUrl()
    {
        return $this->mainImageUrl;
    }

    /**
     * Set isMarkdown.
     *
     * @param bool $isMarkdown
     *
     * @return BlogPost
     */
    public function setIsMarkdown($isMarkdown)
    {
        $this->isMarkdown = $isMarkdown;

        return $this;
    }

    /**
     * Get isMarkdown.
     *
     * @return bool
     */
    public function getIsMarkdown()
    {
        return $this->isMarkdown;
    }

    /**
     * Set published.
     *
     * @param bool $published
     *
     * @return BlogPost
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published.
     *
     * @return bool
     */
    public function getPublished()
    {
        return $this->published;
    }
}
