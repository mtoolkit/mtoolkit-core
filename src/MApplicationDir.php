<?php
namespace mtoolkit\core;

class MApplicationDir
{
    /**
     * @var string
     */
    private $path="";

    /**
     * @var string
     */
    private $namespace="";

    /**
     * @return string
     */
    public function getPath():string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return MApplicationDir
     */
    public function setPath(string $path):MApplicationDir
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace():string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     * @return MApplicationDir
     */
    public function setNamespace(string $namespace):MApplicationDir
    {
        $this->namespace = $namespace;
        return $this;
    }
}