<?php

namespace Royalcms\Component\Session;

use Illuminate\Contracts\Session\Session;
use Royalcms\Component\Support\Arr;
use Royalcms\Component\Support\Str;
use SessionHandlerInterface;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Store extends \Illuminate\Session\Store implements SessionInterface, StoreInterface, Session
{

    /**
     * The session bags.
     *
     * @var array
     */
    protected $bags = [];

    /**
     * The meta-data bag instance.
     *
     * @var \Symfony\Component\HttpFoundation\Session\Storage\MetadataBag
     */
    protected $metaBag;

    /**
     * Local copies of the session bag data.
     *
     * @var array
     */
    protected $bagData = [];

    /**
     * Create a new session instance.
     *
     * @param  string $name
     * @param  \SessionHandlerInterface $handler
     * @param  string|null $id
     * @return void
     */
    public function __construct($name, SessionHandlerInterface $handler, $id = null)
    {
        parent::__construct($name, $handler, $id);

        $this->metaBag = new MetadataBag;
    }

    /**
     * Load the session data from the handler.
     *
     * @return void
     */
    protected function loadSession()
    {
        parent::loadSession();

        foreach (array_merge($this->bags, [$this->metaBag]) as $bag) {
            $this->initializeLocalBag($bag);

            $bag->initialize($this->bagData[$bag->getStorageKey()]);
        }
    }

    /**
     * Initialize a bag in storage if it doesn't exist.
     *
     * @param  \Symfony\Component\HttpFoundation\Session\SessionBagInterface  $bag
     * @return void
     */
    protected function initializeLocalBag($bag)
    {
        $this->bagData[$bag->getStorageKey()] = $this->pull($bag->getStorageKey(), []);
    }

    /**
     * Get a new, random session ID.
     *
     * @return string
     */
    protected function generateSessionId()
    {
        return sha1(uniqid('', true).Str::random(25).microtime(true));
    }

    /**
     * {@inheritdoc}
     */
    public function save()
    {
        $this->addBagDataToSession();

        parent::save();
    }

    /**
     * Merge all of the bag data into the session.
     *
     * @return void
     */
    protected function addBagDataToSession()
    {
        foreach (array_merge($this->bags, [$this->metaBag]) as $bag) {
            $key = $bag->getStorageKey();

            if (isset($this->bagData[$key])) {
                $this->put($key, $this->bagData[$key]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function set($name, $value)
    {
        Arr::set($this->attributes, $name, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->attributes = [];

        foreach ($this->bags as $bag) {
            $bag->clear();
        }
    }

    /**
     * Remove all of the items from the session.
     *
     * @return void
     */
    public function flush()
    {
        $this->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function registerBag(SessionBagInterface $bag)
    {
        $this->bags[$bag->getStorageKey()] = $bag;
    }

    /**
     * {@inheritdoc}
     */
    public function getBag($name)
    {
        return Arr::get($this->bags, $name, function () {
            throw new InvalidArgumentException('Bag not registered.');
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadataBag()
    {
        return $this->metaBag;
    }

    /**
     * Get the raw bag data array for a given bag.
     *
     * @param  string  $name
     * @return array
     */
    public function getBagData($name)
    {
        return Arr::get($this->bagData, $name, []);
    }

    /**
     * Get the CSRF token value.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token();
    }


}
