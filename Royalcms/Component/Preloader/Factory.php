<?php


namespace Royalcms\Component\Preloader;


use ClassPreloader\Factory as BaseFactory;
use Royalcms\Component\Preloader\MyStandard as PrettyPrinter;

class Factory extends BaseFactory
{

    /**
     * Create a new class preloader instance.
     *
     * Any options provided determine how the node traverser is setup.
     *
     * @param bool[] $options
     *
     * @return \ClassPreloader\ClassPreloader
     */
    public function create(array $options = [])
    {
        $printer = new PrettyPrinter();

        $parser = $this->getParser();

        $options = array_merge(['dir' => true, 'file' => true, 'skip' => false, 'strict' => false], $options);

        $traverser = $this->getTraverser($options['dir'], $options['file'], $options['skip'], $options['strict']);

        return new ClassPreloader($printer, $parser, $traverser);
    }

    /**
     * Get the node traverser to use.
     *
     * @param bool $dir
     * @param bool $file
     * @param bool $skip
     * @param bool $strict
     *
     * @return \ClassPreloader\Parser\NodeTraverser
     */
    protected function getTraverser($dir, $file, $skip, $strict)
    {
        $traverser = parent::getTraverser($dir, $file, $skip, $strict);

        // add your visitor
        $traverser->addVisitor(new MyNodeVisitor);

        return $traverser;
    }

}