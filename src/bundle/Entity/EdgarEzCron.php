<?php

namespace Edgar\EzUICronBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EdgarEzCron
 *
 * @ORM\Entity(repositoryClass="Edgar\EzUICron\Repository\EdgarEzCronRepository")
 * @ORM\Table(name="edgar_ez_cron")
 */
class EdgarEzCron
{
    /**
     * @var string
     *
     * @ORM\Column(name="alias", type="string", length=255, nullable=false)
     * @ORM\Id
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(name="expression", type="string", length=255, nullable=false)
     */
    private $expression;

    /**
     * @var string
     *
     * @ORM\Column(name="arguments", type="string", length=255, nullable=true)
     */
    private $arguments;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer", nullable=false)
     */
    private $priority = 100;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    private $enabled = 1;

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * Set alias
     *
     * @param string $alias
     * @return EdgarEzCron
     */
    public function setAlias(string $alias): self
    {
        $this->alias = $alias;
        return $this;
    }

    /**
     * Set expression
     *
     * @param string $expression
     * @return EdgarEzCron
     */
    public function setExpression(string $expression): self
    {
        $this->expression = $expression;
        return $this;
    }

    /**
     * Get expression
     *
     * @return string
     */
    public function getExpression(): string
    {
        return $this->expression;
    }

    /**
     * Set arguments
     *
     * @param string $arguments
     * @return EdgarEzCron
     */
    public function setArguments(?string $arguments): self
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * Get arguments
     *
     * @return string
     */
    public function getArguments(): ?string
    {
        return $this->arguments;
    }

    /**
     * Set priority
     *
     * @param int $priority
     * @return EdgarEzCron
     */
    public function setPriority(int $priority): self
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * Get priority
     *
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * Set enabled
     *
     * @param bool $enabled
     * @return EdgarEzCron
     */
    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * Get enabled
     *
     * @return bool
     */
    public function getEnabled(): bool
    {
        return $this->enabled;
    }
}
