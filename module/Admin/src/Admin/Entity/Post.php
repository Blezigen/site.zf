<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Post
 *
 * @ORM\Table(name="post", indexes={@ORM\Index(name="FK_post_category_idx", columns={"id_category"})})
 * @ORM\Entity
 */
class Post
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_post", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPost;

    /**
     * @var string
     *
     * @ORM\Column(name="post_title", type="string", length=100, nullable=false)
     */
    private $postTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="post_article", type="text", length=65535, nullable=false)
     */
    private $postArticle;

    /**
     * @var string
     *
     * @ORM\Column(name="post_short_article", type="text", length=65535, nullable=true)
     */
    private $postShortArticle;

    /**
     * @var boolean
     *
     * @ORM\Column(name="post_public", type="boolean", nullable=false)
     */
    private $postPublic = '0';

    /**
     * @var \Admin\Entity\Category
     *
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_category", referencedColumnName="id_category")
     * })
     */
    private $idCategory;



    /**
     * Get idPost
     *
     * @return integer
     */
    public function getIdPost()
    {
        return $this->idPost;
    }

    /**
     * Set postTitle
     *
     * @param string $postTitle
     *
     * @return Post
     */
    public function setPostTitle($postTitle)
    {
        $this->postTitle = $postTitle;

        return $this;
    }

    /**
     * Get postTitle
     *
     * @return string
     */
    public function getPostTitle()
    {
        return $this->postTitle;
    }

    /**
     * Set postArticle
     *
     * @param string $postArticle
     *
     * @return Post
     */
    public function setPostArticle($postArticle)
    {
        $this->postArticle = $postArticle;

        return $this;
    }

    /**
     * Get postArticle
     *
     * @return string
     */
    public function getPostArticle()
    {
        return $this->postArticle;
    }

    /**
     * Set postShortArticle
     *
     * @param string $postShortArticle
     *
     * @return Post
     */
    public function setPostShortArticle($postShortArticle)
    {
        $this->postShortArticle = $postShortArticle;

        return $this;
    }

    /**
     * Get postShortArticle
     *
     * @return string
     */
    public function getPostShortArticle()
    {
        return $this->postShortArticle;
    }

    /**
     * Set postPublic
     *
     * @param boolean $postPublic
     *
     * @return Post
     */
    public function setPostPublic($postPublic)
    {
        $this->postPublic = $postPublic;

        return $this;
    }

    /**
     * Get postPublic
     *
     * @return boolean
     */
    public function getPostPublic()
    {
        return $this->postPublic;
    }

    /**
     * Set idCategory
     *
     * @param \Admin\Entity\Category $idCategory
     *
     * @return Post
     */
    public function setIdCategory(\Admin\Entity\Category $idCategory = null)
    {
        $this->idCategory = $idCategory;

        return $this;
    }

    /**
     * Get idCategory
     *
     * @return \Admin\Entity\Category
     */
    public function getIdCategory()
    {
        return $this->idCategory;
    }
}
