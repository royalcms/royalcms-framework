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

        $core = require __DIR__.'/configs/config.php';

        $files = array_merge($core, $royalcms['config']->get('compile.files', []));

        foreach ($royalcms['config']->get('compile.providers', []) as $provider) {
            $files = array_merge($files, forward_static_call([$provider, 'compiles']));
        }

        return array_map('realpath', $files);
    }

    /**
     * Get the node traverser used by the command.
     *
     * Note that this method is only called if we're using Class Preloader 2.x.
     *
     * @return \ClassPreloader\Parser\NodeTraverser
     */
    protected function getTraverser()
    {
        $traverser = new NodeTraverser;

        $traverser->addVisitor(new DirVisitor(true));

        $traverser->addVisitor(new FileVisitor(true));

        return $traverser;
    }

}