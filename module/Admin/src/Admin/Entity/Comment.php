<?php

namespace Admin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table(name="comment", indexes={@ORM\Index(name="FK_COMMENT_POST_idx", columns={"id_post"})})
 * @ORM\Entity
 */
class Comment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id_comment", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idComment;

    /**
     * @var string
     *
     * @ORM\Column(name="comment_user_email", type="string", length=50, nullable=false)
     */
    private $commentUserEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="comment_text", type="text", length=65535, nullable=false)
     */
    private $commentText;

    /**
     * @var \Admin\Entity\Post
     *
     * @ORM\ManyToOne(targetEntity="Admin\Entity\Post")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_post", referencedColumnName="id_post")
     * })
     */
    private $idPost;



    /**
     * Get idComment
     *
     * @return integer
     */
    public function getIdComment()
    {
        return $this->idComment;
    }

    /**
     * Set commentUserEmail
     *
     * @param string $commentUserEmail
     *
     * @return Comment
     */
    public function setCommentUserEmail($commentUserEmail)
    {
        $this->commentUserEmail = $commentUserEmail;

        return $this;
    }

    /**
     * Get commentUserEmail
     *
     * @return string
     */
    public function getCommentUserEmail()
    {
        return $this->commentUserEmail;
    }

    /**
     * Set commentText
     *
     * @param string $commentText
     *
     * @return Comment
     */
    public function setCommentText($commentText)
    {
        $this->commentText = $commentText;

        return $this;
    }

    /**
     * Get commentText
     *
     * @return string
     */
    public function getCommentText()
    {
        return $this->commentText;
    }

    /**
     * Set idPost
     *
     * @param \Admin\Entity\Post $idPost
     *
     * @return Comment
     */
    public function setIdPost(\Admin\Entity\Post $idPost = null)
    {
        $this->idPost = $idPost;

        return $this;
    }

    /**
     * Get idPost
     *
     * @return \Admin\Entity\Post
     */
    public function getIdPost()
    {
        return $this->idPost;
    }
}
