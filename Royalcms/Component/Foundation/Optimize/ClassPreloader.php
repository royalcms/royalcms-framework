<?php


namespace Royalcms\Component\Foundation\Optimize;

use ClassPreloader\Factory;
use ClassPreloader\Parser\DirVisitor;
use ClassPreloader\Parser\FileVisitor;
use ClassPreloader\Parser\NodeTraverser;
use Royalcms\Component\Foundation\Composer;
use Royalcms\Component\Foundation\Royalcms;

class ClassPreloader
{
    /**
     * The composer instance.
     *
     * @var \Royalcms\Component\Foundation\Royalcms
     */
    protected $royalcms;

    /**
     * Create a new optimize command instance.
     *
     * @param  \Royalcms\Component\Foundation\Royalcms  $royalcms
     * @return void
     */
    public function __construct(Royalcms $royalcms)
    {
        $this->royalcms = $royalcms;
    }

    /**
     * Generate the compiled class file.
     *
     * @return void
     */
    public function compileClasses()
    {
        $preloader = $this->getClassPreloader();

        $handle = $preloader->prepareOutput($this->royalcms->getCachedCompilePath());

        foreach ($this->getClassFiles() as $file) {
            try {
                fwrite($handle, $preloader->getCode($file, false)."\n");
            } catch (VisitorExceptionInterface $e) {
                // Class Preloader 3.x
            }
        }

        fclose($handle);
    }

    /**
     * Get the class preloader used by the command.
     *
     * @return \ClassPreloader\ClassPreloader
     */
    protected function getClassPreloader()
    {
        // Class Preloader 3.x
        if (class_exists(Factory::class)) {
            return (new Factory)->create(['skip' => true]);
        }
    }

    /**
     * Get the classes that should be combined and compiled.
     *
     * @return array
     */
    protected function getClassFiles()
    {
        $royalcms = $this->royalcms;

        $files = array_merge($this->getFrameworkFiles(), $this->getCompileFiles());

        foreach ($royalcms['config']->get('compile.providers', []) as $provider) {
            $files = array_merge($files, forward_static_call([$provider, 'compiles']));
        }

        return array_map('realpath', $files);
    }

    /**
     * @return mixed
     */
    protected function getFrameworkFiles()
    {
        $core = require __DIR__.'/configs/config.php';

        return $core;
    }

    /**
     * @return mixed
     */
    protected function getCompileFiles()
    {
        return $royalcms['config']->get('compile.files', []);
    }


}