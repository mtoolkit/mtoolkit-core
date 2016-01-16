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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return MApplicationDir
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     * @return MApplicationDir
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }
}