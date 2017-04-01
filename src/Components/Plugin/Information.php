<?php

namespace CMS\Components\Plugin;

class Information
{

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $website;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $version;

    public function __construct($filename)
    {
        $contents = file_get_contents($filename);
        $data     = json_decode($contents, true);

        if (!$data)
        {
            throw new \Exception('Unable to read plugin information file!');
        }

        $this->label       = $data['label'];
        $this->description = $data['description'];
        $this->author      = $data['author'];
        $this->website     = $data['website'];
        $this->email       = $data['email'];
        $this->version     = $data['version'];
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getWebsite()
    {
        return $this->website;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getVersion()
    {
        return $this->version;
    }

}