<?php


namespace Royalcms\Component\Foundation\Optimize;

use ClassPreloader\Exceptions\DirConstantException;
use ClassPreloader\Exceptions\FileConstantException;
use ClassPreloader\Exceptions\StrictTypesException;
use ClassPreloader\Factory;
use ClassPreloader\Parser\DirVisitor;
use ClassPreloader\Parser\FileVisitor;
use ClassPreloader\Parser\NodeTraverser;
use Royalcms\Component\Foundation\Composer;
use Royalcms\Component\Foundation\Royalcms;
use Royalcms\Component\Support\Facades\Log;

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
    public function compile()
    {
        $this->compileClasses();
    }

    /**
     * Generate the compiled class file.
     *
     * @return void
     */
    protected function compileClasses()
    {
        $preloader = $this->getClassPreloader();

        $handle = $preloader->prepareOutput($this->royalcms->getCachedCompilePath());

        $files = $this->getClassFiles();

        foreach ($files as $file) {
            try {
                if (file_exists($file)) {
                    $file = realpath($file);
                    $code = $preloader->getCode($file, false);
                    fwrite($handle, $code."\n");
                }
                else {
                    Log::notice($file . ' file not found.');
                }
            }
            catch (VisitorExceptionInterface $e) {
                Log::error($e);
            }
            catch (DirConstantException $e) {
                Log::error($e);
            }
            catch (FileConstantException $e) {
                Log::error($e);
            }
            catch (StrictTypesException $e) {
                Log::error($e);
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
            $files = array_merge($files, $this->getProviderCompiles($provider));
        }

        return $files;
    }

    /**
     * @param $provider
     * @return mixed
     */
    protected function getProviderCompiles($provider)
    {
        return forward_static_call([$provider, 'compiles']) ?: [];
    }

    /**
     * @return mixed
     */
    protected function getFrameworkFiles()
    {
        $royalcms = $this->royalcms;

        $core = require __DIR__.'/configs/config.php';

        return $core;
    }

    /**
     * @return mixed
     */
    protected function getCompileFiles()
    {
        return $this->royalcms['config']->get('compile.files', []);
    }


}