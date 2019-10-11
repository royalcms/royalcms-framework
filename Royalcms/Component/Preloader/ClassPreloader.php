<?php


namespace Royalcms\Component\Preloader;

use ClassPreloader\Exceptions\DirConstantException;
use ClassPreloader\Exceptions\FileConstantException;
use ClassPreloader\Exceptions\StrictTypesException;
use ClassPreloader\Factory;
use ClassPreloader\Parser\DirVisitor;
use ClassPreloader\Parser\FileVisitor;
use ClassPreloader\Parser\NodeTraverser;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\ParserFactory;
use Royalcms\Component\Foundation\Composer;
use Royalcms\Component\Foundation\Royalcms;
use Royalcms\Component\Support\Facades\Log;

class ClassPreloader extends \ClassPreloader\ClassPreloader
{

    /**
     * Wrap the code into a namespace.
     *
     * @param array  $parsed
     * @param string $pretty
     *
     * @return string
     */
    protected function getCodeWrappedIntoNamespace(array $parsed, $pretty)
    {
        if ($this->parsedCodeHasNamespaces($parsed)) {
            $pretty = preg_replace('/^\s*(namespace.*);/im', '${1} {', $pretty, 1)."\n}\n";
        } else {
            $pretty = sprintf("namespace {\n%s\n}\n", $pretty);
        }

        return preg_replace('/(?<!.)[\r\n]+/', '', $pretty);
    }

    /**
     * @param $file
     * @return string
     */
    protected function getFileClassName($file)
    {
        $namespaces = $this->parseFile($file);

        if (empty($namespaces)) {
            return null;
        }

        $namespace = $namespaces[0][0];
        $namespaceName = $namespace->name->toString();

        $stmts = $namespace->stmts;

        $class = null;

        foreach ($stmts as $stmt) {
            if ($stmt instanceof \PhpParser\Node\Stmt\Interface_) {
                $class = $stmt;
                break;
            }
            elseif ($stmt instanceof \PhpParser\Node\Stmt\Class_) {
                $class = $stmt;
                break;
            }
        }

        $fullClassName = null;

        if ($class) {
            $className = $class->name->toString();
            $fullClassName = $namespaceName . '\\' . $className;
        }

        return $fullClassName;
    }

    protected function parseFile($path)
    {
        $code = php_strip_whitespace($path);
        $parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);
        $ast = $parser->parse($code);
        if (! count($ast)) {
            throw new Exception('No PHP code found.');
        }
        $namespaces = $this->parsePHPSegments($ast);

        return $namespaces;
    }

    protected function parsePHPSegments($segments)
    {
        $segments = array_filter($segments, function ($segment) {
            return $segment instanceof Namespace_;
        });

        $segments = array_map(function (Namespace_ $segment) {
            return [$segment, $this->parseNamespace($segment)];
        }, $segments);

        return $segments;
    }

    protected function parseNamespace(Namespace_ $namespace)
    {
        $classes = array_values(array_filter($namespace->stmts, function ($class) {
            return $class instanceof Class_;
        }));

        return $classes;
    }


}