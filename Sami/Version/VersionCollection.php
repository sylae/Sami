<?php

/*
 * This file is part of the Sami utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sami\Version;

use Sami\Project;

abstract class VersionCollection implements \Iterator, \Countable
{
    protected $versions = array();
    protected $indice;
    protected $project;

    public function __construct($versions)
    {
        $this->add($versions);
    }

    abstract protected function switchVersion(Version $version);

    public static function create()
    {
        $r = new \ReflectionClass(get_called_class());

        return $r->newInstanceArgs(func_get_args());
    }

    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    public function add($version, $longname = null)
    {
        if (is_array($version)) {
            foreach ($version as $v) {
                $this->add($v);
            }
        } else {
            if (!$version instanceof Version) {
                $version = new Version($version, $longname);
            }

            $this->versions[] = $version;
        }

        return $this;
    }

    public function getVersions()
    {
        return $this->versions;
    }

    public function key(): mixed
    {
        return $this->indice;
    }

    public function current(): mixed
    {
        return $this->versions[$this->indice];
    }

    public function next(): void
    {
        ++$this->indice;
    }

    public function rewind(): void
    {
        $this->indice = 0;
    }

    public function valid(): bool
    {
        if ($this->indice < count($this->versions)) {
            $this->switchVersion($this->current());

            return true;
        }

        return false;
    }

    public function count(): int
    {
        return count($this->versions);
    }
}
