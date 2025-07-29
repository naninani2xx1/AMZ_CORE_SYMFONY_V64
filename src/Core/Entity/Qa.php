<?php

namespace App\Core\Entity;

use App\Core\Trait\DoctrineIdentifierTrait;
use App\Core\ValueObject\LifecycleEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Core\Repository\QaRepository")
 * @ORM\Table(name="core_qa")
 * @ORM\HasLifecycleCallbacks
 */
class Qa extends LifecycleEntity
{
    use DoctrineIdentifierTrait;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $question;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $answer;

    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion($question): void
    {
        $this->question = $question;
    }

    /**
     * @param mixed $answer
     */
    public function setAnswer($answer): void
    {
        $this->answer = $answer;
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
