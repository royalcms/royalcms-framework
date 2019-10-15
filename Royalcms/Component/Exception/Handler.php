<?php

namespace Royalcms\Component\Exception;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Exceptions\WhoopsHandler;
use Psr\Log\LoggerInterface;
use Royalcms\Component\Http\Response;
use Royalcms\Component\Auth\Access\UnauthorizedException;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use Royalcms\Component\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use ReflectionFunction;
use Closure;
use Whoops\Handler\HandlerInterface;
use Whoops\Run as Whoops;

class Handler implements ExceptionHandlerContract
{
    /**
     * The log implementation.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $log;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [];


    /**
     * Create a new exception handler instance.
     *
     * @param  \Psr\Log\LoggerInterface  $log
     * @return void
     */
    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        if ($this->shouldReport($e)) {
            $this->log->error($e);
        }
    }

    /**
     * Determine if the exception should be reported.
     *
     * @param  \Exception  $e
     * @return bool
     */
    public function shouldReport(Exception $e)
    {
        return ! $this->shouldntReport($e);
    }

    /**
     * Determine if the exception is in the "do not report" list.
     *
     * @param  \Exception  $e
     * @return bool
     */
    protected function shouldntReport(Exception $e)
    {
        foreach ($this->dontReport as $type) {
            if ($e instanceof $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * Render an exception into a response.
     *
     * @param  \Royalcms\Component\Http\Request  $request
     * @param  \Exception  $e
     * @return \Royalcms\Component\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($this->isUnauthorizedException($e)) {
            $e = new HttpException(403, $e->getMessage());
        }

        if ($this->isHttpException($e)) {
            return $this->toRoyalcmsResponse($this->renderHttpException($e), $e);
        } else {
            return $this->toRoyalcmsResponse($this->convertExceptionToResponse($e), $e);
        }
    }

    /**
     * Map exception into an Royalcms response.
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @param  \Exception  $e
     * @return \Royalcms\Component\Http\Response
     */
    protected function toRoyalcmsResponse($response, Exception $e)
    {
        $response = new Response($response->getContent(), $response->getStatusCode(), $response->headers->all());

        $response->exception = $e;

        return $response;
    }

    /**
     * Render an exception to the console.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @param  \Exception  $e
     * @return void
     */
    public function renderForConsole($output, Exception $e)
    {
        (new ConsoleApplication)->renderException($e, $output);
    }

    /**
     * Render the given HttpException.
     *
     * @param  \Symfony\Component\HttpKernel\Exception\HttpException  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpException $e)
    {
        $status = $e->getStatusCode();

        if (view()->exists("errors.{$status}")) {
            return response()->view("errors.{$status}", ['exception' => $e], $status);
        }
        else {
            return $this->convertExceptionToResponse($e);
        }
    }

    /**
     * Convert the given exception into a Response instance.
     *
     * @param  \Exception  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertExceptionToResponse(Exception $e)
    {
        return SymfonyResponse::create(
            $this->renderExceptionContent($e),
            $this->isHttpException($e) ? $e->getStatusCode() : 500,
            $this->isHttpException($e) ? $e->getHeaders() : []
        );
    }

    /**
     * Get the response content for the given exception.
     *
     * @param  \Exception  $e
     * @return string
     */
    protected function renderExceptionContent(Exception $e)
    {
        try {

            ob_end_clean();

            $royalcms = royalcms();

            // If one of the custom error handlers returned a response, we will send that
            // response back to the client after preparing it. This allows a specific
            // type of exceptions to handled by a Closure giving great flexibility.
            if ($royalcms->has('exception.display')) {
                return $royalcms['exception.display']->displayException($e);
            }

            return config('system.debug') && class_exists(Whoops::class)
                ? $this->renderExceptionWithWhoops($e)
                : $this->renderExceptionWithSymfony($e, config('system.debug'));
        } catch (Exception $e) {
            return $this->renderExceptionWithSymfony($e, config('system.debug'));
        }
    }

    /**
     * Render an exception to a string using "Whoops".
     *
     * @param  \Exception  $e
     * @return string
     */
    protected function renderExceptionWithWhoops(Exception $e)
    {
        return tap(new Whoops, function ($whoops) {
            $whoops->appendHandler($this->whoopsHandler());

            $whoops->writeToOutput(false);

            $whoops->allowQuit(false);
        })->handleException($e);
    }

    /**
     * Get the Whoops handler for the application.
     *
     * @return \Whoops\Handler\Handler
     */
    protected function whoopsHandler()
    {
        try {
            return royalcms(HandlerInterface::class);
        } catch (BindingResolutionException $e) {
            return (new WhoopsHandler)->forDebug();
        }
    }

    /**
     * Render an exception to a string using Symfony.
     *
     * @param  \Exception  $e
     * @param  bool  $debug
     * @return string
     */
    protected function renderExceptionWithSymfony(Exception $e, $debug)
    {
        return (new SymfonyExceptionHandler($debug))->getHtml(
            FlattenException::create($e)
        );
    }

    /**
     * Determine if the given exception is an access unauthorized exception.
     *
     * @param  \Exception  $e
     * @return bool
     */
    protected function isUnauthorizedException(Exception $e)
    {
        return $e instanceof UnauthorizedException;
    }

    /**
     * Determine if the given exception is an HTTP exception.
     *
     * @param  \Exception  $e
     * @return bool
     */
    protected function isHttpException(Exception $e)
    {
        return $e instanceof HttpException;
    }

}
